<?php
include_once dirname(__FILE__).'/../api/rail.php';

//Make sure all required params exist
if(isset($_GET['line'])):

  //Get live train feed
  include 'trainview.php';

  ?>
  <table id="scheduleList" data-line="<?php echo $line; ?>">
    <!--Table header-->
    <tr>
      <th class="trainNo">Train</th>
      <th class="station"><?php echo $trips->stops->{$origin}->name; ?></th>
      <th class="arrow"></th>
      <th class="station"><?php echo $trips->stops->{$destination}->name; ?></th>
    </tr>

    <?php
    //Output schedule structure
    foreach($timeTable as $train):
      $startTime = date('g:i A', strtotime($train->stops[$origin]));
      $endTime = date('g:i A', strtotime($train->stops[$destination]));
      echo '<tr class="trainWrap" data-train="'.$train->train_number.'">';
        echo '<td class="trainNo">'.$train->train_number.'</td>';
        echo '<td class="station">'.$startTime.'</td>';
        echo '<td class="arrow">&rarr;</td>';
        echo '<td class="station">'.$endTime.'</td>';
      echo '</tr>';
    endforeach; ?>
  </table>
<?php endif; ?>
