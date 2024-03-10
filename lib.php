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

class local_easyconf {
    // Method to check permissions.
    public static function has_permission() {
        return has_capability('local/easyconf:execute', context_system::instance());
    }

    // Method to run perfom main task.
    public static function run() {
        global $CFG, $easyconfout;

        $easyconfout = '';

        if (is_file($CFG->dirroot . '/local/easyconf/configuration.yml')) {
            $configuration = yaml_parse_file($CFG->dirroot . '/local/easyconf/configuration.yml');
            if (!$configuration) {
                $easyconfout = get_string('configuration_file_error', 'local_easyconf');
                return false;
            }
        } else {
            $configuration = yaml_parse(get_config('local_easyconf', 'configuration'));
            if (!$configuration) {
                $easyconfout = get_string('configuration_field_error', 'local_easyconf');
                return false;
            }
        }

        $return = true;

        foreach ($configuration as $table => $config) {
            if (method_exists('local_easyconf', 'set_' . $table)) {
                foreach ($config as $name => $value) {
                    if (!call_user_func(['local_easyconf', 'set_' . $table], [$name => $value])) {
                        $return = false;
                    }
                }
            }
        }
        return $return;
    }

    // Function to set values for table 'config'.
    public static function set_config($entry) {
        global $DB, $easyconfout;
        $params = ['mode', 'state'];
        $table = 'config';
        $name = array_keys($entry)[0];
        $value = $entry[$name]['value'];

        foreach ($params as $param) {
            $$param = isset($entry[$name][$param]) ? $entry[$name][$param] : '';
        }

        if ($state != 'absent') {
            $value = $entry[$name]['value'];
        }

        $sql = "SELECT id,value FROM {" . $table . "} WHERE name='" . $name . "'";
        $record = $DB->get_record_sql($sql);

        $result = false;

        if (isset($record->id) && isset($state) && $state == 'absent') {
            $action = 'delete';

            if ($DB->delete_records($table, ['id' => $record->id])) {
                $result = true;
            }

        } else if (!isset($record->id) && isset($state) && $state == 'absent') {
            $action = 'absent';
            $result = true;

        } else if (isset($record->id) && isset($mode) && $mode == 'nooverwrite') {
            $action = 'nooverwrite';
            $result = true;

        } else if (isset($record->id)) {
            $action = 'update';

            if ($DB->set_field($table, "value", $value, ["id" => $record->id])) {
                $result = true;
            }
        } else {
            $action = 'insert';
            $recordnew = new stdClass();
            $recordnew->name   = $name;
            $recordnew->value  = $value;

            if ($DB->insert_record($table, $recordnew, false)) {
                $result = true;
            }
        }

        $easyconfout .= get_string('set_config_' . $action, 'local_easyconf', ['name' => $name, 'value' => $value]) . ' ';

        if ($result) {
            $easyconfout .= get_string('setsuccess', 'local_easyconf');
        } else {
            $easyconfout .= get_string('seterror', 'local_easyconf');
        }

        $easyconfout .= "\n";

        return $result;
    }

    // Function to set values for table 'config_plugins'.
    public static function set_config_plugins($entry) {
        global $easyconfout, $DB;
        $params = ['mode', 'state'];
        $table  = 'config_plugins';

        $result = true;

        foreach ($entry as $plugin => $values) {

            foreach ($values as $name => $entry) {

                foreach ($params as $param) {
                    $$param = isset($entry[$param]) ? $entry[$param] : '';
                }

                if ($state != 'absent') {
                     $value = $entry['value'];
                }

                $sql = "SELECT id,value FROM {" . $table . "} WHERE plugin='" . $plugin . "' AND name='" . $name . "'";
                $record = $DB->get_record_sql($sql);

                $resultentry = false;

                if (isset($record->id) && isset($state) && $state == 'absent') {
                    $action = 'delete';

                    if ($DB->delete_records($table, ['id' => $record->id])) {
                        $resultentry = true;
                    }

                } else if (!isset($record->id) && isset($state) && $state == 'absent') {
                    $action = 'absent';
                    $resultentry = true;

                } else if (isset($record->id) && isset($mode) && $mode == 'nooverwrite') {
                    $action = 'nooverwrite';
                    $resultentry = true;

                } else if (isset($record->id)) {
                    $action = 'update';

                    if ($DB->set_field($table, "value", $value, ["id" => $record->id])) {
                        $resultentry = true;
                    }

                } else {
                    $action = 'insert';

                    $recordnew = new stdClass();
                    $recordnew->plugin = $plugin;
                    $recordnew->name   = $name;
                    $recordnew->value  = $name;

                    if ($DB->insert_record($table, $recordnew, false)) {
                        $resultentry = true;
                    }
                }

                if (!$resultentry) {
                    $result = false;
                }

                $easyconfout .= get_string('set_config_plugins_' . $action, 'local_easyconf',
                                ['plugin' => $plugin, 'name' => $name, 'value' => $value]) . ' ';

                if ($resultentry) {
                    $easyconfout .= get_string('setsuccess', 'local_easyconf');
                } else {
                    $easyconfout .= get_string('seterror', 'local_easyconf');
                }

                $easyconfout .= "\n";

            }

        }

        return $result;
    }

}
