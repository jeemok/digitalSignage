<?php
  ob_start();
  session_start();
?>
<html lang = "en">
  <head>
    <title>HTM Signage Player Administrator</title>
    <link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="semantic/semantic.min.js"></script>
  </head>

  <body style="overflow-y: hidden;">
    <i class="play circle outline icon" style="font-size: 50em; position: fixed; left: -100; opacity: 0.1"></i>

    <div class="ui segment" style="max-width: 600px; margin: 200px auto;">
      <h3 class="ui header">Enter Username and Password</h3>
      <form class="ui form" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <?php
          if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
            // Search for valid user
            function login($username, $password) {
              $userFile = file_get_contents(__DIR__ . "/config/users.json");
              $users = json_decode($userFile, true);

              foreach($users as $user) {
                if ($user['username'] === $username && $user['password'] === $password ) {
                  return $user;
                }
              }

              return false;
            }

            // Log in
            $authenticatedUser = login($_POST['username'], $_POST['password']);

            if ($authenticatedUser !== false) {
              $_SESSION['valid'] = true;
              $_SESSION['timeout'] = time();
              $_SESSION['displayName'] = $authenticatedUser['displayName'];
              $_SESSION['username'] = $authenticatedUser['username'];

              echo '
                <div class="ui positive message" style="text-align: center;">
                  <div class="ui active inline loader"></div>
                  <p>Welcome back! ' . $authenticatedUser["displayName"] . '.</p>
                </div>
              ';

              // Redirect to main.php after 1 second
              header("Refresh: 1; URL = main.php");
            }
            else {
              echo '
                <div class="ui negative message">
                  <div class="header">
                    Failed Authentication
                  </div>
                  <p>Wrong username or password</p>
                </div>
              ';
            }
          }
        ?>

        <div class="field">
          <label>Username</label>
          <input type="text" name="username" placeholder="Username" required autofocus>
        </div>

        <div class="field">
          <label>Password</label>
          <input type="password" name="password" placeholder="Password" required>
        </div>

        <button class="ui primary button" type="submit" name="login">Login</button>
      </form>
    </div>

    <!-- HTM Logo -->
    <img src="images/htmniseko.png" style="margin: -50px auto 50px; display: block; max-width: 200px; opacity: 0.1;" />
  </body>
</html>
