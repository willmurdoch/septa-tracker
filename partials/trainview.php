<?php
include_once dirname(__FILE__).'/../api/rail.php';

//Fetch train view
$json = file_get_contents('http://www3.septa.org/hackathon/TrainView/');
$liveTrains = json_decode($json);

if(!isset($_GET['line'])) $line = 'PAO';
else $line = $_GET['line'];

echo '<div class="trainBlockWrap" data-line="'.$line.'">';

foreach($liveTrains as $train){
  if(in_array($train->trainno, $timeTable)) echo 'yes';
  if($train->line == $trips->lines->{$line}){
    foreach($timeTable as $scheduled){
      if($scheduled->train_number == $train->trainno){
        $startTime = date('g:i A', strtotime($scheduled->stops[$origin]));
        $endTime = date('g:i A', strtotime($scheduled->stops[$destination]));
        if($train->late < 1) $classList = '';
        elseif($train->late <= 6) $classList = 'delayed';
        elseif($train->late >= 7) $classList = 'late';
        echo '<div class="trainWrap '.$classList.'" data-train="'.$train->trainno.'">';
          //Train title bar
          echo '<div class="trainHeader">';
            echo '<p class="number">'.$train->trainno.'</p>';
            echo '<p class="current">&nbsp;'.$train->nextstop.' next</p>';
            echo '<p class="delay">'.($train->late > 0 ? $train->late.' mins' : '').'</p>';
          echo '</div>';

          //Train details
          echo '<div class="trainDets">';
            echo '<div class="trainStop start">';
              echo '<span>'.$trips->stops->{$origin}->name.'</span>';
              echo '<span>'.$startTime.'</span>';
            echo '</div>';

            echo '<div class="trainArrow">→</div>';

            echo '<div class="trainStop end">';
              echo '<span>'.$trips->stops->{$destination}->name.'</span>';
              echo '<span>'.$endTime.'</span>';
            echo '</div>';
          echo '</div>';

        echo '</div>';
      }
    }
  }
}
echo '</div>';

?>
