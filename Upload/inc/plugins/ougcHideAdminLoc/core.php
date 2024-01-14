<?php

/***************************************************************************
 *
 *    OUGC Hide Administrator Location (/inc/plugins/ougcHideAdminLoc/core.php)
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

namespace OUGCHideAdminLoc\Core;

function loadLanguage(): bool
{
    global $lang;

    if (!isset($lang->setting_group_ougc_hal)) {
        $lang->load('ougc_hal');
    }

    return true;
}

function pluginLibraryRequirements(): object
{
    return (object)\OUGCHideAdminLoc\Admin\pluginInfo()['pl'];
}

function loadPluginLibrary(bool $doCheck = true): bool
{
    global $PL, $lang;

    loadLanguage();

    if ($fileExists = file_exists(PLUGINLIBRARY)) {
        ($PL instanceof PluginLibrary) or require_once PLUGINLIBRARY;
    }

    if (!$doCheck) {
        return false;
    }

    if (!$fileExists || $PL->version < pluginLibraryRequirements()->version) {
        flash_message(
            $lang->sprintf(
                $lang->ougc_hal_pluginlibrary,
                pluginLibraryRequirements()->url,
                pluginLibraryRequirements()->version
            ),
            'error'
        );

        admin_redirect('index.php?module=config-plugins');
    }

    return true;
}

function addHooks(string $namespace): bool
{
    global $plugins;

    $namespaceLowercase = strtolower($namespace);
    $definedUserFunctions = get_defined_functions()['user'];

    foreach ($definedUserFunctions as $callable) {
        $namespaceWithPrefixLength = strlen($namespaceLowercase) + 1;

        if (substr($callable, 0, $namespaceWithPrefixLength) == $namespaceLowercase . '\\') {
            $hookName = substr_replace($callable, '', 0, $namespaceWithPrefixLength);

            $priority = substr($callable, -2);

            if (is_numeric(substr($hookName, -2))) {
                $hookName = substr($hookName, 0, -2);
            } else {
                $priority = 10;
            }

            $plugins->add_hook($hookName, $callable, $priority);
        }
    }

    return true;
}

function getSetting(string $settingKey = ''): string
{
    global $mybb;

    $string = 'OUGC_HAL_' . strtoupper($settingKey);

    return defined($string) ? constant($string) : (string)$mybb->settings['ougc_hal_' . $settingKey];
}