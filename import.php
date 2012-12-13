<?php
require_once('util.php');
require_once('database.php');
check_public_request();
$obj_id = $_GET['obj_id'];
$fbid = $_GET['user_id'];
if (!$obj_id) {
  error('invalid_request', 'missing obj id');
} else {
  $user_id = get_user_id_by_fbid($fbid);
  if(!check_obj_id($user_id, $obj_id)) {
     error('invalid_request', 'invalid obj id '.$obj_id.'. Expecting '.$user_id.'_something');
  }
  header("text/html");
  $testObj = get_name_by_obj_id($obj_id).' city for fbid='.$fbid.' with obj_id='.$obj_id;
  echo('<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# actionimporter: http://ogp.me/ns/fb/actionimporter#">');
  echo('<meta property="fb:app_id" content="310812085693919" />');
  echo('<meta property="og:type"   content="actionimporter:city" />');
  echo('<meta property="og:url"    content="'.getUrl('/import?obj_id='.$obj_id.'&user_id='.$fbid).'"/>');
  echo('<meta property="og:title"  content="'.get_name_by_obj_id($obj_id).'" />');
  echo('<meta property="og:image"  content="'.get_image_by_obj_id($obj_id).'"/>');
  echo('</head>');
  echo('<body>'.$testObj.
          '<ima url="'.get_image_by_obj_id($obj_id).'"/>'.
       '</body>');
}
