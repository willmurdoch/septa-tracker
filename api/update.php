<?php

//Get zip contents from Septa server
$url  = 'http://www3.septa.org/developer/gtfs_public.zip';
$zipFile = 'gtfs_public.zip';
$zipResource = fopen($zipFile, 'w');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_FILE, $zipResource);
$page = curl_exec($ch);
if(!$page) {
 echo "Error :- " . curl_error($ch);
}
curl_close($ch);

//Extract the zip
$zip = new ZipArchive;
$extractPath = "gtfs_public";
if($zip->open($zipFile) != "true"){
 echo "Error :- Unable to open the Zip File";
}
$zip->extractTo($extractPath);
$zip->close();

//Extract sub-zip
$zip = new ZipArchive;
$extractPath = "gtfs_public";
if($zip->open('gtfs_public/google_rail.zip') != "true"){
 echo "Error :- Unable to open the Zip File";
}
$zip->extractTo('rail');
$zip->close();

//Delete stuff we don't need
unlink($zipFile);
unlink('gtfs_public/google_bus.zip');
unlink('gtfs_public/google_rail.zip');
rmdir('gtfs_public');

?>
