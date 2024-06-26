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
 * Capability definitions for the local_easyconf plugin.
 *
 * @package   local_easyconf
 * @copyright 2024 Andreas Koch
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    // Nobody else than admins should be able to
    // use this plugin as configuration and can be destroyed.
    'local/easyconf:execute' => [
        'riskbitmask' =>        RISK_CONFIG | RISK_DATALOSS,
        'captype' =>            'write',
        'contextlevel' =>       CONTEXT_SYSTEM,
        'archetypes' => [
            'student' =>        CAP_PROHIBIT,
            'teacher' =>        CAP_PROHIBIT,
            'editingteacher' => CAP_PROHIBIT,
            'manager' =>        CAP_PROHIBIT,
        ],
    ],
];
