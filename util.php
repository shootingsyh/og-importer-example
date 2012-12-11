<?php

function error($err, $des) {
  die(
    json_encode(
      array(
        'error' => $err,
        'error_description' => $des
      )));
}

function check_request() {
  if (!$_SERVER['HTTPS'] && $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') {
    header('WWW-Authenticate: Bearer, error=invalid_request');
    error('invalid_request', 'You must use https://');
  }

  $secret = $_GET['secret'];
  if ($secret !== '01b416405245af8a0ee4deec6e37ed82') {
     error('invalid_request', 'Wrong secret');
  }

  $fbid = $_GET['fbid'];
  if (!$fbid) {
    error('invalid_request', 'Missing FBID');
  }
}
