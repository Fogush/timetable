<?php

JHtml::_('jquery.framework');
$document = JFactory::getDocument();
$document->addStyleSheet('media/jui/css/chosen.css');
$document->addStyleSheet('media/timetable/css/styles.css');
$document->addScript('/media/timetable/js/tt.js');
$document->addScript('/media/jui/js/chosen.jquery.min.js');

require_once "timetable/tt.php";