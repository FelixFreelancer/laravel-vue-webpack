<?php

/* 
 * Script Author: @ezidaf / Cloudsa Systems
 * This is a backup scrip for mysql database. It keeps only 31 backups of past 31 days, and backups of each 1st day of past months.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'globalpa_site');
define('DB_USER', 'globalpa_dbuser');
define('DB_PASSWORD', 'JmW9,zB{@8oe');
define('BACKUP_SAVE_TO', '/home/globalparcelforw/DBackup_sql'); // without trailing slash

$time = time();
$day = date('j', $time);
if ($day == 1) {
    $date = date('Y-m-d', $time);
} else {
    $date = $day;
}

$backupFile = BACKUP_SAVE_TO . '/' . DB_NAME . '_' . $date . '.gz';
if (file_exists($backupFile)) {
    unlink($backupFile);
}
$command = 'mysqldump --opt -h ' . DB_HOST . ' -u ' . DB_USER . ' -p\'' . DB_PASSWORD . '\' ' . DB_NAME . ' | gzip > ' . $backupFile;
system($command);

?>