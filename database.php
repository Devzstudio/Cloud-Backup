<?php
include 'config.php';

//Include the Dropbox SDK libraries
require_once "dropbox-sdk/lib/Dropbox/autoload.php";  
use \Dropbox as dbx; 

// location of your temp directory
$tmpDir = "/tmp/";

// the zip database file will have this prefix
$prefix = $dropbox_database_prefix."_";

// Create the database backup file with specified prefix and current date , time
$sqlFile = $tmpDir.$prefix.date('Y_m_d_h:i:s').".sql";
$backupFilename = $prefix.date('Y_m_d_h:i:s').".tgz";
$backupFile = $tmpDir.$backupFilename;

$createBackup = "mysqldump -h ".$database_host." -u ".$database_user." --password='".$database_pass."' ".$database_name." --> ".$sqlFile;
$createZip = "tar cvzf $backupFile $sqlFile";
exec($createBackup);
exec($createZip);

//Upload the database to dropbox

$appInfo = dbx\AppInfo::loadFromJsonFile(__DIR__."/config.json");
$dbxClient = new dbx\Client($dropbox_accessToken, $dropbox_folder);


//this message will send in a system e-mail from your cron job (assuming you set up cron to email you);
echo("Uploading $backupFilename to Dropbox\n");

//this is the actual Dropbox upload method;
$f = fopen($backupFile, "rb");
$result = $dbxClient->uploadFile('/'.$dropbox_folder.'/'.$backupFilename, dbx\WriteMode::force(), $f);
fclose($f);

// Delete the temporary files
unlink($sqlFile);
unlink($backupFile);

?>