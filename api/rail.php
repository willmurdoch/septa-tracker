<?php

//Structure data in a way that is actually useful
$trips = (object)[];
$trips->trains = (object)[];
$trips->stops = (object)[];
$trips->lines = (object)[];

//Get all routes
$routes = file(dirname(__FILE__).'/rail/routes.txt', FILE_IGNORE_NEW_LINES);
$lines = [];
foreach($routes as $route){
  $routeData = explode(',', $route);
  if($routeData[0] != 'route_id'){
    $trips->lines->{$routeData[0]} = explode(' Line', $routeData[1])[0];
    $lines[$routeData[0]] = explode(' Line', $routeData[1])[0];
  }
}

//Get all trains
$trains = file(dirname(__FILE__).'/rail/trips.txt', FILE_IGNORE_NEW_LINES);
for($i = 1; $i < count($trains); $i++){
  $trainData = explode(',', $trains[$i]);
  $myLine = $trainData[0];
  $myTrain = explode('_', $trainData[2], 2)[1];
  if(!isset($trips->trains->{$myTrain})) $trips->trains->{$myTrain} = (object)[];
  if(!isset($trips->trains->{$myTrain}->line)) $trips->trains->{$myTrain}->line = $myLine.' ';
  else $trips->trains->{$myTrain}->line .= $myLine.' ';
  $trips->trains->{$myTrain}->train_number = explode('_', $trainData[2])[1];
  $trips->trains->{$myTrain}->train_day = $trainData[1];
}

//Get all stops
$stops = file(dirname(__FILE__).'/rail/stops.txt', FILE_IGNORE_NEW_LINES);
$stopStorage = Array();
for($i = 1; $i < count($stops); $i++){
  $stopArray = explode(',', $stops[$i]);
  $stopStorage[$stopArray[0]] = $stopArray[1];
}

//Associate stops with lines
$stopTimes = file(dirname(__FILE__).'/rail/stop_times.txt', FILE_IGNORE_NEW_LINES);
for($i = 1; $i < count($stopTimes); $i++){
  $stopTimeData = explode(',', $stopTimes[$i]);
  $myLine = explode('_', $stopTimeData[0])[0];
  $myTrain = explode('_', $stopTimeData[0], 2)[1];
  $tripID = $stopTimeData[3];
  $trips->trains->{$myTrain}->stops[$tripID] = $stopTimeData[2];
  if(!isset($trips->stops->$tripID)){
    $trips->stops->$tripID = (object)[];
    $trips->stops->{$tripID}->name = $stopStorage[$tripID];
  }
  if(!isset($trips->stops->{$tripID}->line)){
    $trips->stops->{$tripID}->line = $myLine.' ';
  }
  elseif(strpos($trips->stops->{$tripID}->line, $myLine) === false){
    $trips->stops->{$tripID}->line .= $myLine.' ';
  }
}

//Organize schedule by line & time
if(isset($_GET['line']) && isset($_GET['days']) && isset($_GET['origin']) && isset($_GET['destination'])){
  //Check for passed in variables
  $line = $_GET['line'];
  $origin = $_GET['origin'];
  $destination = $_GET['destination'];
  $day = $_GET['days'];

  //Reorder trains by arrival time
  $trainList = $trips->trains;
  $timeTable = [];
  foreach($trainList as $train){
    if($train->train_day == $day && isset($train->stops[$origin]) && isset($train->stops[$destination])){
      $myStops = $train->stops;
      if(strtotime($train->stops[$origin]) < strtotime($train->stops[$destination])){
        $startTime = strtotime($train->stops[$origin]);
        $timeTable[$startTime] = $train;
      }
    }
  }
  ksort($timeTable);
}

// echo '<pre>';
// print_r($trips);
// echo '</pre>';

?>
