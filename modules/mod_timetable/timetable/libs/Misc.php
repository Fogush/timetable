<?php

//TODO: переименовать потом...
class Misc
{
	
	/**
	 * Получить массив всех предметов. 
	 * Если указана группа, то выберет предметы только этой группы
	 *
	 * @return array (id => name)
	 */
	public static function getSubjects($iGroupId = false)
	{
	    $aSubjects = array();
	    
        $db =& JFactory::getDBO();
        
        if ( !$iGroupId ) {
	        
        	//Вообще все предметы
        	$sQuery = "
	            SELECT
	                *
	            FROM
	                subjects
	        ";
	        
        } else {
        	
        	//Предметы только какой-то одной группы
        	$sQuery = "
                SELECT
                    s.*
                FROM
                    #__tt_groups_subjects gs
                    INNER JOIN #__tt_subjects s ON s.id = gs.subject_id
                    INNER JOIN #__tt_groups g ON g.id = gs.group_id
                WHERE
                    g.id = '".$db->escape($iGroupId)."'
        	";
        	
        }

        $db->setQuery($sQuery);

        $aRows = $db->loadAssocList();
        foreach ($aRows as $aRow) {
            $aSubjects[$aRow['id']] = $aRow['name'];
        }
		
        return $aSubjects;
	}

	/**
	 * Получить массив всех типов занятий
	 *
	 * @return array (id => name)
	 */
    public static function getLessonTypes()
    {
        $aLessonTypes = array();
        
        $db =& JFactory::getDBO();
        $sQuery = "
            SELECT
                *
            FROM
                #__tt_lesson_types
        ";
        $db->setQuery($sQuery);

        $aRows = $db->loadAssocList();
        foreach ($aRows as $aRow) {
            $aLessonTypes[$aRow['id']] = $aRow['shortname'];
        }
        
        return $aLessonTypes;
    }
    
    /**
     * Получить массив всех групп или принадлежащих какому-то уч. заведению
     *
     * @param int $iInstitution
     * @return array (id => name)
     */
    public static function getGroups($iInstitution = false)
    {
        $aGroups = array();

        $db =& JFactory::getDBO();
        $sQuery = "
            SELECT
                *
            FROM
                #__tt_groups
            ".($iInstitution !== false ? "WHERE institution = '".$db->escape($iInstitution)."'" : '')."
        ";
        $db->setQuery($sQuery);

        $aRows = $db->loadAssocList();
        foreach ($aRows as $aRow) {
            $aGroups[$aRow['id']] = $aRow['name'];
        }

        return $aGroups;
    	
    }
    
    /**
     * Получить массив-данные текущего timetable для указанной подгруппы
     * Текущий определяется как тот, у которого наиболее поздняя дата создания 
     *
     * @param int $iGroupId
     * @return array
     */
    public static function getActualTimetable($iGroupId)
    {
    	$db =& JFactory::getDBO();
        $sQuery = "
            SELECT
                t.*
            FROM
                #__tt_timetables t
                INNER JOIN #__tt_groups g ON g.id = t.group_id
            WHERE
                g.id = '".$db->escape($iGroupId)."'
            ORDER BY
                t.date_created DESC
            LIMIT 
                1
        ";
        $db->setQuery($sQuery);

        return $db->loadAssoc();
    }
    
    /**
     * Записать действие в лог.
     *
     * @param string $sDescription
     * @param int $iUserId
     */
    public static function logAction($sDescription)
    {
    	$db =& JFactory::getDBO();
    	
    	require_once 'libs/User.php';
		$oUser = new User();
		
		$iUserId = $oUser->getUserId();
    	if ( !$iUserId ) {
    		$iUserId = 'null';
    	}
    	
    	$sQuery = "
            INSERT INTO #__tt_action_log (description, user_id, date_created) VALUES
                ('".$db->escape($sDescription)."', ".$iUserId.", NOW()); 
    	";
    	$db->setQuery($sQuery);
        $db->execute();
    }
    
    
    
    /**
     * Выдает список всех преподавателей для указанной группы
     *
     * @return array
     */
    public function getTeachers($iGroupId)
    {
        $db =& JFactory::getDBO();
         
        $sQuery = "
            SELECT
                t.id as teacher_id, t.phone as teacher_phone,
                t.first_name as teacher_first_name, t.last_name as teacher_last_name, t.middle_name as teacher_middle_name,
                s.name as subject_name, lt.shortname as lesson_type
            FROM
                #__tt_teachers t
                INNER JOIN #__tt_subjects_lesstypes_teachers slt ON slt.teacher_id = t.id
                INNER JOIN #__tt_lesson_types lt ON lt.id = slt.lesson_type_id
                INNER JOIN #__tt_subjects s ON s.id = slt.subject_id
                INNER JOIN #__tt_groups_subjects gs ON gs.subject_id = s.id
                INNER JOIN #__tt_groups g ON g.id = gs.group_id
            WHERE
                g.id = '".$db->escape($iGroupId)."'
            GROUP BY
                t.id, s.id, lt.id
            ORDER BY
                t.id
        ";
    
        $oRes = $db->query($sQuery);
    
        $aTeachers = array();
    
        while ( $aRow = $db->fetchAssoc($oRes) ) {
    
            if ( !isset($aTeachers[$aRow['teacher_id']]) ) {
                $aTeachers[$aRow['teacher_id']]['first_name'] = $aRow['teacher_first_name'];
                $aTeachers[$aRow['teacher_id']]['last_name'] = $aRow['teacher_last_name'];
                $aTeachers[$aRow['teacher_id']]['middle_name'] = $aRow['teacher_middle_name'];
                $aTeachers[$aRow['teacher_id']]['phone'] = $aRow['teacher_phone'];
            }
    
            if ( !isset($aTeachers[$aRow['teacher_id']]['subjects']) ) {
                $aTeachers[$aRow['teacher_id']]['subjects'] = array();
            }
    
            $aTeachers[$aRow['teacher_id']]['subjects'][$aRow['subject_name']][] = $aRow['lesson_type'];
    
        }
    
        return $aTeachers;
    }
}