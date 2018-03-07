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

function getMeetTheTeacher($connection2, $guid, $gibbonPersonIDChild = null)
{
    $output = '';

    $url = getSettingByScope($connection2, 'Meet The Teacher', 'url');
    $text = getSettingByScope($connection2, 'Meet The Teacher', 'text');
    $yearGroups = getSettingByScope($connection2, 'Meet The Teacher', 'yearGroups');
    $authenticateBy = getSettingByScope($connection2, 'Meet The Teacher', 'authenticateBy');

    // Get parent details to be passed to URL params
    $data = array('gibbonPersonID' => $_SESSION[$guid]['gibbonPersonID']);
    $sql = "SELECT DISTINCT email AS parentEmailAddress, email AS parentEmailAddressConfirm, meetTheTeacherLogin.loginCode as parentCode
            FROM gibbonPerson
            LEFT JOIN meetTheTeacherLogin ON (gibbonPerson.gibbonPersonID=meetTheTeacherLogin.gibbonPersonID)
            WHERE gibbonPerson.gibbonPersonID=:gibbonPersonID";
    $result = $connection2->prepare($sql);
    $result->execute($data);

    if ($result->rowCount() == 1) {
        $params = $result->fetch();
    } else {
        $output .= "<div class='error'>";
        $output .= __($guid, 'There are no records to display.');
        $output .= '</div>';
        return '';
    }

    // Get student details for this parent
    $data = array(
        'gibbonPersonID' => $_SESSION[$guid]['gibbonPersonID'],
        'gibbonPersonIDChild' => $gibbonPersonIDChild,
        'date' => date('Y-m-d'),
        'gibbonSchoolYearID' => $_SESSION[$guid]['gibbonSchoolYearID'],
        'yearGroups' => $yearGroups,
    );
    $sql = "SELECT gibbonPerson.gibbonPersonID, gibbonPerson.surname, gibbonPerson.preferredName, gibbonYearGroup.nameShort as yearGroupName, gibbonRollGroup.nameShort as rollGroupName, gibbonPerson.dob
        FROM gibbonFamilyChild
        JOIN gibbonFamily ON (gibbonFamilyChild.gibbonFamilyID=gibbonFamily.gibbonFamilyID)
        JOIN gibbonFamilyAdult ON (gibbonFamilyAdult.gibbonFamilyID=gibbonFamily.gibbonFamilyID)
        JOIN gibbonPerson ON (gibbonFamilyChild.gibbonPersonID=gibbonPerson.gibbonPersonID)
        JOIN gibbonStudentEnrolment ON (gibbonStudentEnrolment.gibbonPersonID=gibbonPerson.gibbonPersonID)
        JOIN gibbonYearGroup ON (gibbonYearGroup.gibbonYearGroupID=gibbonStudentEnrolment.gibbonYearGroupID)
        JOIN gibbonRollGroup ON (gibbonRollGroup.gibbonRollGroupID=gibbonStudentEnrolment.gibbonRollGroupID)
        WHERE gibbonStudentEnrolment.gibbonSchoolYearID=:gibbonSchoolYearID
        AND FIND_IN_SET(gibbonYearGroup.nameShort, :yearGroups)
        AND gibbonPerson.status='Full' AND (dateEnd IS NULL OR dateEnd>=:date)
        AND gibbonFamilyAdult.gibbonPersonID=:gibbonPersonID
        AND gibbonFamilyAdult.childDataAccess='Y'
        AND gibbonFamilyChild.gibbonPersonID=:gibbonPersonIDChild
        ORDER BY gibbonYearGroup.sequenceNumber ASC";
    $result = $connection2->prepare($sql);
    $result->execute($data);

    $output .= '<div class="message" style="padding-top: 14px">';
    $output .= "<b>".__($text).'</b><br/>';

    if ($result->rowCount() != 1) {
        $output .= "<div class='error'>";
        $output .= __($guid, 'There are no records to display.');
        $output .= '</div>';
    }
    else {
        $student = $result->fetch();
        if ($authenticateBy == 'dob') {
            $dob = new DateTime($student['dob']);
            $params['DateOfBirthHelper_Day'] = $dob->format('j');
            $params['DateOfBirthHelper_Month'] = $dob->format('n');
            $params['DateOfBirthHelper_Year'] = $dob->format('Y');
        } else {
            $params['StudentClass'] = $student['rollGroupName'];
        }

        $output .= '<br/>';
        $output .= '<a href="'.$url.'?isPostback=true&'.http_build_query($params).'" target="_blank" style="font-size:16px;">';
        $output .= formatName('', $student['preferredName'], $student['surname'], 'Student', false, true);
        $output .= ' - '.$student['yearGroupName'];
        $output .= '</a>';
        $output .= '<br/><br/>';
    }

    $output .= '<p class="noMargin emphasis"><b>'.__('Note').':</b> '.__('Please do not share the bookings URL with anyone, as it contains a unique login code for your student.').'</p>';
    $output .= '</div><br/>';

    return $output;
}
