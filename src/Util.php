<?php

namespace AndrewCarterUK\NoMoreLeaksBundle;

class Util
{
    public static function readProperty($object, $property)
    {
        $closure = \Closure::bind(function () use ($object, $property) {
            return $object->{$property};
        }, null, $object);

        return $closure();
    }
}
