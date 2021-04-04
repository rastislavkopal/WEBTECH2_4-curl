<?php

require_once '/home/xkopalr1/public_html/zadanie4/models/ResourcesModel.php';
require_once '/home/xkopalr1/public_html/zadanie4/models/RecordsModel.php';


function updateResourcesAndRecordsIfNeeded()
{
    $filenames = getFilesList();
    $resourceModel = new ResourcesModel();
    $recordsModel = new RecordsModel();

    // if arrays intersection is not empty => there is new item => update resources
    if ((empty($missingResources = array_diff($filenames, $resourceModel->getResourceNames())) == false))
    {
        foreach ($missingResources as $key => $value) { // for each value in directory
            echo $value;
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
        return date('Y-m-d H:i:s',date_create_from_format('m/d/Y, H:i:s A',$strDate)->getTimestamp());
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
    return mb_convert_encoding($result, "UTF-8","UTF-16");
}

// returns json array -> with all resource names
function getFilesList()
{
    $some_link = 'https://github.com/apps4webte/curldata2021';
    $tagName = 'a';
    $attrName = 'class';
    $attrValue = 'js-navigation-open Link--primary';

    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = false;
    @$dom->loadHTMLFile($some_link);

    $html = getTags( $dom, $tagName, $attrName, $attrValue );

    $html = strip_tags($html);

    $resourcesCount = substr_count($html, '.');

    $resources = [];
    for ($i =0; $i < $resourcesCount; $i++){
        $resources[] = strval(substr($html,0, $pos = strpos($html, '.')) . ".csv");
        $html = substr($html, $pos = strpos($html, '.')+4);
    }

    return $resources;
}


function getTags( $dom, $tagName, $attrName, $attrValue ){
    $html = '';
    $domxpath = new DOMXPath($dom);
    $newDom = new DOMDocument;
    $newDom->formatOutput = true;

    $filtered = $domxpath->query("//$tagName" . '[@' . $attrName . "='$attrValue']");

    // since above returns DomNodeList Object
    // I use following routine to convert it to string(html); copied it from someone's post in this site. Thank you.
    $i = 0;
    $arrayOfName = [];
    while( $myItem = $filtered->item($i++) ){
        $node = $newDom->importNode( $myItem, true );    // import node
        $newDom->appendChild($node);                    // append node
        $arrayOfName[] = $newDom->textContent;
    }
    $html = $newDom->saveHTML();
    return $html;
}
