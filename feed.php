<?php
require_once('util.php');
$request = check_request();
$fbid = $request['user_id'];
for ($i = 0; $i < 10; $i++) {
  $data[] = array ('id'=>$fbid.'_'.$i, 'timestamp' => $i);
}
header('Content-Type: text/javascript');
echo(
  json_encode(
    array(
      'meta' => array('code'=>200),
      'data' => $data,
    )
  )
);
