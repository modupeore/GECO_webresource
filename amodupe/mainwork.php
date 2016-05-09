<!DOCTYPE HTML> 
<html>
	<head>
		<style>
			div {
    			padding: 0 50px 0 50px;
    			border: 15px;
    			margin: 50px;
    		}
    		.error {color: #FF0000;}
			table, th, td {
    			border: 1px solid black;
			}
			a:link {
				color: #0E287C;
			}
			a:visited {
				color: #FF0000;
			}
			a:hover {
				color: #FFF800;
			}
		</style>
	</head>
	<body> 
		<?php
			$commentErr =  "";
			$comment = "";
			$path = "/home/modupeore17/public/amodupe";
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (empty($_POST["comment"])) {
					$commentErr = "Library list is required";
				} else {
					$comment = test_input($_POST["comment"]);
					if (preg_match("/[a-zA-Z]+/",$comment)) {
						$commentErr = "Only library numbers allowed"; 
					}
				}
			}
			function test_input($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
		?>
		
		<p><span class="error">* required field.</span></p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["https://geco.iplantcollaborative.org/modupeore17/amodupe/mainwork.php"]);?>">  
			<textarea name="comment" rows="5" cols="40" placeholder="Enter your library numbers separated by space or new line"><?php echo $comment;?></textarea>
			<span class="error">* <?php echo $commentErr;?></span>
			<br><br>
			<input type="submit" name="submit" value="Submit"> 
		</form>
		<?php
			$date = shell_exec("date +%Y-%m-%d-%T");
			$explodedate = substr($date,0,-1);
			$genelist = "gene-list_".$explodedate.".txt";
			$chrlist = "chr-list_".$explodedate.".txt";
		?>
		<?php
			$check = 0;
			if (empty($_POST["comment"])) {
				$commentErr = "Library list is required";
			}
			else {
				$comment = test_input($_POST["comment"]);
				foreach (preg_split('/[\s]+/', $comment) as $oline){
					if (preg_match("/[0-9]+$/",$oline)) {   	
						$check = 1;
						$point = exec("perl $path/SQLscripts/html-librarycheck.pl -1 $oline");
						if ($point == 2){ print "Invalid library : $oline<br>";}
						if($point != 2){
							if(empty ($newcomment)){
								$newcomment = $oline;
							}
							else {
								$newcomment = $newcomment."AA".$oline;
							}
						} 
					} 
					else {
						$check = 2;
						break;
					}
				}
				if ($check == 1 && !empty ($newcomment)){
					print "<br> Successful <br>";
					print "<br> Working on libraries : <br>";
					$libs = 0;
					$explodedlibraries = explode("AA", $newcomment);
					foreach ($explodedlibraries as $line){  
						if (empty($var)) {
							$var = $line;
							echo '&nbsp&nbsp'.++$libs.'&nbsp&nbsp'."$line<br>";
						}
						else {
							$var = $var.",".$line;
							echo '&nbsp&nbsp'.++$libs.'&nbsp&nbsp'."$line<br>";
						}
					}
					$output1 = "$path/OUTPUT/$genelist";
					$output2 = "$path/OUTPUT/$chrlist";
					$script = "perl $path/SQLscripts/html-genelistquery.pl -1 $var -2 $output1 -3 $output2 ";
					
					shell_exec("$script");
					print "<h3> Results :</h3>";
					echo "<table cellpadding=\"20\">";
					echo "<tr>";
					
					$name = "GENELIST.txt";
					$name2 = "CHRLIST.txt";

					if(file_exists("$output1")) {
						echo "yes <br>";
						header('Content-Disposition: attachment; filename='."\"$name\"");
						readfile($output1);   
					}
					
// 					if(file_exists("$output2")){ 
// 						header('Content-Disposition: attachment; filename='."\"$name2\"");
// 						readfile($output2);      
// 					}

// copied for somewhere
// 					if (file_exists($output1)) {
// 						header('Content-Description: File Transfer');
// 						header('Content-Type: application/octet-stream');
// 						header('Content-Disposition: attachment; filename="'.basename($output1).'"');
// 						header('Expires: 0');
// 						header('Cache-Control: must-revalidate');
// 						header('Pragma: public');
// 						header('Content-Length: ' . filesize($output1));
// 						readfile($output1);
// 						exit;
// 					}

					#echo "<th><a href=$output1 download>Genes List</a></th>";
					#echo "<th><a href=$output2 download>Chromosomes List</a></th>";
					#echo "</tr></table><br><br>";
				}
			}
		?>
		</div>
	</body>
</html>
