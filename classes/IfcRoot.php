<?php

abstract class IfcRoot
{
    public string $lineId;
    public string $globalId;
    public ?IfcOwnerHistory $ownerHistory;
    public ?string $name;
    public ?string $description;

    private static array $ifcObjects = [];

    public function init(): void
    {
    }

    public static function initAllIfcs(): void
    {
        foreach (self::$ifcObjects as $object) {
            $object->init();
        }
    }

    public static function registerIfcLine(string $ifcId, IfcRoot $object)
    {
        $object->lineId = $ifcId;
        self::$ifcObjects[$ifcId] = $object;
    }

    public static function getIfcLine(string $ifcId): ?IfcRoot
    {
        if (array_key_exists($ifcId, self::$ifcObjects)) {
            return self::$ifcObjects[$ifcId];
        }

        return null;
    }
}
