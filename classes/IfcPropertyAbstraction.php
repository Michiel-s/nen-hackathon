<?php

abstract class IfcPropertyAbstraction
{
    private static array $ifcProperties = [];

    public static function registerIfcLine(string $ifcId, IfcPropertyAbstraction $property)
    {
        self::$ifcProperties[$ifcId] = $property;
    }

    public static function getIfcLine(string $ifcId): ?IfcPropertyAbstraction
    {
        if (array_key_exists($ifcId, self::$ifcProperties)) {
            return self::$ifcProperties[$ifcId];
        }

        return null;
    }
}
