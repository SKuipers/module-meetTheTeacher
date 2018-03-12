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

class StudentController implements PESAPIController
{
	private $db;
	protected $sqlCommands = array(
		"GetAllStudents" => "
			select
				gse.gibbonStudentEnrolmentID as 'EnrolmentID',
				gp.gibbonPersonID as 'PersonID',
				gyg.name as 'YearName',
				gyg.nameShort as 'ShortYearName',
				gp.status as 'Status',
				gp.preferredName as 'Forename',
				gp.surname as 'Surname',
				gp.dob as 'DOB',
				grg.nameShort as 'RegistrationClass',
				gp.status as 'Status'
			from
				gibbonStudentEnrolment gse
			inner join gibbonPerson gp on gp.gibbonPersonID = gse.gibbonPersonID
			inner join gibbonYearGroup gyg on gyg.gibbonYearGroupID = gse.gibbonYearGroupID
			inner join gibbonRollGroup grg on grg.gibbonRollGroupID = gse.gibbonRollGroupID
			inner join gibbonSchoolYear gsy on gse.gibbonSchoolYearID=gsy.gibbonSchoolYearID
			where
				gsy.status='Current'

		",

    "GetAllCurrentStudents" => "
      	select
				gse.gibbonStudentEnrolmentID as 'EnrolmentID',
				gp.gibbonPersonID as 'PersonID',
				gyg.name as 'YearName',
				gyg.nameShort as 'ShortYearName',
				gp.status as 'Status',
				gp.preferredName as 'Forename',
				gp.surname as 'Surname',
				gp.dob as 'DOB',
				grg.nameShort as 'RegistrationClass',
				gp.status as 'Status'
			from
				gibbonStudentEnrolment gse
			inner join gibbonPerson gp on gp.gibbonPersonID = gse.gibbonPersonID
			inner join gibbonYearGroup gyg on gyg.gibbonYearGroupID = gse.gibbonYearGroupID
			inner join gibbonRollGroup grg on grg.gibbonRollGroupID = gse.gibbonRollGroupID
			inner join gibbonSchoolYear gsy on gse.gibbonSchoolYearID=gsy.gibbonSchoolYearID
			where
				gsy.status='Current'
        and gp.status = 'Full'
        and (gp.dateStart is null or gp.dateStart <= curdate())
        and (gp.dateEnd is null or gp.dateEnd >= curdate());",

		"GetStudentByID" => "
			select
				gse.gibbonStudentEnrolmentID as 'EnrolmentID',
				gp.gibbonPersonID as 'PersonID',
				gyg.name as 'YearName',
				gyg.nameShort as 'ShortYearName',
				gp.status as 'Status',
				gp.firstName as 'Forename',
				gp.surname as 'Surname',
				gp.dob as 'DOB',
				grg.nameShort as 'RegistrationClass',
				gp.status as 'Status'
			from
				gibbonStudentEnrolment gse
			inner join gibbonPerson gp on gp.gibbonPersonID = gse.gibbonPersonID
			inner join gibbonYearGroup gyg on gyg.gibbonYearGroupID = gse.gibbonYearGroupID
			inner join gibbonRollGroup grg on grg.gibbonRollGroupID = gse.gibbonRollGroupID
			inner join gibbonSchoolYear gsy on gse.gibbonSchoolYearID=gsy.gibbonSchoolYearID
			where
				gp.gibbonPersonID = :ID
				AND gsy.status='Current'
		"
	);

	public function __construct($_db)
	{
		$this->db = new DatabaseHelper($_db);
	}

  public function GetAll()
  {
    return $this->GetAllOnRoll();
  }

	public function GetAllCurrent()
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands["GetAllStudents"],"Student",null);
		}
		catch(Exception $_e)
		{
			$e = new Exception("Failed to return data from database while getting all students");
			$e->InnerException = $_e;
			throw $e;
		}
	}

  public function GetAllOnRoll()
  {
    try
    {
      return $this->db->RunSQL($this->sqlCommands['GetAllCurrentStudents'],"Student",null);
    }
    catch(Exception $_e)
    {
      $e = new Exception("Failed to return data from database while getting student");
      $e->InnerException = $_e;
      throw $e;
    }
  }

	public function GetByID($id)
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands["GetStudentByID"],"Student",array("ID" => $id));
		}
		catch(Exception $_e)
		{
			$e = new Exception("Failed to return data from database while getting student");
			$e->InnerException = $_e;
			throw $e;
		}
	}
}
?>
