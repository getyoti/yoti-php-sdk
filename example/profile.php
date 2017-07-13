<?php
require_once './vendor/autoload.php';//make sure you run composer update inside the example folder before trying them
$errorMsg = '';
//get and sanitize the token
$token = (isset($_GET['token']))? preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['token']) : '';
$config = [
    'sdkId' => 'add your SDK ID here', //this is your SDK ID associated with the Yoti Application you created on Dashboard
    'pemFile' => __DIR__ . '/keys/your-key-name.pem', //This is the public key (in .pem format) associated with the Yoti Application you created on Dashboard
];

try {
    $yotiClient = new Yoti\YotiClient($config['sdkId'], $config['pemFile']);
    $profile = $yotiClient->getActivityDetails($token);
    $attributes = $profile->getProfileAttribute();
    $selfie = base64_encode($attributes['selfie']);
} catch(\Exception $e) {
    $errorMsg = "Error - {$e->getMessage()}";
}
?>
<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <title>PROFILE PAGE </title>
   </head>
   <body>
        <?php if (!empty($errorMsg)) : ?>
            <p><strong><?php echo $errorMsg; ?></strong></p>
        <?php else: ?>
            <strong>Given Name</strong> <?php echo $attributes['given_names']; ?><br>
            <strong>Family Name</strong> <?php echo $attributes['family_name']; ?><br>
            <strong>Phone</strong> <?php echo $attributes['phone_number']; ?><br>
            <strong>Email</strong> <?php echo $attributes['email_address']; ?><br>
            <strong>DOB</strong> <?php echo $attributes['date_of_birth']; ?><br>
            <strong>Address</strong> <?php echo $attributes['postal_address']; ?><br>
            <strong>Gender</strong> <?php echo $attributes['gender']; ?><br>
            <strong>Nationality</strong> <?php echo $attributes['nationality']; ?><br>
            <strong>Photo</strong><br> <img src="data:image/x-icon;base64,<?php echo $selfie ?>" />
            <br>
        <?php endif; ?>
   </body>
 </html>
