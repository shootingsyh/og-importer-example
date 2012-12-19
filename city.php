<?php
require_once('util.php');
require_once('database.php');
check_public_request();
$obj_id = $_GET['obj_id'];
if ($obj_id===null) {
  error('invalid_request', 'missing obj id');
} else {
  header("text/html");
  $testObj = get_name_by_obj_id($obj_id).' city';
  echo('<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# actionimporter: http://ogp.me/ns/fb/actionimporter#">');
  echo('<meta property="fb:app_id" content="310812085693919" />');
  echo('<meta property="og:type"   content="actionimporter:city" />');
  echo('<meta property="og:url"    content="'.getUrl('/city?obj_id='.$obj_id).'"/>');
  echo('<meta property="og:title"  content="'.get_name_by_obj_id($obj_id).'" />');
  echo('<meta property="og:image"  content="'.get_image_by_obj_id($obj_id).'"/>');
  echo('</head>');
  echo('<body>'.$testObj.
          '<br/><img src="'.get_image_by_obj_id($obj_id).'" />'.
       '</body>');
}
