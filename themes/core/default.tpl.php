<!doctype html>
<html lang="sv"> 
<head>
  <meta charset="utf-8">
  <title><?=$title?></title>
  <link rel="stylesheet" href="<?=$stylesheet?>">
</head>
<body>
  <div id="header">
    <div id='login-menu'>
        <?=login_menu()?>
      </div>
    <?=$header?>
  </div>
  <div id="main" role="main">
    <?=get_messages_from_session()?>
    <?=@$main?>
    <?=render_views()?>
  </div>
  <div id="footer">
    <?=$footer?>
  </div>
  <div id="debug">
    <?=get_debug()?>
  </div>
</body>
</html>