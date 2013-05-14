<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="css/styles.css" type="text/css" />     
  <title>АСЕР - Загрузка расписаний</title>
</head>
<body>
    <form action="" enctype="multipart/form-data" method="post">
        Группа: <?=$sGroupName?><br /><br />
        Расписание (<a href="upload.php?dwnld_tt=1<?=($iReplaceId ? '&user_id='.$iReplaceId :'')?>">скачать текущее</a>): <br />
        <input name="timetable" type="file" /><br />
        <input type="submit" value="Отправить" /><br />
		<br />
        <div><?=$sResultTimetable?></div>
        <br />
        Исключения: <br />
        <input name="timetable_exceptions" type="file" /><br />
        <input type="submit" value="Отправить" /><br />
        <br />
        <div><?=$sResultExceptions?></div>    
    </form>
</body>
</html>