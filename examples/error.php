<!DOCTYPE html>
<html class="yoti-html">
<head>
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="assets/css/profile.css">
</head>
<body class="yoti-body">
    <h2><a href="/">Home</a></h2>
    <h3>Could not login user for the following reason: <?php echo isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '' ?> </h3>
</body>
</html>