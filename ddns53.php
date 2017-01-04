<?php
require_once('r53.php');
require_once('IncR53.php');
$awszoneid='/hostedzone/'.$awszoneid;


$r53 = new Route53($awsid, $awskey);

function getPublicIP() { 
  // create a new cURL resource
  $ch = curl_init();

  // set URL and other appropriate options
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_URL, "http://icanhazip.com");

  // grab URL and pass it to the browser
  $ip = curl_exec($ch);

  // close cURL resource, and free up system resources
  curl_close($ch);
  $ip = trim(preg_replace('/\s+/', ' ', $ip));
  return $ip;
}

function updateIP ($hostname, $newIP, $oldIP, $awszoneid, $type = 'A', $ttl = 60 ) {
  global $r53;
  $delete = $r53->prepareChange('DELETE', $hostname, $type, $ttl, $oldIP);
  $result = $r53->changeResourceRecordSets($awszoneid, $delete);
  $create = $r53->prepareChange('CREATE', $hostname, $type, $ttl, $newIP);
  $result = $r53->changeResourceRecordSets($awszoneid, $create);
}

//print_r($r53->listHostedZones());
//print_r($r53->getHostedZone($awszoneid));


$recordSet = $r53->listResourceRecordSets($awszoneid);
//print_r($recordSet['ResourceRecordSets']);

for ($i = 0; $i < count($recordSet['ResourceRecordSets']); $i++) {
  if ($recordSet['ResourceRecordSets'][$i]['Name'] == $hostname) {

//    print_r($recordSet['ResourceRecordSets'][$i]);
//    echo $recordSet['ResourceRecordSets'][$i]['Name'];
    $oldIP = $recordSet['ResourceRecordSets'][$i]['ResourceRecords'][0];
    $type  = $recordSet['ResourceRecordSets'][$i]['Type'];
    $ttl   = $recordSet['ResourceRecordSets'][$i]['TTL'];
//    echo $oldIP;
  }}

$newIP = getPublicIP();

if ($oldIP == $newIP) {
//  echo "No change necessary.\n";
} else {
  echo "Updating ".$hostname." from ".$oldIP." to ".$newIP."\n";
  updateIP($hostname, $newIP, $oldIP, $awszoneid);
//  echo " done.";
}
?>
