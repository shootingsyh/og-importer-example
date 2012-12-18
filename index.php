<?php
require_once('util.php');
require_once('database.php');
check_public_request();


/*****************************************************************************
 *
 * The content below provides examples of how to fetch Facebook data using the
 * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
 * do so.  You should change this section so that it prepares all of the
 * information that you want to display to the user.
 *
 ****************************************************************************/

require_once('sdk/src/facebook.php');

$facebook = new Facebook(array(
  'appId'  => getAppID(),
  'secret' => getSecret(),
  'sharedSession' => true,
  'trustForwarded' => true,
));

$fbid = $facebook->getUser();
if ($fbid) {
  // Create user account after login if not exist
  $user_id = create_user_if_not_exist($fbid); 
  
  try {
    // Fetch the viewer's basic information
    $basic = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    // If the call fails we check if we still have a user. The user will be
    // cleared if the error is because of an invalid accesstoken
    if (!$facebook->getUser()) {
      header('Location: '. getUrl($_SERVER['REQUEST_URI']));
      exit();
    }
  }
}

// Fetch the basic info of the app that they are using
$app_info = $facebook->api('/'. getAppID());

$app_name = idx($app_info, 'name', '');

?>
<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

    <title><?php echo he($app_name); ?></title>
    <link rel="stylesheet" href="stylesheets/screen.css" media="Screen" type="text/css" />
    <link rel="stylesheet" href="stylesheets/mobile.css" media="handheld, only screen and (max-width: 480px), only screen and (max-device-width: 480px)" type="text/css" />

    <!--[if IEMobile]>
    <link rel="stylesheet" href="mobile.css" media="screen" type="text/css"  />
    <![endif]-->

    <script type="text/javascript" src="/javascript/jquery-1.7.1.min.js"></script>

    <!--[if IE]>
      <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->
  </head>
  <body>
    <div id="fb-root"></div>
    <script type="text/javascript">
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo getAppID(); ?>', // App ID
          channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/channel.html', // Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true // parse XFBML
        });

        // Listen to the auth.login which will be called when the user logs in
        // using the Login button
        FB.Event.subscribe('auth.login', function(response) {
          // We want to reload the page now so PHP can read the cookie that the
          // Javascript SDK sat. But we don't want to use
          // window.location.reload() because if this is in a canvas there was a
          // post made to this page and a reload will trigger a message to the
          // user asking if they want to send data again.
          window.location = window.location;
        });
      $("#publish").click(function(event) {
        <?php
           $user_id = get_user_id_by_fbid($fbid);
        ?>
        FB.api('https://graph.facebook.com/me/actionimporter:fly',
               'post',
               { city: '<?php echo getUrl("/import?user_id=".$user_id."&obj_id=".$user_id) ?>'+ '_' + $('#city').val(), 'fb:explicitly_shared': 1},
               function (response) {
                 if (response != null) {
                   if (console && console.log) {
                     console.log('The response was', response);
                   }
                 }
               }
         )
       });
        FB.Canvas.setAutoGrow();
      };

      // Load the SDK Asynchronously
      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));

    </script>

    <header class="clearfix">
      <?php if (isset($basic)) { ?>
      <p id="picture" style="background-image: url(https://graph.facebook.com/<?php echo he($fbid); ?>/picture?type=normal)"></p>

      <div>
        <h1>Welcome, <strong><?php echo he(idx($basic, 'name')); ?></strong></h1>
        <p class="tagline">
          This is your app
          <a href="<?php echo he(idx($app_info, 'link'));?>" target="_top"><?php echo he($app_name); ?></a>
        </p>

      </div>
      <?php } else { ?>
      <div>
        <h1>Welcome</h1>
        <div class="fb-login-button" data-scope="user_likes,user_photos"></div>
      </div>
      <?php } ?>
    </header>

    <?php
      if ($fbid) {
    ?>
    <section id="fly" class="clearfix" >
      <h1>Submit your flight</h1>
      <div>
         <form>
            <label>Fly to </label>
            <input type="text" id="city" />
            <input type="button" id="publish" />
         </form>
      </div>
    </section>
    <section id="samples" class="clearfix">
      <h1>The city you flew to</h1>
      <div class="list">
        <h3>The cities</h3>
        <ul class="friends">  
          <?php
            $user_id = get_user_id_by_fbid($fbid);
            $obj_ids = get_obj_ids_by_user_id($user_id);
            $data = array();
            foreach ($obj_ids as $obj) {
              // Extract the pieces of info we need from the requests above
              $image = get_image_by_obj_id($obj);
              $name = get_name_by_obj_id($obj);
              $obj_url = get_obj_url_by_user_obj_id($user_id, $obj);
          ?>
          <li>
            <a href="<?php echo he($obj_url); ?>" target="_top">
              <img src="<?php echo he($image); ?>" alt="<?php echo he($name); ?>">
              <?php echo he($name); ?>
            </a>
          </li>
          <?php
            }
          ?>
        </ul>
      </div>
    </section>
    <?php 
     }
    ?>
  </body>
</html>
