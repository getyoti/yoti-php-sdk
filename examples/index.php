<?php

// Load dependent packages and env data
require_once __DIR__ . '/bootstrap.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Your Site</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">

        <script src="https://sdk.yoti.com/clients/browser.2.1.0.js"></script>
    </head>
    <body>
        <div class="container">
            <h2>Your Site Information</h2>
            <span data-yoti-application-id="<?php echo getenv('YOTI_APP_ID') ?>"
                  data-yoti-type="inline"
                  data-yoti-scenario-id="<?php echo getenv('YOTI_SCENARIO_ID') ?>">
             Use Yoti
            </span>

            <!-- Initiate Yoti button -->
            <script>
                _ybg.init()
            </script>
        </div>
    </body>
</html>