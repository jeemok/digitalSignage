<body style="padding: 100px; text-align: center; font-family: Arial;">
  <?php
    $rootPath = dirname(dirname(__FILE__));
    $targetDir = $rootPath . "\screens\\" . $_POST["screen"] . "\images\\";
    $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validations
    if(isset($_POST["submit"])) {
      // Check if image file is an actual image
      if(getimagesize($_FILES["fileToUpload"]["tmp_name"]) === false) {
        echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
        echo "<p>File is not an image.</p>";
      }
      // Check if file already exists
      else if (file_exists($targetFile)) {
        echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
        echo "<p>Sorry, file already exists.</p>";
      }
      // Check file size
      else if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
        echo "<p>Sorry, your file is too large.</p>";
      }
      // Check file formats
      else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
        echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
      }
      // if everything is ok, try to upload file
      else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
          echo "<img src=\"images/success.png\" style=\"max-width: 500px;\" />";
          echo "<p>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</p>";

          // Redirect back to main page
          header("Location: ../main.php");
        }
        else {
          echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
          echo "<p>Sorry, there was an error uploading your file.</p>";

          // Redirect back to index.php after 2 seconds
          header("Refresh: 2; URL = ../main.php");
          echo "<p>Page will auto redirect in 2 seconds.</p>";
        }
      }
    }
  ?>
</body>
