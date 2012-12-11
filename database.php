<?php
// Fake database implementation. Should connect to real database.
function create_user_if_not_exist($fbid)  {
  $user_id = "fake user id"; // Should create user in database.
  return $user_id;
}

function get_user_id_by_fbid($fbid) {
  $user_id = $fbid; // Should query in database.
  return $user_id;
}

function check_obj_id($user_id, $obj_id) {
  $exps = explode('_', $obj_id);
  if (count($exps) < 2 || $exps[0] !== $user_id) {
    return false;
  } 
  return true;
}

function get_obj_ids_by_user_id($id, $count) {
  $objs = array();
  for($i = 0;$i < 10;$i++) {
    $objs[] = $id.'_'.$i; // Should query in database.
  }
  return objs;
}
