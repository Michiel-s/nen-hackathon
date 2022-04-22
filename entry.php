<?php

spl_autoload_register(function ($className) {
    $filename = "./classes/{$className}.php";
    if (is_readable($filename)) {
        require $filename;
    }
});

$filename = 'pontsteiger_afbouw_laagbouw-adapted.ifc';
$doors = [];

if ($file = fopen($filename, "r")) {
    while (!feof($file)) {
        $line = fgets($file);
        
        // Case IFCDOOR
        if (str_contains($line, '=IFCDOOR(')) {
            $matches = [];
            preg_match('/(#\d*).*?\((.*?)\);/', $line, $matches);
            $doors[$matches[1]] = $door = IfcDoor::fromIfcData($matches[2]);
            IfcRoot::registerIfcLine($matches[1], $door);
        }

        // Case IFCRELDEFINESBYPROPERTIES
        if (str_contains($line, '=IFCRELDEFINESBYPROPERTIES(')) {
            $matches = [];
            preg_match('/(#\d*).*?\((.*?)\);/', $line, $matches);
            $rel = IfcRelDefinesByProperties::fromIfcData($matches[2]);
            IfcRoot::registerIfcLine($matches[1], $rel);
        }

        if (str_contains($line, 'IFCPROPERTYSET')) {
            $propSet = IfcPropertySet::fromIfcData($line);
        }

        // #6=IFCPROPERTYSINGLEVALUE('IsExternal',$,IFCBOOLEAN(.F.),$);
        if (str_contains($line, 'IFCPROPERTYSINGLEVALUE')) {
            $matches = [];
            preg_match('/(#\d*).*?\((.*?)\);/', $line, $matches);
            $prop = IfcPropertySingleValue::fromIfcData($matches[2]);
            IfcPropertyAbstraction::registerIfcLine($matches[1], $prop);
        }
    }
    fclose($file);
}

IfcRoot::initAllIfcs();

// print count($doors) . " deuren \n";
// $subset = array_slice($doors, 0, 10, true);

$hsldEntrees = array_filter($doors, function (IfcDoor $door) {
    return $door->objectType === 'HSLD_woningentree';
});

array_walk($hsldEntrees, fn (IfcDoor $door) => print_r($door->getData()));


// $subset = array_slice($relDefs, 0, 10, true);
// print_r($subset);

// print implode(",", array_map(function(IfcDoor $door) {
//     return $door->lineId;
// }, $hsldEntrees));
