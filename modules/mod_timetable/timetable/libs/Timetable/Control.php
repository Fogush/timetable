<?php

class Timetable_Control
{
	const LESSON_TYPE_LK = 0;
	const LESSON_TYPE_PZ = 1;
	const LESSON_TYPE_LB = 2;
	const LESSON_TYPE_UNKNOWN = 3;
    const LESSON_TYPE_NONE = 4;
	
    /**
     * Объект с раписанием занятий
     *
     * @var object Timetable_Data
     */
    public $oTimetableData = null;
    
    /**
     * Массив с расписанием
     *
     * @var array
     */
    private $aTimetableData = array();
    
    /**
     * Массив с исключениями в расписании
     *
     * @var array
     */
    private $aTimetableExceptions = array();
        
	/**
	 * Номер подгруппы
	 *
	 * @var int
	 */
    private $iSubgroup = false;
    
    /**
     * Дата первого дня учебы. Используется для определения текущей учебной недели
     * 
     * @var int 
     */
    private $iFirstDay = false;
    
    /**
     * Номер первой учебной недели
     * 
     * @var int 
     */
    private $iFirstNumWeek = false;
    
    /**
     * Максимальное число учебных недель
     * 
     * @var int
     */
    private $iCountWeeks = 1;
    
    /**
     * ID группы, которой принадлежит расписание
     * 
     * @var int
     */
    private $iGroupId = false;
    
    /**
     * ID предмета, по которому нужно сделать фильтр. 
     * Если 0, то без фильтра
     * 
     * @var int
     */
    private $iSubjectId = false;
    
    /**
     * ID типа занятия из lesson_types, по которому нужно сделать фильтр
     * Если 0, то без фильтра
     * 
     * @var int
     */
    private $iLessonTypeId = false;
    
    
	/**
	 * @return int
	 */
	public function getCountWeeks() 
	{
		return $this->iCountWeeks;
	}
	
	/**
	 * @return int
	 */
	public function getFirstDay() 
	{
		return $this->iFirstDay;
	}
    
    /**
     * @return int
     */
    public function getFirstNumWeek() 
    {
        return $this->iFirstNumWeek;
    }
	
	/**
	 * @return int
	 */
	public function getSubgroup() 
	{
		return $this->iSubgroup;
	}

    /**
     * @return int
     */
    public function getGroupId() 
    {
        return $this->oTimetableData->getGroupId();
    }

    
    /**
     * @param int $iSubgroup
     */
    public function setGroupId($iGroupId) 
    {
        $this->iGroupId = $iGroupId;
    }
    
	/**
	 * @param int $iSubgroup
	 */
	public function setSubgroup($iSubgroup) 
	{
		$this->iSubgroup = $iSubgroup;
	}
    
    /**
     * @param int $iSubjectId
     */
    public function setSubjectId($iSubjectId) 
    {
        $this->iSubjectId = $iSubjectId;
    }
    
    /**
     * @param int $iLessonTypeId
     */
    public function setLessonTypeId($iLessonTypeId) 
    {
        $this->iLessonTypeId = $iLessonTypeId;
    }

    /**
     * Получить расписание занятий в виде массива 
     * 
     * @return array
     */
    protected function getTimetableData() 
    {
        return $this->oTimetableData->getTimetableData();
    }
    
    /**
     * Получить исключения в расписании в виде массива 
     * 
     * @return array
     */
    protected function getTimetableExceptions() 
    {
        return $this->oTimetableData->getExceptions();
    }
    
	/**
	 * ///
	 * Пока что group_id нужно передавать всегда
	 *
	 * @param array $aOptions
	 */
    public function __construct($aOptions = array())
    {
        if ( isset($aOptions['group_id']) ) {
            $this->setGroupId($aOptions['group_id']);
        } // else { error }
   		if ( isset($aOptions['subgroup']) ) {
   			$this->setSubgroup($aOptions['subgroup']);
   		}
        if ( isset($aOptions['subject_id']) ) {
            $this->setSubjectId($aOptions['subject_id']);
        }
        if ( isset($aOptions['lesson_type_id']) ) {
            $this->setLessonTypeId($aOptions['lesson_type_id']);
        }
    	
        require_once 'libs/Timetable/Data.php';
        $this->oTimetableData = new Timetable_Data($aOptions['group_id']);
        
        $this->aTimetableData = $this->getTimetableData();
        $this->aTimetableExceptions = $this->getTimetableExceptions();
        $this->iFirstDay = $this->oTimetableData->getFirstDay();
        $this->iFirstNumWeek = $this->oTimetableData->getFirstNumWeek();
        $this->iCountWeeks = $this->oTimetableData->getCountWeeks();
    }
    
    /**
     * Получить номер учебной недели для указанной даты
     *
     * @param int(timestamp) $iReqDate 
     * @return int
     */
    protected function getNumWeek($iReqDate = false)
    {
	    if ( $this->iCountWeeks > 1 && $iReqDate ) {
	    	$iFirstWeek = date('W', $this->iFirstDay);    //W - номер недели в году
		    $iReqWeek = date('W', $iReqDate);
		    if ( date('Y', $iReqDate) > date('Y', $this->iFirstDay) ) {  //для следующего от начала учебы года
		        $iReqWeek += date('W', mktime(0, 0, 0, 12, 31, date('Y', $this->iFirstDay))); 
		    }
		    
		    $iFirstNumWeek = $this->iFirstNumWeek ? $this->iFirstNumWeek : 1;
		    
		    $iNumWeek = abs(($iReqWeek - $iFirstWeek + $iFirstNumWeek)) % $this->iCountWeeks;
		    return $iNumWeek === 0 ? $this->iCountWeeks : $iNumWeek;
		} else {
		    return 1;
		}
    }
	
    /**
     * По типу занятия получить его текстовое значение
     *
     * @param int $type
     * @return string
     */
	public function convertLessonType($type) 
	{
	    $ret = '';
	    switch ($type) {
	        case self::LESSON_TYPE_LK: {
	            $ret = 'лк'; 
	        } break;
	        case self::LESSON_TYPE_PZ: {
	            $ret = 'пз'; 
	        } break;
	        case self::LESSON_TYPE_LB: {
	            $ret = 'лб'; 
	        } break;
	        case self::LESSON_TYPE_UNKNOWN: {
                $ret = 'неизв.';
            } break;
            case self::LESSON_TYPE_NONE:
	        default: {
	            $ret = 'неизв.';
	        }
	    }
	    return $ret;
	}
	
	/**
	 * Получить расписание занятий для указанного дня.
	 * Если $iReqDate == false, то вернет для текущего дня
	 *
	 * @param int(timestamp) $iReqDate
	 * @return array
	 */
	public function getTimetableForDay($iReqDate = false)
	{
		if ( !$iReqDate ) {
			$iReqDate = mktime(0, 0, 0); 
		}
		
        $aRetTimetable = array();
		
        //Не выдавать расписание в дни до начала учебы
		if ( $this->getFirstDay() > $iReqDate ) {
			return $aRetTimetable;
		}
		
		//Узнать номер учебной недели
		$iNumWeek = $this->getNumWeek($iReqDate);
		
		//Узнать номер дня недели
		$iWeekDay = date('w', $iReqDate);   //0 - воскресенье
        
        //Получить исключения в расписании для требуемого дня. Ключи в массиве представляют собой даты
        if ( isset($this->aTimetableExceptions[ date('d.m.Y', $iReqDate) ]) ) {
        	$aExceptions = $this->aTimetableExceptions[ date('d.m.Y', $iReqDate) ];
        } else {
        	$aExceptions = array();
        }

        //Попытаться взять занятия из исключений
        foreach ($aExceptions as $sBeginTime => $aLesson) {
            if ( $this->checkLesson($aLesson, $iReqDate) ) {
                $aRetTimetable[$sBeginTime] = $aLesson;
            }
        }
        
		//Получить всё расписание для текущего дня недели
        $aCurDay = isset($this->aTimetableData[$iWeekDay]) ? $this->aTimetableData[$iWeekDay] : false;
        if ( !$aCurDay ) {
        	return $aRetTimetable;
        }

        //Пройтись по каждой паре нужного дня недели
        foreach ($aCurDay as $aPair) {
        	 
        	//Если занятие с данным временем уже есть, т.е. найдено исключение, то обычное расписание выводить не нужно
        	if ( isset($aRetTimetable[ $aPair['begin_time'] ]) ) {
        		
	            //Если стоит фильтр и without_lesson = 1 (из исключений), то не просто не выводить обычное расписание,
	            //но и удалить из исключений - при фильтра по предмету/типу не нужно выводить надписи "нет занятий" 
	            if ( ($this->iSubjectId || $this->iLessonTypeId) && 
	                 isset($aRetTimetable[ $aPair['begin_time'] ]['without_lesson']) && $aRetTimetable[ $aPair['begin_time'] ]['without_lesson'] ) {
	            
	                unset($aRetTimetable[ $aPair['begin_time'] ]);
	            }
        		
                continue;  
        	}
        	
		    $mLessons = isset($aPair['lessons'][$iNumWeek]) ? $aPair['lessons'][$iNumWeek] : false;
		    
	        //Взять расписание с указанной недели. 
	        //TODO: Старый функционал, когда можно было создавать ссылки на другие занятия.
	        //В данный момент не работает из-за незавершенности XML схемы. Нужно доделать 
		    if ( is_numeric($mLessons) ) {
		        $mLessons = isset($aPair['lessons'][$mLessons]) ? $aPair['lessons'][$mLessons] : false;
		    }
		    
		    //Если для указанной недели не найдено занятий
		    if ( $mLessons === false ) {
                continue;
		    }
		    
		    //Обработать все занятия для указанной учебной недели
		    foreach ($mLessons as $aLesson) {
//		        if ( $iWeekDay == 2 && $iNumWeek == 2 && $aLesson['subgroup'] == 1) {
//		        	Utils::deb($aLesson);
//		        }

		    	if ( !$this->checkLesson($aLesson, $iReqDate) ) {
		    		continue;
		    	}
		    	
		    	//Записывать занятие только когда пройдены все проверки
                $aRetTimetable[ $aPair['begin_time'] ] = $aLesson;
		    }
        }
        
        //Из-за наличия исключений и из-за того, что юзер мог ввести данные в любом порядке
        uksort($aRetTimetable, array($this, 'compareTime'));
        
        return $aRetTimetable;
	}

	/**
	 * Функция для сравнения времени начала пары. 
	 * Время в виде строки \d\d?:\d\d 
	 *
	 * @param string $a
	 * @param string $b
	 * @return -1 | 0 | 1 
	 */
	private function compareTime($a, $b) 
	{
        $a = str_replace(':', '', $a);
        $b = str_replace(':', '', $b);
		
        if ( $a == $b ) {
			return 0; 
		} 
		
		return ($a > $b ? 1 : -1);
	}
                
	/**
	 * Проверить, удовлетворяет ли занятие всем условиям и фильтрам
	 *
	 * @param array $aLesson
	 * @param int (timestamp) $iReqDate
	 * @return true | false
	 */
    private function checkLesson($aLesson, $iReqDate)
    {
        //Для занятия указана подгруппа и она не совпадает с нужной
        if ( isset($aLesson['subgroup']) && $aLesson['subgroup'] != $this->iSubgroup) {
            return false;
        }
    
        //Пропускать остальные проверки, если установлен флаг without_lesson.
        //В этом случае не важны даты, предметы и типы занятий - они вообще не указываются. Занятие всегда валидно
        //Однако подгруппа имеет значение, так что сначала проверяется она
        if ( isset($aLesson['without_lesson']) && $aLesson['without_lesson'] ) {
            return true;
        }
        
        $iBeginDate = isset($aLesson['begin_date']) && $aLesson['begin_date'] ? $aLesson['begin_date'] : 0;
        $iEndDate = isset($aLesson['end_date'])  && $aLesson['end_date'] ? $aLesson['end_date'] : $iReqDate + 1;

        //Для занятия указан временной интервал и запрашиваемая дата в него не попадает
        if ( !($iReqDate >= $iBeginDate && $iReqDate <= $iEndDate) ) {
            return false;
        }
    
        //Фильтр по предмету
        if ( !empty($this->iSubjectId) && (!isset($aLesson['subject_id']) || $this->iSubjectId != $aLesson['subject_id']) ) {
            return false;
        }
    
        //Фильтр по типу занятий    
        if ( !empty($this->iLessonTypeId) && (!isset($aLesson['type_id']) || $this->iLessonTypeId != $aLesson['type_id']) ) {
            return false;
        }
        
        return true;
    }

    /**
     * Вывод всего расписания 
     * Это полувременная функция. Формировать вывод надо через шаблоны...
     * 
     * @param array $aTimetable
     */
	public function printTimetable($aTimetable)
	{
		$buffer = '';
		foreach ($aTimetable as $sBeginTime => $aLesson) {
		    	
		  	//Сформировать свою строку для исключения. Если есть коммент (и мб подгруппа), 
		  	//то вывести коммент вместе со временем.  
		  	if ( isset($aLesson['without_lesson']) && $aLesson['without_lesson'] ) {
  		
                //Но исключение без коммента не выводить вообще
                if ( isset($aLesson['comment']) ) {
                    $buffer .= "<b>".htmlspecialchars($sBeginTime, ENT_QUOTES)."</b>: ";
	
	                if ( isset($aLesson['subgroup']) ) {
	                    $buffer .= $aLesson['subgroup']." подгр. ";
	                }
	        
			        $buffer .= "Комментарий: ".htmlspecialchars($aLesson['comment'], ENT_QUOTES);
			        $buffer .= "<br/>\n";
                }
  		
            } else {
  		
                //Вывести обычное занятие
                $buffer .= "<span class='bld'>".htmlspecialchars($sBeginTime, ENT_QUOTES)."</span>: ";
            
                if ( !empty($aLesson['type_color']) ) {
                    $buffer .= "<span style='color: ".$aLesson['type_color']."'>";
                }
                
                
                //TODO: вынести обработку учителей в отд. функцию, которая будет кэшировать результаты
                $sTeacherFirstName = ($aLesson['teacher']['first_name'] ? mb_substr($aLesson['teacher']['first_name'], 0, 1) . '.' : '');
                $sTeacherMiddleName = ($aLesson['teacher']['middle_name'] ? mb_substr($aLesson['teacher']['middle_name'], 0, 1) . '.' : '');
                $sTeacherShortName = trim($aLesson['teacher']['last_name'] . ' ' . $sTeacherFirstName . $sTeacherMiddleName);
                
                $sTeacherFullInfo = trim($aLesson['teacher']['last_name'] . ' ' . $aLesson['teacher']['first_name'] . ' ' . $aLesson['teacher']['middle_name']);
                if ( $aLesson['teacher']['phone'] ) {
                    $sTeacherFullInfo .= ' (' . $aLesson['teacher']['phone'] . ')';
                }
                
                $buffer .= $aLesson['subject'].", ";
                
                if ( $sTeacherShortName ) {
                    $buffer .= '<span title="'.$sTeacherFullInfo.'">'.$sTeacherShortName."</span>, ";                
                }
                
                $buffer .= mb_strtolower($aLesson['type']).", "; 
                $buffer .= "а. ".htmlspecialchars($aLesson['classroom'], ENT_QUOTES);
                
                if ( isset($aLesson['subgroup']) ) {
                    $buffer .= " (".$aLesson['subgroup']." подгр.)";
                }
                if ( isset($aLesson['comment']) ) {
                    $buffer .= ". Комментарий: ".htmlspecialchars($aLesson['comment'], ENT_QUOTES);
                }
                if ( !empty($aLesson['type_color']) ) {
                    $buffer .= "</span>";
                }
                
                $buffer .= "<br/>\n";
            }
			    
	                
		}
		
		return $buffer;
	}
}