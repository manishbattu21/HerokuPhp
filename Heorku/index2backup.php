<html>
<body>
<?php
define("USERNAME", "manishbattu@dev.com");
define("PASSWORD", "manomani@2169");
define("SECURITY_TOKEN", "oqwAHteZvrpluSMW2LsRiDps");

require_once ('soapclient/SforcePartnerClient.php');
global $mySforceConnection ;
global $id;
global $contdocId;
global $query2;
global $queryResult1;
global $body;


$mySforceConnection = new SforcePartnerClient();
$mySforceConnection->createConnection("PartnerWSDL.xml");
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);

$query = "SELECT Id, FirstName, LastName, Phone from Contact limit 5";
$response = $mySforceConnection->query($query);

foreach ($response->records as $record)
{
	$id =$record->Id;
	echo '<tr>
	<td>'.$record->Id.'</td>&nbsp;&nbsp;&nbsp;
	<td>'.$record->fields->FirstName.'</td>&nbsp;&nbsp;&nbsp;
	<td>'.$record->fields->LastName.'</td>&nbsp;&nbsp;&nbsp;
	<td>'.$record->fields->Phone.'</td>&nbsp;&nbsp;&nbsp;
	</tr><br>';

$query1 = "SELECT ContentDocumentId FROM ContentDocumentLink WHERE LinkedEntityId ='" .$id ."'";
$queryResult = $mySforceConnection->query($query1);
if (!empty($queryResult->records)) {
	foreach($queryResult->records as $contentDocumentId) {
		$contdocId = $contentDocumentId->fields->ContentDocumentId;
		
$query2 = "SELECT Id,Title,VersionData FROM ContentVersion WHERE ContentDocumentId ='" .$contdocId ."'";
$queryResult1 = $mySforceConnection->query($query2);

	if (!empty($queryResult1->records)) { // if condition to check for the records
		foreach($queryResult1->records as $attachment) {
// to print the attachment name
echo $attachment->fields->Title.'&nbsp;&nbsp;&nbsp;'.$attachment->Id.'</br></br>';
$body = $attachment->fields->VersionData;
// to zip attachments

$zip_name = $record->fields->LastName.'.zip';
$path = 'C:/xampp/htdocs/Heorku/'.$zip_name;
$zip = new ZipArchive();
			if ($zip->open($path, ZipArchive::CREATE) === TRUE )
			{
				$zip->addFromString($record->Id,base64_decode($body));
				//echo '<br>Zip file created '.$record->fields->LastName;
				
			}


		}
		$zip->close();
	}
	}
}
}
?>
</body>
</html>