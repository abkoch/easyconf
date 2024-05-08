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

/**
 * Class that holds all logic, especially for performing SQL queries
 */
class local_easyconf {
    /**
     * Method to check permissions
     */
    public static function has_permission() {
        return has_capability('local/easyconf:execute', context_system::instance());
    }

    /**
     * Method to run main task
     */
    public static function run() {
        global $CFG, $easyconfout;

        $easyconfout = '';

        try {

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

        } catch (Exception $e) {

            $easyconfout = get_string('configuration_error', 'local_easyconf');
            return false;

        }

        purge_caches();

        return $return;

    }

    /**
     * Method to delete, edit and set entries in table 'config'
     */
    public static function set_config($entries) {

        global $easyconfout;

        if (!is_array($entries)) {
            throw new Exception (get_string('configuration_error', 'local_easyconf'));
        }

        $table = 'config';
        $specialkeys = ['name', 'params'];

        for ($i = 0; $i < count($entries); $i++) {

            if (!isset($entries[$i]) || !is_array($entries[$i])) {
                throw new Exception (get_string('configuration_error', 'local_easyconf'));
            }

            foreach ($entries[$i] as $key => $value) {

                if (!in_array($key, $specialkeys)) {
                    $entries[$i]['name'] = $key;
                    $entries[$i]['value'] = $value;
                    unset($entries[$i][$key]);
                }
            }
            $entries[$i]['params']['condition'] = 'name="' . $entries[$i]['name'] . '"';
        }

        return self::set($entries, $table);

    }

    /**
     * Method to delete, edit and set entries in table 'config_plugins' 
     */
    public static function set_config_plugins($entries) {

        global $easyconfout;

        if (!is_array($entries)) {
            throw new Exception (get_string('configuration_error', 'local_easyconf'));
        }

        $table = 'config_plugins';
        $specialkeys = ['name', 'params'];

        $entriesnew = [];

        for ($i = 0; $i < count($entries); $i++) {
            $entriesnew[$i]['plugin'] = 'bbb';

            foreach ($entries[$i] as $plugin => $values) { // There should be only one element present.
                $entriesnew[$i]['plugin'] = $plugin;

                if (!isset($entries[$i]) || !is_array($entries[$i])) {
                    throw new Exception (get_string('configuration_error', 'local_easyconf'));
                }

                foreach ($values as $key => $value) {

                    if (!is_array($values)) {
                        throw new Exception (get_string('configuration_error', 'local_easyconf'));
                    }

                    if (!in_array($key, $specialkeys)) {
                        $entriesnew[$i]['name'] = $key;
                        $entriesnew[$i]['value'] = $value;
                    } else {
                        $entriesnew[$i][$key] = $value;
                    }
                }
                $entriesnew[$i]['params']['condition'] = 'plugin="' . $plugin . '" AND name="' . $entriesnew[$i]['name'] . '"';
            }
        }

        return self::set($entriesnew, $table);

    }

    /**
     * Default method to delete, edit and set entries in tables
     */
    public static function set($entries, $table) {
        global $easyconfout;

        if (!is_array($entries)) {
            throw new Exception (get_string('configuration_error', 'local_easyconf'));
        }

        $result = true;

        for ($i = 0; $i < count($entries); $i++) {

            if (!self::setentry($entries[$i], $table)) {
                $result = false;
            }

        }

        return $result;
    }

    /**
     * Default method to manipulate a single entry in a table
     */
    public static function setentry($entry, $table) {
        global $DB, $easyconfout;

        $params = ['condition', 'mode', 'state'];

        foreach ($params as $param) {
                $$param = isset($entry['params'][$param]) ? $entry['params'][$param] : '';
        }

        $sql = "SELECT * FROM {" . $table . "} WHERE " . $condition;

        try {

            $record = $DB->get_record_sql($sql);

        } catch (Exception $e) {

            $easyconfout .= get_string('db_read_error', 'local_easyconf', ['sql' => $sql]) . "\n";

            return false;

        }

        try {

            $result = false;

            $entrylangstring = '';

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

                if (!is_array($entry)) {
                    throw new Exception (get_string('configuration_error', 'local_easyconf'));
                }

                foreach ($entry as $key => $value) {
                    if (isset($record->$key)) {
                        $record->$key = $value;
                    }
                }

                $entrylangstring = print_r($record, true);

                if ($DB->update_record($table, $record, false)) {

                    $result = true;
                }

            } else {
                $action = 'insert';
                $recordnew = new stdClass();
                $recordnew->id = $record->id;

                if (!is_array($entry)) {
                    throw new Exception (get_string('configuration_error', 'local_easyconf'));
                }

                foreach ($entry as $key => $value) {
                    if (!is_array($value)) {
                        $recordnew->$key = $value;
                    }
                }

                $entrylangstring = print_r($recordnew, true);

                if ($DB->insert_record($table, $recordnew, false)) {
                    $result = true;
                }

            }

            $easyconfout .= get_string('set_' . $action, 'local_easyconf',
                                       ['table' => $table, 'entry' => $entrylangstring, 'condition' => $condition]) . ' ';

            if ($result) {
                $easyconfout .= get_string('setsuccess', 'local_easyconf');
            } else {
                $easyconfout .= get_string('seterror', 'local_easyconf');
            }

            $easyconfout .= "\n";

            return $result;

        } catch (Exception $e) {

            if (isset($record)) {
                $entry = print_r($record, true);
            } else if (isset($recordnew)) {
                $entry = print_r($recordnew, true);
            } else {
                $entry = '';
            }

            $easyconfout .= get_string('db_write_error', 'local_easyconf', ['entry' => $entry]) . "\n";

            return false;

        }

    }

}
