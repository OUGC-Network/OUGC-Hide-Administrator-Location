<?php

/***************************************************************************
 *
 *	OUGC Hide Administrator Location (/inc/plugins/ougc_hal.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2016 - 2023 Omar Gonzalez
 *
 *	Website: https://ougc.network
 *
 *	Hide administrator's location from the Who Is Online (WOL) list.
 *
 ***************************************************************************

 ****************************************************************************
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ****************************************************************************/

// Die if IN_MYBB is not defined, for security reasons.
defined('IN_MYBB') or die('Direct initialization of this file is not allowed.');

// The following users and groups will be able to bypass this plugin and see the location of any user
const OUGC_HAL_SETTING_UIDS = []; // comma separated users (uid) to hide
const OUGC_HAL_SETTING_GIDS = []; // comma separated groups (gid) to hide

const OUGC_HAL = MYBB_ROOT . 'inc/plugins/ougcHideAdminLoc';

require_once OUGC_HAL.'/core.php';

// PLUGINLIBRARY
defined('PLUGINLIBRARY') or define('PLUGINLIBRARY', MYBB_ROOT.'inc/plugins/pluginlibrary.php');

// Add our hooks
if(defined('IN_ADMINCP')) {
    require_once OUGC_HAL.'/admin.php';
}
else {
    require_once OUGC_HAL.'/forumHooks.php';

    \OUGCHideAdminLoc\Core\addHooks('OUGCHideAdminLoc\ForumHooks');
}

// Plugin API
function ougc_hal_info() : array {
    return \OUGCHideAdminLoc\Admin\pluginInfo();
}

// _activate() routine
function ougc_hal_activate() : true {
    return \OUGCHideAdminLoc\Admin\pluginActivate();
}

// _is_installed() routine
function ougc_hal_is_installed() : bool {
    return \OUGCHideAdminLoc\Admin\pluginIsInstalled();
}

// _uninstall() routine
function ougc_hal_uninstall() : true {
    return \OUGCHideAdminLoc\Admin\pluginUninstall();
}