# Easy configuration (local_easyconf) #

This plugins allows administrators to easily configure settings in the tables `{config}` and
`{config_plugins}`. It may be run via GUI or via CLI. The latter makes it possible
to easily roll out standard configuration for several moodles.

If you require addional features feel free to contact me: kocha (at) posteo.de

## Requirements ##
The PHP package YAML is required. You may install it e.g. by

    $ apt install php8.1-yaml

## Riscs ##
Before running a YAML configuration be sure you really know what you do.
It is possible to change every entry in your moodle database.
That means you may delete and edit data irrevocably.
Perform a database backup before if you are unsure.
Usage is on your own risc. No liability.

## History ##
2024/05/08 local_easyconf 0.3.1 (2024050401): Bug fixing (see CONTRIB-9516) and hardening, including:
- Added `has_permission()` checks
- Added and fixed lang strings
- Added try-catch-blocks to catch configuration errors
- Fixed some typos in code
- Removed remains of development
2024/05/04 local_easyconf 0.3 (2024050400): Enhancements
- Added `db/access.php` to check permissions
- Added `environment.xml` to ensure that php-yaml is installed
- Changed output from JSON using `serialize(.)` to prettier print using `pring_r(.)`
- Fixed some typos, enhanced coding style and documentation
2024/03/24 local_easyconf 0.2.1 (2024032401): Enhancements
- Added error handling
2024/03/24 local_easyconf 0.2 (2024032400): Enhancements
- New YML syntax to support an table:
-- Universal method for setting records in any table
-- Special methods for setting records in tables `{config}` and `{config_plugins}`
- Check for valid session key
- Automatically purge caches
- Adaptions in language files
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
are needed). A sample configuration file is `configiration.yml.sample`.

The general YAML syntax is:

```
---
config:
  - name1: value1
  - name2: value2
    params:
        mode: nooverwrite
  - name3: value3
    params:
        state: absent
config_plugins:
  - plugin1:
       name1: value1
  - plugin2:
        name2: value2
        params:
            mode: nooverwrite
  - plugin3:
        name3: value3
        params:
            state: absent
table1:
  - field1: value1
    field2: value2
    params:
        condition: fieldA="valueB"
  - field1: value1
    field2: value2
    field3: value3
    params:
        condition: fieldA="valueB" AND fieldC="valueD"
        mode: nooverwrite
table2:
  - field1: value1
    field2: value2
    params:
        condition: fieldA="valueB"
  - field1: value1
    field2: value2
    field3: value3
    params:
        condition: fieldA="valueB" AND fieldC="valueD"
        mode: nooverwrite
```

The general syntax is shown by the `{table1}` and `{table2}` entries.
At least one pair `file: value` and `params['condition']` is mandatory.
If `mode=nooverwrite` is used, records will only be overwritten if there's no one present matching the condition.
If `state=absent` is used, the entry matching the condition will be deleted. So type your conditions with care.

For tables `{config}` and `{config_plugins}` the syntax is eased and differing as no condition is needed.
Take a look at the examples above.

To apply the configuration via CLI run

    $ sudo -u <user> <path-to-php-binary> <moodle-dirroot>/local/easyconf/cli/run_easyconf.php

After an execution via CLI or GUI you should purge the caches.

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
