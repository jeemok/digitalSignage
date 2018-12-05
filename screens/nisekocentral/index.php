<!-- NISEKO CENTRAL TV -->
<head>
  <script type="text/javascript">
			function f_onload() {
        const CACHE_TIME = escape(new Date());
        const CHECK_TIME_SECONDS = 15; // check every 15 seconds
        const RELOAD_CLOCK_MINUTE = 27; // reload the page when the clock minute is 0
        let shouldReload = false; // Flag for reloading page

        // render an image
        function renderImage(url) {
          return "<span style=\"background: url(\'" + url + "?" + CACHE_TIME + "\') no-repeat center; background-size: cover; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; display: block; width: 100vw; height: 100vh;\"></span>";
        }

        // render a webpage
        function renderWeb(url) {
          return "<iframe src=\"" + url + "\" width=\"100%\" height=\"100%\" style=\"border-width: 0;\"></iframe>";
        }

        // render a video
        function renderVideo(video) {
          return "<video autoplay=\"\" muted=\"\" width=\"100%\" height=\"100%\">  <source src=\"" + video + "?" + CACHE_TIME + "\" type=\"video/mp4\">  Your browser does not support HTML5 video.</video>";
        }

        // slides list
        const contents = [
          <?php
            $jsonFile = file_get_contents("slides.json");
            $json = json_decode($jsonFile, true);

            // Construct the contents
            foreach($json as $key => $val):
              $keys = array_keys($val);
              echo '{';
              foreach($keys as $key):
                $printValue = is_int($val[$key]) ? $val[$key] : '"' . $val[$key] . '"';
                echo $key . ': ' . $printValue . ', ';
              endforeach;
              echo '}, ';
            endforeach;
          ?>
				];

        // function to show content 1 (infront)
        function showContent1(value) {
          // animation to fade out content 2
          document.getElementById('content2').classList.add('end');
          // set content one
          document.getElementById('content1').innerHTML = value;
          // clean up the css class for next animation
          document.getElementById('content1').classList.remove('end');
          document.getElementById('content2').classList.remove('end');
        }

        // function to render HTML based on the content type
        function renderHTML(contentIndex) {
          const content = contents[contentIndex];
          if (content.type === 'url') {
            return renderWeb(content.value);
          }
          else if (content.type === 'image') {
            return renderImage(content.value);
          }
          else if (content.type === 'video') {
            return renderVideo(content.value);
          }
          return null;
        }

        // set timeout for switching slides
        function switchSlides() {
          // Animation waiting time for css manipulation
          const ANIMATION_SECONDS = 2;
          const current = document.getElementById('content1').innerHTML;
          // NOTE: Use the console.log below to get the real rendered html and replace it into the function
          /* console.log('current', current); */

          // Find the content index
          const index = contents.findIndex(c => {
            // compare the content based on the content type
            if (c.type === 'url') {
              return renderWeb(c.value) === current;
            }
            else if (c.type === 'image') {
              return renderImage(c.value) === current;
            }
            else if (c.type === 'video') {
              return renderVideo(c.value) === current;
            }
            return false;
          });

          // animation for fading out
          document.getElementById('content1').classList.add('end');

          // if it is the last slide and we should reload to fetch the new contents
          if (index === contents.length - 1 && shouldReload === true) {
            // reload the site
            window.location.reload();
          }
          else if (index === -1 || index === contents.length - 1) {
            // Set the content 2 to be ready (using existing slide)
            document.getElementById('content2').innerHTML = renderHTML((index === -1 ? 0 : index));
            // switch to the next slide in 2s (waiting for the animation to finish)
            setTimeout(function() {
              // show the content1 with animation
              showContent1(renderHTML(0));
              // set next timeout
              setTimeout(switchSlides, contents[0].duration * 1000);
            }, ANIMATION_SECONDS * 1000);
          }
          else {
            // Set the content 2 to be ready
            document.getElementById('content2').innerHTML = renderHTML((index + 1));
            // switch to the next slide in 2s (waiting for the animation to finish)
            setTimeout(function() {
              // show the content1 with animation
              showContent1(renderHTML(index + 1));
              // set next timeout
              setTimeout(switchSlides, contents[index + 1].duration * 1000);
            }, ANIMATION_SECONDS * 1000);
          }
				}

        // set initial slide
        document.getElementById('content1').innerHTML = renderHTML(0);
        // start timer
        setTimeout(switchSlides, contents[0].duration * 1000);

        // set watch to determine if we should reload the site
        function checkTime() {
          // if current minute is 00
          if (new Date().getMinutes() == RELOAD_CLOCK_MINUTE) {
            // we should fetch new content
            shouldReload = true;
          }
          setTimeout(checkTime, CHECK_TIME_SECONDS * 1000);
        }
        setTimeout(checkTime, CHECK_TIME_SECONDS * 1000);
			}
  </script>
  <style>
    .fadeOut{
      visibility: visible;
      opacity: 1;
      transition: opacity 2s linear;
    }
    .fadeOut.end{
      visibility: hidden;
      opacity: 0;
      transition: visibility 0s 2s, opacity 2s linear;
    }
  </style>
</head>
<body onLoad="f_onload()" style="margin: 0; background: black;">
  <div id="content1" class="fadeOut" style="position: absolute; z-index: 5; width: 100%; height: 100%;"></div>
  <div id="content2" class="fadeOut" style="position: absolute; z-index: 1; width: 100%; height: 100%;"></div>
</body>
