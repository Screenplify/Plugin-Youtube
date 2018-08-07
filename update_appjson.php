<?php 	

$file = 'app.json';

$jsonString = file_get_contents($file);
$data = json_decode($jsonString, true);

$version = $data['application']['version'];
$version = explode('.', $version);
$end = end($version);

array_pop($version);
array_push($version, (int)$end + 1);

$newversion = implode('.', $version);

$data['application']['version'] = $newversion;

//print_r($data);


$newJsonString = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents($file, $newJsonString);

echo $newversion;

// or if you want to change all entries with activity_code "1"
/*foreach ($data as $key => $entry) {
    if ($entry['activity_code'] == '1') {
        $data[$key]['activity_name'] = "TENNIS";
    }
}*/

 ?>