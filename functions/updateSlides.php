<?php
  $screen = $_POST["screen"];
  $json = $_POST[$screen . "_state"];

  // Image folder path
  $rootPath = dirname(dirname(__FILE__));
  $jsonPath = $rootPath . "/screens/$screen/slides.json";
  $path = $rootPath . "/screens/$screen/images/*.{jpg,png,gif}";
  $images = array_filter(glob($path, GLOB_BRACE));

  // Decode the JSON string
  $data = json_decode($json, true);
  // Remove null values
  $array = array_filter($data);
  // Sort array by the index value
  usort($array, function ($a, $b) {
    return $a['order'] - $b['order'];
  });
  // Error message stacks
  $errorMsg = "";

  // Data validations
  if (isset($_POST[$screen . "_state"])) {
    foreach ($array as $index => $val) {
      $keys = array_keys($val);
      foreach ($keys as $key) {
        // Reconstruct the index to be sequential starts from 0
        if ($key == "order") {
          $array[$index][$key] = $index;
        }
        // Validate 'Duration' field value
        if ($key == "duration") {
          if ($val[$key] < 1 || $val[$key] > 120) {
            $errorMsg = $errorMsg . "<p>Invalid duration <strong>$val[$key]</strong> seconds at index [$index]. Duration must be within: 1 - 120 seconds.</p>";
          }
        }
        // Validate 'Type' field value
        if ($key == "type") {
          if ($val[$key] != "url" && $val[$key] != "image") {
            $errorMsg = $errorMsg . "<p>Invalid type: <strong>$val[$key]</strong> found at index [$index]. Supported types: image, url.</p>";
          }
        }
        // Validate 'Value' field value
        if ($key == "value") {
          if ($val["type"] == "url") {
            // Check if URL starts with the correct prefix
            if (strtolower(substr($val[$key], 0, 7 )) != "http://" && strtolower(substr($val[$key], 0, 8 ) != "https://")) {
              $errorMsg = $errorMsg . "<p>Invalid URL: <strong>$val[$key]</strong> found at index [$index]. URL must starts with 'http'.</p>";
            }
          }
          if ($val["type"] == "image") {
            // Check if there is existing image in the folder
            $notFound = true;
            // Loop through the folder
            foreach ($images as $value) {
              // Take the last two values from the array
              $pieces = array_slice(explode("/", $value), -2, 2);
              // Reconstruct the new link again
              $imageValue = $pieces[0] . "/" . $pieces[1];
              // If found
              if ($imageValue == $val[$key]) {
                $notFound = false;
                break;
              }
            }
            if ($notFound) {
              $errorMsg = $errorMsg . "<p>Invalid image: <strong>$val[$key]</strong> found at index [$index]. File not exist.</p>";
            }
          }
        }
        // Save warning if there is a value that is not an Int or String
        if (!is_int($val[$key]) && !is_string($val[$key])) {
          $errorMsg = $errorMsg . "<p>There is an empty value on field [<strong>$key</strong>] at index [$index]</p>";
        }
      }
    }
  }

  // If there is error message
  if ($errorMsg != "") {
    // Redirect back to main page after 2 seconds
    header("Refresh: 2; URL = ../main.php");

    // Error icon
    echo '<body style="padding: 100px; text-align: center; font-family: Arial;">';
    echo "<img src=\"images/failed.png\" style=\"max-width: 200px;\" />";
    echo $errorMsg;
    echo "<p>Page will auto redirect in 2 seconds.</p>";
    echo '</body>';
  }
  else {
    // Redirect back to main page
    header("Location: ../main.php");

    // Save into the file
    file_put_contents($jsonPath, json_encode($array, JSON_PRETTY_PRINT));
    echo '<body style="padding: 100px; text-align: center; font-family: Arial;">';
    echo "<img src=\"images/success.png\" style=\"max-width: 200px;\" />";
    echo '</body>';
  }
?>
