<?php

require_once '/home/xkopalr1/public_html/zadanie4/models/ResourcesModel.php';
require_once '/home/xkopalr1/public_html/zadanie4/models/RecordsModel.php';

/*
 * Returns array of all persons with times (in minutes) for each resource
 */
function getAllPersons()
{
    $recordsModel = new RecordsModel();
    $resourcesModel = new ResourcesModel();
    $persons = $recordsModel->getDistinctPeople();
    $resources = $resourcesModel->getResourceNames();
    $usersWithTimes = [];


    foreach ($persons as $person){
        $usersWithTimes[$person['id']] = [
            'id' => $person['id'],
            'first_name' => $person['first_name'],
            'last_name' => $person['last_name'],
        ];

         for ($i=0; $i < count($resources); $i++)
         {
            $userTimesPerResource = $recordsModel->getPersonTimestampsByResource($person['first_name'],$person['last_name'], $resources[$i]);
            $lastLeft = $recordsModel->lastLeftByResource($resources[$i]);
            $usersWithTimes[$person['id']][$resources[$i]] =  calculateTime($userTimesPerResource, $lastLeft);
//            echo "resource: " . $resources[$i] . ", id: " . $person['id']  . " -> " . $person['first_name'] . " " . $person['last_name'] . ".... time: " . calculateTime($userTimesPerResource, $lastLeft) . "<br>";
        }
//        echo var_dump($usersWithTimes[$person['id']]);
//        echo "<br><br><br>";
    }
    return $usersWithTimes;
}


/*
 * @param: array of records for user and selected resource --> [action_type, action_time]
 * @return: time (in minutes) spent on selected seminar
 */
function calculateTime($records, $lastLeftForResource)
{
    if (count($records) % 2 != 0)
        array_push($records, ["action_type" => "Left", "action_time" => $lastLeftForResource]);

    $timeSpent = 0;
    for ($i = 0; $i < count($records); $i+=2){
        $from_time = strtotime($records[$i]['action_time']);
        $to_time = strtotime($records[$i+1]['action_time']);
        $timeSpent += round(abs($to_time - $from_time) / 60,2);
    }

    return $timeSpent;
}