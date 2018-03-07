<?php
/**
 * If we aren't using the composer vendor auto loader then run this script to get all
 * required files
 */

require_once __DIR__ . '/protobuf-php/protobuf/src/Message.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/AbstractMessage.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/Unknown.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/Enum.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/Configuration.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/Binary/StreamReader.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/Binary/Platform/BigEndian.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/ReadContext.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/Stream.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/WireFormat.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/Field.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/Collection.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/MessageCollection.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/StreamCollection.php';
require_once __DIR__ . '/protobuf-php/protobuf/src/UnknownFieldSet.php';

require_once __DIR__ . '/attrpubapi_v1/Anchor.php';
require_once __DIR__ . '/attrpubapi_v1/Attribute.php';
require_once __DIR__ . '/attrpubapi_v1/AttributeList.php';
require_once __DIR__ . '/attrpubapi_v1/ContentType.php';
require_once __DIR__ . '/compubapi_v1/EncryptedData.php';

// Autoload Yoti classes
spl_autoload_register(function($className) {
    $file = __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});

// Load Entities
/*require_once __DIR__ . '/Yoti/Entity/Country.php';
require_once __DIR__ . '/Yoti/Entity/AmlAddress.php';
require_once __DIR__ . '/Yoti/Entity/AmlProfile.php';
require_once __DIR__ . '/Yoti/Entity/Selfie.php';

// Load Exceptions
require_once __DIR__ . '/Yoti/Exception/AmlException.php';
require_once __DIR__ . '/Yoti/Exception/ActivityDetailsException.php';

// Load main classes
require_once __DIR__ . '/Yoti/ActivityDetails.php';
require_once __DIR__ . '/Yoti/YotiClient.php';

// Load Helpers
require_once __DIR__ . '/Yoti/Helper/ActivityDetailsHelper.php';

// Load Http classes
require_once __DIR__ . '/Yoti/Http/AbstractRequest.php';
require_once __DIR__ . '/Yoti/Http/AmlResult.php';
require_once __DIR__ . '/Yoti/Http/Payload.php';
require_once __DIR__ . '/Yoti/Http/RestRequest.php';
require_once __DIR__ . '/Yoti/Http/SignedRequest.php';

// Load Util
require_once __DIR__ . '/Yoti/Util/Age/AbstractAgeProcessor.php';
require_once __DIR__ . '/Yoti/Util/Age/AgeUnderOverProcessor.php';
require_once __DIR__ . '/Yoti/Util/Age/Condition.php';
require_once __DIR__ . '/Yoti/Util/Age/Processor.php';*/
