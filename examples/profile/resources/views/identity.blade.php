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

        <h2 class="yoti-top-header">Digital Identity Share Complete Example page</h2>

        <div>
            <p> Id: {{$result->getId()}}</p>
            <p> Status: {{$result->getStatus()}}</p>
            <p> Expiry: {{$result->getExpiry()}}</p>
        </div>

    </section>
</main>
</body>

</html>