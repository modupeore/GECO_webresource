<?php include("header.php") ?>

<div><p>You can provide a list of library IDs to compare the FPKM values
of all the genes and their chromosomal location.<br>This provides a
tab-delimited <em>".txt"</em> file to easily compare the genes FPKM values
across different samples.</p>
<form method="POST" action="queryresults.php">  
<p><label><h2>Your Libraries<span class="error">*</span></h2></p>
<textarea name="comment" rows="5" cols="40" required=true
		  placeholder="Enter your library numbers separated by space, new line or commas(,)">
</textarea>
</label>
<br><br>
<input type="submit" value="Submit"> 
</form>
</div>
<?php include("footer.php"); ?>

