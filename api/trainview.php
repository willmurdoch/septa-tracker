<?php
//Fetch train view
$json = file_get_contents('http://www3.septa.org/hackathon/TrainView/');
echo $json;
?>
