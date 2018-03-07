<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

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
*/

//This file describes the module, including database tables

//Basic variables
$name = 'Meet The Teacher';
$description = 'The Meet The Teacher module provides easy access for parents (via Gibbon\'s Parent Dashboard) to the online Meet The Teacher service.';
$entryURL = 'meetTheTeacher_view.php';
$type = 'Additional';
$category = 'Other';
$version = '0.0.02';
$author = 'Sandra Kuipers & Ross Parker';
$url = 'http://gibbonedu.org';

//Module tables
$moduleTables[0] = "CREATE TABLE `meetTheTeacherLogin` (
  `meetTheTeacherLoginID` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `gibbonPersonID` int(10) UNSIGNED ZEROFILL NOT NULL,
  `loginCode` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`meetTheTeacherLoginID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

//Action rows
$actionRows[0]['name'] = 'View Meet The Teacher';
$actionRows[0]['precedence'] = '0';
$actionRows[0]['category'] = 'Meet The Teacher';
$actionRows[0]['description'] = 'Allows a user to view the Meet The Teacher information.';
$actionRows[0]['URLList'] = 'meetTheTeacher_view.php';
$actionRows[0]['entryURL'] = 'meetTheTeacher_view.php';
$actionRows[0]['menuShow'] = 'N';
$actionRows[0]['defaultPermissionAdmin'] = 'N';
$actionRows[0]['defaultPermissionTeacher'] = 'N';
$actionRows[0]['defaultPermissionStudent'] = 'N';
$actionRows[0]['defaultPermissionParent'] = 'Y';
$actionRows[0]['defaultPermissionSupport'] = 'N';
$actionRows[0]['categoryPermissionStaff'] = 'N';
$actionRows[0]['categoryPermissionStudent'] = 'N';
$actionRows[0]['categoryPermissionParent'] = 'Y';
$actionRows[0]['categoryPermissionOther'] = 'N';

$actionRows[1]['name'] = 'Manage Settings';
$actionRows[1]['precedence'] = '0';
$actionRows[1]['category'] = 'Admin';
$actionRows[1]['description'] = 'Allows a privileged user to manage Meet The Teacher settings.';
$actionRows[1]['URLList'] = 'settings_manage.php';
$actionRows[1]['entryURL'] = 'settings_manage.php';
$actionRows[1]['defaultPermissionAdmin'] = 'Y';
$actionRows[1]['defaultPermissionTeacher'] = 'N';
$actionRows[1]['defaultPermissionStudent'] = 'N';
$actionRows[1]['defaultPermissionParent'] = 'N';
$actionRows[1]['defaultPermissionSupport'] = 'N';
$actionRows[1]['categoryPermissionStaff'] = 'Y';
$actionRows[1]['categoryPermissionStudent'] = 'N';
$actionRows[1]['categoryPermissionParent'] = 'N';
$actionRows[1]['categoryPermissionOther'] = 'N';

//Settings
$gibbonSetting[0] = "INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'yearGroups', 'Year Groups', 'List of year group short names to match against, as a comma-separated list.', 'Y07,Y08,Y09,Y10,Y11,Y12,Y13');";
$gibbonSetting[1] = "INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'text', 'Text', 'Introductory text used at the top of the dashboard entry', 'Login to Meet The Teacher, using the link below, to make consultation bookings:');";
$gibbonSetting[2] = "INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'url', 'URL', 'URL to use for school\'s MTT installation.', 'https://school.meettheteacher.com');";

//HOOKS
$array = array();
$array['sourceModuleName'] = 'Meet The Teacher';
$array['sourceModuleAction'] = 'View Meet The Teacher';
$array['sourceModuleInclude'] = 'hook_dashboard_meetTheTeacher.php';
$hooks[0] = "INSERT INTO `gibbonHook` (`gibbonHookID`, `name`, `type`, `options`, gibbonModuleID) VALUES (NULL, 'Meet The Teacher', 'Parental Dashboard', '".serialize($array)."', (SELECT gibbonModuleID FROM gibbonModule WHERE name='$name'));";
