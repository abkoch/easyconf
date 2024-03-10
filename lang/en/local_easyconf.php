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
 * Plugin strings are defined here.
 *
 * @package     local_easyconf
 * @category    string
 * @copyright   2024 Andreas Koch
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['settings'] = 'Settings';
$string['execute'] = 'Run';
$string['enabled'] = 'Aktiviert';
$string['enabled_text'] = 'Plugin is activated.';
$string['disabled_text'] = 'Plugin is deactivated.';
$string['cli_run'] = 'Running plugin local_easyconf via CLI...';
$string['run_caches'] = 'You should purge all caches after execution.';
$string['run_error'] = 'Finished running local_easyconf with errors.';
$string['run_success'] = 'Finished running local_easyconf successfully.';
$string['configuration'] = 'Configuration';
$string['configuration_descr'] = 'Enter your configuration here in valid YAML.';
$string['configuration_text'] = 'The configuration may be entered in a text field or in a file called configuration.yml. In both cases in valid YAML. The file configuration.yml.same shows an example. The text field is displayed only if there is no file configuration.yml present.';
$string['configuration_field_error'] = 'At the moment configuration in this text field is used. But it can\'t be applied because it\'s not valid YAML.';
$string['configuration_field_ok'] = 'At the moment configuration in this text field is used. It\'s valid YAML.';
$string['configuration_file_error'] = 'At the moment the configuration in the file configuration.yml is used. But it can\'t be applied because it\'s not valid YAML.';
$string['configuration_file_ok'] = 'At the moment the configuration in the fil configuration.yml is used. It\'s valid YAML.';
$string['yes'] = 'Yes';
$string['no'] = 'No';
$string['save'] = 'Save changes';
$string['set']  = 'Set configuration';
$string['yml_error'] = 'No valid YAML';
$string['setsuccess'] = 'Success';
$string['seterror'] = 'Error';
$string['set_config_absent'] = 'Ignore deletion in config of {$a->name} because not present...';
$string['set_config_delete'] = 'Delete in config setting {$a->name}...';
$string['set_config_insert'] = 'Insert into config: {$a->name} with {$a->value}...';
$string['set_config_nooverwrite'] = 'Ignore in config: {$a->name} already exists and nooverwrite is set...';
$string['set_config_update'] = 'Update in config: {$a->name} with {$a->value}...';
$string['set_config_plugins_absent'] = 'Ignore deletion in config_plugins for plugin {$a->plugin} of {$a->name} because not present...';
$string['set_config_plugins_delete'] = 'Delete in config_plugins for plugin {$a->plugin} setting {$a->name}...';
$string['set_config_plugins_insert'] = 'Insert into config_plugins for plugin {$a->plugin}: {$a->name} with {$a->value}...';
$string['set_config_plugins_nooverwrite'] = 'Ignore in config for plugin {$a->plugin}: {$a->name} already exists and nooverwrite is set...';
$string['set_config_plugins_update'] = 'Update config_plugins for plugin {$a->plugin}: {$a->name} with {$a->value}...';
$string['pluginname'] = 'Easy Configuration';
$string['privacy:null_reason'] = 'This plugin does not store or process any personal data.';
