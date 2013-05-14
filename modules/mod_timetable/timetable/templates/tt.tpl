  <form id="timetable" method="GET" action="" class="form-horizontal">
    <div class="row">
      <div class="control-label">Группа:</div>
      <div class="controls">
      <select name="group_id" id="group_id" class="form-select">
      <?php foreach ($aGroups as $iGroupId => $sGroupName) { ?>
        <option value="<?=$iGroupId?>" <?=($iGroupId == $iMyGroupId ? 'selected="selected"' : '')?>><?=$sGroupName?></option>
      <?php } ?>
      </select>
      </div>
    </div>
    <div class="clear"></div>
    <div class="row" >
      <div class="control-label">
        Подгруппа:
      </div>
      <div class="controls">
      <select name="subgroup" id="subgroup" class="form-select">
        <option value="1" <?php echo $iMySubgroup == 1 ? 'selected="selected"' : '';?>>1</option>
        <option value="2" <?php echo $iMySubgroup == 2 ? 'selected="selected"' : '';?>>2</option>
      </select>
    </div>
    </div>
    <div class="clear"></div>
    
    <div class="row">
      <div class="control-label">Предмет:</div>
      <div class="controls">
      <select name="subject_id" id="subject_id" class="form-select">
      <?php foreach ($aSubjects as $iSubjectId => $sSubjectName) { ?>
        <option value="<?=$iSubjectId?>" <?=($iSubjectId == $iMySubjectId ? 'selected="selected"' : '')?>><?=$sSubjectName?></option>
      <?php } ?>
      </select>
      </div>
    </div>
    <div class="clear"></div>

    <div class="row">
      <div class="control-label">Тип занятия:</div>
      <div class="controls">
      <select name="lesson_type_id" id="lesson_type_id" class="form-select">
      <?php foreach ($aLessonTypes as $iLessonTypeId => $sLessonTypeName) { ?>
        <option value="<?=$iLessonTypeId?>" <?=($iLessonTypeId == $iMyLessonTypeId ? 'selected="selected"' : '')?>><?=$sLessonTypeName?></option>
      <?php } ?>
      </select>
      </div>
    </div>
    <div class="clear"></div>

    <div class="row" >
      <div class="control-label">Дата:</div>
      <div class="controls">
        <?php echo JHTML::calendar($sReqDate,'reqdate','first-date','%d.%m.%Y'); ?>
      </div>
    </div>
    <div class="clear"></div>

    <div class="row" >
      <div class="control-label">Дата 2:</div>
      <div class="controls">
        <?php echo JHTML::calendar($sReqDate2,'reqdate2','second-date','%d.%m.%Y'); ?>
      </div>
      <div class="controls">
        <span id="ajax-loader"></span>
      </div>
    </div>
    <div class="clear"></div>

    <div id="get-timetable-box">
      <div id="get-timetable-label"><a href="#" id="show-help">(?)</a> Получить расписание на:</div>
      <input type="submit" value="Сегодня" id="submit-today" onclick="setAction('<?php echo ACTION_TODAY;?>')" />
      <input type="submit" value="Завтра" id="submit-tomorrow" onclick="setAction('<?php echo ACTION_TOMORROW;?>')" />
      <input type="submit" value="Указанную дату" id="submit-reqdate" onclick="setAction('<?php echo ACTION_REQDATE;?>')" />
      <input type="submit" value="Интервал дат" id="submit-interval" onclick="setAction('<?php echo ACTION_REQINTERVAL;?>')" title="Если даты не ввести, то будет расписание на 7 дней" />
      <input type="hidden" id="action" name="action" value="<?php echo $sAction;?>" />
      <br />
    </div>
    <div class="clear"></div>
    
    <ol id="help-box" style="display: none">
      <li>1. Формат даты - дд.мм.гггг</li>
      <li>2. Для типа "На указанную дату" нужно заполнить первое поле "Дата".</li>
      <li>3. Для типа "Интервал дат" нужно заполнить поля "Дата" (начало интервала) и "Дата 2" (конец интервала). 
    Если ничего не ввести, то будет расписание на 7 дней от текущей даты.</li> 
      <li>4. Номера группы и подгруппы запоминаются в куки.</li>
      <li>5. Цвета определяют тип занятия (по умолчанию, красный - ЛБ, синий - ПЗ).</li>
      <li>6. При наведении курсора на имя преподавателя появляется подсказка, содержащая его полное имя и телефон (если известно).</li>
    </ol>
    
    <div class="clear" id="timetable-indent"></div>
    
    <div id="errors">
      <?php echo join('<br />', $aErrors);?>
    </div>
    
    <div><a href="#" id="teachers-link">Преподаватели</a></div>
    <div id="teachers-block" class="hidden">
	    <?php foreach ($aTeachers as $aTeacher) { ?>
            <div>
            <?php 
                $iSubjectIndex = 0; 
                foreach ($aTeacher['subjects'] as $sSubjectName => $aLessonTypes) {
                    
                    echo '<span class="bld">', $sSubjectName, ' (';
                    
                    foreach ($aLessonTypes as $iIndex => $sLessonType) {
                        
                        echo mb_strtolower($sLessonType);
                        
                        if ( count($aLessonTypes) != ($iIndex + 1) ) {
                            echo ", ";
                        }
                    }
 
                    echo ")";

                    if ( count($aTeacher['subjects']) != (++$iSubjectIndex) ) {
                        echo ", ";
                    }
                    echo "</span>";
                }

	            echo ": ", trim($aTeacher['last_name'] . ' ' . $aTeacher['first_name'] . ' ' . $aTeacher['middle_name']);
                if ( $aTeacher['phone'] ) {
                    echo ' (', $aTeacher['phone'], ')';
                }
	        ?>
	        </div>
        <?php } ?>
    </div>
    <div id="timetable-data">
      <?php echo $sTimetable;?>
    </div>
  </form>