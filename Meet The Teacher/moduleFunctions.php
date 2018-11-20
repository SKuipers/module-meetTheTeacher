<?php

use Gibbon\Forms\Form;
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
    $textUnavailable = getSettingByScope($connection2, 'Meet The Teacher', 'textUnavailable');
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
        $output .= "<div class='warning'>";
        $output .= $textUnavailable;
        $output .= '</div>';
        return $output;
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

    if ($result->rowCount() != 1) {
        $output .= "<div class='warning'>";
        $output .= $textUnavailable;
        $output .= '</div>';
    } else {
        $student = $result->fetch();

        // Check for form submission
        if (isset($_POST['translatorLanguage'])) {
            $data = [
                'gibbonPersonID' => $_SESSION[$guid]['gibbonPersonID'],
                'translatorLanguage' => $_POST['translatorLanguage'],
                'translatorRequired' => $_POST['translatorLanguage'] != 'None' ? 'Y' : 'N',
            ];
            $sql = "UPDATE meetTheTeacherTranslator SET translatorLanguage=:translatorLanguage, translatorRequired=:translatorRequired, lastUpdated=CURRENT_TIMESTAMP() WHERE gibbonPersonID=:gibbonPersonID";

            $result = $connection2->prepare($sql);
            $result->execute($data);
        }

        // Check for translator
        $data = ['gibbonPersonID' => $_SESSION[$guid]['gibbonPersonID']];
        $sql = "SELECT * FROM meetTheTeacherTranslator WHERE gibbonPersonID=:gibbonPersonID AND translatorRequired='Y' LIMIT 1";

        $result = $connection2->prepare($sql);
        $result->execute($data);

        if ($result->rowCount() == 1) {
            $translatorRequest = $result->fetch();

            $languages = [
                'None'            => 'No Translator Required 無需預約翻譯',
                'Cantonese 廣東話' => 'Cantonese 廣東話',
                'Mandarin 普通話'  => 'Mandarin 普通話',
                'Portuguese 葡語'  => 'Portuguese 葡語',
                'Korean 韓語'      => 'Korean 韓語',
            ];

            if (!empty($translatorRequest['translatorLanguage']) && isset($languages[$translatorRequest['translatorLanguage']])) {
                $output .= "<div class='success'>";
                $output .= '<h2 style="margin-top:0;">Request Translators 預約翻譯</h2>';

                $language = $languages[$translatorRequest['translatorLanguage']];

                $output .= "<p>Thank you. We've received your request for a ".$language." translator. The school will try their best to accommodate your request.</p>";
                $output .= "<p>感謝。 預約翻譯 「".$language."」。學校會儘量作出安排。</p>";
                $output .= '</div>';
            } else {

                $output .= "<div class='warning'>";
                $output .= '<h2 style="margin-top:0;">Request Translators 預約翻譯</h2>';

                $output .= '<p>TIS is asking parents to please bring along a translator if you need one. If you are unable to arrange for a translator to accompany you, please select a language below. The school will try their best to accommodate your request but this cannot be guaranteed.</p>';

                $output .= '<p style="text-align: left;">若有需要，家長可自行帶同翻譯人員與老師面談。若 閣下無法安排翻譯人員，請點擊「translator required」提出要求，學校會儘量作出安排。</p>';
                
                $form = Form::create('translationRequest', '')->setClass('blank fullWidth');
                $form->addHiddenValue('address', $_SESSION[$guid]['address']);

                $row = $form->addRow();
                    $row->addLabel('translatorLanguage', 'Request Translators 預約翻譯');
                    $row->addRadio('translatorLanguage')->fromArray($languages)->checked($translatorRequest['translatorLanguage'] ?? null);

                $row = $form->addRow()->addSubmit();

                $output .= $form->getOutput();
                $output .= '</div>';
            }
        }

        $output .= '<div class="message" style="padding-top: 14px">';
        $output .= "<b>".__($text).'</b><br/>';

        
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

        $output .= '<p class="noMargin emphasis"><b>'.__('Note').':</b> '.__('Please do not share the bookings URL with anyone, as it contains a unique login code.').'</p>';
        $output .= '</div><br/>';
    }

    return $output;
}
