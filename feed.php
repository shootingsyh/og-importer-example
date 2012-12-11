<?php
require_once('util.php');
$request = check_feed_request();
$fbid = $request['user_id']; // user_id in request is fbid.
$id = get_user_id_by_fbid($fbid); // retriece app's user id from fbid.
$obj_ids = get_objs_by_user_id($id);
$data = array();
$i = 0;
foreach ($obj_ids as $obj_id) {
  $data[] = array ('object'=>'https://mighty-stream-5804.herokuapp.com/import/?obj_id='
                          .$obj_id.'&user_id='.$fbid,
                   'timestamp'=>$i);
  $i++;
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
