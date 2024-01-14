<p align="center">
    <a href="" rel="noopener">
        <img width="700" height="400" src="https://github.com/OUGC-Network/OUGC-Hide-Administrator-Location/assets/1786584/dc45e56a-f17a-47b9-a68f-0e34358e35ee" alt="Project logo">
    </a>
</p>

<h3 align="center">OUGC Hide Administrator Location</h3>

<div align="center">

[![Status](https://img.shields.io/badge/status-active-success.svg)]()
[![GitHub Issues](https://img.shields.io/github/issues/OUGC-Network/OUGC-Hide-Administrator-Location.svg)](./issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/OUGC-Network/OUGC-Hide-Administrator-Location.svg)](./pulls)
[![License](https://img.shields.io/badge/license-GPL-blue)](/LICENSE)

</div>

<p align="center"> Hide administrator's location from the Who Is Online (WOL) list..
    <br> 
</p>

## 📜 Table of Contents <a name = "table_of_contents"></a>

- [About](#about)
- [Getting Started](#getting_started)
    - [Dependencies](#dependencies)
    - [File Structure](#file_structure)
    - [Install](#install)
    - [Update](#update)
- [Settings](#settings)
    - [Allow Bypassing](#settings_bypass)
- [Usage](#usage)
- [Built Using](#built_using)
- [Authors](#authors)
- [Acknowledgments](#acknowledgement)
- [Support & Feedback](#support)

## 🚀 About <a name = "about"></a>

OUGC Hide Administrator Location is a MyBB plugin designed to enhance privacy and security. With this plugin,
administrators can effortlessly hide their location from the Who Is Online (WOL) list, ensuring their presence remains
discreet. Additionally, the plugin allows for selective user and group hiding, providing administrators with greater
control over who appears in the WOL list. However, administrators retain the ability to see the location of any user at
all times.

[Go up to Table of Contents](#table_of_contents)

## 📍 Getting Started <a name = "getting_started"></a>

The following information will assist you into getting a copy of this plugin up and running on your forum.

### Dependencies <a name = "dependencies"></a>

A setup that meets the following requirements is necessary to use this plugin.

- [MyBB](https://mybb.com/) >= 1.8
- PHP >= 7.0
- [MyBB-PluginLibrary](https://github.com/frostschutz/MyBB-PluginLibrary) >= 13

### File structure <a name = "file_structure"></a>

  ```
   .
   ├── inc
   │ ├── plugins
   │ │ ├── ougcHideAdminLoc
   │ │ │ │ ├── admin.php
   │ │ │ │ ├── core.php
   │ │ │ │ ├── forumHooks.php
   │ │ ├── ougc_hal.php
   │ ├── languages
   │ │ ├── espanol
   │ │ │ ├── admin
   │ │ │ │ ├── ougc_hal.lang.php
   │ │ ├── english
   │ │ │ ├── admin
   │ │ │ │ ├── ougc_hal.lang.php
   ```

### Installing <a name = "install"></a>

Follow the next steps in order to install a copy of this plugin on your forum.

1. Download the latest package from the [MyBB Extend](https://community.mybb.com/mods.php?action=view&pid=1361) site or
   from the [repository releases](https://github.com/OUGC-Network/OUGC-Hide-Administrator-Location/releases/latest).
2. Upload the contents of the _Upload_ folder to your MyBB root directory.
3. Browse to _Configuration » Plugins_ and install this plugin by clicking _Install & Activate_.

### Updating <a name = "update"></a>

Follow the next steps in order to update your copy of this plugin.

1. Browse to _Configuration » Plugins_ and deactivate this plugin by clicking _Deactivate_.
2. Follow step 1 and 2 from the [Install](#install) section.
3. Browse to _Configuration » Plugins_ and activate this plugin by clicking _Activate_.

[Go up to Table of Contents](#table_of_contents)

## 🛠 Settings <a name = "settings"></a>

Below you can find a description of the plugin settings.

### Global Settings

- **Hide Specific Users** `select`
    - _Insert the UID of the users that you want to hide from the Who Is Online (WOL) list._
- **Hide Specific Groups** `yesNo` Default: `yes`
    - _Select the groups that you want to hide from the Who Is Online (WOL) list._

### Allow User or Group Bypassing <a name = "settings_bypass"></a>

Additionally, you can stop allow specific users or groups to see any location as if they were administrators by
modifying a constant in the plugin file `./inc/plugins/ougc_hal.php`:

`OUGC_HAL_SETTING_UIDS` `array`; default is set to `[]`

`OUGC_HAL_SETTING_GIDS` `array`; default is set to `[]`

Note that by default super administrators and administrators can see any location so modification of these constants is
not necessary under common setups.

[Go up to Table of Contents](#table_of_contents)

## 📖 Usage <a name="usage"></a>

This plugin has no additional configurations; after activating make sure to modify the global settings in order to get
this plugin working.

[Go up to Table of Contents](#table_of_contents)

## ⛏ Built Using <a name = "built_using"></a>

- [MyBB](https://mybb.com/) - Web Framework
- [MyBB PluginLibrary](https://github.com/frostschutz/MyBB-PluginLibrary) - A collection of useful functions for MyBB
- [PHP](https://www.php.net/) - Server Environment

[Go up to Table of Contents](#table_of_contents)

## ✍️ Authors <a name = "authors"></a>

- [@Omar G](https://github.com/Sama34) - Idea & Initial work

See also the list of [contributors](https://github.com/OUGC-Network/OUGC-Hide-Administrator-Location/contributors) who
participated in this project.

[Go up to Table of Contents](#table_of_contents)

## 🎉 Acknowledgements <a name = "acknowledgement"></a>

- [The Documentation Compendium](https://github.com/kylelobo/The-Documentation-Compendium)

[Go up to Table of Contents](#table_of_contents)

## 🎈 Support & Feedback <a name="support"></a>

This is free development and any contribution is welcome. Get support or leave feedback at the
official [MyBB Community](https://community.mybb.com/thread-227574.html).

Thanks for downloading and using our plugins!

[Go up to Table of Contents](#table_of_contents)