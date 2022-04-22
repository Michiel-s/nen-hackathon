<?php

class IfcRelDefinesByProperties extends IfcRelDefines
{
    /** @var IfcObjectDefinition[] */
    public array $relatedObjects = [];
    public ?IfcPropertySet $relatingPropertyDefinition;

    private string $relatedObjectIds;
    private string $propertySetId;

    public function init(): void
    {
        parent::init();

        // relatedObjectIds
        $relatedObjectIds = trim($this->relatedObjectIds, "()");
        foreach (explode(',', $relatedObjectIds) as $id) {
            $objectDef = IfcRoot::getIfcLine($id);
            if (is_null($objectDef)) {
                return;
            }
            if (!($objectDef instanceof IfcObjectDefinition)) {
                throw new Exception('Incorrect type');
            }
            $this->relatedObjects[] = $objectDef;
            $objectDef->hasAssociations[] = $this;
        }

        // propertySetId
        $this->relatingPropertyDefinition = IfcRoot::getIfcLine($this->propertySetId);
    }

    public static function fromIfcData(string $ifcData): self
    {
        // trim values
        $ifcData = array_map(fn ($x) => trim($x, "'"), explode(',', $ifcData));

        $ifc = new self();
        [ $ifc->globalId
        , // ownerHistory
        , // name
        , // description
        , $ifc->relatedObjectIds // relatedObjects
        , $ifc->propertySetId // relatingPropertyDefinition
        ] = $ifcData;

        return $ifc;
    }
}
