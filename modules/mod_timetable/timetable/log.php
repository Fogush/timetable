<?php

require_once 'App.php';


require_once 'libs/User.php';
$oUser = new User();

//Перенаправлять неавторизированных юзеров
$aUser = $oUser->getUserData();
if ( empty($aUser) || $aUser['role_id'] != User::ROLE_ADMIN ) {
	header('Location: login.php');
}

$db = Db::getInstance();

$sQuery = "
    SELECT
        al.*, TRIM(CONCAT(u.first_name, ' ', u.last_name)) as user_name
    FROM
        action_log al
        LEFT JOIN users u ON u.id = al.user_id
    ORDER BY
        al.date_created
";

$res = $db->query($sQuery);

$aLogTable = array();
while ( $row = $db->fetchAssoc($res) ) {
	$aLogTable[] = $row;
}

require_once 'templates/log.tpl';
