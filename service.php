<?php

$data = json_decode(file_get_contents('php://input'), true);

$items = $data["tasks"];
$names = [];

foreach ($items as $item){
    array_push($names, $item["name"]);
}

$sorted = [];
function itemOf($name, $items){
    foreach ($items as $item)
        if($item["name"] == $name)
            return $item;
}

function sorter($names, $items, &$sorted)
{
    foreach($names as $name) {
        $item = itemOf($name, $items);
        if (array_key_exists("dependencies", $item))
                sorter($item["dependencies"], $items, $sorted);
        if(!in_array($item["name"], $sorted))
        array_push($sorted, $item["name"]);
    }
}
sorter($names, $items,$sorted);
print_r($sorted);


$file = fopen($data["filename"], "w");
foreach ($sorted as $name){
    fwrite($file, itemOf($name, $items)["command"]."\n");
}
fclose($file);
