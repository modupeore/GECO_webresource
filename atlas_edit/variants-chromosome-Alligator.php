<?php
  session_start();
  require_once('atlas_header.php'); //Display heads
  require_once('atlas_fns.php'); //All the routines
  d_var_header();
?>
  <div id="metamenu">
	<ul>
		<li><a href="variants-genename.php">Gene Name</a></li>
		<?php
            $phpscript = "variants-chromosome-".$_SESSION['genomename'].".php";
            echo "<li><a class='active' href=$path>Chromosomal Location</a></li>";
            ?>
	</ul>
</div>
  <div class="explain"><p><center>View variants based on a chromosomal location.</center></p></div>
<?php
  if (!empty($_REQUEST['reveal'])) {
    // if the sort option was used
    $_SESSION['chromposend'] = $_POST['chromposend'];
    $_SESSION['chrompos'] = $_POST['chrompos'];
    $_SESSION['chromposbegin'] = $_POST['chromposbegin'];
  }
  echo "<p class='pages'><span>Genome selected : ".$_SESSION['genomename']."</span>";
?>
  <input type="image" class="vbtn" src="images/return.png" align="texttop" alt="return" style="width:22px;height:22px;border-radius:30px;" value="variant" onclick="window.location.href='variants.php'"></span>
<?PHP
  echo '<div class="question">';
  echo '<form id="query" class="top-border" action="'.$phpscript.'" method="post">';
?>
  <div>
    <p class="pages"><span>Specify chromosomal location : </span> </p>
          <p class="pages"><span>Chromosome: </span>
            <?php
              if (!empty($_SESSION['chrompos'])) {
                echo '<input class = "vartext" type="text" name="chrompos" value="' . $_SESSION["chrompos"] . '"/>';
              } else {
                echo '<input type="text" name="chrompos" value="scaffold-" placeholder="Chromosome number (like: scaffold-1)" />';
              }
            ?>
          <span>Starting position: </span>
            <?php
              if (!empty($_SESSION['chromposbegin'])) {
                echo '<input class = "vartext" type="text" name="chromposbegin" value="' . $_SESSION["chromposbegin"] . '"/>';
              } else {
                echo '<input class = "vartext" type="text" name="chromposbegin" placeholder="Enter starting position" />';
              }
            ?>
          <span>Ending position: </span>
            <?php
              if (!empty($_SESSION['chromposend'])) {
                echo '<input class = "vartext" type="text" name="chromposend" value="' . $_SESSION["chromposend"] . '"/>';
              } else {
                echo '<input class = "vartext" type="text" name="chromposend" placeholder="Enter ending position" />';
              }
            ?>
          </p>
      <br>
    <center><input type="submit" name="reveal" value="View Results" onClick="this.value='Sending… Please Wait'; style.backgroundColor = '#75684a'; this.form.submit();"></center>
  </div>
</form>
</div>
<hr>
<?PHP
  if ((isset($_POST['reveal']) || isset($_POST['downloadvalues'])) && !empty($_SESSION['chrompos']) && !empty($_SESSION['chromposbegin']) && !empty($_SESSION['chromposend'])) {
    $output1 = "$base_path/OUTPUT/Voutput_".$explodedate.".par"; $output2 = "$base_path/OUTPUT/Voutput_".$explodedate.".txt";
    $pquery = 'perl '.$base_path.'/SQLscripts/fboutputvariantinfo.pl -c '.$_SESSION['chrompos'].' -b '.$_SESSION['chromposbegin'].' -e '.$_SESSION['chromposend'].' -s '.$_SESSION['species'].' -o '.$output1.'';
    shell_exec($pquery);
    $rquery = file_get_contents($output1);
    $dloadquery = file_get_contents($output2); shell_exec ("rm -f ".$output2);
    if (count(explode ("\n", $rquery)) <= 13){
      echo '<center>No results were found with your search criteria.<center>';
    } else {
      echo '<div class="gened">';
      echo '<form action="' . $phpscript . '" method="post">';
      echo '<p class="gened">Below are the variants found. ';
      echo '<input type="submit" name="downloadvalues" value="Download Results"/>';
      echo $rquery;
      if(isset($_POST['downloadvalues'])){
        file_put_contents($output2, $dloadquery);
        $filer = "file=$output2&name=variants.txt";
        print("<script>location.href='results.php?file=$output2&name=variants.txt'</script>");

      }
      echo '</form></div>';
    }
    shell_exec ("rm -f ".$output1); 
  }
?>
  </div>
  </div> <!--in header-->
  
</body>
</html>


