<?php

require_once 'libs/Timetable/Upload.php';

class Timetable_Upload_Exceptions extends Timetable_Upload {


    public $sPathToSchema = 'data/xml_schemas/exceptions.xsd';
    public $sPathToValidated = 'data/exceptions/';
    public $sPathToTmp = 'data/exceptions/tmp/';

    private $aExceptions = array();

    /**
     * 3-е действие.
     * Распарсить XML, сделать дополнительные проверки, сформировать массив исключений,
     * который будет пригоден для удобного сохранения данных в базу
     *
     * @return true | parse error
     */
    protected function parse()
    {
        $oXml = new SimpleXMLElement(file_get_contents($this->sTmpFilename));

        //Получить массив всех предметов для того, чтобы проверять введенные предметы на существование
        $aSubjects = Misc::getSubjects();
        //Аналогично для типов занятий (ЛК, ЛБ, ПЗ)
        $aLessonTypes = Misc::getLessonTypes();

        $aExceptions = array();

        //Файл разбит на дни недели
        foreach ($oXml->exception as $oException) {

            //Дата исключения
            if ( ($iTimestamp = Utils::strToTimestamp((string)$oException->attributes()->date, '%d.%m.%Y')) === false ) {
                $this->addError('Не удалось сохранить дату '. $oException->attributes()->date);
                return false;
            }
            $sDate = date('Y-m-d', $iTimestamp);

            //Время начала
            $sBeginTime = str_replace('.', ':', (string)$oException->attributes()->beginTime);

            if ( isset($oException->attributes()->withoutLesson) ) {
                $bWithoutLesson = (boolean)$oException->attributes()->withoutLesson;
            } else {
                $bWithoutLesson = false;
            }

            $aException = array(
                'exception_date' => $sDate,
                'begin_time' => trim($sBeginTime),
                'without_lesson' => $bWithoutLesson,
            );

            //Не обязательные поля
            if ( isset($oException->subgroup) && is_numeric((int)$oException->subgroup) ) {
                $aException['subgroup'] = (int)$oException->subgroup;
            }

            if ( isset($oException->comment) ) {
                $aException['comment'] = trim((string)$oException->comment);
            }

            //Если нет занятий в этот день, то остальные значения не записывать
            if ( !$bWithoutLesson ) {

                //Проверить по базе название предмета и тип занятий. Сохранить ID записи, а не имя
                $iSubjectId = array_search((string)$oException->subject, $aSubjects);
                if ( empty($iSubjectId) ) {
                    $this->addError('Предмет "'. $oException->subject . '" не существует');
                    return false;
                }
                $iLessonTypeId = array_search($oException->type, $aLessonTypes);
                if ( empty($iLessonTypeId) ) {
                    $this->addError('Типа занятий "'. $oException->type . '" не существует');
                    return false;
                }

                //Записать обязательные поля
                $aException = $aException + array(
                    'subject_id' => $iSubjectId,
                    'type_id' => $iLessonTypeId,
                    'classroom' => trim($oException->classroom),   //любая строка
                );

            }

            $aExceptions[] = $aException;

        }

        //Сохранить в объекте, чтобы обращаться к нему в save()
        $this->aExceptions = $aExceptions;

        return true;
    }


    /**
     * 4-е действие.
     * Сохранить данные в базу. Они берутся из массива $this->aExceptions,
     * который формируется в $this->parse().
     *
     * @return true | save error
     */
    protected function save()
    {
        //Если массив пуст, значит были ошибки.
        if ( empty($this->aExceptions) ) {
            return false;
        }

        require_once 'libs/User.php';
        $oUser = new User();

        //Узнать ID группы текущего юзера (админа)
        $aGroup = $oUser->getGroup();

        $aTimetable = Misc::getActualTimetable($aGroup['id']);
        if ( empty($aTimetable) ) {
            $this->addError('Не удалось определить текущее расписание');
            return false;
        }

        $db =& JFactory::getDBO();

        //Сначала удаляем все предыдущие исключения - чтобы не накладывались друг на друга в пределах одного timetable
        $sQuery = "
            DELETE FROM
                #__tt_exceptions
            WHERE
                timetable_id = '".$db->escape($aTimetable['id'])."'
        ";
        $db->setQuery($sQuery);
        if ( !$db->execute($sQuery) ) {
            $this->addError('Не удалось сохранить в базу');
            return false;
        }

        $sQuery = "
            INSERT INTO #__tt_exceptions (
                timetable_id,
                exception_date,
                begin_time,
                without_lesson,
                subject_id,
                type_id,
                classroom,
                subgroup,
                comment) VALUES
        ";

        //subject и type сохраняются как ID записей в базе,
        foreach ($this->aExceptions as $aException) {
            $sQuery .= "(
            '".$db->escape($aTimetable['id'])."',
            '".$db->escape($aException['exception_date'])."',
            '".$db->escape($aException['begin_time'])."',
            '".($db->escape($aException['without_lesson']) ? '1' : '0')."',
            ".(isset($aException['subject_id']) ? "'".$db->escape($aException['subject_id'])."'" : "NULL").",
            ".(isset($aException['type_id']) ? "'".$db->escape($aException['type_id'])."'" : "NULL").",
            ".(isset($aException['classroom']) ? "'".$db->escape($aException['classroom'])."'" : "NULL").",
            ".(isset($aException['subgroup']) ? "'".$db->escape($aException['subgroup'])."'" : "NULL").",
            ".(isset($aException['comment']) ? "'".$db->escape($aException['comment'])."'" : "NULL")."),";
        }
        $sQuery = substr($sQuery, 0, -1);

        $db->setQuery($sQuery);
        if ( !$db->execute($sQuery) ) {
            $this->addError('Не удалось сохранить в базу');
            return false;
        }

        $sFilename = 't' . $aTimetable['id'] . '_' . time() . '.xml';
        copy($this->sTmpFilename, App::getInstance()->sRootPath.$this->sPathToValidated.$sFilename);

        return true;
    }

    private function log()
    {
        $oUser = new User();
        $aGroup = $oUser->getGroup();

        Misc::logAction("Загружены исключения (Group ID: {$aGroup['id']})");
    }
}

?>