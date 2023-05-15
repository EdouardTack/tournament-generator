<?php

namespace Tackacoder\Tournament\Helpers;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Yaml;

class SerializerEncoder
{
    public static function toArray($data)
    {
        $encoders = [/*new XmlEncoder(), new JsonEncoder(),*/ new YamlEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $content = $serializer->serialize($data, 'yaml', ['yaml_inline' => 20, 'yaml_indent' => 0]);

        return Yaml::parse($content);
    }
}