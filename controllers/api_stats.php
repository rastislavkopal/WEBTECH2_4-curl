<?php

require_once '/home/xkopalr1/public_html/zadanie4/models/ResourcesModel.php';
require_once '/home/xkopalr1/public_html/zadanie4/models/RecordsModel.php';

$recordsModel = new RecordsModel();
$resourcesModel = new ResourcesModel();
$resources = $resourcesModel->getResourceNames();

$json = [];
foreach ($resources as $resource)
{
    $date = substr($resource,0,8);
    $object = [
        "date" => substr($date, 0,4) . " " . substr($date, 4,2) . " " . substr($date, 6,2),
        "minutes" => intval($recordsModel->getDistinctPeopleByResource($resource))
    ];
    $json[] = $object;
}

echo json_encode($json);