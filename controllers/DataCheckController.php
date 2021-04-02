<?php

require_once '/home/xkopalr1/public_html/zadanie4/models/ResourcesModel.php';
require_once '/home/xkopalr1/public_html/zadanie4/models/RecordsModel.php';

function updateData()
{

}

function updateResourcesIfNeeded()
{
    $filesList = getFilesList();
    $filenames = [];
    $resourceModel = new ResourcesModel();
    $recordsModel = new RecordsModel();

    foreach ($filesList as $key => $value)
        $filenames[] = $value->name;

    // if arrays intersection is not empty => there is new item => update resources
    if ((empty($missingResources = array_diff($filenames, $resourceModel->getResourceNames())) == false))
    {
        foreach ($missingResources as $key => $value) { // for each value in directory
            if (!empty($value)){
                $resourceModel->insertNewResource($value);

                // for each line, split by tabulator
                $csv = explode(PHP_EOL, getCsvFile($value));
                $isFirst = true;
                foreach($csv as $line){
                    if ($isFirst){ // skip first line => headers
                        $isFirst = false;
                        continue;
                    }

                    $line = explode("\t", $line);
                    $splitName = explode(" ", $line[0]);
                    if(empty($splitName[0]) || empty($splitName[1]) || empty($line[1]) || empty($line[2]) || empty($value))
                        continue;

                    $recordsModel->insertNewRecord([
                        'first_name' => $splitName[0],
                        'last_name' => $splitName[1],
                        'action_type' => $line[1],
                        'action_time' => convertDateToTimestamp($line[2]),
                        'resource_name' => $value,
                    ]);
                }
            }
        }
    }
}

function convertDateToTimestamp($strDate)
{
    if (str_contains($strDate,"AM")){
        return date('Y-m-d H:i:s',date_create_from_format('d/m/Y, H:i:s A',$strDate)->getTimestamp());
    } else {
        return date('Y-m-d H:i:s',date_create_from_format('d/m/Y, H:i:s',$strDate)->getTimestamp());
    }
}

// returns raw output string
function getCsvFile($filename)
{
    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, "https://raw.githubusercontent.com/apps4webte/curldata2021/main/".$filename);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // as string

    $result = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);
    return mb_convert_encoding($result, "UTF-8","UTF-16");;
}

// returns json array -> github api: all resources
function getFilesList()
{
    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/apps4webte/curldata2021/contents");
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string

    // $output contains the output string
    $result = curl_exec($ch);

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($result , 0, $header_size);
    $body = substr($result , $header_size);

    curl_close($ch); // close curl resource to free up system resources
    return json_decode($body);
}