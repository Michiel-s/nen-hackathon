<?php

class IfcPropertySingleValue extends IfcSimpleProperty
{
    public ?string $nominalValue;
    public ?string $unit;

    // #6=IFCPROPERTYSINGLEVALUE('IsExternal',$,IFCBOOLEAN(.F.),$);
    public static function fromIfcData(string $ifcData): self
    {
        // trim values
        $ifcData = array_map(fn ($x) => trim($x, "'"), explode(',', $ifcData));

        $ifc = new self();
        [ $ifc->name
        , // description
        , $ifc->nominalValue // nominalValue
        , // unit
        ] = $ifcData;

        return $ifc;
    }
}
