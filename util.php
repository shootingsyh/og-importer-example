<?php

function error($err, $des) {
  die(
    json_encode(
      array(
        'error' => $err,
        'error_description' => $des
      )));
}

function base64_url_encode($input) {
    return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_,', '+/='));
}

function check_request() {
  if (!$_SERVER['HTTPS'] && $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') {
    header('WWW-Authenticate: Bearer, error=invalid_request');
    error('invalid_request', 'You must use https://');
  }
  $secret = '01b416405245af8a0ee4deec6e37ed82';
  $signed_request = $_GET['signed_request'];

  if(!$signed_request) {
    error('invalid_request', 'Missing signed request');
  }

  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
  
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);
 
  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error('invalid_request', 'Unknown algorithm. Expected HMAC-SHA256');
  }

  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error('invalid_request', 'Bad Signed JSON signature!');
  }

  $fbid = $data['user_id'];
  if(!$fbid) {
    error('invalid_request', 'Missing user id');
  }

  return $data;
}
