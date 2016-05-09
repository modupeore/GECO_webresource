<!DOCTYPE HTML> 
<html>
<body>
<?php
$list = shell_exec("ls -la OUTPUT/*");
$newlist = explode("txt", $list);
foreach ($newlist as $line){ 
	echo "$line<br>";
}
shell_exec("rm -rf OUTPUT/*");
echo "Removed all";
?>
</body>
</html>
