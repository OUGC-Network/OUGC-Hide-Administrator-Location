<?php

/***************************************************************************
 *
 *	OUGC Hide Administrator Location (/inc/plugins/ougc_hal.php)
 *	Author: Omar Gonzalez
 *	Copyright: © 2016 Omar Gonzalez
 *   
 *	Website: http://omarg.me
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

// Tell MyBB when to run the hook
if(!defined('IN_ADMINCP'))
{
	$plugins->add_hook('online_user', 'online_user');
}

// Settings
define('OUGC_HAL_SETTING_UIDS', ''); // comma separated users (uid) to hide
define('OUGC_HAL_SETTING_GIDS', ''); // comma separated groups (gid) to hide

// Plugin API
function ougc_hal_info()
{
	return array(
		'name'			=> 'OUGC Hide Administrator Location',
		'description'	=> "Hide administrator's location at WOL list.",
		'website'		=> 'http://mods.mybb.com/view/ougc-inlire',
		'author'		=> 'Omar Gonzalez',
		'authorsite'	=> 'http://omarg.me',
		'version'		=> '1.0',
		'guid' 			=> '',
		'compatibility' => '18*'
	);
}

// Add our moderation option
function online_user()
{
	global $user, $mybb;

	static $admins = array();
	if(empty($admins))
	{
		global $config;

		$admins['users'] = explode(',', (string)$config['super_admins']);

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
		//return;
	}
_dump($admins);
	if(in_array($user['uid'], $admins['users']) || is_member($admins['groups'], $user))
	{
		$user['ip'] = '';
		//$user['nopermission'] = 1;
		$user['location'] = '/index.php?';
		//$user['uid'] = 0;
		//$user['invisible'] = 1;
	}
}