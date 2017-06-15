<?php
require_once __DIR__ . '/../src/boot.php';
require_once './vendor/autoload.php';

$config = [
    
    'sdkId' => 'add your SDK ID here',
    'pemFile' => __DIR__ . '/keys/your-key-name.pem',
];

$client = new Yoti\YotiClient($config['sdkId'], $config['pemFile']);
$profile = $client->getActivityDetails($parsedQueryString['token']);
$attributes = $profile->getProfileAttribute();
$selfie = base64_encode($attributes['selfie']);

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <title>PROFILE PAGE </title>
   </head>
   <body>

       <?php echo $attributes['given_names']; ?><br>
       <?php echo $attributes['family_name']; ?><br>
       <?php echo $attributes['phone_number']; ?><br>
       <?php echo $attributes['email_address']; ?><br>
       <?php echo $attributes['date_of_birth']; ?><br>
       <?php echo $attributes['postal_address']; ?><br>
       <?php echo $attributes['gender']; ?><br>
       <?php echo $attributes['nationality']; ?><br>
       <img src="data:image/x-icon;base64,<?php echo $selfie ?>" /><br>
   </body>
 </html>
