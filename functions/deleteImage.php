<body style="padding: 100px; text-align: center; font-family: Arial;">
  <?php
    if(isset($_POST["imageUrl"])) {
      $rootPath = dirname(dirname(__FILE__));
      $filename = $rootPath . '/' . $_POST['imageUrl'];
      // Split the image url
      $values = explode("/", $_POST['imageUrl']);
      $screen = $values[1];

      // Get the slide configs
      $jsonPath = $rootPath . "/screens/" . $screen . "/slides.json";
      $json = file_get_contents($jsonPath);
      // Decode the JSON string
      $data = json_decode($json, true);

      // Check if the image is in used right now
      $imgInUsed = false;
      // Reconstruct the image URL to check with the folders
      $imgValue = $values[2] . '/' . $values[3];
      foreach ($data as $val) {
        if ($val["type"] == "image" && $val["value"] == $imgValue) {
          $imgInUsed = true;
        }
      }

      // If the image is in used
      if ($imgInUsed) {
        // Redirect back to main page after 2 seconds
        header("Refresh: 2; URL = ../main.php");

        echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
        echo "<p>Could not delete " . $filename . ". Image is in used.</p>";
        echo "<p>Page will auto redirect in 2 seconds.</p>";
      }
      // If the file exist
      else if (file_exists($filename)) {
        unlink($filename);

        // Redirect back to main page
        header("Location: ../main.php");

        echo "<img src=\"images/success.png\" style=\"max-width: 500px;\" />";
        echo "<p>File " . $filename . " has been deleted.</p>";
      }
      // If the file doesn't exist
      else {
        // Redirect back to main page after 2 seconds
        header("Refresh: 2; URL = ../main.php");

        echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
        echo "<p>Could not delete " . $filename . ". File does not exist.</p>";
        echo "<p>Page will auto redirect in 2 seconds.</p>";
      }
    }
  ?>
</body>
