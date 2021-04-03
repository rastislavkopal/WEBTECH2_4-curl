<?php

require_once '/home/xkopalr1/public_html/zadanie4/models/RecordsModel.php';

// url must contain name and last_name, resource name
if (!isset($_GET['first_name']) || empty($_GET['first_name'] || $_GET['last_name']) || empty($_GET['last_name'] || $_GET['resource']) || empty($_GET['resource'] )) {
    echo "Missing parameter...";
    return;
}

$recordsModel = new RecordsModel();

$records = $recordsModel->getPersonTimestampsByResource($_GET['first_name'],$_GET['last_name'],$_GET['resource']);
if (empty($records)){
    echo "Nezúčastnil sa na prednáške..";
    return;
}

foreach ($records as $record){
    echo "Typ: " . $record['action_type'] . " -> " . $record['action_time'] . "<br>";
}
