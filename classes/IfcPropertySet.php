<?php

class IfcPropertySet extends IfcPropertySetDefinition
{
    /** @var IfcProperty[] */
    public array $hasProperties = [];

    private string $propertyIds;

    public function init(): void
    {
        parent::init();

        // propertyIds
        $propertyIds = trim($this->propertyIds, "()");
        foreach (explode(',', $propertyIds) as $id) {
            $objectDef = IfcPropertyAbstraction::getIfcLine($id);
            if (is_null($objectDef)) {
                throw new Exception("Line {$id} undefined");
            }
            if (!($objectDef instanceof IfcProperty)) {
                throw new Exception('Incorrect type');
            }
            $this->hasProperties[] = $objectDef;
        }
    }

    public function getProperties(): array
    {
        return $this->propertyIds;
        return array_map(function (IfcProperty $property) {
            // assert($property instanceof IfcPropertySingleValue);
            return [
                'name' => $property->name,
                'value' => $property->nominalValue
            ];
        }, $this->hasProperties);
    }

    public static function fromIfcData(string $ifcData): self
    {

        // #527905=IFCPROPERTYSET('1YAIurcGT62vlZJhB7$YUx',#1,'Pset_DoorCommon',$,(#6,#514));
        $matches = [];
        preg_match('/(#\d*)=IFCPROPERTYSET\((.*?),(.*?),(.*?),(.*?),(.*?)\);/', $ifcData, $matches); // 6 groups

        // trim values
        $ifcData = array_map(fn ($x) => trim($x, "'"), $matches);

        $ifc = new self();
        [ $ifc->lineId
        , $ifc->globalId
        , // ownerHistory
        , $ifc->name
        , // description
        , $ifc->propertyIds // hasProperties
        ] = $ifcData;
        
        IfcRoot::registerIfcLine($matches[1], $ifc);

        return $ifc;
    }
}
