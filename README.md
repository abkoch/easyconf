# Easy configuration #

This plugins allows administrators to easily configure settings in table 'config' and
table 'config_plugins'. It may be run via GUI or via CLI. The latter makes it possible
to easily roll out standard configuration for several moodles.

If you want to set additional configurations in other tables, you may extend lib.php
or ask me (koch (at) posteo.de).

## Requirements ##
The package PHP-YAML is required. You may install it e.g. by

   $ apt install php8.1-yaml

## History ##
2024/03/10 local_easyconf 0.1 (2024031000): Initial release

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/easy_configuration

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## Usage ##

First enter configuration in YAML syntax. It's possible to enter it in a text field
via GUI or in a file called configuration.yml (recommended if a lot of configurations
are needed). A sample configuration file is configiration.yml.sample.

The general YAML syntax is:
---
config:
  name_1:
    value: value_1
  name_2:
    value: value_2
    mode: nooverwrite
  name_3:
    state: absent
config_plugins:
  plugin_1:
    name_1:
      value: value_1
      mode: nooverwrite
    name_2:
      value: value_2
    name_3:
      state: absent
  plugin_2:
    name_1:
      value: value_1
      mode: nooverwrite
    name_2:
      value: value_2
    name_3:
      state: absent

Field value is mandatory, fields mode and state are optional.

If mode=nooverwrite is used, the value will only be set if an entry for name
in the table "config" or for name and plugin in the table "config_plugins"
is not present in the database.

If state=absent is used, the entry will be deleted.

To run this plugin via CLI enter

   $ sudo -u <user> <path-to-php-binary> <moodle-dirroot>/local/easyconf/run_easyconf.php

## License ##

2024 Andreas Koch

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
