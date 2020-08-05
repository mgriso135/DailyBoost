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
          client_id: "<?= AppConfig::$GOOGLE_CLIENT_ID ?>",
          redirect_uri: 'https://www.virtualchief.net',
          //redirect_uri: "http://localhost:88",
          // Scopes to request in addition to 'profile' and 'email'
          scope: 'profile email https://www.googleapis.com/auth/calendar'
        });
      });
      }

    $(document).ready(function(){
    $('#signinButton').click(function() {
    // signInCallback defined in step 6.
    auth2.grantOfflineAccess().then(GoogleCalendarRegisterToken);
  });
  
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
<div class="container">
    <div class="row">
    <div class="col-sm text-center">
    <input type="image" src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Google_Calendar_icon.svg" id="signinButton" style="max-width: 50px;" /><br />Google Calendar
    </div>
    <div class="col-sm text-center">
    <input type="image" src="https://img.icons8.com/color/96/000000/outlook-calendar.png" id="outlookCalSignIn" style="max-width: 50px;" /><br />Outlook Calendar
    </div>
</div>
</div>
<div class="container">
    <div class="row">
        
    </div>
</div>