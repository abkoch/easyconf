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

$string['install_phpyamlrequired'] = 'Die PHP-YAML-Bibliothek wird für local_easyconf benötigt.';
$string['settings'] = 'Einstellungen';
$string['execute'] = 'Ausführen';
$string['enabled'] = 'Aktiviert';
$string['enabled_text'] = 'Plugin ist aktiviert';
$string['disabled_text'] = 'Plugin ist deaktiviert';
$string['cli_run'] = 'Führe Plugin Einfache Konfiguration per CLI aus...';
$string['run_error'] = 'Ausführung von Einfache Konfiguration mit Fehlern beendet.';
$string['run_success'] = 'Ausführung von Einfache Konfiguration erfolgreich beendet.';
$string['configuration'] = 'Konfiguration';
$string['configuration_descr'] = 'Geben Sie hier die Konfiguration in gültigem YAML ein.';
$string['configuration_text'] = 'Die Konfiguration kann entweder direkt in einem Textfeld definiert werden oder in der Datei configuration.yml. In beiden Fällen ist gültiges YAML zu verwenden. In der Datei configuration.yml.sample finden Sie ein Beispiel. Das Textfeld wird nur angezeigt, wenn die Datei configuration.yml nicht existiert.';
$string['configuration_field_error'] = 'Aktuell wird die Konfiguration hier im Textfeld verwendet. Sie kann nicht verwendet werden, da sie Fehler enthält. Es handelt sich nicht um gültiges YAML.';
$string['configuration_field_ok'] = 'Aktuell wird die Konfiguration hier im Textfeld verwendet. Sie liegt in gültigem YAML vor.';
$string['configuration_file_error'] = 'Aktuell wird die Konfiguration in der Datei configuration.yml verwendet. Allerdings enthält sie Fehler und kann daher nicht verwendet werden. Es handelt sich nicht um gültiges YAML.';
$string['configuration_file_ok'] = 'Aktuell wird die Konfiguration in der Datei configuration.yml verwendet. Sie liegt in gültigem YAML vor.';
$string['yes'] = 'Ja';
$string['no'] = 'Nein';
$string['save'] = 'Änderungen speichern';
$string['set']  = 'Konfiguration setzen';
$string['yaml_error'] = 'Kein gültiges YAML-Format';
$string['setsuccess'] = 'Erfolgreich';
$string['seterror'] = 'Fehler';
$string['set_absent'] = 'Ignorere Löschen in {$a->table} für {$a->condition}, da nicht mehr vorhanden...';
$string['set_delete'] = 'Lösche in {$a->table} Eintrag für {$a->condition}...';
$string['set_insert'] = 'Setze neu in {$a->table}: {$a->entry}...';
$string['set_nooverwrite'] = 'Ignoriere in {$a->table}: Eintrag für {$a->condition} bereits vorhanden und Nichtüberschreiben gesetzt...';
$string['set_update'] = 'Überschreibe in {$a->table} für {$a->condition}: {$a->entry} ...';
$string['db_read_error'] = 'Lesen der Datenbank: {$a->sql}... Error';
$string['db_write_error'] = 'Schreiben in Datenbank: {$a->entry}... Error';
$string['pluginname'] = 'Einfache Konfiguration';
$string['privacy:null_reason'] = 'Dieses Plugin speichert und verarbeitet keine personenbezogene Daten.';
