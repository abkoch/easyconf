<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Runs local_easyconf if enabled
 *
 * @package    local_easyconf
 * @copyright  2024 Andraeas Koch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__.'/../../../config.php');
require_once("$CFG->libdir/clilib.php");
require_once("$CFG->dirroot/local/easyconf/lib.php");

cli_heading(get_string('cli_run', 'local_easyconf')." ($CFG->wwwroot)");

global $easyconfout;

\core\cron::setup_user();

if (get_config('local_easyconf', 'enabled') != 'yes') {
    die (get_string('disabled_text', 'local_easyconf'));
}

if (local_easyconf::has_permission()) {
    $resultrun = local_easyconf::run();
} else {
    die (get_string('no_permission', 'local_easyconf'));
}

echo $easyconfout;

if ($resultrun) {
    echo get_string('run_success', 'local_easyconf');
} else {
    echo get_string('run_error', 'local_easyconf');
}

return $resultrun;
