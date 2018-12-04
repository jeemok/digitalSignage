<?php
  $rootPath = dirname(dirname(__FILE__));
  $targetDir = $rootPath . "/screens/" . $_POST["screen"] . "/images/";
  $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Validations
  if(isset($_POST["submit"])) {
    // Check if image file is an actual image
    if(getimagesize($_FILES["fileToUpload"]["tmp_name"]) === false) {
      // Redirect back to index.php after 2 seconds
      header("Refresh: 2; URL = ../main.php");

      echo '<body style="padding: 100px; text-align: center; font-family: Arial;">';
      echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
      echo "<p>File is not an image.</p>";
      echo '</body>';
    }
    // Check if file already exists
    else if (file_exists($targetFile)) {
      // Redirect back to index.php after 2 seconds
      header("Refresh: 2; URL = ../main.php");

      echo '<body style="padding: 100px; text-align: center; font-family: Arial;">';
      echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
      echo "<p>Sorry, file already exists.</p>";
      echo '</body>';
    }
    // Check file size
    else if (round($_FILES["fileToUpload"]["size"] / 1024 / 1024, 1) > 2) { // count in megabytes
      // Redirect back to index.php after 2 seconds
      header("Refresh: 2; URL = ../main.php");

      echo '<body style="padding: 100px; text-align: center; font-family: Arial;">';
      echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
      echo "<p>Sorry, your file is too large. Image has to be less than 2 MB.</p>";
      echo '</body>';
    }
    // Check file formats
    else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      // Redirect back to index.php after 2 seconds
      header("Refresh: 2; URL = ../main.php");

      echo '<body style="padding: 100px; text-align: center; font-family: Arial;">';
      echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
      echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
      echo '</body>';
    }
    // if everything is ok, try to upload file
    else if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
      // Redirect back to main page
      header("Location: ../main.php");

      echo '<body style="padding: 100px; text-align: center; font-family: Arial;">';
      echo "<img src=\"images/success.png\" style=\"max-width: 500px;\" />";
      echo "<p>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</p>";
      echo '</body>';
    }
    else {
      // Redirect back to index.php after 2 seconds
      header("Refresh: 2; URL = ../main.php");

      echo '<body style="padding: 100px; text-align: center; font-family: Arial;">';
      echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
      echo "<p>Sorry, there was an error uploading your file.</p>";
      echo "<p>Page will auto redirect in 2 seconds.</p>";
      echo '</body>';
    }
  }
?>
