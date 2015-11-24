<?php
include 'config.php';

// Include the Dropbox SDK libraries
require_once "dropbox-sdk/lib/Dropbox/autoload.php";  
use \Dropbox as dbx; 

// Function to create zip file of specified destination
function Zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}


// location of your temp directory
$tmpDir = "/tmp/";

 
 
//this folder must be writeable by the server
//Backup file name with prefix and date , time
$backup = '/tmp/';
$rootPath = $backup.'backup_'.date('Y_m_d_h:i:s').'.zip';
$backupFilename = $dropbox_file_prefix.'_'.date('Y_m_d_h:i:s').'.zip';
$backupFile  = $backup.'backup_'.date('Y_m_d_h:i:s').'.zip'; 

  

Zip($files_dir, $backupFile );


//Upload zip file to dropbox


$appInfo = dbx\AppInfo::loadFromJsonFile(__DIR__."/config.json");
$dbxClient = new dbx\Client($dropbox_accessToken, $dropbox_folder);


//this message will send in a system e-mail from your cron job (assuming you set up cron to email you);
echo("Uploading $backupFilename to Dropbox\n");

//this is the actual Dropbox upload method;
$f = fopen($backupFile, "rb");
$result = $dbxClient->uploadFile('/'.$dropbox_folder.'/'.$backupFilename, dbx\WriteMode::force(), $f);
fclose($f);

// Delete the temporary files
unlink($backupFile);

?>