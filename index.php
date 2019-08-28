<?php include 'api/rail.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>SEPTA Tracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" href="css/main.css" type="text/css">
</head>
<body>
  <div id="back"<?php if(isset($_GET['line'])) echo ' class="in"'; ?>>&lsaquo;</div>
  <div id="stations" style="display: none;">
    <?php
    $routes = array();
    foreach($trips->lines as $id => $line){
      echo '<select data-line="'.$id.'">';
      echo '<option value="" selected>Select a station</option>';
      $myStops = Array();
      foreach($trips->stops as $key => $stop){
        if(strpos($stop->line, $id) !== false) $myStops[$key] = $stop;
      }
      asort($myStops);
      foreach($myStops as $key => $station){
        echo '<option value="'.$key.'">'.$station->name.'</option>';
      }
      echo '</select>';
    }
    ?>
  </div>
  <form<?php if(isset($_GET['destination'])) echo ' class="out"'; ?> action="schedule.php" method="POST">
    <img src="assets/logo.png" alt="SEPTA">
    <div class="formPart inactive" id="lineSelect">
      <select name="line" id="line" required>
        <option value="" <?php if(!isset($_GET['line'])) echo 'selected'; ?>>Select a train line</option>
        <?php
        foreach($lines as $key => $value){
          echo '<option '.(isset($_GET['line']) && $_GET['line'] == $key ? 'selected' : '').' value="'.$key.'">'.$value.'</option>';
        }
        ?>
      </select>
    </div>
    <div class="formPart inactive" id="scheduleType">
      <select name="days" id="days" required>
        <option value="">Select a schedule</option>
        <option value="M1">Weekday</option>
        <option value="M3">Saturday</option>
        <option value="M4">Sunday</option>
      </select>
    </div>
    <div class="formPart inactive" id="trip">
      <select name="origin" id="origin" required>
        <option value="" selected>Select a station</option>
      </select>
      <span class="transitArrow">&rarr;</span>
      <select name="destination" id="destination" required>
        <option value="" selected>Select a station</option>
      </select>
    </div>
    <div class="formPart inactive" id="submitter">
      <input type="submit" value="Submit">
    </div>
  </form>


  <div class="scheduleWrap<?php if(isset($_GET['destination'])) echo ' in'; ?>">
    <?php include 'schedule.php'; ?>
  </div>

  <script src="js/jquery.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
