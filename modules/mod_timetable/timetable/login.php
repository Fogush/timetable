<?php

require_once 'App.php';

$sEmail = Utils::inGetPost('email'); //ie login
$sPassword = Utils::inGetPost('password');
$sSubmit = Utils::inGetPost('submit');

$sError = '';

if ( !empty($sSubmit) ) {
	
    if ( empty($sEmail) || empty($sPassword) ) {
    	
	   $sError = "Введите все данные.";
	   
    } else {

		//Вынести в класс
		//Отсюда
		$db = Db::getInstance();
		
		$sSoil = "aserkey";
		$sPassword = md5($sPassword.$sSoil);
		
		$sQuery = "
		    SELECT
		        *
		    FROM
		        users
		    WHERE
		        email = '".$db->escape($sEmail)."' AND
		        password = '".$db->escape($sPassword)."'
		    LIMIT 
		        1
		";
		$aUser = $db->query($sQuery, true);
		
		if ( !empty($aUser) ) {
			$_SESSION['user_id'] = $aUser['id'];
			header('Location: upload.php');
		} else {
		    $sError = "Не удается войти по введенным данным.";
		}
		//До сюда
    }
}

require_once 'templates/login.tpl';