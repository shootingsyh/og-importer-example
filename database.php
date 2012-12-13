<?php
// Fake database implementation. Should connect to real database when implementing.

function create_user_if_not_exist($fbid)  {
  $user_id = $fbid; // Should create user in database.
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

function get_obj_ids_by_user_id($id) {
  $objs = array();
  for($i = 0;$i < 10;$i++) {
    $objs[] = $id.'_'.$i; // Should query in database.
  }
  return $objs;
}

function get_count_by_obj_id($obj_id) {
  $exp = explode('_', $obj_id);
  return $exp[1];
}

function get_image_by_obj_id($obj_id) {
  $count = get_count_by_obj_id($obj_id);
  if ($count > 4) {
    return getUrl('/images/city_forgotten.jpeg');
  }
  return getUrl('/images/city_'.$count.'.jpeg');
}

$city_mapping = array('beijing', 'shanghai', 'seattle', 'dublin', 'menlopark');

function get_name_by_obj_id($obj_id) {
  $count = get_count_by_obj_id($obj_id);
  if ($count > 4) { 
    return 'Forgotten Realm'
  }
  return $city_mapping[$count];
}
