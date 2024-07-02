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
                <img class="yoti-logo-image" src="assets/images/logo.png" srcset="assets/images/logo@2x.png 2x"
                     alt="Yoti"/>
            </a>
        </div>
        <h1 class="yoti-top-header">Digital Identity(Advanced)4 Share Example</h1>

        <div class="yoti-sdk-integration-section">
            <div id="webshare-target"></div>
        </div>

    </section>

    <section class="yoti-sponsor-app-section">
        <h3 class="yoti-sponsor-app-header">The Yoti app is free to download and use:</h3>

        <div class="yoti-store-buttons-section">
            <a href="https://itunes.apple.com/us/app/yoti/id983980808?ls=1&mt=8" class="yoti-app-button-link">
                <img src="assets/images/app-store-badge.png"
                     srcset="assets/images/app-store-badge@2x.png 2x"
                     alt="Download on the App Store" />
            </a>

            <a href="https://play.google.com/store/apps/details?id=com.yoti.mobile.android.live" class="yoti-app-button-link">
                <img src="assets/images/google-play-badge.png"
                     srcset="assets/images/google-play-badge@2x.png 2x"
                     alt="get it on Google Play" />
            </a>
        </div>
    </section>
</main>
<script>async function onSessionIdResolver() {
        const response = await fetch('/generate-advanced-identity-session');
        if (!response.ok) {
            throw new Error('Response was not ok');
        }
        const result = await response.text();
        console.log("session id %s", result);
        return result;
    }

    async function completionHandler(receivedReceiptId) {
        console.log('completion handler:', receivedReceiptId)
        const url = '/receipt-info?ReceiptID=' + encodeURIComponent(receivedReceiptId);
        window.location.href = url;
    }

    function onErrorListener(...data) {
        console.warn('onErrorListener:', ...data)
    }

    async function onReadyToStart() {
        const { Yoti } = window
        await Yoti.createWebShare({
            name: 'Use Yoti',
            domId: 'webshare-target',
            sdkId: '{{$sdkId}}',
            hooks: {
                sessionIdResolver: onSessionIdResolver,
                errorListener: onErrorListener,
                completionHandler,
            },
            flow: "REVEAL_MODAL"
        })
    }

    async function onClientLoaded() {
        const { Yoti } = window
        await Yoti.ready()
        await onReadyToStart()
    }</script>
<script src="https://www.yoti.com/share/client/v2" onload="onClientLoaded()"></script>
</body>
</html>
