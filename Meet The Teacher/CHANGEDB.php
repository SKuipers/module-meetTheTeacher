<?php
//USE ;end TO SEPERATE SQL STATEMENTS. DON'T USE ;end IN ANY OTHER PLACES!

$sql = array();
$count = 0;

//v0.0.01
$sql[$count][0] = '0.0.01';
$sql[$count][1] = '-- First version, nothing to update';

//v0.0.02
++$count;
$sql[$count][0] = '0.0.02';
$sql[$count][1] = '';

//v0.0.03
++$count;
$sql[$count][0] = '0.0.03';
$sql[$count][1] = "
INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'authenticateBy', 'Authenticate By', 'Which authentication method is configured in MTT.', 'rollGroup');end
";

//v0.0.04
++$count;
$sql[$count][0] = '0.0.04';
$sql[$count][1] = "INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'textUnavailable', 'Text when Unavailable', 'A message to display in the dashboard tab when MTT is unavailable for this user.', 'Login access for Meet The Teacher is not available at this time, or is not currently active for students of this year group.');";

//v1.0.00
++$count;
$sql[$count][0] = '1.0.00';
$sql[$count][1] = "
UPDATE gibbonModule SET author='Jim Speir, Sandra Kuipers & Ross Parker', description='Provides an API for data syncing, and and interface for acess, to making using Gibbon and the online Meet The Teacher service easy.' WHERE name='Meet The Teacher';end
CREATE TABLE `meetTheTeacherCustomGroups` (`ID` int(11) NOT NULL AUTO_INCREMENT, `TeacherID` int(10) unsigned zerofill NOT NULL, `StudentID` int(10) unsigned zerofill NOT NULL, `GroupName` varchar(255) NOT NULL, `GroupID` int(11) DEFAULT NULL, PRIMARY KEY (`ID`), KEY `TeacherID` (`TeacherID`), KEY `StudentID` (`StudentID`), KEY `GroupID` (`GroupID`)) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;end
INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'apiKey', 'API Key', 'Long, random string controlling access to API.', '');end
INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'allowedIPs', 'Allowed IP Address', 'Comma-seperated list of IP addresses with permission to access the API.', '');end
INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'lsTeacherRole', 'LS Teacher Role', 'User role which designates who the learning support teachers in school are. Leave blank to show all teacher roles on the API.', '');end
INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'lsIgnoreClasses', 'Ignore Classes', 'Set whether teachers only show on the API if they have an assigned student or simply assign all teachers with the specified role to all children with individual needs.', '');end
INSERT INTO `gibbonSetting` (`gibbonSettingID` ,`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES (NULL , 'Meet The Teacher', 'version', 'API Version', 'Currently installed version of the Meet The Teacher', '" . $version . "');end
";

//v1.0.01
++$count;
$sql[$count][0] = '1.0.01';
$sql[$count][1] = "";
