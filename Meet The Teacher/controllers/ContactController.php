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

class ContactController implements PESAPIController
{

	private $sqlCommands = array(
		"GetAll" =>"
			select
				p.gibbonPersonID as 'PersonID',
				p.title as 'Title',
				p.preferredName as 'Forename',
				p.surname as 'Surname',
				p.username as 'Username',
				p.email as 'Email',
				p.phone1 as 'Telephone',
				p.status as 'Status'
			from
				gibbonPerson p
			where
				exists (
					select gibbonPersonID from gibbonFamilyAdult where gibbonPersonID = p.gibbonPersonID
				)
				and p.status in ('Full','Expected')
		;",

		"GetByID" => "
			select
				p.gibbonPersonID as 'PersonID',
				p.title as 'Title',
				p.preferredName as 'Forename',
				p.surname as 'Surname',
				p.username as 'Username',
				p.email as 'Email',
				p.phone1 as 'Telephone',
				p.status as 'Status'
			from
				gibbonPerson p
			where
				exists (
					select gibbonPersonID from gibbonFamilyAdult where gibbonPersonID = p.gibbonPersonID
				)
				and p.status in ('Full','Expected')
				and p.gibbonPersonID = :ID
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
			return $this->db->RunSQL($this->sqlCommands["GetAll"],"Contact",null);
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
			return $this->db->RunSQL($this->sqlCommands["GetAll"],"Contact",array("ID",$id));
		}
		catch(PDOException $_e)
		{
			throw $_e;
		}
	}
}

?>
