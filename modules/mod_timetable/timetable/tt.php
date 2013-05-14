<?php
require_once 'App.php';
require_once 'libs/Timetable/Control.php';
require_once 'libs/Misc.php';

define('ACTION_VIEW', 'view');
define('ACTION_TODAY', 'today');
define('ACTION_TOMORROW', 'tomorrow');
define('ACTION_REQDATE', 'reqdate');
define('ACTION_REQINTERVAL', 'reqinterval');

define('DEFAULT_GROUP_ID', 2);
define('DEFAULT_SUBGROUP', 1);
define('SUBJECTS_ALL', 0);
define('LESSON_TYPES_ALL', 0);

$aErrors = array();;
$sTimetable = '';
$iCountLessons = 0;
$iSecInDay = 86400;
$bFilterOn = false;
$aTeachers = array();


//Получение данных
$sAction = Utils::inGetPost('action', ACTION_REQINTERVAL);
$sReqDate = Utils::inGetPost('reqdate');
$sReqDate2 = Utils::inGetPost('reqdate2');
$bAjax = Utils::inGetPost('ajax', false);

//В будущем не будет группы и подгруппы по умолчанию, 
//а будут выбираться либо из настроек юзера, либо как предварительное действие
$iMyGroupId = Utils::inGetPostCookie('group_id', DEFAULT_GROUP_ID);     //Также попытаться взять из куки
 
if ( !$iMyGroupId || !is_numeric($iMyGroupId) ) {   
    $aErrors[] = "Не указана группа.";
}

$iMySubgroup = Utils::inGetPostCookie('subgroup', DEFAULT_SUBGROUP);
if ( !$iMySubgroup || !is_numeric($iMySubgroup) ) {
    $aErrors[] = "Не указана подгруппа.";
}
    
$iMySubjectId = Utils::inGetPost('subject_id', SUBJECTS_ALL);
if ( $iMySubjectId && !is_numeric($iMySubjectId) ) {
	$aErrors[] = "Указан невереный предмет.";
}

$iMyLessonTypeId = Utils::inGetPost('lesson_type_id', LESSON_TYPES_ALL);
if ( $iMyLessonTypeId && !is_numeric($iMyLessonTypeId) ) {
    $aErrors[] = "Указан невереный тип занятий.";
}

$aGroups = Misc::getGroups();
$aSubjects[SUBJECTS_ALL] = 'Все предметы';
$aSubjects = $aSubjects + Misc::getSubjects($iMyGroupId);
$aLessonTypes[LESSON_TYPES_ALL] = 'Все типы';
$aLessonTypes = $aLessonTypes + Misc::getLessonTypes();

$bFilterOn = ($iMySubjectId != SUBJECTS_ALL || $iMyLessonTypeId != LESSON_TYPES_ALL);

if ( ! in_array($sAction, array(ACTION_TODAY, ACTION_TOMORROW, ACTION_REQDATE, ACTION_REQINTERVAL)) ) {
	$aErrors[] = 'Выбрано неверное действие';
} else  {
	
    //Определение интервала
	$iReqDate = $iReqDate2 = false;
	switch ($sAction) {
        case ACTION_TOMORROW: {
            $iReqDate = mktime(0, 0, 0) + $iSecInDay;
        } break;
        
        case ACTION_REQDATE: {
            if ( empty($sReqDate) ) {
            	$aErrors[] = "Не введена дата.";
            } else {
	            $iReqDate = Utils::strToTimestamp($sReqDate, '%d.%m.%Y');
			    if ( !$iReqDate ) {
			        $aErrors[] = "Неправильный формат даты.";
			    }
            }
        } break;
        
        case ACTION_REQINTERVAL: {
	        $iReqDate = Utils::strToTimestamp($sReqDate, '%d.%m.%Y');
            if ( empty($iReqDate) ) {
				$iReqDate = mktime(0, 0, 0);
			}
			
	        $iReqDate2 = Utils::strToTimestamp($sReqDate2, '%d.%m.%Y');
			if ( empty($iReqDate2) ) {
	        	$iReqDate2 = $iReqDate + $iSecInDay * 6;   //на неделю
			}
			
			$iReqDate2++;
			if ( $iReqDate2 < $iReqDate ) {
                $aErrors[] = "Вторая дата меньше первой.";
			}
        } break;
        
        case ACTION_TODAY:
        default: {
            $iReqDate = mktime(0, 0, 0);
        } break;
	}

	/*  
	$iFirstDay = Utils::strToTimestamp(Utils::inGetPost('firstday', $sFirstDayDefault), '%d.%m.%Y');
	if ( !$iFirstDay ) {
	    $aErrors[] = "Не удалось определить номер учебной недели";
	}
    */
	
    $oTimetable = new Timetable_Control(array(
        'group_id' => $iMyGroupId, 
        'subgroup' => $iMySubgroup,
        'subject_id' => $iMySubjectId,
        'lesson_type_id' => $iMyLessonTypeId,
    ));

    //Несколько проверок дат на отдаленность от начала учебы
    if ( $oTimetable->getFirstDay() > $iReqDate && empty($aErrors) ) {
        $aErrors[] = "Запрошена дата до начала учебы (".date('d.m.Y', $oTimetable->getFirstDay()).").";
    }
    if ( strtotime('+1 year', $oTimetable->getFirstDay()) < $iReqDate && empty($aErrors) ) {
    	$aErrors[] = "Запрошенная дата слишком далеко от начала учебы (".date('d.m.Y', $oTimetable->getFirstDay()).").";
    }
    if ( $iReqDate2 && strtotime('+1 year', $oTimetable->getFirstDay()) < $iReqDate2 && empty($aErrors) ) {
        $aErrors[] = "Конец интервала дат слишком далек от начала учебы.";
    }
    
	//Получение расписания
	$sTimetable = '';
	if ( empty($aErrors) ) {
		
		//Узнать число дней в запрашиваемом интервале 
		$iCountDays = 1;
		if ( !empty($iReqDate2) ) {
			$iCountDays = ceil(($iReqDate2 - $iReqDate) / $iSecInDay);
		}
		
		//Для каждого дня из интервала получить расписание
		for ($i = 0; $i < $iCountDays; $i++) {
			
			//Если день недели - воскресенье, то не выводить расписание
			if ( date('w', $iReqDate) == 0 && $iCountDays != 1 ) {
				$iReqDate += $iSecInDay;
				 
				//Визуальный отступ между неделями. Не выводить, если есть фильтр (иначе может быть много пустых строк)
				if ( !$bFilterOn ) {
					$sTimetable .= '<br />';    
				}
				continue;
			}

			//Массив с действительным расписанием для текущего дня
			$aTimetable = $oTimetable->getTimetableForDay($iReqDate);
			
			//Преобразовать массив с расписанием в удобный для юзера вид 
			$_sTimetable = $oTimetable->printTimetable($aTimetable);

			//Если занятий не найдено
			if ( empty($_sTimetable) ) {
				//Если выбран фильтр по предметам или типам занятий, то не выводить день без занятий
                if ( $bFilterOn ) {
                    $iReqDate += $iSecInDay; 
					continue;
    			} else {     //В остальных случаях имеет смысл выводить надпись ниже
	       			$_sTimetable = 'Занятий нет<br/>';
                }
			} else if ( $bFilterOn ) {
				//Если есть занятия и включен фильтр, то надо подсчитать пары
				$iCountLessons += count($aTimetable);
			}
			
			//Шапка для текущего дня (день месяц (день недели))
			$sTimetable .= "<span class='bld'>".date('d', $iReqDate).' '.Utils::getMonthName(date('n', $iReqDate)).' ('.Utils::getDayShortName(date('w', $iReqDate) ). '):</span>';
			$sTimetable .= '<br />' . $_sTimetable . '<br />';
			
			$iReqDate += $iSecInDay; //перейти к следующему дню     
		}
	}
	
	$aTeachers = Misc::getTeachers($iMyGroupId);
}

//Записать лог (только при отсутствии ошибок)
if ( empty($aErrors) ) {
	Misc::logAction("Выдано расписание (Group ID: $iMyGroupId)");
}

//Если задан фильтр по предметам или типам занятий, то нужно вывести число найденных занятий.
//При этом если занятий не найдено, то нужно выводить "0", а если фильтр не задан, но занятий нет, то вывести надпись
if ( $bFilterOn ) {
    $sTimetable = "Найдено занятий: $iCountLessons<br /><br />".$sTimetable;
} else if ( empty($sTimetable) && empty($aErrors) ) {
	$aErrors[] = 'Занятий нет.';
}
    
//Если юзер зашел не с мобильного устройства, то при изменении селект-боксов
//происходит ajax-запрос, который возвращает только расписание
if ( $bAjax ) {
	//Если были ошибки, то вывести только их
	echo (empty($aErrors) ? $sTimetable : join('<br />', $aErrors));
	exit;
}

require_once 'templates/tt.tpl';
?>