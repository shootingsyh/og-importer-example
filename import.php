<?php
if (!$_SERVER['HTTPS'] && $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') {
  header('WWW-Authenticate: Bearer, error=invalid_request');
  die(
    json_encode(
      array(
        'error' => 'invalid_request',
        'error_description' => 'You must use https://',
      )
    )
  );
}

// Use 'heroku config' to view current env variables
// Use 'heroku config:add NAME=value' to set
$publish_count = getenv('OG_IMPORT_COUNT') !== FALSE
  ? intval(getenv('OG_IMPORT_COUNT'))
  : 15;

$secret = $_GET['secret'];
if ($secret !== '01b416405245af8a0ee4deec6e37ed82') {
    die(
       json_encode(
          array(
            'error' => 'invalid_request',
            'error_description' => 'Wrong secret'
          )
       )
    );
}
$id = $_GET['id'];
if (!$id) {
  $fbid = $_GET['fbid'];
  $data = array();
  for ($i = 0; $i < 10; $i++) {
    $data[] = array ('id'=>$i, 'timestamp' => $i);
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
} else {
  header("text/html");
  echo('<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# actionimporter: http://ogp.me/ns/fb/actionimporter#">');
  echo('<meta property="fb:app_id" content="310812085693919" />');
  echo('<meta property="og:type"   content="actionimporter:obj" />');
  echo('<meta property="og:url"    content="https://mighty-stream-5804.herokuapp.com/import/?id=' .$id.'"/>');
  echo('<meta property="og:title"  content="Sample Obj" />');
  echo('<meta property="og:image"  content="https://s-static.ak.fbcdn.net/images/devsite/attachment_blank.png"/>');
}
