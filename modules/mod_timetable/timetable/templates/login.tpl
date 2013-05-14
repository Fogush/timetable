<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="css/styles.css" type="text/css" />    
  <link rel="stylesheet" href="css/login.css" type="text/css" />
  <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
  <script type="text/javascript" src="js/login.js"></script>
  <title>АСЕР - Авторизация</title>
</head>
<body>
    <form action="" enctype="multipart/form-data" method="post" id="form">
        Email: <br />
        <input name="email" type="text" class="form-input" id="email" value="<?=$sEmail?>" /><br />
        <br />
        Пароль: <br />
        <input name="password" type="password" class="form-input" id="password" /><br />
        <br />
        <input type="submit" name="submit" value="Войти" id="submit" />
        <br />
        <div id="login-errors"><?=$sError?></div>
    </form>
</body>
</html>