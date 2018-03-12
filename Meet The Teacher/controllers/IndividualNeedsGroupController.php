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


class IndividualNeedsGroupController implements PESAPIController
{

	private $db;

    //Need the distinct in both cases since it could be possible IN has different levels assigned.
	private $sqlCommands = array(
        "GetAll" => "
            select
                distinct
                ina.gibbonINAssistantID 'GroupID',
                concat(trim(leading '0' from ind.gibbonINDescriptorID),trim(leading '0' from ina.gibbonPersonIDAssistant)) as 'CompoundID',
                ina.gibbonPersonIDStudent as 'StudentID',
                ina.gibbonPersonIDAssistant as 'TeacherID',
                ind.name as 'GroupName',
                inpd.gibbonINPersonDescriptorID
            from
                gibbonINAssistant ina
            inner join gibbonINPersonDescriptor inpd on inpd.gibbonPersonID = ina.gibbonPersonIDStudent
            inner join gibbonINDescriptor ind on ind.gibbonINDescriptorID = inpd.gibbonINDescriptorID
            inner join gibbonPerson p on p.gibbonPersonID = ina.gibbonPersonIDAssistant
            where
                p.status in ('Full','Expected')

        ;",

        "GetByRole" => "
            select
                distinct
                ina.gibbonINAssistantID 'GroupID',
                concat(trim(leading '0' from ind.gibbonINDescriptorID),trim(leading '0' from ina.gibbonPersonIDAssistant)) as 'CompoundID',
                ina.gibbonPersonIDStudent as 'StudentID',
                ina.gibbonPersonIDAssistant as 'TeacherID',
                ind.name as 'GroupName',
                inpd.gibbonINPersonDescriptorID
            from
                gibbonINAssistant ina
            inner join gibbonINPersonDescriptor inpd on inpd.gibbonPersonID = ina.gibbonPersonIDStudent
            inner join gibbonINDescriptor ind on ind.gibbonINDescriptorID = inpd.gibbonINDescriptorID
            inner join gibbonPerson p on p.gibbonPersonID = ina.gibbonPersonIDAssistant
            where
                p.status in ('Full','Expected')
                and FIND_IN_SET(:lsrole, p.gibbonRoleIDAll)
        ;",

		"GetByID" => "
            select
                distinct
                ina.gibbonINAssistantID 'GroupID',
                concat(trim(leading '0' from ind.gibbonINDescriptorID),trim(leading '0' from ina.gibbonPersonIDAssistant)) as 'CompoundID',
                ina.gibbonPersonIDStudent as 'StudentID',
                ina.gibbonPersonIDAssistant as 'TeacherID',
                ind.name as 'GroupName',
                inpd.gibbonINPersonDescriptorID
            from
                gibbonINAssistant ina
            inner join gibbonINPersonDescriptor inpd on inpd.gibbonPersonID = ina.gibbonPersonIDStudent
            inner join gibbonINDescriptor ind on ind.gibbonINDescriptorID = inpd.gibbonINDescriptorID
            inner join gibbonPerson p on p.gibbonPersonID = ina.gibbonPersonIDAssistant
            where
                p.status in ('Full','Expected')
                and ina.gibbonINAssistantID = :ID
		;",

        "ClasslessGetByRole" => "
              SELECT DISTINCT
                1 as 'GroupID',
                1 as 'CompoundID',
                s.gibbonPersonID as 'StudentID',
                t.gibbonPersonId as 'TeacherID',
                'Individual Needs' as 'GroupName'
              FROM gibbonPerson s
              JOIN gibbonStudentEnrolment se ON ( se.gibbonPersonID = s.gibbonPersonID )
              JOIN gibbonRollGroup rg ON ( se.gibbonRollGroupID = rg.gibbonRollGroupID )
              JOIN gibbonSchoolYear sy ON (se.gibbonSchoolYearID= sy.gibbonSchoolYearID)
              JOIN gibbonIN gin ON (gin.gibbonPersonID=s.gibbonPersonID)
              JOIN gibbonINPersonDescriptor inpd ON (inpd.gibbonPersonID=gin.gibbonPersonID)
              left outer join gibbonPerson t on t.status in ('Full','Expected') and FIND_IN_SET(:lsrole, t.gibbonRoleIDAll)
              WHERE
                s.status = 'Full'
                AND t.`status` = 'Full'
                AND sy.status = 'Current'
            ;"
	);

	function __construct($_db)
	{
		$this->db = new DatabaseHelper($_db);
	}

	public function GetAll()
	{
		try
		{
			return $this->db->RunSQL($this->sqlCommands["GetAll"],"IndividualNeedsGroupLink",null);
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
			return $this->db->RunSQL($this->sqlCommands["GetByID"],"IndividualNeedsGroupLink",array(":ID" => $id));
		}
		catch(PDOException $_e)
		{
			throw $_e;
		}
	}

    public function GetByRole($role)
    {
        try
        {
            return $this->db->RunSQL($this->sqlCommands["GetByRole"],"IndividualNeedsGroupLink", array(":lsrole" => $role));
        }
        catch(PDOException $_e)
        {
            throw $_e;
        }
    }

    public function ClasslessGetByRole($role)
    {
        try
        {
            return $this->db->RunSQL($this->sqlCommands["ClasslessGetByRole"],"IndividualNeedsGroupLink", array(":lsrole" => $role));
        }
        catch(PDOException $_e)
        {
            throw $_e;
        }
    }
}

?>
