<?php

class PhpBbBridge
{
    const Leader981011 = 347;
    const Leader981012 = 53;
    const Leader981013 = 346;
    const LeaderZam981013 = 350;
    const LeaderZam981013_2 = 354;
    const LeaderAlexBy = 357;

    public function getCurrentUserId()
    {
        if ( isset($_COOKIE['phpbb3_j3saj_u']) && is_numeric($_COOKIE['phpbb3_j3saj_u']) ) {
            return $_COOKIE['phpbb3_j3saj_u'];
        }

        return false;
    }

    public function getCurrentGroup()
    {
        //TODO: Сделать вместо хардкода взаимодействие с форумом
        //TODO: перенести получение текущей группы в Users.php!

        $iUserId = $this->getCurrentUserId();
        switch ($iUserId) {
            case self::Leader981011:
            case self::LeaderAlexBy: {
                $sGroupName = '981011';
            } break;
            case self::Leader981012: {
                $sGroupName = '981012';
            } break;
            case self::Leader981013:
            case self::LeaderZam981013:
            case self::LeaderZam981013_2:    {
                $sGroupName = '981013';
            } break;
            default: {
                return false;
            }
        }

        $db =& JFactory::getDBO();

        //Получить ID текущей группы
        $sQuery = "
            SELECT
               id
            FROM
               groups
            WHERE
               name = '".$db->escape($sGroupName)."'
        ";
        $db->setQuery($sQuery);
        $aGroup = $db->loadAssoc();

        $iGroupId = $aGroup['id'];

        //Вернуть ID и name группы
        return array($iGroupId => $sGroupName);
    }

    /**
     * Получить все основные данные текущего юзера форума
     * TODO: пока просто заглушка
     *
     * @return array
     */
    public function getCurrentUser()
    {
        $aUser = array();
        $aUser['id'] = $this->getCurrentUserId();
        return $aUser;
    }
}