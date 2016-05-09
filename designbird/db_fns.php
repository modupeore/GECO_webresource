<?php

function db_connect($host, $db='information_schema') {
    if ($db) {
        $db_conn = new mysqli($host, 'frnakenstein','maryshelley', $db);
    } else {
        $db_conn = new mysqli($host, 'frnakenstein','maryshelley');
    }

    if (mysqli_connect_errno()) {
        throw new Exception("Connection to database failed: " . mysqli_connect_error());
        return false;
    }
    return $db_conn;
}




function db_display_result($result) {
    $num_rows = $result->num_rows;
    ?>
    <table class="border">
        <?php
        echo "<tr>";
        $j = 0;
        while ($j < $result->field_count) {
            $meta = $result->fetch_field_direct($j);
            echo '<th class="border">' . $meta->name . '</th>';
            $j++;
        }
        echo "</tr>";
        for ($i = 0; $i < $num_rows; $i++) {
            if ($i % 2 == 0) {
                echo "<tr class=\"even\">";
            } else {
                echo "<tr class=\"odd\">";
            }
            $row = $result->fetch_assoc();

            $j = 0;
            while ($j < $result->field_count) {
                $meta = $result->fetch_field_direct($j);
                echo '<td class="border">' . stripslashes($row[$meta->name]) . '</td>';
                $j++;
            }
            echo "</tr>\n";
        }
        ?>
    </table>
    <?php
}


function db_display_result_links($result) {
    $num_rows = $result->num_rows;
    ?>
    <table class="border">
        <?php
        echo "<tr>";
        $j = 0;
        while ($j < $result->field_count) {
            $meta = $result->fetch_field_direct($j);
            echo '<th class="border">' . $meta->name . '</th>';
            $j++;
        }
        echo "</tr>";
        for ($i = 0; $i < $num_rows; $i++) {
            if ($i % 2 == 0) {
                echo "<tr class=\"even\">";
            } else {
                echo "<tr class=\"odd\">";
            }            $row = $result->fetch_assoc();

            $j = 0;
            while ($j < $result->field_count) {
                $meta = $result->fetch_field_direct($j);
                if ($meta->name == "birdbase_id") {
                    echo '<td class="border"><a href="http://birdbase.arizona.edu/birdbase/gene.jsp?db_key_value='.$row[$meta->name].'" target="_blank">'.$row[$meta->name].'</a></td>';
                } elseif ($meta->name == "gene_id") {
                    echo '<td class="border"><a href="http://www.ncbi.nlm.nih.gov/gene/'.$row[$meta->name].'" target="_blank">'.$row[$meta->name].'</a></td>';
                } elseif ($meta->name == "unigene") {
                    echo '<td class="border"><a href="http://www.ncbi.nlm.nih.gov/UniGene/ESTProfileViewer.cgi?uglist='.$row[$meta->name].'" target="_blank">'.$row[$meta->name].'</a></td>';
                } elseif ($meta->name == "ref_assembly") {
                    echo '<td class="border"><a href="http://www.ncbi.nlm.nih.gov/nuccore/'.$row[$meta->name].'" target="_blank">'.$row[$meta->name].'</a></td>';
                } elseif ($meta->name == "ensembl_gene_id" ||
                          $meta->name == "ensembl_t_id" ||
                          $meta->name == "ensembl_p_id") {
                    echo '<td class="border"><a href="http://useast.ensembl.org/Multi/Search/Results?species=all;idx=;q='.$row[$meta->name].'" target="_blank">'.$row[$meta->name].'</a></td>';
                }
                else {
                    echo '<td class="border">' . stripslashes($row[$meta->name]) . '</td>';
                }
                $j++;
            }
            echo "</tr>\n";
        }
        ?>
    </table>
    <?php
}


function db_display($action, $result, $primary_key) {
    $num_rows = $result->num_rows;
    echo '<form action="' . $action . '" method="post">';
    ?>
    <table class="border">
        <tr>
            <td> <input type="submit" name="delete" value="Delete"/></td><td class="border"></td>
                <?php
                $j = 0;
                while ($j < $result->field_count) {
                    $meta = $result->fetch_field_direct($j);
                    echo '<th class="border" id="' . $meta->name . '">' . $meta->name . '</th>';
                    $j++;
                }
                ?>
        </tr>
        <?php
        for ($i = 0; $i < $num_rows; $i++) {
if ($i % 2 == 0) {
                echo "<tr class=\"even\">";
            } else {
                echo "<tr class=\"odd\">";
            }            $row = $result->fetch_assoc();
            $pk_values_string = "";
            foreach ($primary_key as $pk) {
                $pk_values_string = "$pk_values_string,$row[$pk]";
            }
            $pk_values_string = ltrim($pk_values_string, ",");
            //echo '<td class="border"><input type="checkbox" name="' . $row[$primary_key] . '" value="' . $row[$primary_key] . '" class="pk_'. $row[$primary_key] .'" disabled="disabled" /></td>';
            //echo "<td class=\"border lock\" id=\"$row[$primary_key]\"></td>";
            echo '<td class="border"><input type="checkbox" name="check[]" value="check,' . $pk_values_string . '" class="pk_' . $pk_values_string . '" disabled="disabled" /></td>';
            echo "<td class=\"border lock\" id=\"pk_$pk_values_string\"></td>";
            $j = 0;
            while ($j < $result->field_count) {
                $meta = $result->fetch_field_direct($j);
                if (in_array($meta->name, $primary_key)) {
                    echo '<td headers="' . $meta->name . '" class="border primary_key edit_off pk_' . $pk_values_string . '">' . $row[$meta->name] . '</td>';
                } else {
                    echo '<td headers="' . $meta->name . '" class="border edit_off pk_' . $pk_values_string . '">' . $row[$meta->name] . '</td>';
                }
                /* if ($meta->name != $primary_key) {
                  echo '<td class="border"><input type="text" name="'. $meta->name .'" class="edit-off pk_'. $row[$primary_key] .'" value="' . $row[$meta->name] . '" disabled="disabled" /></td>';
                  }
                  else {
                  echo '<td class="border"><input type="text" name="'. $meta->name .'" class="edit-off primary_key pk_'. $row[$primary_key] .'" value="' . $row[$meta->name] . '" disabled="disabled" /></td>';
                  } */
                $j++;
            }
            echo "</tr>\n";
        }
        ?>
    </table>
    </form>

    <?php
}

function db_delete($primary_key, $table, $db_conn) {
    if (!empty($_REQUEST['delete'])) {
        foreach ($_POST['check'] as $entry) {
            $entry_arr = explode(",", $entry);
                $query = "DELETE FROM $table WHERE ";
                $i = 1;
                foreach ($primary_key as $pk) {
                    $query = $query . "$pk='$entry_arr[$i]' AND ";
                    $i++;
                }
                $query = rtrim($query, " AND ");
                $result = $db_conn->query($query);
        }
    }
}

function db_insert($table, $db_conn) {
    if (!empty($_REQUEST['insert'])) {
        $query = "SELECT * FROM $table";
        $result = $db_conn->query($query);
        $query = "INSERT INTO $table VALUES (";
        $i = 0;
        while ($i < $result->field_count) {
            $meta = $result->fetch_field_direct($i);
            if ($meta->flags & 512 || $meta->flags & 1024) {
                // if auto_increment or timestamp, insert as null
                $query = $query . "NULL" . ",";
            } elseif (($meta->type == 253 || $meta->type == 254 || $meta->type == 10
                       || $meta->type == 11 || $meta->type == 12) 
                       && !empty($_POST[$meta->name])) {
                // if it's a text field or date, add single quotes
                $query = $query . "'" . $_POST[$meta->name] . "'" . ",";
            } elseif (empty($_POST[$meta->name])) {
                $query = $query . "NULL" . ",";
            } else {
                $query = $query . $_POST[$meta->name] . ",";
            }
            $i++;
        }
        $query = rtrim($query, ",");
        $query = $query . ")";

        $result = $db_conn->query($query);
        echo '<div id="insert-status"';
        if (!$result) {
            echo "<span><strong>Insert unsuccessful.</strong></span>";
            echo "<span><strong>Query: </strong>$query</span>";
            echo "<span><strong>Errormessage: </strong>" . $db_conn->error . "</span>";
        } else {
            echo "<span>" . $db_conn->affected_rows . " row inserted into $table</span>";
        }
        echo '</div>';
    }
}

function db_table_num_rows($table, $db_conn) {
    $query = "SELECT * FROM $table";
    $all_rows = $db_conn->query($query);
    return $all_rows->num_rows;
}

function refValues($arr) {
    if (strnatcmp(phpversion(), '5.3') >= 0) { //Reference is required for PHP 5.3+
        $refs = array();
        foreach ($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}
?>
