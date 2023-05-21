<?php

/***************************************************************************
 *
 *	OUGC Hide Administrator Location (/inc/plugins/ougcHideAdminLoc/forumHooks.php)
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

namespace OUGCHideAdminLoc\ForumHooks;

function online_user() : bool {
    global $user, $mybb;

    static $adminUsers = null;

    static $hiddenUsers = null;

    if($adminUsers === null) {
        $adminUsers = [
            'users' => array_map(
                'intval',
                explode(
                    ',',
                    (string) $mybb->config['super_admins']
                )
            ),
            'groups' => []
        ];

        foreach((array) $mybb->cache->cache['usergroups'] as $groupPermissions) {
            if((bool) $groupPermissions['cancp']) {
                $adminUsers['groups'][] = (int) $groupPermissions['gid'];
            }
        }

        foreach(OUGC_HAL_SETTING_UIDS as $userID) {
            $adminUsers['users'][] = (int) $userID;
        }

        foreach(OUGC_HAL_SETTING_GIDS as $groupID) {
            $adminUsers['groups'][] = (int) $groupID;
        }

        $hiddenUsers = [
            'users' => array_merge(
                $adminUsers['users'],
                array_map(
                    'intval',
                    explode(
                        ',',
                        \OUGCHideAdminLoc\Core\getSetting('uids')
                    )
                )
            ),
            'groups' => array_merge(
                $adminUsers['groups'],
                array_map(
                    'intval',
                    explode(
                        ',',
                        \OUGCHideAdminLoc\Core\getSetting('gids')
                    )
                )
            )
        ];
    }

    if(
        in_array($mybb->user['uid'], $adminUsers['users']) ||
        is_member($adminUsers['groups'])
    ) {
        return false;
    }

    $userData = [
        'usergroup' => $user['usergroup'],
        'additionalgroups' => '' // unsure why the core ignores this for the WOL
    ];

    if(
        in_array($user['uid'], $hiddenUsers['users']) ||
        is_member($hiddenUsers['groups'], $userData) ||
        (int) \OUGCHideAdminLoc\Core\getSetting('gids') === -1
    ) {
        $user['ip'] = '';
        $user['location'] = '/index.php?';
    }

    return true;
}