<?php
  session_start();
  echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
  echo "<title>Variant analysis</title>";
  echo '<script type="text/javascript" src="//code.jquery.com/jquery-1.8.3.js"></script>';
  echo "<style type= 'text/css'>";
  echo '</style>';
  
  $json = file_get_contents('includechromdetail.json');
  $yummy = json_decode($json,true);
  echo '<form id="query" class="top-border" action="check.php" method="post">'; 
?>
  <link href="../jquery/jquery-ui-1.11.4.custom/jquery-ui.min.css" rel="stylesheet" type="text/css" />
  <script src="../jquery/jquery-1.11.3.min.js"></script>
  <script src="../jquery/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
  <script>
    $(function() {
            var availableTags = <?php include("../names/chickenall.php"); ?>;
            $("#genename").autocomplete({
              source: availableTags,
              autoFocus:true
            });
          });
  </script>
    <p class="pages"><span>Specify your gene name: </span>
      <?php

        if (!empty($_POST['reveal']) && !empty($_POST['select'])) { /* GeneName */
           $_SESSION['select'] = $_POST['select'];
        }
  
          if (!empty($_SESSION['select'])) {
            echo '<input type="text" name="select" id="genename" value="' . $_SESSION["select"] . '"/>';
          } else {
            echo '<input type="text" name="select" id="genename" placeholder="Enter Gene Name" />';
          }
      ?>
      </p><br>
    <center><input type="submit" name="reveal" value="View Results"></center>
  </form></div> 
     <?php
  if (isset($_POST['reveal']) && !empty($_SESSION['select'])) {
    echo '<table border="solid black"><tr><th></th><th>Chr</th><th>Start</th><th>Stop</th><th>Orn</th><th>Rhtseq</th><th>Ihtseq</th>
          <th>Rcuff</th><th>Icuff</th><th>Rmlwl</th><th>Imlwl</th><th>Rgy</th><th>Igy</th><th>Ryn</th><th>Iyn</th>
          <th>Rmyn</th><th>Imyn</th><th>Rma</th><th>Ima</th></tr>';
    $number = 0; $list = array();
    foreach ($yummy[$_SESSION['select']] as $sabc) {
      $number += 1;
      $Rsum = 0; $Rsum = (($sabc['Rmlwl']+$sabc['Rgy']+$sabc['Ryn']+$sabc['Rmyn']+$sabc['Rma'])/5);
      $Isum = 0; $Isum = (($sabc['Imlwl']+$sabc['Igy']+$sabc['Iyn']+$sabc['Imyn']+$sabc['Ima'])/5);
      print "<tr><td>$number</td><td>".$sabc['chr']."</td><td>".$sabc['start']."</td><td>".$sabc['stop']."</td><td>". $sabc['orn']."</td><td>".
            $sabc['Rhtseq']."</td><td>".$sabc['Ihtseq']."</td><td>".$sabc['Rcuff']."</td><td>". $sabc['Icuff']."</td><td>".
            $sabc['Rmlwl']."</td><td>". $sabc['Imlwl']."</td><td>".$sabc['Rgy']."</td><td>".$sabc['Igy']."</td><td>".
            $sabc['Ryn']."</td><td>".$sabc['Iyn']."</td><td>". $sabc['Rmyn']."</td><td>".$sabc['Imyn']."</td><td>".
            $sabc['Rma']."</td><td>".$sabc['Ima']."</td></tr>";
            $naming = $_SESSION['select']."-".$number;
            array_push($list,$number,$naming,$Rsum,$Isum);
    }
    echo "</table>";
    $result = count($list); $x = 0;
    while ($x < $result-1) {
      $data[$list[$x]] = array($list[$x+1], $list[$x+2], $list[$x+3]);
      $x = $x+4;
    }
    ?>
    <?php
    require_once 'phplot.php';
    $s1 = array("gene-1", -3, 4); $s2 = array("gene-1", 4, 6);
    $data = array( $s1, $s2, );
$plot = new PHPlot(400, 300);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($data);

# Main plot title:
$plot->SetTitle('Shaded Bar Chart with 3 Data Sets');

# Make a legend for the 3 data sets plotted:
$plot->SetLegend(array('Ross', 'Illinois'));

# Turn off X tick labels and ticks because they don't apply here:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->DrawGraph();

  }

     /*
     foreach ($yummy[$_SESSION['select']] as $sabc) {
        print $sabc['chr']."\t".$sabc['start']."\t".$sabc['stop']."\t". $sabc['orn']."\t".$sabc['Rhtseq']."\t".
              $sabc['Ihtseq']."\t".$sabc['Rcuff']."\t". $sabc['Icuff']."\t".$sabc['Rmlwl']."\t". $sabc['Imlwl']."\t".
              $sabc['Rgy']."\t".$sabc['Igy']."\t". $sabc['Ryn']."\t".$sabc['Iyn']."\t". $sabc['Rma']."\t".$sabc['Ima']."<br>";
     }*/
  

        /*var model = data.cars[make][i].model;
        var doors = data.cars[make][i].doors;
        alert(make + ', ' + model + ', ' + doors);
    }
    
    for (var i = 0; i < data.cars[make].length; i++) {
        var model = data.cars[make][i].model;
        var doors = data.cars[make][i].doors;
        alert(make + ', ' + model + ', ' + doors);
    }*/


/*
<!--
<!DOCTYPE html>
<html>
<body>

<h2>JSON Object Creation in JavaScript</h2>

<p id="demo"></p>

<script>
var text = '{"name":"John Johnson","street":"Oslo West 16","phone":"555 1234567"}';

var obj = JSON.parse(text);

document.getElementById("demo").innerHTML =
obj.name + "<br>" +
obj.street + "<br>" +
obj.phone;
</script>

</body>
</html>
-->
*/
?>
