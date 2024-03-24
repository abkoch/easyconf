<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_easyconf
 * @category    admin
 * @copyright   2024 Andreas Koch
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/local/easyconf/lib.php');

if (!class_exists('local_easyconf_admin_setting_configtextarea')) {
    class local_easyconf_admin_setting_configtextarea extends admin_setting_configtextarea {
        public function validate($data) {
            if (yaml_parse($data)) {
                return true;
            }
            return get_string('yml_error', 'local_easyconf');
        }
    }
}

if ($hassiteconfig) {

    $settings = new admin_settingpage('local_easyconf_settings', new lang_string('pluginname', 'local_easyconf'));
    $ADMIN->add('localplugins', $settings);

    if ($ADMIN->fulltree) {

        $result = '<a href="?section=local_easyconf_settings&execute=1&sesskey=' . sesskey() . '" class="btn btn-secondary">'
                  . get_string('execute', 'local_easyconf') . '</a>';

        if (optional_param('execute', 0, PARAM_INT) && confirm_sesskey()) {
            global $easyconfout;
            $resultrun  = local_easyconf::run();
            $resultruntext = $resultrun ? get_string('run_success', 'local_easyconf') : get_string('run_error', 'local_easyconf');
            $result = '<pre>' . $easyconfout . $resultruntext . '</pre>' . $result;
        }

        $setting = new admin_setting_heading('execute', get_string('execute', 'local_easyconf'), $result);
        $settings->add($setting);

        $setting = new admin_setting_heading('settings', get_string('settings', 'local_easyconf'), '');
        $settings->add($setting);

        $setting = new admin_setting_configselect('local_easyconf/enabled', get_string('enabled', 'local_easyconf'),
                    '', 'yes',
                    ['yes' => get_string('yes', 'local_easyconf'), 'no' => get_string('no', 'local_easyconf')]);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        if (file_exists($CFG->dirroot .'/local/easyconf/configuration.yml')) {
            $contents = file_get_contents($CFG->dirroot . '/local/easyconf/configuration.yml');
            $result = yaml_parse($contents) ? 'ok' : 'error';

            $setting = new admin_setting_heading('configuration', get_string('configuration', 'local_easyconf'),
                                                 get_string('configuration_text', 'local_easyconf') . ' '
                                                 . get_string('configuration_file_' . $result, 'local_easyconf'));
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

        } else {
            $result = yaml_parse(get_config('local_easyconf', 'configuration')) ? 'ok' : 'error';
            $setting = new admin_setting_heading('configuration', get_string('configuration', 'local_easyconf'),
                                                  get_string('configuration_text', 'local_easyconf') . ' '
                                                  . get_string('configuration_field_' . $result, 'local_easyconf'));
            $settings->add($setting);
            $setting = new local_easyconf_admin_setting_configtextarea('local_easyconf/configuration',
                                                                       get_string('configuration', 'local_easyconf'),
                                                                       get_string('configuration_descr', 'local_easyconf'),
                                                                       '', PARAM_RAW);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

        }
    }
}
