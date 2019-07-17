<html>
<body>
<?php

define("USERNAME", "manishbattu@dev.com");
define("PASSWORD", "manomani@2169");
define("SECURITY_TOKEN", "oqwAHteZvrpluSMW2LsRiDps");
require_once ('soapclient/SforcePartnerClient.php');

$mySforceConnection = new SforcePartnerClient();
$mySforceConnection->createConnection("PartnerWSDL.xml");
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
if($mySforceConnection !=NULL)
{
	echo '<b>Connected successfully to <b>'.USERNAME.'<br><br>';
}
else
{
	echo 'Failed to Connect to'.USERNAME;
}
$query = "SELECT Id, FirstName, LastName, Phone from Contact limit 5";
$response = $mySforceConnection->query($query);

foreach ($response->records as $record)
{
$id =$record->Id;
echo '<tr>
<td>'.$record->Id.'</td>&nbsp;&nbsp;&nbsp;
<td>'.$record->fields->FirstName.'</td>&nbsp;&nbsp;&nbsp;
<td>'.$record->fields->LastName.'</td>&nbsp;&nbsp;&nbsp;
</tr><br>';
}
?>
</body>
</html>
