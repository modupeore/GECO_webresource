<?php
$filename = $_POST['genes'];
$name = $_POST['name'] .".txt";
header('Content-disposition: attachment; filename='."\"$name\"");
header('Content-type: text/plain');

readfile($filename);
?>

