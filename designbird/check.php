<?php
  session_start();
  echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
  echo "<title>Variant analysis</title>";
  echo '<script type="text/javascript" src="//code.jquery.com/jquery-1.8.3.js"></script>';
  echo "<style type= 'text/css'>";
  echo '</style>'
$json = file_get_contents('data.txt');
$yummy = json_decode($json,true)
?>
  <link href="jquery/jquery-ui-1.11.4.custom/jquery-ui.min.css" rel="stylesheet" type="text/css" />
  <script src="jquery/jquery-1.11.3.min.js"></script>
  <script src="jquery/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
  <script>
    $(function() {
            var availableTags = <?php include("data.php"); ?>;
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
            echo '<input type="text" name="select" id="genename" value="' . $_POST["select"] . '"/>';
          } else {
            echo '<input type="text" name="select" id="genename" placeholder="Enter Gene Name" />';
          }
      ?>
      </p><br>
    <center><input type="submit" name="reveal" value="View Results" onClick="this.value='Sending… Please Wait'; style.backgroundColor = '#75684a'; this.form.submit();"></center>
  </div> 
     <?php
  if (isset($_POST['reveal']) && !empty($_SESSION['select'])) {
    echo $yummy['name']."<br>";

     foreach ($yummy[$_SESSION['select']] as $sabc) {
        print $sabc['id']."\t". $sabc['type']."<br>";
     }
  }

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
