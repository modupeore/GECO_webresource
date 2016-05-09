<?php include("header.php") ?>
<?php echo '<table class="result"><tr class="result"><td>'; ?>
<form method="POST" action="index.php">
<?PHP echo "<table><tr><td>"; ?>
<!--1-->
Bird Number :
<?PHP echo "</td><td>"; ?>
<input type="text" name="birdno" size="30" placeholder="Bird number (eg. 343)"/>
<?PHP echo "</td></tr><tr><td>"; ?>
<!--2-->
Species :
<?PHP echo "</td><td>"; ?>
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
<?PHP echo "</td></tr><tr><td>"; ?>
<!--3-->
Line :
<?PHP echo "</td><td>"; ?>
<input type="text" name="line" required=true size="30" placeholder="Chicken Line (eg. Ross 708)"/>
<span class="error">*</span>
<?PHP echo "</td></tr><tr><td>"; ?>
<!--4-->
Tissue :
<?PHP echo "</td><td>"; ?>
<input type="text" name="tissue" required=true size="30" placeholder="Tissue (eg. kidney)"/>
<span class="error">*</span>
<?PHP echo "</td></tr><tr><td>"; ?>
<!--5-->
Method :
<?PHP echo "</td><td>"; ?>
<select name="method">
    <option value="rna_seq">rna_seq</option>
    <option value="micro_seq">micro_seq</option>
    <option value="index_seq">index_seq</option>
    <option value="16s">16s</option>
</select>
<?PHP echo "</td></tr><tr><td>"; ?>
<!--6-->
Chip Result :
<?PHP echo "</td><td>"; ?>
<select name="chipresult">
    <option value=""></option>
    <option value="accept">accept</option>
    <option value="reject">reject</option>
</select>
<?PHP echo "</td></tr><tr><td>"; ?>
<!--7-->
Scientist :
<?PHP echo "</td><td>"; ?>
<input type="text" name="scientist" required=true size="30" placeholder="Your name (e.g. Blair S.)"/>
<span class="error">*</span>
<?PHP echo "</td></tr><tr><td>"; ?>
<!--8-->
Notes : 
<?PHP echo "</td><td>"; ?>
<textarea name="notes" rows="3" cols="30" placeholder="Notes (e.g. Fall 2012 Control D42)"></textarea>
<?PHP echo "</td></tr><tr><td>"; ?>
<center><input type="submit" value="INSERT"/></center>
<?PHP echo "</td></tr></table>";
?>
</form>

<!--Results column-->
<?php
if(!empty($_POST["line"])){
echo '</td><td class="result">';
echo "<strong><font size=5>Meta-data Entry</font></strong><br>";
?>
<?PHP echo "<table><tr><td>"; ?>
<!--1-->
Bird Number :
<?PHP echo "</td><td>"; echo $_POST["birdno"]; echo "</td></tr><tr><td>"; ?>
<!--2-->
Species :
<?PHP echo "</td><td>"; echo $_POST["species"]; echo "</td></tr><tr><td>"; ?>
<!--3-->
Line :
<?PHP echo "</td><td>"; echo $_POST["line"]; echo "</td></tr><tr><td>"; ?>
<!--4-->
Tissue :
<?PHP echo "</td><td>"; echo $_POST["tissue"]; echo "</td></tr><tr><td>"; ?>
<!--5-->
Method :
<?PHP echo "</td><td>"; echo $_POST["method"]; echo "</td></tr><tr><td>"; ?>
<!--6-->
Chip Result :
<?PHP echo "</td><td>"; echo $_POST["chipresult"]; echo "</td></tr><tr><td>"; ?>
<!--7-->
Scientist :
<?PHP echo "</td><td>"; echo $_POST["scientist"]; echo "</td></tr><tr><td>"; ?>
<!--8-->
Notes : 
<?PHP echo "</td><td>"; echo $_POST["notes"]; echo "</td></tr></table>"; ?>

<form action="index.php" method="POST">
<!--<input type="hidden" name="value" value="true"/>-->
<center><input type="submit" value="ACCEPT"/></center>
</form>

<?PHP
echo "</td></tr><tr><td>";

//if(!empty($_POST["value"])){
//    echo '<center>';
//    $script = "perl $path/SQLscripts/html-genelistquery.pl";
//    echo "$script";
//    echo '</center>';
//}

echo "</td></tr></table>";
?>
<?php include("footer.php"); ?>


