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
                if (!call_user_func(['local_easyconf', 'set_' . $table], $config)) {
                    $return = false;
                }
	        } else {
                if (!call_user_func(['local_easyconf', 'set'], $config, $table)) {
                    $return = false;
                }
	        }
        }

        purge_caches();

        return $return;
    }

    // Function to set values for table 'config'.
    public static function set_config($entries) {

        global $easyconfout;

        $table = 'config';
        $specialkeys = ['name', 'params'];

        for ($i=0; $i<count($entries); $i++) {
            foreach ($entries[$i] as $key => $value) {
                if (!in_array($key, $specialkeys)) {
                    $entries[$i]['name'] = $key;
                    $entries[$i]['value'] = $value;
                    unset($entries[$i][$key]);
                }
            }
            $entries[$i]['params']['condition'] = 'name="' . $entries[$i]['name'] . '"';            
        }

        return local_easyconf::set($entries, $table);

    }

    // Function to set values for table 'config_plugins'.
    public static function set_config_plugins($entries) {

        global $easyconfout;

        $table = 'config_plugins';
        $specialkeys = ['name', 'params'];

        $entries_new = Array();

        for ($i=0; $i<count($entries); $i++) {
            $entries_new[$i]['plugin'] = 'bbb';

            foreach ($entries[$i] as $plugin => $values) { // There should be only one element present.
                $entries_new[$i]['plugin'] = $plugin;

                foreach ($values as $key => $value) {
                    if (!in_array($key, $specialkeys)) {
                        $entries_new[$i]['name'] = $key;
                        $entries_new[$i]['value'] = $value;
                    } else {
                        $entries_new[$i][$key] = $value;
                    }
                }
                $entries_new[$i]['params']['condition'] = 'plugin="' . $plugin . '" AND name="' . $entries_new[$i]['name'] . '"';
            }
        }

        return local_easyconf::set($entries_new, $table);

    }

    // Default function to set values for different tables.
    public static function set($entries, $table) {
        global $easyconfout;

	    $result = true;

	    for ($i=0; $i<count($entries); $i++) {

            if (!local_easyconf::setentry($entries[$i], $table)) {
                $result = false;
            }

	    }

       return $result;
    }

    // Default function to set an entry.
    public static function setentry($entry, $table) {
        global $DB, $easyconfout;

        $params = ['condition', 'mode', 'state'];

        foreach ($params as $param) {
                $$param = isset($entry['params'][$param]) ? $entry['params'][$param] : '';
        }

        $sql = "SELECT * FROM {" . $table . "} WHERE " . $condition;

        $record = $DB->get_record_sql($sql);

        $result = false;

        $entry_lang_string = '';

        if (isset($record->id) && isset($state) && $state == 'absent') {
            $action = 'delete';

            if ($DB->delete_records($table, ['id' => $record->id])) {
                $result = true;
            }

        } else if (!isset($record->id) && isset($state) && $state == 'absent') {
            $action = 'absent';
            $resultentry = true;

        } else if (isset($record->id) && isset($mode) && $mode == 'nooverwrite') {
            $action = 'nooverwrite';
            $result = true;

        } else if (isset($record->id)) {
            $action = 'update';

            foreach ($entry as $key => $value) {
                if (isset($record->$key)) {
                    $record->$key = $value;
                }
            }

            $entry_lang_string = serialize($record);

            if ($DB->update_record($table, $record, false)) {

                $result = true;
            }

        } else {
            $action = 'insert';
            $recordnew = new stdClass();
            $recordnew->id = $record->id;
            foreach ($entry as $key => $value) {
                if (!is_array($value)) {
                    $recordnew->$key = $value;
                }
            }

            $entry_lang_string = serialize($record_new);

            if ($DB->insert_record($table, $recordnew, false)) {
                $result = true;
            }

        }

        $easyconfout .= get_string('set_' . $action, 'local_easyconf',
                                   ['table' => $table, 'entry' => $entry_lang_string, 'condition' => $condition]) . ' ';

        if ($result) {
            $easyconfout .= get_string('setsuccess', 'local_easyconf');
        } else {
            $easyconfout .= get_string('seterror', 'local_easyconf');
        }

        $easyconfout .= "\n";

        return $result;
   }

}
