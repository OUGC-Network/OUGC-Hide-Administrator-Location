<?php

/***************************************************************************
 *
 *	OUGC Hide Administrator Location (/inc/plugins/ougc_hal.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2016 - 2020 Omar Gonzalez
 *
 *	Website: https://ougc.network
 *
 *	Hide administrator's location at WOL list.
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

// PLUGINLIBRARY
defined('PLUGINLIBRARY') or define('PLUGINLIBRARY', MYBB_ROOT.'inc/plugins/pluginlibrary.php');

// Tell MyBB when to run the hook
if(!defined('IN_ADMINCP'))
{
	$plugins->add_hook('online_user', 'online_user');
}

// Settings
define('OUGC_HAL_SETTING_UIDS', ''); // comma separated users (uid) to hide
define('OUGC_HAL_SETTING_GIDS', ''); // comma separated groups (gid) to hide
// Thread as a "additional administrators" settings, as they will be able to bypass the system and view any location
// To hide the location of specific users or groups without them being able treated as admins use the added settings instead

// Plugin API
function ougc_hal_info()
{
	global $lang;

	isset($lang->setting_group_ougc_hal) || $lang->load('ougc_hal');

	return array(
		'name'			=> 'OUGC Hide Administrator Location',
		'description'	=> $lang->setting_group_ougc_hal_desc,
		'website'		=> 'https://ougc.network',
		'author'		=> 'Omar G.',
		'authorsite'	=> 'https://ougc.network',
		'version'		=> '1.8.20',
		'versioncode'	=> 1820,
		'compatibility'	=> '18*',
		'codename' 		=> 'ougc_hal',
		'pl'			=> array(
			'version'	=> 13,
			'url'		=> 'http://community.mybb.com/mods.php?action=view&pid=573'
		)
	);
}

// _activate() routine
function ougc_hal_activate()
{
	global $PL, $lang, $mybb;
	ougc_hal_load_pluginlibrary();

	// Add settings group
	$PL->settings('ougc_hal', $lang->setting_group_ougc_hal, $lang->setting_group_ougc_hal_desc, array(
		'uids'			=> array(
		   'title'			=> $lang->setting_ougc_hal_uids,
		   'description'	=> $lang->setting_ougc_hal_uids_descs,
		   'optionscode'	=> 'text',
			'value'			=>	'',
		),
		'gids'				=> array(
		   'title'			=> $lang->setting_ougc_hal_gids,
		   'description'	=> $lang->setting_ougc_hal_gids_desc,
		   'optionscode'	=> 'groupselect',
			'value'			=>	4,
		)
	));

	// Insert/update version into cache
	$plugins = $mybb->cache->read('ougc_plugins');
	if(!$plugins)
	{
		$plugins = array();
	}

	$plugin = ougc_hal_info();

	if(!isset($plugins['ougc_hal']))
	{
		$plugins['ougc_hal'] = $plugin['versioncode'];
	}

	/*~*~* RUN UPDATES START *~*~*/

	/*~*~* RUN UPDATES END *~*~*/

	$plugins['ougc_hal'] = $plugin['versioncode'];
	$mybb->cache->update('ougc_plugins', $plugins);
}

// _is_installed() routine
function ougc_hal_is_installed()
{
	global $cache;

	$plugins = $cache->read('ougc_plugins');

	return isset($plugins['ougc_hal']);
}

// _uninstall() routine
function ougc_hal_uninstall()
{
	global $PL, $cache;
	ougc_hal_load_pluginlibrary();

	$PL->settings_delete('ougc_hal');

	// Delete version from cache
	$plugins = (array)$cache->read('ougc_plugins');

	if(isset($plugins['ougc_hal']))
	{
		unset($plugins['ougc_hal']);
	}

	if(!empty($plugins))
	{
		$cache->update('ougc_plugins', $plugins);
	}
	else
	{
		$PL->cache_delete('ougc_plugins');
	}
}

// PluginLibrary requirement check
function ougc_hal_load_pluginlibrary()
{
	global $lang, $PL;

	$plugin = ougc_hal_info();

	!file_exists(PLUGINLIBRARY) || $PL or require_once PLUGINLIBRARY;

	isset($lang->setting_group_ougc_hal) || $lang->load('ougc_hal');

	if(!file_exists(PLUGINLIBRARY) || empty($PL->version) || $PL->version < $plugin['pl']['version'])
	{
		flash_message($lang->printf($lang->ougc_hal_pluginlibrary, $plugin['pl']['url'], $plugin['pl']['version']), 'error');
		admin_redirect('index.php?module=config-plugins');
	}
}

// Add our moderation option
function online_user()
{
	global $user, $mybb;

	static $admins = null;

	if($admins === null)
	{
		$admins = array();

		global $mybb;

		$admins['users'] = explode(',', (string)$mybb->config['super_admins']);

		$admins['groups'] = array();

		foreach($mybb->cache->cache['usergroups'] as $group)
		{
			if((bool)$group['cancp'])
			{
				$admins['groups'][(int)$group['gid']] = $group['gid'];
			}
		}

		$admins['users'] = array_filter(array_map('intval', array_merge($admins['users'], explode(',', OUGC_HAL_SETTING_UIDS))));
		$admins['groups'] = array_filter(array_map('intval', array_merge($admins['groups'], explode(',', OUGC_HAL_SETTING_GIDS))));
	}

	if(in_array($mybb->user['uid'], $admins['users']) || is_member($admins['groups']))
	{
		return;
	}

	$uids = array_filter(array_map('intval', array_merge($admins['users'], explode(',', $mybb->settings['ougc_hal_uids']))));

	if(
		in_array($user['uid'], $uids) ||
		in_array($user['uid'], $admins['users']) ||
		is_member($admins['groups'], $user) ||
		$mybb->settings['ougc_hal_gids'] == -1
	)
	{
		$user['ip'] = '';
		//$user['nopermission'] = 1;
		$user['location'] = '/index.php?';
		//$user['uid'] = 0;
		//$user['invisible'] = 1;
	}
}