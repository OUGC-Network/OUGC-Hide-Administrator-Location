<?php

/***************************************************************************
 *
 *    OUGC Hide Administrator Location (/inc/plugins/ougcHideAdminLoc/admin.php)
 *    Author: Omar Gonzalez
 *    Copyright: © 2016 - 2023 Omar Gonzalez
 *
 *    Website: https://ougc.network
 *
 *    Hide administrator's location from the Who Is Online (WOL) list.
 *
 ***************************************************************************
 ****************************************************************************
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ****************************************************************************/

namespace OUGCHideAdminLoc\Admin;

function pluginInfo(): array
{
    global $lang;

    \OUGCHideAdminLoc\Core\loadLanguage();

    return [
        'name' => 'OUGC Hide Administrator Location',
        'description' => $lang->setting_group_ougc_hal_desc,
        'website' => 'https://ougc.network',
        'author' => 'Omar G.',
        'authorsite' => 'https://ougc.network',
        'version' => '1.8.33',
        'versioncode' => 1833,
        'compatibility' => '183*',
        'codename' => 'ougc_hal',
        'pl' => [
            'version' => 13,
            'url' => 'http://community.mybb.com/mods.php?action=view&pid=573'
        ]
    ];
}

function pluginActivate(): true
{
    global $PL, $lang, $cache;

    \OUGCHideAdminLoc\Core\loadPluginLibrary();

    // Add settings group
    $PL->settings('ougc_hal', $lang->setting_group_ougc_hal, $lang->setting_group_ougc_hal_desc, [
        'uids' => [
            'title' => $lang->setting_ougc_hal_uids,
            'description' => $lang->setting_ougc_hal_uids_descs,
            'optionscode' => 'text',
            'value' => '',
        ],
        'gids' => [
            'title' => $lang->setting_ougc_hal_gids,
            'description' => $lang->setting_ougc_hal_gids_desc,
            'optionscode' => 'groupselect',
            'value' => 4,
        ]
    ]);

    // Insert/update version into cache
    $plugins = (array)$cache->read('ougc_plugins');

    if (!$plugins) {
        $plugins = [];
    }

    if (!isset($plugins['ougc_hal'])) {
        $plugins['ougc_hal'] = pluginInfo()['versioncode'];
    }

    /*~*~* RUN UPDATES START *~*~*/

    /*~*~* RUN UPDATES END *~*~*/

    $plugins['ougc_hal'] = pluginInfo()['versioncode'];

    $cache->update('ougc_plugins', $plugins);

    return true;
}

function pluginIsInstalled(): bool
{
    global $cache;

    $plugins = $cache->read('ougc_plugins');

    return isset($plugins['ougc_hal']);
}

function pluginUninstall(): bool
{
    global $db, $PL, $cache;

    \OUGCCoinbasePoints\Core\loadPluginLibrary();

    $PL->settings_delete('ougc_hal');

    // Delete version from cache
    $plugins = (array)$cache->read('ougc_plugins');

    if (isset($plugins['ougc_hal'])) {
        unset($plugins['ougc_hal']);
    }

    if (!empty($plugins)) {
        $cache->update('ougc_plugins', $plugins);
    } else {
        $PL->cache_delete('ougc_plugins');
    }

    return true;
}