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

class ActivityGroupController implements PESAPIController
{
	private $db;

	protected $sqlCommands = array
	(
		"GetAllActivityGroups" => "
			select
				a.gibbonActivityID as 'GroupID',
				a.name as 'GroupName',
				actstudent.gibbonPersonID as 'StudentID',
				actstaff.gibbonPersonID as 'TeacherID'
			from
				gibbonActivity a
			inner join gibbonActivityStudent actstudent on actstudent.gibbonActivityID = a.gibbonActivityID
			inner join gibbonActivityStaff actstaff on actstaff.gibbonActivityID = a.gibbonActivityID
			inner join gibbonSchoolYear as gsy on a.gibbonSchoolYearID=gsy.gibbonSchoolYearID
			where 
        gsy.status='Current'
		;",

		"GetActivityGroupByID" => "
			select
				a.gibbonActivityID as 'GroupID',
				a.name as 'GroupName',
				actstudent.gibbonPersonID as 'StudentID',
				actstaff.gibbonPersonID as 'TeacherID'
			from
				gibbonActivity a
			inner join gibbonActivityStudent actstudent on actstudent.gibbonActivityID = a.gibbonActivityID
			inner join gibbonActivityStaff actstaff on actstaff.gibbonActivityID = a.gibbonActivityID
			inner join gibbonSchoolYear as gsy on a.gibbonSchoolYearID=gsy.gibbonSchoolYearID
			where
				a.gibbonActivityID = :ID
				and gsy.status='Current'
		;"
	);

	public function __construct($_db)
	{
		$this->db = new DatabaseHelper($_db);
	}

	public function GetAll()
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetAllActivityGroups'],"ActivityGroupLink",null);
		}
		catch(Exception $_e)
		{
			$e = new Exception("Failed to run SQL while getting all activity groups.");
			$e->InnerException = $_e;
			throw $e;
		}
	}

	public function GetByID($id)
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetActivityGroupByID'],"ActivityGroupLink",array("ID" => $id));
		}
		catch(Exception $_e)
		{
			$e = new Exception("Failed to run SQL while getting all activity groups.");
			$e->InnerException = $_e;
			throw $e;
		}
	}

	public function Add($dataArr)
	{

	}
}

?>
