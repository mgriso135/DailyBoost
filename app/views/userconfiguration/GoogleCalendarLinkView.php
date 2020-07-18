  <?php
    $lang = $_SESSION['language'];
    require_once "assets/GoogleCalendarLinkView_{$lang}.php"; 
    ?>

  <script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>

<meta name="google-signin-client_id" content="<?= AppConfig::$GOOGLE_CLIENT_ID ?>">
<script>
    function GoogleCalendarRegisterToken(authResult)
    {
        $.ajax({ 
             // $account, $user, $checksum, $firstname, $lastname, $email, $password, $password2
             url: "/dailyboost/public/UserConfigurationController/GoogleCalendarRegisterToken",
             type: 'POST',
             dataType: "html",
             data: {
                 code: authResult['code'],
             },
             success: function (result) {
                console.log(result);
             },
             error: function (result) {
                 alert("Error");
             },
             warning: function (result) {
                 alert("Warning");
             }
            });
    }
    
    function start() {
      gapi.load('auth2', function() {
        auth2 = gapi.auth2.init({
          client_id: '<?= AppConfig::$GOOGLE_CLIENT_ID ?>',
          redirect_uri: '<?= AppConfig::$GOOGLE_REDIRECT_URI ?>',
          // Scopes to request in addition to 'profile' and 'email'
          //scope: 'additional_scope'
        });
      });
gapi.signin2.render('signinButton', {
        'scope': 'profile email https://www.googleapis.com/auth/calendar',
        /*'width': 240,
        'height': 50,*/
        'longtitle': false,
        'theme': 'light',
        /*'onsuccess': onSuccess,
        'onfailure': onFailure*/
      });
    }
    
    $('#signinButton').click(function() {
    // signInCallback defined in step 6.
    auth2.grantOfflineAccess().then(GoogleCalendarRegisterToken);
  });
         
</script>
<!--
  <div id="my-signin2"></div>

<a href="#" onclick="signOut();">Sign out</a>
<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
  }
</script>-->
<h5><?= _LINK_EXTERNAL_CALENDARS ?></h5>
<div id="signinButton">Sign in with Google</div>