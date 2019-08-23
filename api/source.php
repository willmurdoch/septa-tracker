<?php

//Structure data in a way that is actually useful
$trips = (object)[];

//Get all routes
$routes = file('api/rail/routes.txt', FILE_IGNORE_NEW_LINES);
foreach($routes as $route){
  $routeData = explode(',', $route);
  if($routeData[0] != 'route_id'){
    $trips->{$routeData[0]} = (object)[];
    $trips->{$routeData[0]}->trains = (object)[];
    $trips->{$routeData[0]}->stops = (object)[];
    $trips->{$routeData[0]}->name = $routeData[1];
  }
}

//Get all trains
$trains = file('api/rail/trips.txt', FILE_IGNORE_NEW_LINES);
for($i = 1; $i < count($trains); $i++){
  $trainData = explode(',', $trains[$i]);
  $myLine = $trainData[0];
  $trips->{$myLine}->trains->{$trainData[2]} = (object)[];
  $trips->{$myLine}->trains->{$trainData[2]}->train_number = explode('_', $trainData[2])[1];
  $trips->{$myLine}->trains->{$trainData[2]}->train_day = $trainData[1];
}

//Get all stops
$stops = file('api/rail/stops.txt', FILE_IGNORE_NEW_LINES);
$stopStorage = Array();
for($i = 1; $i < count($stops); $i++){
  $stopArray = explode(',', $stops[$i]);
  $stopStorage[$stopArray[0]] = $stopArray[1];
}

//Associate stops with lines
$stopTimes = file('api/rail/stop_times.txt', FILE_IGNORE_NEW_LINES);
for($i = 1; $i < count($stopTimes); $i++){
  $stopTimeData = explode(',', $stopTimes[$i]);
  $myLine = explode('_', $stopTimeData[0])[0];
  $tripID = $stopTimeData[3];
  $trips->{$myLine}->trains->{$stopTimeData[0]}->stops[$tripID] = $stopTimeData[2];
  $trips->{$myLine}->stops->$tripID = $stopStorage[$tripID];
}

// echo '<pre>';
// print_r($trips);
// echo '</pre>';

?>
