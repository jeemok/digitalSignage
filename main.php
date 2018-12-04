<?php
  session_start();
  // Validate authentication
  if ($_SESSION['valid'] != 1) {
    // Redirect back to the login page
    header("Location: index.php");
  }
?>
<?php
  // Screens folder path
  $path = "screens/*";
  $dirs = array_filter(glob($path), 'is_dir');
?>

<head>
  <title>HTM Signage Player Administrator</title>
  <link rel="shortcut icon" type="image/png" href="/images/favicon.png"/>
  <link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
  <script src="semantic/semantic.min.js"></script>
  <script>
    function onTabChange(tabName) {
      // Remove all the 'active' classname
      const tabs = document.getElementsByName("tabs");
      const contents = document.getElementsByName("contents");
      for (let i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove("active");
        contents[i].classList.remove("active");
      }
      // Set active classname to the selected tab
      document.getElementById(tabName + "_tab").classList.add("active");
      document.getElementById(tabName + "_content").classList.add("active");
    }
  </script>
</head>
<body>
  <div style="padding: 100px;">
    <!-- Logo background -->
    <i class="play circle outline icon" style="font-size: 50em; position: fixed; left: -100; opacity: 0.1"></i>
    <!-- Logout button -->
    <a class="ui red small button" href="/functions/logout.php" style="float: right;">
      <i class="logout icon"></i>
      Logout
    </a>
    <!-- Title -->
    <h1 class="ui header">
      HTM Signage Player Administrator
    </h1>
    <h2 class="ui header">
      Screens
    </h2>

    <!-- Menu bar -->
    <div class="ui top attached tabular menu">
      <?php
        // Print each screen
        foreach ($dirs as $key => $value) {
          $pieces = explode("/", $value);
          $class = $key == 0 ? "item active" : "item";
          echo "<a id=\"$pieces[1]_tab\" name=\"tabs\" class=\"" . $class . "\" data-tab=\"" . $pieces[1] ."\" onClick={onTabChange(\"" . $pieces[1] . "\")}><i class=\"tv icon\"></i>" . $pieces[1] . "</a>";
        }
      ?>
    </div>

    <!-- Menu contents -->
    <?php
      // Print each screen
      foreach ($dirs as $key => $value) {
        $pieces = explode("/", $value);
        $screen = $pieces[1]; // folder name. eg: kiniseko
        $class = $key == 0 ? "active" : "";

        // start div
        echo '<div id="' . $screen . '_content" name="contents" class="ui bottom attached tab segment ' . $class . '">';

        // Live screen
        include "liveScreen.php";
        // Slides Config
        include "slidesConfig.php";
        // Images ref
        include "imageFolder.php";

        // end div
        echo '</div>';
      }
    ?>
  </div>

  <!-- HTM Logo -->
  <img src="/images/htmniseko.png" style="margin: -50px auto 50px; display: block; max-width: 200px; opacity: 0.1;" />

  <!-- Add event listeners -->
  <script>
    window.onload = function () {
      <?php
        foreach ($dirs as $key => $value) {
          $pieces = explode("/", $value);
          $screen = $pieces[1]; // folder name. eg: kiniseko
          echo 'renderSlidesConfig("' . $screen . '");';
        }
      ?>
    }
  </script>
</body>
