<?php

require_once 'App.php';


//TODO: hardcode
if ( $_SERVER['SERVER_NAME'] == 'localhost' && App::getInstance()->aConfig['Debug'] ) {
    $_COOKIE['phpbb3_j3saj_u'] = PhpbbBridge::Leader981012;
}

require_once 'libs/User.php';
$oUser = new User();

//Перенаправлять неавторизированных юзеров
$aUser = $oUser->getUserData();
if ( empty($aUser) ) {
    if ( App::getInstance()->aConfig['PhpBB'] ) {
        header('Location: .');
        exit;
    } else {
        header('Location: login.php');
        exit;
    }
}

//Замена ID юзера для того, чтобы админ мог заливать расписание для других групп.
//TODO: надо придумать какой-нибудь менее хардкодный способ определения админа и замены айди
$iReplaceId = Utils::inGetPost('user_id');
if ( $aUser['role_id'] == User::ROLE_ADMIN && !empty($iReplaceId) && App::getInstance()->aConfig['PhpBB'] ) {
    $_COOKIE['phpbb3_j3saj_u'] = $iReplaceId;
}

//Узнать к какой группе форума принадлежит текущий юзер для того, чтобы
//выводить страницу только для админов
$aGroup = $oUser->getGroup();    //groupId => groupName

if ( empty($aGroup) ) {
    header('Location: .');
    exit;
}

if ( Utils::inGetPost('dwnld_tt') ) {
    $aTimetable = Misc::getActualTimetable($aGroup['id']);
    require_once 'libs/Timetable/Upload.php';
    $oUpload = new Timetable_Upload();

    header('Content-type: text/xml');
    header('Content-Disposition: attachment; filename="timetable_'.$aGroup['name'].'.xml"');
    readfile($oUpload->sPathToValidated . $aTimetable['filename']);

    exit;
}


$sResultTimetable = $sResultExceptions = '';
if ( isset($_FILES['timetable']) && $_FILES['timetable']['error'] != UPLOAD_ERR_NO_FILE ) {
    require_once 'libs/Timetable/Upload.php';
    $oUpload = new Timetable_Upload();

    if ( !$oUpload->upload('timetable') ) {

        $aErrors = $oUpload->getErrors();
        if ( !empty($aErrors) ) {

            $sResultTimetable = "<strong>Произошли ошибки:</strong><br />";
            foreach ($aErrors as $sError) {
                $sResultTimetable .= $sError . "<br />";
            }
            $sResultTimetable .= "<br />";

        }

    } else {
        $sResultTimetable = "Сохранение прошло успешно<br />";
    }
}

if ( isset($_FILES['timetable_exceptions']) && $_FILES['timetable_exceptions']['error'] != UPLOAD_ERR_NO_FILE ) {
    require_once 'libs/Timetable/Upload/Exceptions.php';
    $oUpload = new Timetable_Upload_Exceptions();

    if ( !$oUpload->upload('timetable_exceptions') ) {
        $aErrors = $oUpload->getErrors();
        if ( !empty($aErrors) ) {

            $sResultExceptions = "<strong>Произошли ошибки:</strong><br />";
            foreach ($aErrors as $sError) {
                $sResultExceptions .= $sError . "<br />";
            }
            $sResultExceptions .= "<br />";

        }

    } else {
        $sResultExceptions = "Сохранение прошло успешно<br />";
    }
}

$sGroupName = $aGroup['name'];
require_once 'templates/upload.tpl';
