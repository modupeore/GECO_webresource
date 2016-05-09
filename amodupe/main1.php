<?php
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
function multiexplode ($delimiters,$string) {
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

$base_path = "/home/modupeore17/public/amodupe";
$libraries = $_POST['comment'];
$lib_list = "";
$libs = explode(",", $libraries);
foreach($libs as $library) {
	$point = shell_exec("perl $base_path/SQLscripts/html-librarycheck.pl -1 $library");
	if(is_numeric($library)){
		if ($point == 1){
			$lib_list .= test_input($library).",";
		}
		else {
			echo htmlspecialchars($library)." is doesn't exist in the database! <br>";
		}
	}
	else {
		echo htmlspecialchars($library)." is not a valid library! <br>";
	}	
}
echo "<br>";

$lib_list = rtrim($lib_list, ",");
echo "libraries entered: ".$lib_list;

$date = shell_exec("date +%Y-%m-%d-%T");
$explodedate = substr($date,0,-1);
$genelist = "gene-list_".$explodedate.".txt";
$chrlist = "chr-list_".$explodedate.".txt";

$output1 = "$base_path/OUTPUT/$genelist";
$output2 = "$base_path/OUTPUT/$chrlist";

shell_exec("perl $base_path/SQLscripts/html-genelistquery.pl -1 ".$lib_list." -2 ".$output1." -3 ".$output2."");
?>
<form action="results.php" method="POST"> 
<div>
<?php
    echo "<input type=\"hidden\" name=\"genes\" value=\"$output1\"/>";
	echo "<input type=\"hidden\" name=\"name\" value=\"genes\" />";
?>
<input type="submit" name="gdownload" value="download gene list"/>
</div>
</form>
<form action="results.php" method="POST">
<?php
    echo "<input type=\"hidden\" name=\"genes\" value=\"$output2\"/>";
	echo "<input type=\"hidden\" name=\"name\" value=\"chromosome\" />";
?>
<input type="submit" name="cdownload" value="download chromosome list"/>
</form>
