<?php
require_once('util.php');
require_once('database.php');
check_public_request();
$obj_id = $_GET['obj_id'];
$fbid = $_GET['user_id'];
if (!$obj_id) {
  error('invalid_request', 'invalid obj id');
} else {
  $user_id = get_user_id_by_fbid($fbid);
  if(!check_obj_id($user_id, $obj_id)) {
     error('invalid_request', 'invalid obj id');
  }
  header("text/html");
  $testObj = 'Test Obj for fbid='.$fbid.' with obj_id='.$obj_id;
  echo('<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# actionimporter: http://ogp.me/ns/fb/actionimporter#">');
  echo('<meta property="fb:app_id" content="310812085693919" />');
  echo('<meta property="og:type"   content="actionimporter:obj" />');
  echo('<meta property="og:url"    content="https://mighty-stream-5804.herokuapp.com/import?obj_id=' .$obj_id.'&fbid='.$fbid.'"/>');
  echo('<meta property="og:title"  content="'.$testObj.'" />');
  echo('<meta property="og:image"  content="https://s-static.ak.fbcdn.net/images/devsite/attachment_blank.png"/>');
  echo('</head>');
  echo('<body>'.$testObj.'</body>');
}
