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
	if(is_numeric($library)){
		//$lib_list .= escapeshellcmd(escapeshellarg( (string)(int) $library )).",";
		$lib_list .= test_input($library).",";
	}
	else {
		echo htmlspecialchars($library)." is not a valid library! <br><br>";
	}
}


$lib_list = rtrim($lib_list, ",");
echo "libraries entered: ".$lib_list;

$date = shell_exec("date +%Y-%m-%d-%T");
$explodedate = substr($date,0,-1);
$genelist = "gene-list_".$explodedate.".txt";
$chrlist = "chr-list_".$explodedate.".txt";

$output1 = "$base_path/OUTPUT/$genelist";
$output2 = "$base_path/OUTPUT/$chrlist";

shell_exec("perl $base_path/SQLscripts/html-genelistquery.pl -1 ".$lib_list." -2 ".$output1." -3 ".$output2."");
					



$genes =  multiexplode(array("\t", "\r", "\n"), file_get_contents($output1));


echo "<table> <tr> <td><table border=\"1\" >";
$count = 0;
$rowLen = 1 + count($libs);
foreach($genes as $gene){
	if ($count == 0){
		echo "<tr>";
	}
	echo "\n<td>".$gene."</td>";
	$count += 1;
	if ($count == $rowLen+1){
		echo "</tr>";
		$count = 0;
	}
}
echo "</table></td>";

//if(file_exists("$output1")) {
//	header('Content-Disposition: attachment; filename='."\"$name\"");
//	readfile($output1);   
//}
					
//$chrs =  file_get_contents($base_path."/OUTPUT/chr-list.txt");
//
//echo "<td><table border=\"1\" >";
//$count = 0;
//$rowLen = 1 + count($libs);
//foreach($chrs as $chr){
//        if ($count == 0){
//                echo "<tr>";
//        }
//        echo "\n<td>".$chr."</td>";
//        $count += 1;
//        if ($count == $rowLen+1){
//                echo "</tr>";
//                $count = 0;
//        }
//}
//echo "</table></td></tr></table>";


?>
