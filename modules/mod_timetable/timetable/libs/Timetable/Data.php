<?php

class Timetable_Data
{
    private $aTimetable = array();
    private $aTimetableData = array();
    private $aExceptions = array();
    
    private $iGroupId = false;
    private $iModifiedDate = false;
    private $iFirstDay = false;
    private $iFirstNumWeek = false;
    private $iCountWeeks = false;
    
    public function __construct($iGroupId)
    {
    	//Задается именно в конструкторе, чтобы показать, что один объект - это одно расписание,
    	//а для получения другого расписания нужно создать другой объект
    	$this->iGroupId = $iGroupId;
        
        //Получить данные непосредственно расписания (первый день, число уч. недель и др.) 
        $db =& JFactory::getDBO();
        
        $this->aTimetable = Misc::getActualTimetable($this->iGroupId);
        
        if ( empty($this->aTimetable) ) {
//            return false;
        }
        
        //Вот сами параметры расписания
        $this->iFirstDay = strtotime($this->aTimetable['first_day']);
        $this->iFirstNumWeek = $this->aTimetable['first_num_week'];
        $this->iCountWeeks = $this->aTimetable['count_weeks'];
        $this->iModifiedDate = strtotime($this->aTimetable['date_created']);
    }
    
    
    public function getModifiedDate()
    {
        return $this->iModifiedDate;
    }
    
    public function getFirstDay()
    {
        return $this->iFirstDay;
    }
    
    public function getFirstNumWeek()
    {
        return $this->iFirstNumWeek;
    }
    
    public function getCountWeeks()
    {
        return $this->iCountWeeks;
    }
    
    public function getGroupId()
    {
        return $this->iGroupId;
    }
    
    /**
     * Получить раписание занятий
     *
     * @return array
     */
    public function getTimetableData()
    {
    	if ( !empty($this->aTimetableData) ) {
    		return $this->aTimetableData;
    	}
        
        $db =& JFactory::getDBO();
        
        //Самый главный запрос :)
        $sQuery = "
            SELECT
                l.*, s.id as subject_id, s.name as subject_name, 
                lt.id as type_id, lt.shortname as type_name,
                lt.color as type_color,
                tc.first_name as teacher_first_name, tc.last_name as teacher_last_name, tc.middle_name as teacher_middle_name,
                tc.phone as teacher_phone
            FROM
                #__tt_lessons l
                INNER JOIN #__tt_timetables t ON t.id = l.timetable_id
                INNER JOIN #__tt_subjects s ON s.id = l.subject_id
                INNER JOIN #__tt_lesson_types lt ON lt.id = l.type_id
                LEFT JOIN #__tt_subjects_lesstypes_teachers slt ON slt.subject_id = s.id AND slt.lesson_type_id = lt.id 
                LEFT JOIN #__tt_teachers tc ON tc.id = slt.teacher_id
            WHERE
                t.id = '".$db->escape($this->aTimetable['id'])."'                
        ";
        $db->setQuery($sQuery);

        $aRows = $db->loadAssocList();
        
        //Сформировать массив расписания
        foreach ($aRows as $aRow) {
            
        	$aLesson = array(
                'subject' => $aRow['subject_name'],
                'subject_id' => $aRow['subject_id'],
                'type' => $aRow['type_name'],
                'type_id' => $aRow['type_id'],
                'type_color' => $aRow['type_color'],
                'classroom' => $aRow['classroom'],
	            'teacher' => array(
                    'first_name' => $aRow['teacher_first_name'],
                    'last_name' => $aRow['teacher_last_name'],
                    'middle_name' => $aRow['teacher_middle_name'],
                    'phone' => $aRow['teacher_phone'],
                )
        	);
            if ( !empty($aRow['subgroup']) ) {
                $aLesson['subgroup'] = $aRow['subgroup'];
            }
            if ( !empty($aRow['begin_date']) ) {
                $aLesson['begin_date'] = strtotime($aRow['begin_date']);
            }
            if ( !empty($aRow['end_date']) ) {
                $aLesson['end_date'] = strtotime($aRow['end_date']);
            }
            if ( !empty($aRow['comment']) ) {
                $aLesson['comment'] = $aRow['comment'];
            }

            $this->aTimetableData[ $aRow['weekday'] ][ $aRow['pair_number'] ]['begin_time'] = $aRow['begin_time'];
            $this->aTimetableData[ $aRow['weekday'] ][ $aRow['pair_number'] ]['lessons'][ $aRow['week_number'] ][] = $aLesson;  
        	
        }
        
        return $this->aTimetableData;
    }
    
    /**
     * Получить исключения в расписании
     *
     * @return array
     */
    public function getExceptions()
    {
        if ( !empty($this->aExceptions) ) {
            return $this->aExceptions;
        }
        
        $db =& JFactory::getDBO();
    	
        $sQuery = "
            SELECT
                e.*, DATE_FORMAT(e.exception_date, '%d.%m.%Y') as ex_date,  
                s.id as subject_id, s.name as subject_name, 
                lt.id as type_id, lt.shortname as type_name,
                lt.color as type_color,
                tc.first_name as teacher_first_name, tc.last_name as teacher_last_name, tc.middle_name as teacher_middle_name,
                tc.phone as teacher_phone
            FROM
                #__tt_exceptions e    
                INNER JOIN #__tt_timetables t ON t.id = e.timetable_id 
                INNER JOIN #__tt_subjects s ON s.id = e.subject_id OR e.without_lesson = 1
                INNER JOIN #__tt_lesson_types lt ON lt.id = e.type_id OR e.without_lesson = 1
                LEFT JOIN #__tt_subjects_lesstypes_teachers slt ON (slt.subject_id = s.id AND slt.lesson_type_id = lt.id) OR e.without_lesson = 1
                LEFT JOIN #__tt_teachers tc ON tc.id = slt.teacher_id OR e.without_lesson = 1
            WHERE
                t.id = '".$db->escape($this->aTimetable['id'])."'   
            GROUP BY
                e.id             
        ";
        $db->setQuery($sQuery);

        $aRows = $db->loadAssocList();

        //Сформировать массив расписания
        foreach ($aRows as $aRow) {

        	$aException = array(
                'without_lesson' => $aRow['without_lesson'],
            );
            
            //Нужно не записывать пустые данные о занятии, если его не должно быть 
            if ( !$aRow['without_lesson'] ) {
                
            	$aException = $aException + array(
	                'subject' => $aRow['subject_name'],
	                'subject_id' => $aRow['subject_id'],
	                'type' => $aRow['type_name'],
	                'type_id' => $aRow['type_id'],
                    'type_color' => $aRow['type_color'],
                    'classroom' => $aRow['classroom'],
    	            'teacher' => array(
	                    'first_name' => $aRow['teacher_first_name'],
	                    'last_name' => $aRow['teacher_last_name'],
	                    'middle_name' => $aRow['teacher_middle_name'],
	                    'phone' => $aRow['teacher_phone'],
                    )
            	);
            }

            //Однако, отсутствие лекции может распространяться только на одну из подгрупп
            if ( !empty($aRow['subgroup']) ) {
                $aException['subgroup'] = $aRow['subgroup'];
            }
            
            //И если лекции нет, то может быть коммент
            if ( !empty($aRow['comment']) ) {
                $aException['comment'] = $aRow['comment'];
            }

            $this->aExceptions[ $aRow['ex_date'] ][ $aRow['begin_time'] ] = $aException;
        }
        
        return $this->aExceptions;
    }
    
}