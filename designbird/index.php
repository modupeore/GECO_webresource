<?php include("header.php") ?>
<?php echo '<table class="result"><tr class="result"><td>'; ?>
<form method="POST" action="index.php">
<?PHP echo '<table class="form"><tr class="form"><td class="form">'; ?>
<!--1-->
Bird Number :
<?PHP echo '</td><td class="form">'; ?>
<input type="text" name="birdno" size="30" placeholder="Bird number (eg. 343)"/>
<?PHP echo '</td></tr><tr class="form"><td class="form">'; ?>
<!--2-->
Species :
<?PHP echo '</td><td class="form">'; ?>
<select name="species">
    <option value="gallus">gallus</option>
    <option value="bos_taurus">bos_taurus</option>
    <option value="mus_musuclus">mus_musculus</option>
    <option value="meleagris_gallopavo">meleagris_gallopavo</option>
    <option value="anatidae">anatidae</option>
    <option value="alligator_mississippiensis">alligator_mississippiensis</option>
    <option value="homo_sapiens">homo_sapiens</option>
    <option value="tardigrade">tardigrade</option>
</select>
<?PHP echo '</td></tr><tr class="form"><td class="form">'; ?>
<!--3-->
Line :
<?PHP echo '</td><td class="form">'; ?>
<input type="text" name="line" required=true size="30" placeholder="Chicken Line (eg. Ross 708)"/>
<input type="hidden" name="value" value="1"/>
<span class="error">*</span>
<?PHP echo '</td></tr><tr class="form"><td class="form">'; ?>
<!--4-->
Tissue :
<?PHP echo '</td><td class="form">'; ?>
<input type="text" name="tissue" required=true size="30" placeholder="Tissue (eg. kidney)"/>
<span class="error">*</span>
<?PHP echo '</td></tr><tr class="form"><td class="form">'; ?>
<!--5-->
Method :
<?PHP echo '</td><td class="form">'; ?>
<select name="method">
    <option value="rna_seq">rna_seq</option>
    <option value="micro_seq">micro_seq</option>
    <option value="index_seq">index_seq</option>
    <option value="16s">16s</option>
</select>
<?PHP echo '</td></tr><tr class="form"><td class="form">'; ?>
<!--6-->
Chip Result :
<?PHP echo '</td><td class="form">'; ?>
<select name="chipresult">
    <option value=""></option>
    <option value="accept">accept</option>
    <option value="reject">reject</option>
</select>
<?PHP echo '</td></tr><tr class="form"><td class="form">'; ?>
<!--7-->
Scientist :
<?PHP echo '</td><td class="form">'; ?>
<input type="text" name="scientist" required=true size="30" placeholder="Your name (e.g. Blair S.)"/>
<span class="error">*</span>
<?PHP echo '</td></tr><tr class="form"><td class="form">'; ?>
<!--8-->
Notes : 
<?PHP echo '</td><td class="form">'; ?>
<textarea name="notes" rows="3" cols="30" placeholder="Notes (e.g. Fall 2012 Control D42)"></textarea>
<?PHP echo '</td></tr><tr class="form"><td class="form">'; ?>
<center><input type="submit" value="INSERT"/></center>
<?PHP echo "</td></tr></table>";
?>
</form>

<!--Results column-->
<?php
$birdnumber = $_POST["birdno"];
$species = $_POST["species"];
$line = $_POST["line"];
$tissue = $_POST["tissue"];
$method = $_POST["method"];
$chipresult = $_POST["chipresult"];
$scientist = $_POST["scientist"];
$notes = $_POST["notes"];

if($_POST["value"] == 1){
    if(!empty($_POST["line"])){
    echo '</td><td class="result"><strong><font size=5>Prospective Meta-data Entry</font></strong><br><br><table><tr><td>';
?>
    <!--1-->
    Bird Number : <?PHP echo "</td><td>$birdnumber</td></tr><tr><td>"; ?>
    <!--2-->
    Species : <?PHP echo "</td><td>$species</td></tr><tr><td>"; ?>
    <!--3-->
    Line : <?PHP echo "</td><td>$line</td></tr><tr><td>"; ?>
    <!--4-->
    Tissue : <?PHP echo "</td><td>$tissue</td></tr><tr><td>"; ?>
    <!--5-->
    Method : <?PHP echo "</td><td>$method</td></tr><tr><td>"; ?>
    <!--6-->
    Chip Result : <?PHP echo "</td><td>$chipresult</td></tr><tr><td>"; ?>
    <!--7-->
    Scientist : <?PHP echo "</td><td>$scientist</td></tr><tr><td>"; ?>
    <!--8-->
    Notes : <?PHP echo "</td><td>$notes</td></tr></table>"; ?>

    <form action="index.php" method="POST">
    <?php
    echo "<input type=\"hidden\" name=\"value\" value=\"2\"/>";
    echo "<input type=\"hidden\" name=\"birdno\" value=\"$birdnumber\"/>";
    echo "<input type=\"hidden\" name=\"species\" value=\"$species\"/>";
    echo "<input type=\"hidden\" name=\"line\" value=\"$line\"/>";
    echo "<input type=\"hidden\" name=\"tissue\" value=\"$tissue\"/>";
    echo "<input type=\"hidden\" name=\"method\" value=\"$method\"/>";
    echo "<input type=\"hidden\" name=\"chipresult\" value=\"$chipresult\"/>";
    echo "<input type=\"hidden\" name=\"scientist\" value=\"$scientist\"/>";
    echo "<input type=\"hidden\" name=\"notes\" value=\"$notes\"/>";
    $db_conn = new mysqli('localhost', 'frnakenstein', 'maryshelley','transcriptatlas');
    $db_result = $db_conn->query("select max(cast(library_id as unsigned)) max from bird_libraries");
    if ($db_conn->connect_error) {
        die("Connection failed: " . $db_conn->connect_error);
        exit;
    }
    if ($db_result->num_rows > 0) {
        while($row = $db_result->fetch_assoc()) {
            $maxnumber = $row["max"];
        }
    }
    $maxnumber = $maxnumber+1;
    $db_conn->close();
    
    echo "<input type=\"hidden\" name=\"maximum\" value=\"$maxnumber\"/>";
    ?>
    <center><input type="submit" value="ACCEPT"/></center>
    </form>
    
<?PHP
    echo '</td></tr></table>';
    }
}
elseif($_POST["value"] == 2){
    $library_id = $_POST["maximum"]."DE";
    $time = exec('TZ=EST date +%F+%R:%S'); $midtime = explode('+', $time,2); $date="$midtime[0] $midtime[1]";
    $db_conn = new mysqli('localhost', 'frnakenstein', 'maryshelley','transcriptatlas');
    $query = "insert into bird_libraries (library_id,species,tissue,line,scientist,method,chip_result,date,notes,bird_id) values ('".
            $library_id."','".
            $_POST["species"]."','".
            $_POST["tissue"]."','".
            $_POST["line"]."','".
            $_POST["scientist"]."','".
            $_POST["method"]."','".
            $_POST["chipresult"]."','".
            $date."','".
            $_POST["notes"]."','";
            (($_POST["birdno"]=='')? :("'".$_POST["birdno"]."'")) . ")";
 
    $db_result = $db_conn->query($query);
    if (!$db_result) {
            echo '</td><td class="result">';
            echo "<span><strong>Insert unsuccessful.</strong></span><br>";
            echo "<span><strong>Query: </strong>$query</span><br>";
            echo "<span><strong>Errormessage: </strong>" . $db_conn->error . "</span>";
            echo "</td></tr></table>";
    }
    else {
        echo '</td><td class="result">';
        echo "<strong><font size=5>Successful Meta-data Entry</font></strong><br><br><br>";
        echo "<table><tr><td>";
        echo "<font size=4><strong>Library_id : </td><td>".$_POST["maximum"]. "</strong></td></tr><tr><td>";
        echo "Bird number : </td><td>".$_POST["birdno"]. "</td></tr><tr><td>";
        echo "Species : </td><td>".$_POST["species"]. "</td></tr><tr><td>";
        echo "Line : </td><td>".$_POST["line"]. "</td></tr><tr><td>";
        echo "Tissue : </td><td>".$_POST["tissue"]. "</td></tr><tr><td>";
        echo "Method : </td><td>".$_POST["method"]. "</td></tr><tr><td>";
        echo "Chip Result : </td><td>".$_POST["chipresult"]. "</td></tr><tr><td>";
        echo "Scientist : </td><td>".$_POST["scientist"]. "</td></tr><tr><td>";
        echo "Notes : </td><td>".$_POST["notes"]. "</td></tr><tr><td>";
        echo "Date : </td><td> $date</font><br>";
        //echo "<span>" . $db_conn->affected_rows . " row inserted into bird_libraries</span>";
    }
    $db_conn->close();
    
    echo "</td></tr></table>";
}
?>