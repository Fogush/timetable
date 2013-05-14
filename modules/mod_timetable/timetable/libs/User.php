<?php

require_once 'libs/PhpbbBridge.php';
            
class User
{
    const ROLE_GUEST = 1;
    const ROLE_ADMIN = 2;
    const ROLE_REGISTER = 3;
	
	public function getUserId()
	{
        if ( App::getInstance()->aConfig['PhpBB'] ) {
        	
            $oPhpbbBridge = new PhpBbBridge();
            $iPhpbbUserId = $oPhpbbBridge->getCurrentUserId();
            
            $db =& JFactory::getDBO();
            
            $sQuery = "
                SELECT
                    id
                FROM
                    #__tt_users
                WHERE
                    phpbb_user_id = '".$db->escape($iPhpbbUserId)."';                
            ";
            $db->setQuery($sQuery);
            $aUser = $db->loadAssoc();
            $iUserId = $aUser['id'];
            
            // а нужно ли это? 
//            if ( !empty($iUserId) ) {
//            	$_SESSION['user_id'] = $iUserId;
//            }
            
            return $iUserId;
            
        } else {
        	
        	return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false; //TODO: а возможно, заменять на ID гостя
        	
        }
	}
	
	public function getUserData()
	{
		$iUserId = $this->getUserId();
		if ( empty($iUserId) ) {
			return false;
		}
			
		$db =& JFactory::getDBO();
           
		//Получаем данные юзера из базы приложения
        $sQuery = "
            SELECT
                u.*, rc.id as role_id, rc.name as role
            FROM
                #__tt_users u 
                INNER JOIN #__tt_role_categories rc ON rc.id = u.role_id
            WHERE
                u.id = '".$db->escape($iUserId)."';                
        ";
        $db->setQuery($sQuery);
        $aUser = $db->loadAssoc();

        //А также пытаемся получить данные с форума. Они будут храниться как подмассив
        if ( App::getInstance()->aConfig['PhpBB'] ) {

            $oPhpbbBridge = new PhpBbBridge();
            $aUser['phpbb'] = $oPhpbbBridge->getCurrentUser();
            
        }
		
        return $aUser;
	}
	
	/**
	 * Получить данные группы, к которой принадлежит юзер (не гость)
	 * TODO: сделать отвязку от форума
	 *
	 * @return array
	 */
	public function getGroup()
	{
		//TODO: как-то кешировать запрос, а лучше вынести User.php в App.php (есть в notes.txt)
		//и сделать объект User общедоступным
        $aUser = $this->getUserData();
        
        if ( empty($aUser['group_id']) ) {
        	return false;
        }
        
        $db =& JFactory::getDBO();
        
        $sQuery = "
            SELECT
                *
            FROM
                #__tt_groups
            WHERE
                id = '".$db->escape($aUser['group_id'])."'
        ";
        $db->setQuery($sQuery);

        return $db->loadAssoc();
	}
	
}