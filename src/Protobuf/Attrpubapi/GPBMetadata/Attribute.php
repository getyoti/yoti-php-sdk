<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Attribute.proto

namespace Yoti\Protobuf\Attrpubapi\GPBMetadata;

class Attribute
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \Yoti\Protobuf\Attrpubapi\GPBMetadata\ContentType::initOnce();
        $pool->internalAddGeneratedFile(
            '
�
Attribute.protoattrpubapi_v1"�
	Attribute
name (	
value (0
content_type (2.attrpubapi_v1.ContentType&
anchors (2.attrpubapi_v1.Anchor2
user_metadata (2.attrpubapi_v1.UserMetadata)
metadata (2.attrpubapi_v1.Metadata
ephemeral_id (	"q
Metadata
superseded_time_stamp (	
	deletable (

receipt_id (
revoked (
locked ("�
Anchor
artifact_link (
origin_server_certs (
artifact_signature (
sub_type (	
	signature (
signed_time_stamp (
associated_source (	"*
UserMetadata
key (	
value (	"�

MultiValue/
values (2.attrpubapi_v1.MultiValue.ValueG
Value0
content_type (2.attrpubapi_v1.ContentType
data (B�
$com.yoti.api.client.spi.remote.protoB	AttrProtoZ/github.com/getyoti/yoti-go-sdk/v3/yotiprotoattr�Yoti.Auth.ProtoBuf.Attribute�Yoti\\Protobuf\\Attrpubapi�$Yoti\\Protobuf\\Attrpubapi\\GPBMetadata�Yoti::Protobuf::Attrpubapibproto3'
        , true);

        static::$is_initialized = true;
    }
}

