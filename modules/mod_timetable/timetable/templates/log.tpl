<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="css/styles.css" type="text/css" />     
  <title>АСЕР - Лог действий пользователей</title>
</head>
<body>
<table>
  <tr>
    <td width='150px'><strong>Дата</strong></td>
    <td width='300px'><strong>Описание действия</strong></td>
    <td width='200px'><strong>Пользователь</strong></td>
  </tr>
  <?php foreach ($aLogTable as $aLog) { ?>
    <tr>
        <td><?=$aLog['date_created']?></td>
        <td><?=$aLog['description']?></td>
        <td><?=$aLog['user_name']?></td>
    </tr>
  <?php } ?>
</table>
</body>
</html>