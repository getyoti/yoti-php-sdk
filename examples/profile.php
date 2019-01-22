<?php
require_once __DIR__ . '/profile.inc.php';
?>
<!DOCTYPE html>
<html class="yoti-html">
   <head>
       <meta charset="utf-8">
       <title>Yoti client example</title>
       <link rel="stylesheet" type="text/css" href="assets/css/profile.css">
       <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" />
   </head>
   <body class="yoti-body">
       <main class="yoti-profile-layout">
           <section class="yoti-profile-user-section">

               <div class="yoti-profile-picture-powered-section">
                   <span class="yoti-profile-picture-powered">Powered by</span>
                   <a href="https://www.yoti.com" target="_blank">
                       <img class="yoti-logo-image" src="assets/images/logo.png" srcset="assets/images/logo@2x.png 2x" alt="Yoti" />
                   </a>
               </div>

               <div class="yoti-profile-picture-section">
                   <?php if ($profile->getSelfie()) : ?>
                       <div class="yoti-profile-picture-area">
                           <img src="./<?php echo $selfieFileName ?>" class="yoti-profile-picture-image" alt="Yoti" />
                           <i class="yoti-profile-picture-verified-icon"></i>
                       </div>
                   <?php endif; ?>

                   <div class="yoti-profile-name">
                       <?php echo $fullName ? $fullName->getValue() : '' ?>
                   </div>
               </div>
           </section>

           <section class="yoti-attributes-section">
               <a href="/">
                   <img class="yoti-company-logo" src="assets/images/company-logo.jpg" alt="company logo">
               </a>

               <div class="yoti-attribute-list-header">
                   <div class="yoti-attribute-list-header-attribute">Attribute</div>
                   <div class="yoti-attribute-list-header-value">Value</div>
                   <div>Anchors</div>
               </div>

               <div class="yoti-attribute-list-subheader">
                   <div class="yoti-attribute-list-subhead-layout">
                       <div>S / V</div>
                       <div>Value</div>
                       <div>Sub type</div>
                   </div>
               </div>

               <div class="yoti-attribute-list">

                   <?php foreach($profileAttributes as $item): ?>
                       <?php if ($item['obj']) : ?>
                           <div class="yoti-attribute-list-item">
                               <div class="yoti-attribute-name">
                                   <div class="yoti-attribute-name-cell">
                                       <i class="<?php echo $item['icon'] ?>"></i>
                                       <span class="yoti-attribute-name-cell-text"><?php echo  $item['name'] ?></span>
                                   </div>
                               </div>

                               <div class="yoti-attribute-value">
                                   <?php
                                        $name = $item['name'];
                                        $attributeObj = $item['obj'];
                                        if ($name === 'Date of birth') {
                                            $value = $item['obj']->getValue()->format('d-m-Y');
                                        }
                                        elseif ($name === 'Age Verification') {
                                            // Because AgeVerification::class has a different structure
                                            $attributeObj = $item['obj']->getAttribute();
                                            $value = $ageVerificationStr;
                                        }
                                        else {
                                            $value = $item['obj']->getValue();
                                        }
                                        $anchors = $attributeObj->getAnchors();
                                   ?>
                                   <div class="yoti-attribute-value-text"><?php echo $value; ?></div>
                               </div>
                               <div class="yoti-attribute-anchors-layout">
                                   <div class="yoti-attribute-anchors-head -s-v">S / V</div>
                                   <div class="yoti-attribute-anchors-head -value">Value</div>
                                   <div class="yoti-attribute-anchors-head -subtype">Sub type</div>

                                   <?php foreach($anchors as $anchor) : ?>
                                       <div class="yoti-attribute-anchors -s-v"><?php echo $anchor->getType() ?></div>
                                       <div class="yoti-attribute-anchors -value"><?php echo $anchor->getValue() ?></div>
                                       <div class="yoti-attribute-anchors -subtype"><?php echo $anchor->getSubType() ?></div>
                                   <?php endforeach; ?>

                               </div>
                           </div>
                       <?php endif; ?>
                   <?php endforeach; ?>
               </div>
           </section>
       </main>
   </body>
 </html>
