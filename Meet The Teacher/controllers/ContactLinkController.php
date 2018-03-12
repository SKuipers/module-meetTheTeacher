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

class ContactLinkController implements PESAPIController
{
	private $sqlCommands = array(
		"GetAll" => "
		select distinct
			p.gibbonPersonID as 'AdultID',
			fc.gibbonPersonID as 'ChildID',
			fa.contactPriority as 'Priority',
			fa.childDataAccess as 'ChildDataAccess',
			gfr.relationship as 'Relationship'
		from
		gibbonFamilyAdult fa
			inner join gibbonPerson p on p.gibbonPersonID = fa.gibbonPersonID
			inner join gibbonFamilyChild fc on fc.gibbonFamilyID = fa.gibbonFamilyID
			inner join gibbonPerson c on c.gibbonPersonID = fc.gibbonPersonID
			left join gibbonFamilyRelationship gfr on (gfr.gibbonPersonID1=p.gibbonPersonID and gfr.gibbonPersonID2=c.gibbonPersonID);
		;",

		"GetByID" => "
			select distinct
				p.gibbonPersonID as 'AdultID',
				fc.gibbonPersonID as 'ChildID',
				fa.contactPriority as 'Priority',
				fa.childDataAccess as 'ChildDataAccess',
				gfr.relationship as 'Relationship'
			from
			gibbonFamilyAdult fa
				inner join gibbonPerson p on p.gibbonPersonID = fa.gibbonPersonID
				inner join gibbonFamilyChild fc on fc.gibbonFamilyID = fa.gibbonFamilyID
				inner join gibbonPerson c on c.gibbonPersonID = fc.gibbonPersonID
				left join gibbonFamilyRelationship gfr on (gfr.gibbonPersonID1=p.gibbonPersonID and gfr.gibbonPersonID2=c.gibbonPersonID)
			where
				p.gibbonPersonID = :ID
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
			return $this->db->RunSQL($this->sqlCommands['GetAll'],"ContactLink",null);
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
			return $this->db->RunSQL($this->sqlCommands['GetByID'],"ContactLink",array("ID" => $id));
		}
		catch(PDOException $_e)
		{
			throw $_e;
		}
	}
}

?>
