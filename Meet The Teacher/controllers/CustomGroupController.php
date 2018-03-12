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

class CustomGroupController
{
	private $DatabaseHelper;
	function __construct($_db)
	{
		$this->DatabaseHelper = new DatabaseHelper($_db);
	}

	public function GetAll()
	{
		try
		{
			//var_dump( $this->DatabaseHelper->RunSQL("select ID, TeacherID, StudentID, GroupName, GroupID from meetTheTeacherCustomGroups;",null,null));
			return $this->DatabaseHelper->RunSQL("select ID, TeacherID, StudentID, GroupName, GroupID from meetTheTeacherCustomGroups;","CustomGroupLink",null);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function GetGroupByName($name)
	{	
		try
		{
			return $this->DatabaseHelper->RunSQL("select ID, TeacherID, StudentID, GroupName, GroupID from meetTheTeacherCustomGroups where GroupName = :name;","CustomGroupLink",array("name" => $name));
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function GetGroupByID($id)
	{

		try
		{
			return $this->DatabaseHelper->RunSQL("select ID, TeacherID, StudentID, GroupName, GroupID from meetTheTeacherCustomGroups where ID = :id;","CustomGroupLink",array("id" => $id));
		}
		catch(Exception $e)
		{
			throw $e;
		}

	}

	public function GetGroupByStudentID($id)
	{
		try
		{
			return $this->DatabaseHelper->RunSQL("select ID, TeacherID, StudentID, GroupName, GroupID from meetTheTeacherCustomGroups where StudentID = :id;","CustomGroupLink",array("id" => $id));
		}
		catch(Exception $e)
		{
			throw $e;
		}

	}

	public function GetGroupByTeacherID($id)
	{
		try
		{
			return $this->DatabaseHelper->RunSQL("select ID, TeacherID, StudentID, GroupName, GroupID from meetTheTeacherCustomGroups where TeacherID = :id;","CustomGroupLink",array("id" => $id));
		}
		catch(Exception $e)
		{
			throw $e;
		}

	}

	public function AddGroupLink($cgLink)
	{
		if($cgLink != null)
		{
			if($cgLink->Validate())
			{
				try
				{
					return $this->DatabaseHelper->RunSQL("insert into meetTheTeacherCustomGroups(TeacherID,StudentID,GroupName,GroupID) values (:TeacherID,:StudentID,:GroupName,:GroupID);",null,$cgLink);
					/*
					array(
						"TeacherID" => $cgLink->TeacherID,
						"StudentID" => $cgLink->StudentID,
						"GroupName" => $cgLink->GroupName,
						"GroupID" => $gcLink->GroupID
					));*/
				}
				catch(Exception $e)
				{
					$addE = new Exception();
					$addE->Message = "An error occurred while adding a new group link. Check innerException for more information";
					$addE->InnerException = $e;
					throw $addE;
				}
			}
		}
		$e = new Exception("Failed to add the custom group entry");
		$e->CGLink = $cgLink;
		throw $e;
	}

	public function DeleteGroupLinkByID($id)
	{
		try
		{
			return $this->DatabaseHelper->RunSQL("delete from meetTheTeacherCustomGroups where ID = :id;",null,array("id" => $id));			
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

}


/*
	Testing Code:
	
	$cgc = new CustomGroupController( new PDO("mysql:host=" . $databaseServer . ";dbname=" . $databaseName . ";charset=utf8", $databaseUsername, $databasePassword));
	$newGroupLink = new CustomGroupLink(null,"0000002112","0000000001","Test",1);
	var_dump($cgc->DeleteGroupLinkByID(($cgc->AddGroupLink($newGroupLink))));
	$cgc->GetAll();
	foreach($cgc->GetGroupByStudentID("0000002112") as $g)
	{
		$cgc->DeleteGroupLinkByID($g->ID);
	}
*/

?>
