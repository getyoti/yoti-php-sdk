<!DOCTYPE html>
<html class="yoti-html">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title }}</title>
    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
</head>

<body class="yoti-body">
    <main>
        <section class="yoti-top-section">
            <div class="yoti-logo-section">
                <a href="https://www.yoti.com" target="_blank">
                    <img class="yoti-logo-image" src="assets/images/logo.png" srcset="assets/images/logo@2x.png 2x" alt="Yoti" />
                </a>
            </div>

            <h1 class="yoti-top-header">{{ $title }}</h1>

            <div class="yoti-sdk-integration-section">
                <div id="yoti-share-button"></div>
            </div>

            <div class="yoti-login-or-separator">or</div>

            <div class="yoti-login-dialog">
                <h2 class="yoti-login-dialog-header">Login with your email:</h2>

                <input class="yoti-input" type="text" placeholder="Name" />

                <input class="yoti-input" type="text" placeholder="Email address" />

                <div class="yoti-login-actions">
                    <span class="yoti-login-forgot-button">forgot password?</span>

                    <button class="yoti-login-button">login</button>
                </div>
            </div>
        </section>

        <section class="yoti-sponsor-app-section">
            <h3 class="yoti-sponsor-app-header">The Yoti app is free to download and use:</h3>

            <div class="yoti-store-buttons-section">
                <a href="https://itunes.apple.com/us/app/yoti/id983980808?ls=1&mt=8" class="yoti-app-button-link">
                    <img src="assets/images/app-store-badge.png" srcset="assets/images/app-store-badge@2x.png 2x" alt="Download on the App Store" />
                </a>

                <a href="https://play.google.com/store/apps/details?id=com.yoti.mobile.android.live" class="yoti-app-button-link">
                    <img src="assets/images/google-play-badge.png" srcset="assets/images/google-play-badge@2x.png 2x" alt="Yoti" alt="get it on Google Play" />
                </a>
            </div>
        </section>
    </main>

    <script src="https://www.yoti.com/share/client/"></script>
    <script>
        window.Yoti.Share.init(@json($buttonConfig, JSON_PRETTY_PRINT));
    </script>
</body>

</html>