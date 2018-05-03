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
require_once __DIR__ . '/protobuf-php/protobuf/src/Extension/ExtensionFieldMap.php';

// PHP Secure files
require_once __DIR__ . '/phpseclib/bootstrap.php';

// Autoload Yoti classes and other dependencies
spl_autoload_register(function($className) {
    $file = __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});
