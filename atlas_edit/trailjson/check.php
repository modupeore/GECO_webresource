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
    echo '<table><tr><td align="right">';
    foreach ($yummy[$_SESSION['select']] as $sabc) {
      echo "<table><tr><th>Line</th><th>Htseq</th><th>Cufflinks</th></tr>";
      echo "<tr><td>Ross</td><td>".$sabc['Rhtseq']."</td><td>".$sabc['Rcuff']."</td></tr>";
      echo "<tr><td>Illinois</td><td>".$sabc['Ihtseq']."</td><td>".$sabc['Icuff']."</td></tr>";
      echo "</table><br>";
      break;
    }
    echo '</td></tr><tr></tr><tr><td>';
    echo '<table border="solid black">';
    echo '<tr><th></th><th colspan=4>Chromosomal Location</th><th colspan=5>Ross</th><th colspan=5>Illinois</th></tr>';
          
          
    echo '<tr><th></th><th>Chr</th><th>Start</th><th>Stop</th><th>Orn</th><th>MLWL</th>
          <th>GY</th><th>YN</th><th>MYN</th><th>MA</th><th>MLWL</th>
          <th>GY</th><th>YN</th><th>MYN</th><th>MA</th></tr>';
    $number = 0; $list = array(); $tag = 0;
    foreach ($yummy[$_SESSION['select']] as $sabc) {
      $number += 1;
      
      print "<tr><td>$number</td><td>".$sabc['chr']."</td><td>".$sabc['start']."</td><td>".$sabc['stop']."</td><td>". $sabc['orn']."</td><td>".
            $sabc['Rmlwl']."</td><td>". $sabc['Rgy']."</td><td>".$sabc['Ryn']."</td><td>".$sabc['Rmyn']."</td><td>".
            $sabc['Rma']."</td><td>".$sabc['Imlwl']."</td><td>". $sabc['Igy']."</td><td>".$sabc['Iyn']."</td><td>".
            $sabc['Imyn']."</td><td>".$sabc['Ima']."</td></tr>";
      $naming = $_SESSION['select']."-".$number;
      #Graph image options
      if ($sabc['Rmlwl'] == 50) {$sabc['Rmlwl'] = 0;} if ($sabc['Imlwl'] == 50) {$sabc['Imlwl'] = 0;}
      if ($sabc['Rgy'] == 50) {$sabc['Rgy'] = 0;} if ($sabc['Igy'] == 50) {$sabc['Igy'] = 0;}
      if ($sabc['Ryn'] == 50) {$sabc['Ryn'] = 0;} if ($sabc['Iyn'] == 50) {$sabc['Iyn'] = 0;}
      if ($sabc['Rmyn'] == 50) {$sabc['Rmyn'] = 0;} if ($sabc['Imyn'] == 50) {$sabc['Imyn'] = 0;}
      if ($sabc['Rma'] == 50) {$sabc['Rma'] = 0;} if ($sabc['Ima'] == 50) {$sabc['Ima'] = 0;}
      
      $Rsum = 0; $Rsum = (($sabc['Rmlwl']+$sabc['Rgy']+$sabc['Ryn']+$sabc['Rmyn']+$sabc['Rma'])/5);
      $Isum = 0; $Isum = (($sabc['Imlwl']+$sabc['Igy']+$sabc['Iyn']+$sabc['Imyn']+$sabc['Ima'])/5);
      
      #pushing to the big array
      array_push($list,$tag,$naming,$Rsum,$Isum);
      $tag += 1;
    }
    echo "</table>";
    echo '</td></tr></table>';
    #formating the array to be php plot friendly
    $result = count($list); $x = 0;
    while ($x < $result-1) {
      $_SESSION['kaksdata'][$list[$x]] = array($list[$x+1], $list[$x+2], $list[$x+3]);
      $x = $x+4;
    }

    echo '<form id="query" class="top-border" target="_blank" action="../picturetype.php" method="post">';
    echo '<center><input type="submit" name="result" value="view image"/></center>';
    echo '</form>';
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
