<?php
session_start();
require_once('requiredall.php');
$database = "transcriptatlas";
$table = "bird_libraries";
display_header($database);
?>
<?php
$db_conn = db_connect('localhost', $database);
$query = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'";
$result = $db_conn->query($query);
// create array of primary keys
$primary_key = null;
while ($row = $result->fetch_assoc()) {
	$primary_key[] = $row['Column_name'];
}
if (!$primary_key) {
	echo "<div><strong>NO PRIMARY KEY!\nFUNCTIONS WILL NOT WORK AS EXPECTED!</strong></div>";
}
// get total number of rows in table
$query = "SELECT * FROM $table";
$all_rows = $db_conn->query($query);
$total_rows = $all_rows->num_rows;

// create insert functionality
$query = "SELECT * FROM $table";
$all_rows = $db_conn->query($query);
echo "<form action=\"new_db.php\" method=\"post\">";
?>
<table>
<tr><td class="border"></td>
<th class="border"><strong><font size=2px>bird_id</font></strong></th>
<th class="border"><strong><font size=2px>species</font></strong></th>
<th class="border"><strong><font size=2px>line</font></strong></th>
<th class="border"><strong><font size=2px>tissue</font></strong></th>
<th class="border"><strong><font size=2px>method</font></strong></th>
<th class="border"><strong><font size=2px>index</font></strong></th>
<th class="border"><strong><font size=2px>chip_result</font></strong></th>
<th class="border"><strong><font size=2px>scientist</font></strong></th>
<th class="border"><strong><font size=2px>notes</font></strong></th>
</tr><tr>
<?php
// create accept functionality
echo '<td class="border"> <input type="submit" name="accept" value="insert"/></td>';
echo '<td class="border"><input type="text" name="bird_id"/></td>';	// bird_id
echo '<td class="border"><select name="species">
	<option value="gallus">gallus</option>
	<option value="bos_taurus">bos_taurus</option>
	<option value="mus_musuclus">mus_musculus</option>
	<option value="meleagris_gallopavo">meleagris_gallopavo</option>
	<option value="anatidae">anatidae</option>
	<option value="alligator_mississippiensis">alligator_mississippiensis</option>
	<option value="homo_sapiens">homo_sapiens</option>
	<option value="tardigrade">tardigrade</option>
	</select></td>'; //species
echo '<td class="border"><input type="text" name="line" required=true/></td>'; //line
echo '<td class="border"><input type="text" name="tissue"/></td>'; //tissue
echo '<td class="border">
        <select name="method">
        <option value="rna_seq">rna_seq</option>
        <option value="micro_seq">micro_seq</option>
        <option value="index_seq">index_seq</option>
        <option value="16s">16s</option>
        </select></td>'; //method
echo '<td class="border"><input type="text" name="index_"/></td>'; //index
echo '<td class="border">
        <select name="chip_result">
        <option value=""></option>
        <option value="accept">accept</option>
        <option value="reject">reject</option>
        </select></td>'; //chip_result
echo '<td class="border"><input type="text" name="scientist"/></td>'; //scientist
echo '<td class="border"><input type="text" name="notes"/></td>'; //note		
?>
</tr>
</table>
</form>
<?php
// delete from table
//db_delete($primary_key, $table, $db_conn);
// verify the input
db_accept($table, $db_conn);
//insert into table
db_insert($table, $db_conn);
?>


<br><hr><br>
<?php

//create query for DB display
if (!empty($_REQUEST['order'])) {
    // if the sort option was used
    $_SESSION[$table]['sort'] = $_POST['sort'];
    $_SESSION[$table]['dir'] = $_POST['dir'];
    $_SESSION[$table]['num_recs'] = $_POST['num_recs'];

    $terms = explode(",", $_POST['search']);
    $is_term = false;
    foreach ($terms as $term) {
        if (trim($term) != "") {
            $is_term = true;
        }
    }
    $_SESSION[$table]['select'] = $terms;
    $_SESSION[$table]['column'] = $_POST['column'];

    $query = ("SELECT * FROM $table ");
    if ($is_term) {
        $query .= "WHERE ";
    }
    foreach ($_SESSION[$table]['select'] as $term) {
        if (trim($term) == "") {
            continue;
        }
        $query .= $_SESSION[$table]['column'] . " LIKE '%" . trim($term) . "%' OR ";
    }
    $query = rtrim($query, " OR ");
    $query .= " ORDER BY " . $_SESSION[$table]['sort'] . " " . $_SESSION[$table]['dir'];

    $result = $db_conn->query($query);
    $num_total_result = $result->num_rows;
    if ($_SESSION[$table]['num_recs'] != "all") {
        $query .= " limit " . $_SESSION[$table]['num_recs'];
    }
    unset($_SESSION[$table]['txt_query']);
} elseif (!empty($_SESSION[$table]['sort'])) {
    $is_term = false;
    foreach ($_SESSION[$table]['select'] as $term) {
        if (trim($term) != "") {
            $is_term = true;
        }
    }
    $query = ("SELECT * FROM $table ");
    if ($is_term) {
        $query .= "WHERE ";
    }
    foreach ($_SESSION[$table]['select'] as $term) {
        if (trim($term) == "") {
            continue;
        }
        $query .= $_SESSION[$table]['column'] . " LIKE '%" . trim($term) . "%' OR ";
    }
    $query = rtrim($query, " OR ");
    $query .= " ORDER BY " . $_SESSION[$table]['sort'] . " " . $_SESSION[$table]['dir'];
    $result = $db_conn->query($query);
    $num_total_result = $result->num_rows;

    if ($_SESSION[$table]['num_recs'] != "all") {
        $query .= " limit " . $_SESSION[$table]['num_recs'];
    }
} else {
    // if this is the first time, then just order by line and display all rows //default
        $query = "SELECT * FROM $table ORDER BY $primary_key[0] desc limit 10";
}
$result = $db_conn->query($query);
if ($db_conn->errno) {
    echo "<div id=\"index-error\"";
    echo "<span><strong>Error with query.</strong></span>";
    echo "<span><strong>Error number: </strong>$db_conn->errno</span>";
    echo "<span><strong>Error string: </strong>$db_conn->error</span>";
    echo "</div>";
}
$num_results = $result->num_rows;
if (empty($_SESSION[$table]['sort'])) {
    $num_total_result = $num_results;
}
?>
<?php
echo '<form id="sort" class="top-border" action="new_db.php" method="post">';
?>
<div>
    <span>Search for: </span>
    <?php
    if (!empty($_SESSION[$table]['select'])) {
        echo "<input type=\"text\" name=\"search\" value=\"" . implode(",", $_SESSION[$table]['select']) . "\"/>";
    } else {
        echo "<input type=\"text\" name=\"search\"/>";
    }
    ?>
    <span> in </span>
    <select name="column">
        <?php
        echo '<option value="library_id">library_id</option>
		<option value="bird_id">bird_id</option>
		<option value="species">species</option>
		<option value="line">line</option>
		<option value="tissue">tissue</option>
		<option value="method">method</option>
		<option value="index_">index</option>
		<option value="chip_result">chip_result</option>
		<option value="scientist">scientist</option>
		<option value="date">date</option>
		<option value="notes">notes</option>';
        ?>
    </select>
</div>
<div>
<span>Sort by:</span>
<select name="sort">
<?php
echo '<option value="library_id">library_id</option>
<option value="bird_id">bird_id</option>
<option value="species">species</option>
<option value="line">line</option>
<option value="tissue">tissue</option>
<option value="method">method</option>
<option value="index_">index</option>
<option value="chip_result">chip_result</option>
<option value="scientist">scientist</option>
<option value="date">date</option>
<option value="notes">notes</option>';

?> 
</select>
<select name="dir">
<?php
if (!empty($_SESSION[$table]['dir'])) {
	if ($_SESSION[$table]['dir'] == "desc") {
		echo '<option value="asc">ascending</option>';
		echo '<option selected value="desc">descending</option>';
	}
} else {
	echo '<option value="asc">ascending</option>';
	echo '<option value="desc">descending</option>';
}
?>
</select>
<span>and show</span>
<select name="num_recs">
<option value="10">10</option>
<?php
if (empty($_SESSION[$table]['num_recs'])) {
	$_SESSION[$table]['num_recs'] = "10";
}
if ($_SESSION[$table]['num_recs'] == "20") {
	echo '<option selected value="20">20</option>';
} else {
	echo '<option value="20">20</option>';
}
if ($_SESSION[$table]['num_recs'] == "50") {
	echo '<option selected value="50">50</option>';
} else {
	echo '<option value="50">50</option>';
}
if ($_SESSION[$table]['num_recs'] == "all") {
	echo '<option selected value="all">all</option>';
} else {
	echo '<option value="all">all</option>';
}
?> 
</select>
<span>records.</span>
<input type="submit" name="order" value="Go"/>
</div>
</form>
<br/>
<div id="db-display" class="top-border">
<?php
echo "<span>" . $num_results . " out of " . $num_total_result . " search results displayed. (" . $total_rows . " total rows)</span>";
db_display("new_db.php", $result, $primary_key);
?>
</div>
<?php
$result->free();
$db_conn->close();
?>
</div>

		
<?php
echo "</body>";
echo "</html>";
?>
