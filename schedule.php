<?php
include 'api/source.php';

//Get variables to generate schedule
if(isset($_GET['line'])){

  //Check for passed in variables
  $line = $_GET['line'];
  $origin = $_GET['origin'];
  $destination = $_GET['destination'];
  $day = $_GET['days'];

  ?>

  <table id="scheduleList" data-line="<?php echo $line; ?>">
    <!--Table header-->
    <tr>
      <th class="trainNo">Train</th>
      <th class="station"><?php echo $trips->stops->{$origin}->name; ?></th>
      <th class="arrow"></th>
      <th class="station"><?php echo $trips->stops->{$destination}->name; ?></th>
      <th class="current">Stop</th>
      <th class="delay">Delay</th>
    </tr>

    <?php
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

    //Output schedule structure
    foreach($timeTable as $train){
      $startTime = date('g:i A', strtotime($train->stops[$origin]));
      $endTime = date('g:i A', strtotime($train->stops[$destination]));
      echo '<tr data-train="'.$train->train_number.'">';
        echo '<td class="trainNo">'.$train->train_number.'</td>';
        echo '<td class="station">'.$startTime.'</td>';
        echo '<td class="arrow">&rarr;</td>';
        echo '<td class="station">'.$endTime.'</td>';
        echo '<td class="current"></td>';
        echo '<td class="delay"></td>';
      echo '</tr>';
    }
    echo '</table>';
  }
?>
