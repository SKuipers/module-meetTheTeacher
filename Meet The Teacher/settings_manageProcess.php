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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

include '../../functions.php';
include '../../config.php';

//New PDO DB connection
$pdo = new Gibbon\sqlConnection();
$connection2 = $pdo->getConnection();

@session_start();

$URL = $_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/'.getModuleName($_POST['address']).'/settings_manage.php';

if (isActionAccessible($guid, $connection2, '/modules/Meet The Teacher/settings_manage.php') == false) {
    $URL .= '&return=error0';
    header("Location: {$URL}");
} else {
    //Proceed!
    $apiKey = $_POST['apiKey'];
    $allowedIPs = $_POST['allowedIPs'];
    $lsTeacherRole = $_POST['lsTeacherRole'];
    $lsIgnoreClasses = 0;
    if(isset($_POST['lsIgnoreClasses']))
    {
      $lsIgnoreClasses = $_POST['lsIgnoreClasses'] ? 1 : 0;
    }
    $modVer = '';
    if(isset($_POST['apiVersion']))
    {
      $modVer = $_POST['apiVersion'];
    }
    $url = $_POST['url'];
    $text = $_POST['text'];
    $textUnavailable = $_POST['textUnavailable'];
    $yearGroups = $_POST['yearGroups'];
    $authenticateBy = $_POST['authenticateBy'];

    //Validate Inputs
    if ($apiKey == '' or $allowedIPs == '' or $url == '' or $text == '' or $yearGroups == '') {
        $URL .= '&return=error3';
        header("Location: {$URL}");
    } else {
        //Write to database
        $fail = false;

        try {
            $data = array('apiKey' => $apiKey);
            $sql = "UPDATE gibbonSetting SET value=:apiKey WHERE scope='Meet The Teacher' AND name='apiKey'";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $fail = true;
        }

        try {
            $data = array('allowedIPs' => $allowedIPs);
            $sql = "UPDATE gibbonSetting SET value=:allowedIPs WHERE scope='Meet The Teacher' AND name='allowedIPs'";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $fail = true;
        }

        try {
            $data = array('lsTeacherRole' => $lsTeacherRole);
            $sql = "UPDATE gibbonSetting SET value=:lsTeacherRole WHERE scope='Meet The Teacher' AND name='lsTeacherRole'";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $fail = true;
        }

        try {
            $data = array('lsIgnoreClasses' => $lsIgnoreClasses);
            $sql = "UPDATE gibbonSetting SET value=:lsIgnoreClasses WHERE scope='Meet The Teacher' AND name='lsIgnoreClasses'";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $fail = true;
        }

        try{
          $data = array('version' => $modVer);
          $sql = "UPDATE gibbonSetting set value = :version WHERE scope = 'Meet The Teacher' AND name = 'version'";
          $result = $connection2->prepare($sql);
          $result->execute($data);
        } catch(PDOException $e) {
          $fail = true;
        }

        try {
            $data = array('value' => $url);
            $sql = "UPDATE gibbonSetting SET value=:value WHERE scope='Meet The Teacher' AND name='url'";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $fail = true;
        }

        try {
            $data = array('value' => $text);
            $sql = "UPDATE gibbonSetting SET value=:value WHERE scope='Meet The Teacher' AND name='text'";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $fail = true;
        }

        try {
            $data = array('value' => $textUnavailable);
            $sql = "UPDATE gibbonSetting SET value=:value WHERE scope='Meet The Teacher' AND name='textUnavailable'";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $fail = true;
        }

        try {
            $data = array('value' => $yearGroups);
            $sql = "UPDATE gibbonSetting SET value=:value WHERE scope='Meet The Teacher' AND name='yearGroups'";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $fail = true;
        }

        try {
            $data = array('value' => $authenticateBy);
            $sql = "UPDATE gibbonSetting SET value=:value WHERE scope='Meet The Teacher' AND name='authenticateBy'";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $fail = true;
        }

        if ($fail == true) {
            $URL .= '&return=error2';
            header("Location: {$URL}");
        } else {
            getSystemSettings($guid, $connection2);
            $URL .= '&return=success0';
            header("Location: {$URL}");
        }
    }
}
