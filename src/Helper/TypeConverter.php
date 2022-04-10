<?php


namespace App\Helper;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TypeConverter
{
    public static function objectToJson($objectData): string
    {
        $encode = [new JsonEncoder()]; // array to JSON
        $normalize = [new ObjectNormalizer()]; // object to array

        $serializer = new Serializer($normalize, $encode); // merge them
        return $serializer->serialize($objectData, 'json');
    }
}