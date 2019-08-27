<?php
include 'api/source.php';

//Get variables to generate schedule
if(isset($_GET['line'])){

  //Check for passed in variables
  $line = $_GET['line'];
  $origin = $_GET['origin'];
  $destination = $_GET['destination'];
  $day = $_GET['days'];

  $myStart = $trips->stops->{$origin}->name;
  $myEnd = $trips->stops->{$destination}->name;

  //Get schedule for current line, order by time
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

  echo '<div class="trainBlockWrap" data-line="'.$line.'">';
  foreach($timeTable as $train){
    $startTime = date('g:i A', strtotime($train->stops[$origin]));
    $endTime = date('g:i A', strtotime($train->stops[$destination]));
    echo '<div class="trainWrap" data-train="'.$train->train_number.'">';
      //Train title bar
      echo '<div class="trainHeader">';
        echo '<p class="number">'.$train->train_number.'</p>';
        echo '<p class="current"></p>';
        echo '<p class="delay"></p>';
      echo '</div>';

      //Train details
      echo '<div class="trainDets">';
        echo '<div class="trainStop start">';
          echo '<span>'.$myStart.'</span>';
          echo '<span>'.$startTime.'</span>';
        echo '</div>';

        echo '<div class="trainArrow">→</div>';

        echo '<div class="trainStop end">';
          echo '<span>'.$myEnd.'</span>';
          echo '<span>'.$endTime.'</span>';
        echo '</div>';
      echo '</div>';

    echo '</div>';
  }
  echo '</div>';

}

?>
