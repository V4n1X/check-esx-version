<?php
/*
Plugin / Script for Nagios / Icinga2, displaying version information and hostname of VMWare ESX(i) vSphere

V4n1X (C)2019

Version: master
*/
$host = $argv[1];
$community = "public";

$critical = false;
$warning = false;

$output = "";

try {

$modelName = @snmpget($host, $community, "iso.3.6.1.2.1.1.1.0");
$modelName = str_replace(array("STRING: ", "\"", "\r", "\n"), '', $modelName);

if(!$modelName) {
    fwrite(STDOUT, "Verbindung zur vSphere-SNMP Schnittstelle konnte nicht hergestellt werden.");
  	exit(2);
}

$hostname = snmpget($host, $community, "iso.3.6.1.2.1.1.5.0");
$hostname = str_replace(array("STRING: ", "\"", "\r", "\n"), '', $hostname);

/* Template für weitere Checks

$systemTemperature = snmpget($host, $community, ".1.3.6.1.4.1.24681.1.2.6.0");
$systemTemperature = str_replace("STRING: ", "", $systemTemperature);
$systemTemperature = explode(' C/', $systemTemperature);
$systemTemperature = $systemTemperature[0];

if($systemTemperature > 45) {
  $critical = true;
  $output .= "Temperatur: " . $systemTemperature . "°C" . " - ";
}

*/

$output = rtrim($output, " - ");

if($critical) {
  fwrite(STDOUT, $output);
	exit(2);
}

if($warning) {
  fwrite(STDOUT, $output);
	exit(1);
}

fwrite(STDOUT, "Version: " . $modelName . " - Hostname: " . $hostname);
exit(0);

} catch (Exception $e) {
  fwrite(STDOUT, "Verbindung zur vSphere-SNMP Schnittstelle konnte nicht hergestellt werden.");
	exit(2);
}


function getBatteryStatus($code) {

  $status = "";

  switch ($code) {
    case 1:
    $status = "Unbekannt";
    break;

  }

  return $status;

}

?>
