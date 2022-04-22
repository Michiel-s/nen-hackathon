<?php

class IfcDoor extends IfcBuildingElement
{
    public function getData(): array
    {
        return [
            'id' => $this->globalId,
            'name' => $this->name,
            'properties' => $this->getPropertySets()
        ];
    }

    public function getPropertySets(): array
    {
        $propertySets = array_filter(array_map(function(IfcRelDefinesByProperties $rel) {
            return $rel->relatingPropertyDefinition;
        }, $this->hasAssociations));

        return array_map(function(IfcPropertySet $set) {
            return [
                'group' => $set->name,
                'properties' => $set->getProperties()
            ];
        }, $propertySets);
    }

    public static function fromIfcData(string $ifcData): self
    {
        // trim values
        $ifcData = array_map(fn ($x) => trim($x, "'"), explode(',', $ifcData));

        $door = new self();
        [ $door->globalId
        , // ownerHistory
        , $door->name
        , // description
        , $door->objectType
        , //
        , //
        , $door->tag
        , // height
        , // width
        ] = $ifcData;
        
        return $door;
    }
}
