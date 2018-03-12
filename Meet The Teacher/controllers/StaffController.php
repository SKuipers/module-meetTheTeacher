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

class StaffController implements PESAPIController
{
	protected $sqlCommands = array(
		"GetAllStaff" => "
		select
				gs.type as 'Type',
				gs.gibbonStaffID as 'StaffID',
				gs.gibbonPersonID as 'PersonID',
				gp.title as 'Title',
				gp.preferredName as 'Forename',
				gp.surname as 'Surname',
				gp.username as 'Username',
				gp.email as 'Email' ,
				gp.status as 'Status'
			from
				gibbonStaff gs
			inner join gibbonPerson gp on gp.gibbonPersonID = gs.gibbonPersonID
			where
				gp.status in ('Full','Expected')
		;",

		"GetStaffByID" => "
			select
				gs.type as 'Type',
				gs.gibbonStaffID as 'StaffID',
				gs.gibbonPersonID as 'PersonID',
				gp.title as 'Title',
				gp.preferredName as 'Forename',
				gp.surname as 'Surname',
				gp.username as 'Username',
				gp.email as 'Email' ,
				gp.status as 'Status'
			from
				gibbonStaff gs
			inner join gibbonPerson gp on gp.gibbonPersonID = gs.gibbonPersonID
			where
				gp.status in ('Full','Expected')
		;"

	);

	private $db;

	public function __construct($_db)
	{
		$this->db = new DatabaseHelper($_db);
	}

	public function GetAll()
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetAllStaff'],'StaffMember',null);
		}
		catch(Exception $_e)
		{
			$e = new Exception("Failed to get all staff members from the system");
			$e->InnerException = $_e;
			throw $e;
		}
	}

	public function GetByID($id)
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands['GetStaffByID'],'StaffMember',array("ID" => $id));
		}
		catch(Exception $_e)
		{
			$e = new Exception("Failed to get all staff member by ID from the system");
			$e->InnerException = $_e;
			throw $e;
		}
	}

}

?>
