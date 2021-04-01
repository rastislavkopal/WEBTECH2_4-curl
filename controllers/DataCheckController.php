<?php

function updateData()
{
    $filesList = getFilesList();
    foreach ($filesList as $key => $value) { // for each value in directory
        echo $value->name;
    }

    // curl https://github.com/apps4webte/curldata2021
}

function getFilesList()
{
    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/apps4webte/curldata2021/contents");
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
    curl_setopt($ch, CURLOPT_HEADER, 1);

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $result = curl_exec($ch);

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($result , 0, $header_size);
    $body = substr($result , $header_size);

    curl_close($ch); // close curl resource to free up system resources
    return json_decode($body);
}