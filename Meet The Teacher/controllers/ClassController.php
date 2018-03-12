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

class ClassController implements PESAPIController
{
	private $sqlCommands = array(
		"GetAll" => "
			select distinct
				teacher.gibbonPersonID as 'TeacherID',
				student.gibbonPersonID as 'StudentID',
				cc.gibbonCourseClassID as 'ClassID',
				cc.nameShort as 'ClassReference',
				c.name as 'SubjectName'
			from
				gibbonCourse c
			inner join gibbonCourseClass cc on cc.gibbonCourseID = c.gibbonCourseID
			right outer join gibbonCourseClassPerson t_ccp on (t_ccp.gibbonCourseClassID = cc.gibbonCourseClassID and t_ccp.role = 'Teacher')
			right outer join gibbonCourseClassPerson s_ccp on (s_ccp.gibbonCourseClassID = cc.gibbonCourseClassID and s_ccp.role = 'Student')
			inner join gibbonPerson student on s_ccp.gibbonPersonID = student.gibbonPersonID
			inner join gibbonPerson teacher on t_ccp.gibbonPersonID = teacher.gibbonPersonID
			inner join gibbonSchoolYear ON (c.gibbonSchoolYearID=gibbonSchoolYear.gibbonSchoolYearID)
			where
				student.status = 'Full'
				AND teacher.status='Full'
				AND gibbonSchoolYear.status='Current'
        AND cc.reportable = 'Y'
        AND s_ccp.reportable = 'Y'
		;",

		"GetByID" => "
			select distinct
				teacher.gibbonPersonID as 'TeacherID',
				student.gibbonPersonID as 'StudentID',
				cc.gibbonCourseClassID as 'ClassID',
				cc.nameShort as 'ClassReference',
				c.name as 'SubjectName'
			from
				gibbonCourse c
			inner join gibbonCourseClass cc on cc.gibbonCourseID = c.gibbonCourseID
			right outer join gibbonCourseClassPerson t_ccp on (t_ccp.gibbonCourseClassID = cc.gibbonCourseClassID and t_ccp.role = 'Teacher')
			right outer join gibbonCourseClassPerson s_ccp on (s_ccp.gibbonCourseClassID = cc.gibbonCourseClassID and s_ccp.role = 'Student')
			inner join gibbonPerson student on s_ccp.gibbonPersonID = student.gibbonPersonID
			inner join gibbonPerson teacher on t_ccp.gibbonPersonID = teacher.gibbonPersonID
			inner join gibbonSchoolYear ON (c.gibbonSchoolYearID=gibbonSchoolYear.gibbonSchoolYearID)
			where
				student.status = 'Full'
				AND teacher.status='Full'
				AND gibbonSchoolYear.status='Current'
				AND cc.gibbonCourseClassID = :ID
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
			return $this->db->RunSQL($this->sqlCommands['GetAll'],"ClassLink",null);
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
			return $this->db->RunSQL($this->sqlCommands['GetByID'],'ClassLink',array('ID' => $id));
		}
		catch(PDOException $_e)
		{
			throw $_e;
		}
	}
}

?>
