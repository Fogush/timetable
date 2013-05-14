<?php
require_once 'libs/PhpbbBridge.php';

/**
 * Организует загрузку расписаний.
 * Расписания поступают в виде XML, потом проверяются по XML Schema,
 * Парсятся и сохраняются в базе
 *
 */
class Timetable_Upload
{
    public $sPathToSchema = 'data/xml_schemas/timetable.xsd';
    public $sPathToValidated = 'data/timetables/';
    public $sPathToTmp = 'data/timetables/tmp/';

    protected $sTmpFilename = '';
    private $aLessons = array();
    private $aTimetable = array();

    protected $aErrors = array();

    protected function addError($sError)
    {
        $this->aErrors[] = $sError;
    }

    public function getErrors()
    {
        return $this->aErrors;
    }

    /**
     * Проверить загруженный файл по Схеме, распарсить и сохранить данные в базу.
     * Возвращает текст ошибки или сообщение о том, что загрузка прошла успешна.
     *
     * @param string $sInputName
     * @return string
     */
    public function upload($sInputName)
    {
        //Проверить и переместить файл
        if ( !$this->move($sInputName) ) {
            return false;
        }

        //Проверить по Схеме
        if ( !$this->validate() ) {
            $sError = "<br />Загруженный файл содержит ошибки в синтаксисе XML или не соответствует правилам. ";
            $sError .= "Пожалуйста, исправьте файл и загрузите еще раз";
            $this->addError($sError);
            return false;
        }

        //Распарсить и сформировать массив занятий
        if ( !$this->parse() ) {
            return false;
        }

        //Сохранить массив в базу
        if ( !$this->save() ) {
            return false;
        }

        $this->log();

        return true;
    }

    /**
     * 1-е действие.
     * Проверить и переместить загруженный файл.
     *
     * @param string $sInputName
     * @return bool
     */
    protected function move($sInputName)
    {
        if ( !isset($_FILES[$sInputName]) ) {
            $this->addError("Не удалось загрузить файл");
            return false;
        }

        if ( $_FILES[$sInputName]['error'] != UPLOAD_ERR_OK ) {
            $this->addError("Не удалось загрузить файл");
            return false;
        }

        if ( !is_uploaded_file($_FILES[$sInputName]['tmp_name']) ) {
            $this->addError("Не удалось загрузить файл");
            return false;
        }

        if ( $_FILES[$sInputName]['type'] != 'text/xml' ) {
            $this->addError("Тип файла должен быть XML");
            return false;
        }

        $this->sTmpFilename = $this->sPathToTmp . time() . '.xml';
        if ( !move_uploaded_file($_FILES[$sInputName]['tmp_name'], $this->sTmpFilename) ) {
            $this->addError("Не удалось скопировать файл расписания");
            return false;
        }

        return true;
    }

    /**
     * 2-е действие.
     * Проверить загруженный XML по Схеме
     *
     * @return bool
     */
    protected function validate()
    {
        $oXml = new DomDocument();

        //Определить путь к XML Schema из конфига
        $sXmlSchema = $this->sPathToSchema;
        if ( !file_exists($sXmlSchema) ) {
            return false;
        }

        if ( !$oXml->Load($this->sTmpFilename) ) {
            return false;
        }

        libxml_clear_errors();
        libxml_use_internal_errors(true);

        $aXml = file($this->sTmpFilename);

        //Валидировать загруженный файл с помощью XML Schema
        if ( !$oXml->schemaValidate($sXmlSchema) ) {

            //Получить ошибки валидации в виде массива объектов LibXMLError
            $aErrors = libxml_get_errors();

            //Добавить ошибки валидации к выводимым ошибкам
            foreach ($aErrors as $oError) {

                switch ($oError->level) {
                    case LIBXML_ERR_WARNING:
                        $sError = "XML Validation Warning $oError->code: ";
                        break;
                    case LIBXML_ERR_ERROR:
                        $sError = "XML Validation Error $oError->code: ";
                        break;
                    case LIBXML_ERR_FATAL:
                        $sError = "XML Validation Fatal Error $oError->code: ";
                        break;
                }

                $sError .= trim($oError->message);
                $sError .= " (Line: $oError->line, Column: $oError->column";
                $sError .= ", Content: ".trim(htmlspecialchars($aXml[$oError->line - 1])).")";

                $this->addError($sError);
            }

            libxml_clear_errors();

            return false;
        }

        return true;
    }

    /**
     * 3-е действие.
     * Распарсить XML, сделать дополнительные проверки, сформировать массив занятий,
     * который будет пригоден для удобного сохранения данных в базу
     *
     * @return true | parse error
     */
    protected function parse()
    {
        $oXml = new SimpleXMLElement(file_get_contents($this->sTmpFilename));
//        Utils::deb($oXml);

        //Вытянуть данные, принадлежащие timetable

        if ( ($iTimestamp = Utils::strToTimestamp((string)$oXml->firstDay, '%d.%m.%Y')) === false ) {
            return 'Не удалось сохранить дату '. $oXml->firstDay;
        }
        $this->aTimetable['first_day'] = date('Y-m-d', $iTimestamp);
        $this->aTimetable['count_weeks'] = (int)$oXml->countWeeks;
        $this->aTimetable['first_num_week'] = (int)$oXml->firstNumWeek;

        //Получить массив всех предметов для того, чтобы проверять введенные предметы на существование
        $aSubjects = Misc::getSubjects();
        //Аналогично для типов занятий (ЛК, ЛБ, ПЗ)
        $aLessonTypes = Misc::getLessonTypes();

        $aLessons = array();

        //Файл разбит на дни недели
        foreach ($oXml->weekday as $oWeekDay) {

            $iWeekDayNumber = (int)$oWeekDay->attributes()->number;   //номер дня недели, начиная с 1

            //Каждый день недели содержит одну или несколько пар
            foreach ($oWeekDay->pair as $oPair) {

                $iPairNumber = (int)$oPair->attributes()->number;     //номер пары, 1-10
                $sBeginTime = str_replace('.', ':', (string)$oPair->beginTime);   //начало пары

                //Для каждой пары заданы занятия, разбитые по учебным неделям
                foreach ($oPair->week as $oWeek) {

                    $iWeekNumber = (int)$oWeek->attributes()->number; //номер _учебной_ недели, 1+

                    //И наконец-то сами занятия
                    foreach ($oWeek->lesson as $oLesson) {

                        //Проверить по базе название предмета и тип занятий. Сохранить ID записи, а не имя
                        $iSubjectId = array_search((string)$oLesson->subject, $aSubjects);
                        if ( empty($iSubjectId) ) {
                            $this->addError('Предмет "'. $oLesson->subject . '" не существует');
                            return false;
                        }
                        $iLessonTypeId = array_search($oLesson->type, $aLessonTypes);
                        if ( empty($iLessonTypeId) ) {
                            $this->addError('Типа занятий "'. $oLesson->type . '" не существует');
                            return false;
                        }
                        //TODO: проверять numbers на уникальность (weekday, weeknumber, pairnumber д.б. уникальными)

                        //Записать обязательные поля
                        $aLesson = array(
                            'weekday' => $iWeekDayNumber,
                            'pair_number' => $iPairNumber,
                            'week_number' => $iWeekNumber,
                            'begin_time' => trim($sBeginTime),
                            'subject_id' => $iSubjectId,
                            'type_id' => $iLessonTypeId,
                            'classroom' => trim($oLesson->classroom),   //любая строка
                        );

                        //Не обязательные
                        if ( isset($oLesson->subgroup) && is_numeric((int)$oLesson->subgroup) ) {
                            $aLesson['subgroup'] = (int)$oLesson->subgroup;
                        }

                        //Поступает в формате дд.мм.гггг
                        if ( isset($oLesson->beginDate) ) {

                            if ( ($iTimestamp = Utils::strToTimestamp((string)$oLesson->beginDate, '%d.%m.%Y')) === false ) {
                                $this->addError('Не удалось сохранить дату '. $oLesson->beginDate);
                                return false;
                            }
                            //Преобразовать в формат БД
                            $aLesson['begin_date'] = date('Y-m-d', $iTimestamp);
                        }

                        //Поступает в формате дд.мм.гггг
                        if ( isset($oLesson->endDate) ) {

                            if ( ($iTimestamp = Utils::strToTimestamp((string)$oLesson->endDate, '%d.%m.%Y')) === false ) {
                                $this->addError('Не удалось сохранить дату '. $oLesson->endDate);
                                return false;
                            }
                            //Преобразовать в формат БД
                            $aLesson['end_date'] = date('Y-m-d', $iTimestamp);
                        }
                        if ( isset($oLesson->comment) ) {
                            $aLesson['comment'] = trim((string)$oLesson->comment);
                        }

                        $aLessons[] = $aLesson;
                    }

                }

            }

        }

        //Сохранить в объекте, чтобы обращаться к нему в save()
        $this->aLessons = $aLessons;

        return true;
    }

    /**
     * 4-е действие.
     * Сохранить данные в базу. Они берутся из массива $this->aLessons,
     * который формируется в $this->parse().
     * Также создать запись в таблице timetables, говорящую о том, что
     * загруженный XML прошел все проверки и будет использоваться в приложении.
     *
     * @return true | save error
     */
    protected function save()
    {
        //Если массив пуст, значит были ошибки.
        if ( empty($this->aLessons) ) {
            return false;
        }

        //Создать запись в timetables
        $iTimetableId = $this->createTimetableRecord();
        if ( $iTimetableId === false ) {
            $this->addError('Не удалось создать расписание в базе');
            return false;
        }

        $db =& JFactory::getDBO();
        $sQuery = "
            INSERT INTO #__tt_lessons (
                timetable_id,
                weekday,
                pair_number,
                week_number,
                begin_time,
                subject_id,
                type_id,
                classroom,
                subgroup,
                begin_date,
                end_date,
                comment) VALUES
        ";

        //subject и type сохраняются как ID записей в базе,
        //begin_time и end_time - как timestamp
        foreach ($this->aLessons as $aLesson) {
            $sQuery .= "(
            ".$db->escape($iTimetableId).",
            '".$db->escape($aLesson['weekday'])."',
            '".$db->escape($aLesson['pair_number'])."',
            '".$db->escape($aLesson['week_number'])."',
            '".$db->escape($aLesson['begin_time'])."',
            '".$db->escape($aLesson['subject_id'])."',
            '".$db->escape($aLesson['type_id'])."',
            '".$db->escape($aLesson['classroom'])."',
            ".(isset($aLesson['subgroup']) ? "'".$db->escape($aLesson['subgroup'])."'" : "NULL").",
            ".(isset($aLesson['begin_date']) ? "'".$db->escape($aLesson['begin_date'])."'" : "NULL").",
            ".(isset($aLesson['end_date']) ? "'".$db->escape($aLesson['end_date'])."'" : "NULL").",
            ".(isset($aLesson['comment']) ? "'".$db->escape($aLesson['comment'])."'" : "NULL")."),";
        }
        $sQuery = substr($sQuery, 0, -1);

        $db->setQuery($sQuery);

        if ( !$db->execute() ) {
            $this->addError('Не удалось сохранить занятия в базу');
            return false;
        }

        return true;
    }

    /**
     * Создать новую запись в таблице timetables,
     * чтобы потом с ней связывать все занятия, которые вскоре будут добавлены в базу
     *
     * @return int
     */
    private function createTimetableRecord()
    {
        $oUser = new User();
        $aGroup = $oUser->getGroup();

        //Узнать ID группы текущего юзера
        $iGroupId = $aGroup['id'];

        $db =& JFactory::getDBO();

        //Сначала создаем пустую запись
        $sQuery = "
           INSERT INTO #__tt_timetables (
               group_id, filename, date_created,
               first_day,
               count_weeks,
               first_num_week) VALUES
              (".$db->escape($iGroupId).", '', NOW(),
              '".$db->escape($this->aTimetable['first_day'])."',
              '".$db->escape($this->aTimetable['count_weeks'])."',
              '".$db->escape($this->aTimetable['first_num_week'])."')
        ";
        $db->setQuery($sQuery);
        if ( !$db->execute($sQuery) ) {
            $this->addError('Не удалось создать расписание');
            return false;
        }

        //Даем имя файлу и перемещаем его в хранилище загруженных расписаний
        $iTimetableId = $db->getInsertedId();
        $sFilename = 'g'. $iGroupId . '_t' . $iTimetableId . '.xml';
        copy($this->sTmpFilename, App::getInstance()->sRootPath.$this->sPathToValidated.$sFilename);

        //Записываем в базу новое имя
        $sQuery = "
            UPDATE
                #__tt_timetables
            SET
                filename = '".$sFilename."'
            WHERE
                id = ".$db->escape($iTimetableId)."
        ";
        $db->setQuery($sQuery);
        if ( !$db->execute($sQuery) ) {
            $this->addError('Не удалось создать расписание');
            return false;
        }

        return $iTimetableId;
    }

    private function log()
    {
        $oUser = new User();
        $aGroup = $oUser->getGroup();

        Misc::logAction("Загружено расписание (Group ID: {$aGroup['id']})");
    }
}