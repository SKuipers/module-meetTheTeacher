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

class CustomGroupLink extends GroupLink
{

	public $TeacherID;
	public $StudentID;
	public $GroupName;
	public $GroupID;

	
	public function __construct(){}

	public function FromVariables($id,$tid,$sid,$gn,$gid)
	{
		if($tid != null && $sid != null && $gn != null && $gid != null)
		{
			if($id != null)
			{
				$this->ID = $id;
			}
			$this->TeacherID = $tid;
			$this->StudentID = $sid;
			$this->GroupName = $gn;
			$this->GroupID = $gid;
		}

	}
	
	public function Validate()
	{
		if($this->TeacherID != null && $this->StudentID != null && $this->GroupName != null && $this->GroupID != null)
		{
			return true;
		}
		else
		{
			var_dump($this);
			return false;
		}
	}
}

?>
