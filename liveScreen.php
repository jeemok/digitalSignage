<?php
  // Modify the path here to show the screen correctly
  // $FOLDER_PATH = "/screens/"; // localhost
  $FOLDER_PATH = "/display/digitalsignage/screens/";

  // Base URL
  $baseUrl = strtolower(substr($_SERVER[HTTP_HOST], 0, 7 )) != "http://" ? "http://$_SERVER[HTTP_HOST]" : $_SERVER[HTTP_HOST];
  $liveUrl = $baseUrl . $FOLDER_PATH . $screen;

  // Slides JSON file
  $json = file_get_contents(__DIR__ . "/screens/" . $screen . "/slides.json");
  $decodedJson = json_decode($json, true);

  $imageArray = array_filter($decodedJson, function ($var) {
    return ($var["type"] === "image");
  });

  $videoArray = array_filter($decodedJson, function ($var) {
    return ($var["type"] === "video");
  });

  $urlArray = array_filter($decodedJson, function ($var) {
    return ($var["type"] === "url");
  });

  $totalDurations = array_sum(array_map(function($item) {
    return $item["duration"];
  }, $decodedJson));

  $avgDurations = $totalDurations / count($decodedJson);
?>

<h4 class="ui header">
  Live Screen
</h4>
<div class="ui divider"></div>
<div class="ui stackable two column grid">
  <div class="column" style="max-width: 768px; display: table;">
    <iframe src="<?php echo $liveUrl ?>" width="768px" height="432px" style="border-width: 0;"></iframe>
  </div>
  <div class="column" style="padding: 25px 50px;">
    <h4 class="ui header" style="margin-top: 0;">
      <!-- TODO Make it real "online", probably ping the IP -->
      Status: <a class="ui green empty circular mini label" style="margin: 0 5px;"></a> Online
    </h4>
    <h4 class="ui header" style="margin-top: 0;">
      URL:
      <span style="font-weight: normal;">
        <?php echo "<a href=\"$liveUrl\">$liveUrl</a>" ?>
      </span>
    </h4>
    <h4 class="ui header" style="margin-top: 0;">
      Number of slides:
      <span style="font-weight: normal;">
        <?php echo count($decodedJson) ?>
      </span>
    </h4>
    <div class="ui list" style="margin-left: 10px;">
      <div class="item" style="margin: 5px;">
        <i class="image icon" style="display: inline-flex"></i>
        Images:
        <div class="content" style="display: inline">
          <?php echo count($imageArray) ?>
        </div>
      </div>
      <div class="item" style="margin: 5px;">
        <i class="video icon" style="display: inline-flex"></i>
        Videos:
        <div class="content" style="display: inline">
          <?php echo count($videoArray) ?>
        </div>
      </div>
      <div class="item" style="margin: 5px;">
        <i class="linkify icon" style="display: inline-flex"></i>
        URLs:
        <div class="content" style="display: inline">
          <?php echo count($urlArray) ?>
        </div>
      </div>
    </div>
    <h4 class="ui header" style="margin-top: 0;">
      Average transition time:
      <span style="font-weight: normal;">
        <?php echo number_format((float)$avgDurations, 2, '.', '') ?>
        seconds
      </span>
    </h4>
    <h4 class="ui header" style="margin-top: 0;">
      Total time:
      <span style="font-weight: normal;">
        <?php echo $totalDurations ?>
        seconds
      </span>
    </h4>
    <!-- Notes -->
    <div class="ui divider"></div>
    <ul class="list">
      <li>Screen takes new updates from the server in every hour (every <i>xx:00</i> time) after the end of the slides.</li>
      <li>Each slide duration are limited to 1 - 600 seconds.</li>
      <li>Only files that are not in used can be deleted.</li>
      <li>Currently only images, videos and URLs are supported.</li>
      <li>Image formats: <i>.jpg</i>, <i>.jpeg</i>, <i>.png</i> & <i>.gif</i> are supported.</li>
      <li>Video format: <i>.mp4</i> is supported.</li>
      <li>All videos are muted.</li>
      <li>Please contact IT Support for adding custom URLs or videos.</li>
    </ul>
  </div>
</div>
