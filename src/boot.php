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
require_once __DIR__ . '/Yoti/ActivityDetails.php';
require_once __DIR__ . '/Yoti/YotiClient.php';