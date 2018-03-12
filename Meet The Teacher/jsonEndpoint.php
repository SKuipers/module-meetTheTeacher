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

	include './modConfig.php';

	$canContinue = false;

	$apiKeyProvided = null;
	if (empty($APIKey)) {
		print "API Key has not been set in Manage Settings.";
		http_response_code(400);
		exit;
	}
	else {
		if (empty($ALLOWED_IPS)) {
			print "Allowed IP Addresses has not been set in Manage Settings.";
			http_response_code(400);
			exit;
		}
		else {
			$apiKeyProvided = null;
			if (empty($_POST['apiKey'])) {
				print "An API key has not been provided";
				http_response_code(400);
				exit;
			}
			else {
				$apiKeyProvided = $_POST['apiKey'];

				$realIP = getIPAddress();
				foreach($ALLOWED_IPS as $ip)
				{
					$ip = trim($ip);
					if($realIP == $ip || $realIP == $ip) //REMOTE_ADDR for clients not behind proxy, HTTP_X_FORWARDED_FOR for clients behind proxies
					{
                           $canContinue = true;
					}
				}

				if($canContinue == false)
				{
					print "Your IP address is not in the allow list.";
					http_response_code(403);
					exit;
				}

				if($canContinue == true)
				{

					$controllers = array(
						"Students" => new StudentController($connection2),
						"Staff" => new StaffController($connection2),
						"Contacts" => new ContactController($connection2),
						"CustomGroupLinks" => new CustomGroupController($connection2),
						"ActivityGroupLinks" => new ActivityGroupController($connection2),
						"ContactLinks" => New ContactLinkController($connection2),
						"RollGroupLinks" => new RollGroupController($connection2),
						"ClassLinks" => new ClassController($connection2)
					);
	
					$response = array();
					try
					{
            $response['Info'] = array(
              "APIVersion" => getSettingByScope($connection2, 'Meet The Teacher', 'version',true)['value'],
              "IgnoreClasses" => getSettingByScope($connection2, 'Meet The Teacher', 'lsIgnoreClasses',true)['value'],
              "LSRole" => getSettingByScope($connection2, 'Meet The Teacher', 'lsTeacherRole', true)['value'],
              "GibbonVersion" => $version
            );
						foreach($controllers as $controllerNode => $controller)
						{
							$response[$controllerNode] = $controller->GetAll();
						}

            if(version_compare($version, "15.0.00", '>='))
            {
                $INController = new IndividualNeedsGroupController($connection2); //Implemented in version 15.0.00
                $lsrole = getSettingByScope($connection2, 'Meet The Teacher', 'lsTeacherRole', true)['value'];
                $ignoreClassAllocations = getSettingByScope($connection2, 'Meet The Teacher', 'lsIgnoreClasses',true)['value'];
                if($lsrole == "")
                {
                    //Just get everything
                    $response["IndividualNeedsGroups"] = $INController->GetAll();
                }
                else
                {
                    if($ignoreClassAllocations == "1")
                    {
                        $response["IndividualNeedsGroups"] = $INController->ClasslessGetByRole($lsrole); //Return all students with IN assigned to all teachers with the specified role
                    }
                    else
                    {
                        $response["IndividualNeedsGroups"] = $INController->GetByRole($lsrole); //Return all IN allocations with set assistants filtered by role
                    }
                }
            }

					}
					catch(Exception $e)
					{
						print "error";
						var_dump($e);
					}
					header("Content-Type: text/json");
					print json_encode($response);
				}
			}
		}
	}
?>
