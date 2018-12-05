<?php
  // Available URL values path
  $urlsPath = "config/url.json";
  $urls = file_get_contents($urlsPath);
  $decodedUrls = json_decode($urls, true);

  // Screens folder path
  $screenPath = "screens/*";
  $dirs = array_filter(glob($screenPath), 'is_dir');

  // Slides JSON file
  $json = file_get_contents(__DIR__ . "/screens/" . $screen . "/slides.json");
  $decodedJson = json_decode($json, true);

  // Images & videos folder path
  $images = array_filter(glob("screens/" . $screen . "/images/*.{jpg,png,gif}", GLOB_BRACE));
  $videos = array_filter(glob("screens/" . $screen . "/images/*.{mp4}", GLOB_BRACE));
?>

<!-- State management -->
<script>
  /* Read from the state */
  function getState(screen) {
    return JSON.parse(document.getElementById(screen + "_state").innerHTML);
  }

  /* Update to the state */
  function setState(data, screen) {
    document.getElementById(screen + "_state").innerHTML = JSON.stringify(data);
  }

  /* Add event listeners */
  function addEventListeners(screen) {
    /* Read from the state */
    const data = getState(screen);
    /* Add the event listeners */
    data.forEach((row, index) => {
      /* Skip if the row is 'null' */
      if (!row) { return; }
      /* Event listeners */
      document.getElementById(screen + "_row_" + index + "_order").addEventListener("keyup", function(ev) { update(index, "order", ev.target.value, screen); });
      document.getElementById(screen + "_row_" + index + "_duration").addEventListener("keyup", function(ev) { update(index, "duration", ev.target.value, screen); });
      document.getElementById(screen + "_row_" + index + "_type").addEventListener("change", function(ev) {
        update(index, "type", ev.target.value, screen);
        /* Update the Value field */
        document.getElementById(screen + '_row_' + index + '_value_container').innerHTML = renderValue(index, screen);
        /* Reinitialise listeners */
        document.getElementById(screen + "_row_" + index + "_value").addEventListener("change", function(ev) { update(index, "value", ev.target.value, screen); });

      });
      document.getElementById(screen + "_row_" + index + "_value").addEventListener("change", function(ev) { update(index, "value", ev.target.value, screen); });
    });
  }

  /* Render text input or dropdown selection - per screen */
  function renderValue_<?php echo $screen ?>(index, screen) {
    /* Read from the state */
    const data = getState(screen);
    const row = data[index];
    /* Check row type & display UI */
    if (row.type == 'url') {
      return '<label>Value</label>' +
      '<select id="' + screen + '_row_' + index + '_value" class="ui fluid dropdown">' +
        '<option value=""></option>' +
        <?php
          // Render each URL as an option
          foreach ($decodedUrls as $url) {
            echo "'<option ' + (row.value == '" . $url . "' ? 'selected' : '') + ' value=\"" . $url . "\">" . $url . "</option>' + ";
          }
        ?>
      '</select>';
    }
    else if (row.type == 'image') {
      return '<label>Value</label>' +
      '<select id="' + screen + '_row_' + index + '_value" class="ui fluid dropdown">' +
        '<option value=""></option>' +
        <?php
          // Render each image as an option
          foreach ($images as $value) {
            $pieces = explode("/", $value);
            $imageValue = $pieces[2] . "/" . $pieces[3];
            echo "'<option ' + (row.value == '" . $imageValue . "' ? 'selected' : '') + ' value=\"" . $imageValue . "\">" . $imageValue . "</option>' + ";
          }
        ?>
      '</select>';
    }
    else if (row.type == 'video') {
      return '<label>Value</label>' +
      '<select id="' + screen + '_row_' + index + '_value" class="ui fluid dropdown">' +
        '<option value=""></option>' +
        <?php
          // Render each image as an option
          foreach ($videos as $value) {
            $pieces = explode("/", $value);
            $videoValue = $pieces[2] . "/" . $pieces[3];
            echo "'<option ' + (row.value == '" . $videoValue . "' ? 'selected' : '') + ' value=\"" . $videoValue . "\">" . $videoValue . "</option>' + ";
          }
        ?>
      '</select>';
    }
    return null;
  }

  /* Value render wrapper */
  function renderValue(index, screen) {
    <?php
      // Print each screen
      foreach ($dirs as $key => $value) {
        $pieces = explode("/", $value);
        echo "if (screen == '$pieces[1]') { return renderValue_$pieces[1](index, screen); } ";
      }
    ?>
  }

  /* Render UI input from the state data */
  function renderSlidesConfig(screen) {
    /* Read from the state */
    const data = getState(screen);
    /* Create HTML template */
    let temp = '';
    data.forEach((row, index) => {
      /* Skip if the row is 'null' */
      if (!row) { return; }
      /* Add into the template */
      temp +=
        '<div id="' + screen + '_row_' + index + '" class="three fields">' +
        '  <div class="one wide field"><label>Order</label>' +
        '    <input' +
        '      id="' + screen + '_row_' + index + '_order"' +
        '      type="number"' +
        '      value="' + row.order + '"' +
        '      min="1" max="100"' +
        '    ></input>' +
        '  </div>' +
        '  <div class="one wide field"><label>Duration</label>' +
        '    <input' +
        '      id="' + screen + '_row_' + index + '_duration"' +
        '      type="number"' +
        '      value="' + row.duration + '"' +
        '      min="1" max="600"' +
        '    ></input>' +
        '  </div>' +
        '  <div class="two wide field"><label>Type</label>' +
        '    <select' +
        '      id="' + screen + '_row_' + index + '_type"' +
        '      class="ui fluid dropdown"' +
        '    >' +
        '      <option ' + (row.type == 'image' ? 'selected' : '') + ' value="image">image</option>' +
        '      <option ' + (row.type == 'video' ? 'selected' : '') + ' value="video">video</option>' +
        '      <option ' + (row.type == 'url' ? 'selected' : '') + ' value="url">url</option>' +
        '    </select>' +
        '  </div>' +
        '  <div id="' + screen + '_row_' + index + '_value_container" class="twelve wide field">' +
              /* Render value field based on the value type */
              renderValue(index, screen) +
        '  </div>' +
        '  <div class="one wide field" style="position: relative;">' +
        '    <button class="ui circular red icon button" style="margin: 2px 10px; position: absolute; bottom: 0; left: 0;" onclick="remove(' + index + ', \'' + screen + '\')">' +
        '      <i class="trash icon"></i>' +
        '    </button>' +
        '  </div>' +
        '</div>';
    });
    /* Display on UI */
    document.getElementById(screen + "_configForm").innerHTML = temp;
    /* Add the event listeners */
    addEventListeners(screen);
  }

  /* Add a new row in the state & UI */
  function add(screen) {
    /* Read from the state */
    const data = getState(screen);
    /* Get the next index */
    const nextIndex = data.length;
    /* Add a default row */
    data.push({ order: nextIndex, duration: 10, type: 'url', value: '' });
    /* Update the state */
    setState(data, screen);
    /* Add new row onto the UI */
    renderSlidesConfig(screen);
  }

  /* Update a row in the state */
  function update(index, key, value, screen) {
    /* Read from the state */
    const data = getState(screen);
    /* Update the row */
    data[index][key] = isNaN(parseInt(value, 10)) ? value : parseInt(value, 10);
    /* Update the state */
    setState(data, screen);
  }

  /* Remove a row from the state and UI */
  function remove(index, screen) {
    /* Read from the state */
    const data = getState(screen);
    /* Delete the row from JSON */
    delete data[index];
    /* Update the state */
    setState(data, screen);
    /* Hide from the UI */
    document.getElementById(screen + "_row_" + index).style = "display: none";
  }
</script>

<!-- Contents -->

<div style="margin-top: 50px;">
  <h4 class="ui header">
    Slides Configurations
    <!-- <a class="ui green mini label">Live</a> -->
  </h4>
  <div class="ui divider"></div>

  <!-- JSON Editor Form -->
  <div id="<?php echo $screen ?>_configForm" class="ui form"></div>

  <!-- Actions -->
  <button class="ui green small button" onclick="add('<?php echo $screen ?>')">
    <i class="plus icon"></i>
    Add new slide
  </button>

  <form action="functions/updateSlides.php" method="post" style="display: inline-block;">
    <!-- State storage -->
    <?php echo '<textarea id="' . $screen . '_state" name="' . $screen . '_state" rows="20" cols="150" readonly style="display: none;">' . $json . '</textarea>' ?>
    <!-- Identifiers use for updateSlides.php -->
    <?php echo '<input name="screen" value="' . $screen . '" readonly style="display: none;" />' ?>
    <button name="submit" type="submit" class="ui primary small button">
      <i class="save icon"></i>
      Save
    </button>
  </form>

</div>
