<?php
require_once('util.php');
require_once('database.php');
$request = check_feed_request();
$fbid = $request['user_id']; // user_id in request is fbid.
$id = get_user_id_by_fbid($fbid); // retriece app's user id from fbid.
$obj_ids = get_obj_ids_by_user_id($id);
$data = array();
$i = 0;
foreach ($obj_ids as $obj_id) {
  $data[] = array ('city'=> get_obj_url_by_user_obj_id($id, $obj_id),
                   'timestamp'=>$i+time());
  $i++;
}

header('Content-Type: text/javascript');
echo(
  json_encode(
    array(
      'meta' => array('code'=>200),
      'fly' => $data,
    )
  )
);
