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
$version = '0.0.01';
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

//HOOKS
$array = array();
$array['sourceModuleName'] = 'Meet The Teacher';
$array['sourceModuleAction'] = 'View Meet The Teacher';
$array['sourceModuleInclude'] = 'hook_dashboard_meetTheTeacher.php';
$hooks[0] = "INSERT INTO `gibbonHook` (`gibbonHookID`, `name`, `type`, `options`, gibbonModuleID) VALUES (NULL, 'MTT', 'Parental Dashboard', '".serialize($array)."', (SELECT gibbonModuleID FROM gibbonModule WHERE name='$name'));";
