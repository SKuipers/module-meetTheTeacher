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

class RollGroupController implements PESAPIController
{
	private $sqlCommands = array(
		"GetAll" => "
			select
				s.gibbonPersonID as 'StudentID',
				se.gibbonRollGroupID as 'RollGroupID',
				rg.name as 'RollGroupName',
				rg.nameShort as 'RollGroupShortName',
				rg.gibbonPersonIDTutor as 'TeacherID1',
				rg.gibbonPersonIDTutor2 as 'TeacherID2',
				rg.gibbonPersonIDTutor3 as 'TeacherID3'
			from 
				gibbonPerson s
			inner join gibbonStudentEnrolment se on se.gibbonPersonID = s.gibbonPersonID
			inner join gibbonRollGroup rg on rg.gibbonRollGroupID = se.gibbonRollGroupID
		;",

		"GetByID" => "
			select
				s.gibbonPersonID as 'StudentID',
				se.gibbonRollGroupID as 'RollGroupID',
				rg.name as 'RollGroupName',
				rg.nameShort as 'RollGroupShortName',
				rg.gibbonPersonIDTutor as 'TeacherID1',
				rg.gibbonPersonIDTutor2 as 'TeacherID2',
				rg.gibbonPersonIDTutor3 as 'TeacherID3'
			from 
				gibbonPerson s
			inner join gibbonStudentEnrolment se on se.gibbonPersonID = s.gibbonPersonID
			inner join gibbonRollGroup rg on rg.gibbonRollGroupID = se.gibbonRollGroupID
			where
				s.gibbonPersonID = :ID
		;"
	);

	private $db;

	function __construct($_db)
	{
		$this->db = new DatabaseHelper($_db);
	}

	public function GetAll()
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetAll'],"RollGroup",null);
		}
		catch(PDOException $_e)
		{
			throw $_e;
		}
	}

	public function GetByID($id)
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetByID'],"RollGroup",null);
		}
		catch(PDOException $_e)
		{
			throw $_e;
		}

	}


}

?>
