<script>
  function deleteImage (path) {
    document.getElementById("imageUrl").value = path;
    document.getElementById("deleteForm").submit();
  }
</script>

<div style="margin-top: 50px;">
  <h4 class="ui header">
    Images Folder
  </h4>
  <div class="ui divider"></div>
  <?php
    // Screens folder path
    $images = array_filter(glob("screens/$screen/images/*.{jpg, png, gif}", GLOB_BRACE));
    $videos = array_filter(glob("screens/$screen/images/*.{mp4}", GLOB_BRACE));

    // Print each image
    foreach ($images as $value) {
      $pieces = explode("/", $value);
      echo '
        <div class="ui segment" style="display: inline-block; margin: 10px;">
          <img src="' . $value . '" width="192px" height="108px" />
          <br />'
          . $pieces[3] .
          '<a class="floating ui red label" onClick={deleteImage("' . $value . '")}>X</a>
        </div>
      ';
    }
    // Print each video
    foreach ($videos as $value) {
      $pieces = explode("/", $value);
      echo '
        <div class="ui segment" style="display: inline-block; margin: 10px;">
          <video loop autoplay muted width="192px" height="108px">
            <source src="' . $value . '" type="video/mp4">
            Your browser does not support HTML5 video.
          </video>
          <br />'
          . $pieces[3] .
          '<a class="floating ui red label" onClick={deleteImage("' . $value . '")}>X</a>
        </div>
      ';
    }
  ?>
  <div class="ui segment">
    <form action="functions/uploadImage.php" method="post" enctype="multipart/form-data" style="margin-bottom: 0;">
      <h4 class="ui header">
        Select new image to upload:
      </h4>
      <input type="file" name="fileToUpload" id="fileToUpload" accept=".jpg,.jpeg,.gif,.png">
      <?php echo '<input style="display: none;" type="text" name="screen" id="screen" value="' . $screen . '">'; ?>
      <button name="submit" type="submit" class="ui primary small button" style="display: block; margin-top: 10px;">
        <i class="file image icon"></i>
        Upload image
      </button>
    </form>
  </div>
  <!-- Hidden form for delete image -->
  <form id="deleteForm" action="functions/deleteImage.php" method="post" style="display: none;">
    <input type="text" name="imageUrl" id="imageUrl" value="">
  </form>
</div>
