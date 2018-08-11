<?php


$dataFile      = 'PASTE_FILE_NAME_HERE';
$sftpServer    = 'PASTE_SFTP_SERVER_NAME_HERE';
$sftpUsername  = 'PASTE_USERNAME_HERE';
$sftpPassword  = 'PASTE_PASSWORD_HERE';
$sftpPort      = 'PASTE_PORT_HERE';
$sftpRemoteDir = '/';
 
$ch = curl_init('sftp://' . $sftpServer . ':' . $sftpPort . $sftpRemoteDir . '/' . basename($dataFile));
 
$fh = fopen($dataFile, 'r');
 
if ($fh) {
    curl_setopt($ch, CURLOPT_USERPWD, $sftpUsername . ':' . $sftpPassword);
    curl_setopt($ch, CURLOPT_UPLOAD, true);
    curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
    curl_setopt($ch, CURLOPT_INFILE, $fh);
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($dataFile));
    curl_setopt($ch, CURLOPT_VERBOSE, true);
 
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);
 
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
 
    if ($response) {
        echo "Success";
    } else {
        echo "Failure";
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        echo "Verbose information:\n" . $verboseLog . "\n";
    }
}
