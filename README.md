# Cloud-Backup
Backup your database and website files easily to dropbox.


<img src="http://images.snoork.com/images/1974137711_Firefox_Screenshot_2015-11-24T06-28-53.649Z.png">

[![Build Status](http://img.shields.io/badge/Version-1.0-green.svg)]( http://img.shields.io/badge/Version-1.0-green.svg )


 <a href="https://www.patreon.com/Pjijin" title="Donate to this project"><img src="https://s3.amazonaws.com/patreon_public_assets/toolbox/patreon.png" width="120px" height="30px" alt="Donate" /></a>

[![License](http://img.shields.io/badge/License-MIT-lightgrey.svg)](http://img.shields.io/badge/License-MIT-lightgrey.svg)


# Configuration


1) Create a directory on your site . For eg. BackUp

2) Upload all the files.

3) Edit the configuration.


```
$dropbox_folder = "CXTEST"; // Dropbox foldername
$files_dir = "../"; // Files directory to take backup
$dropbox_file_prefix = "FILE"; // File backup prefix name
$dropbox_database_prefix = "DB"; // Database backup prefix


//your access token from the Dropbox App Panel
$dropbox_accessToken = '';


//Database Configuration
$database_host = "localhost";
$database_user =  "";
$database_pass = "";
$database_name = "";
```

4) Enter key and secret in config.json

# Usage

Go to the URL http://www.YourDomain.com/BackUp/

From there you can take backup on files / database.

If you need to do back up automatically then use cron jobs for these

```
wget http://www.YourDomain.com/BackUp/files.php
```

```
wget http://www.YourDomain.com/BackUp/database.php
```

