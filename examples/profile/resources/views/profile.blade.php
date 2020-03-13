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
                           <img src="<?php echo $profile->getSelfie()->getValue()->getBase64Content(); ?>" class="yoti-profile-picture-image" alt="Yoti" />
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
                                        <span class="yoti-attribute-name-cell-text"><?php echo htmlspecialchars($item['name']) ?></span>
                                    </div>
                                </div>

                                <div class="yoti-attribute-value">
                                   <div class="yoti-attribute-value-text">
                                   <?php
                                   $attributeObj = $item['obj'];
                                   switch ($item['name']) {
                                        case 'Date of birth';
                                            echo htmlspecialchars($item['obj']->getValue()->format('d-m-Y'));
                                            break;
                                        case 'Age Verification':
                                            ?>
                                            <table>
                                                <tr>
                                                    <td>Check Type</td>
                                                    <td><?php echo htmlspecialchars($item['age_verification']->getCheckType()); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Age</td>
                                                    <td><?php echo htmlspecialchars($item['age_verification']->getAge()); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Result</td>
                                                    <td><?php echo htmlspecialchars($item['age_verification']->getResult()); ?></td>
                                                </tr>
                                            </table>
                                            <?php
                                            break;
                                        case 'Structured Postal Address':
                                            ?>
                                            <table>
                                                <?php foreach ($item['obj']->getValue() as $key => $value): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($key); ?></td>
                                                        <td><?php echo htmlspecialchars($value); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <?php
                                            break;
                                        case 'Document Details':
                                            ?>
                                            <table>
                                                <tr>
                                                    <td>Type</td>
                                                    <td><?php echo htmlspecialchars($item['obj']->getValue()->getType()); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Issuing Country</td>
                                                    <td><?php echo htmlspecialchars($item['obj']->getValue()->getIssuingCountry()); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Document Number</td>
                                                    <td><?php echo htmlspecialchars($item['obj']->getValue()->getDocumentNumber()); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Expiration Date</td>
                                                    <td><?php echo htmlspecialchars($item['obj']->getValue()->getExpirationDate()->format('d-m-Y')); ?></td>
                                                </tr>
                                            </table>
                                            <?php
                                            break;
                                        case 'Document Images':
                                            foreach ($item['obj']->getValue() as $image) {
                                            ?>
                                                <img src="<?php echo htmlspecialchars($image->getBase64Content()); ?>" />
                                            <?php
                                            }
                                            break;
                                        default:
                                            echo htmlspecialchars($item['obj']->getValue());
                                   }
                                   ?>
                                   </div>
                               </div>
                               <div class="yoti-attribute-anchors-layout">
                                   <div class="yoti-attribute-anchors-head -s-v">S / V</div>
                                   <div class="yoti-attribute-anchors-head -value">Value</div>
                                   <div class="yoti-attribute-anchors-head -subtype">Sub type</div>

                                   <?php foreach($attributeObj->getAnchors() as $anchor) : ?>
                                       <div class="yoti-attribute-anchors -s-v"><?php echo htmlspecialchars($anchor->getType()); ?></div>
                                       <div class="yoti-attribute-anchors -value"><?php echo htmlspecialchars($anchor->getValue()); ?></div>
                                       <div class="yoti-attribute-anchors -subtype"><?php echo htmlspecialchars($anchor->getSubType()); ?></div>
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