<?php


namespace App\Helper;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TypeConverter
{
    public static function objectToJson($objectData): string
    {
        $encode = [new JsonEncoder()];
        $normalize = [new ObjectNormalizer()];

        $serializer = new Serializer($normalize, $encode);
        return $serializer->serialize($objectData, 'json');
    }
}