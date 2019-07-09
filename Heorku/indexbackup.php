<html>
<body>
<?php
define("USERNAME", "manishbattu@dev.com");
define("PASSWORD", "manomani@2169");
define("SECURITY_TOKEN", "oqwAHteZvrpluSMW2LsRiDps");

require_once ('soapclient/SforcePartnerClient.php');
global $mySforceConnection ;
global $id;

$mySforceConnection = new SforcePartnerClient();
$mySforceConnection->createConnection("PartnerWSDL.xml");
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);

$query = "SELECT Id, FirstName, LastName, Phone from Contact limit 5";
$response = $mySforceConnection->query($query);

foreach ($response->records as $record)
{
	$id =$record->Id;
	$query1 = "SELECT Id, Name, Body from Attachment Where parentid ='" .$id ."'";
	$queryResult = $mySforceConnection->query($query1);
	echo '<tr>
	<td>'.$record->Id.'</td>&nbsp;&nbsp;&nbsp;
	<td>'.$record->fields->FirstName.'</td>&nbsp;&nbsp;&nbsp;
	<td>'.$record->fields->LastName.'</td>&nbsp;&nbsp;&nbsp;
	<td>'.$record->fields->Phone.'</td>&nbsp;&nbsp;&nbsp;
	</tr><br>';

	if (!empty($queryResult->records)) { // if condition to check for the records
		foreach($queryResult->records as $attachment) {
				$fields = array (
				'Id' => $id,
				'Check__c' => 'TRUE'	
			  );
			$sObject  = new SObject();
			$sObject->fields = $fields;
			$sObject->type = 'Contact';
			$response = $mySforceConnection->update(array($sObject));

			foreach ($response as $result) {
				 echo $result->id." updated<br>";
			}

// to print the attachment name
echo $attachment->fields->Name.'&nbsp;&nbsp;&nbsp;'.$attachment->Id.'</br></br>';

// to zip attachments
$zip = new ZipArchive;
			if ($zip->open($record->fields->LastName.'.zip', ZipArchive::CREATE) === TRUE)
			{
				$zip->addFile($attachment->fields->Body);
				$zip->close();
			}
		}
	}
}
?>
</body>
</html>