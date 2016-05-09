<?php

$base_path = "/var/www/html/testing";

$libraries = $_POST['libraries'];

$lib_list = "";
$libs = explode(",", $libraries);
foreach($libs as $library) {
	if(is_numeric($library)){
		$lib_list .= escapeshellcmd(escapeshellarg( (string)(int)$library )).",";
	}
	else {
		echo htmlspecialchars($library)." is not a valid library! <br><br>";
	}
}

$lib_list = rtrim($lib_list, ",");
echo "libraries entered: ".$lib_list;

shell_exec("perl genelistquery.pl -1 ".$lib_list." -2 ".$base_path."");

function multiexplode ($delimiters,$string) {
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}


$genes =  multiexplode(array("\t", "\r", "\n"), file_get_contents($base_path."/gene-list.txt"));
/*
foreach($genes as $gene){
	echo "<br>".$gene;
}*/


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

$chrs =  file_get_contents($base_path."/chr-list.txt");

echo "<td><table border=\"1\" >";
$count = 0;
$rowLen = 1 + count($libs);
foreach($chrs as $chr){
        if ($count == 0){
                echo "<tr>";
        }
        echo "\n<td>".$chr."</td>";
        $count += 1;
        if ($count == $rowLen+1){
                echo "</tr>";
                $count = 0;
        }
}
echo "</table></td></tr></table>";


?>
