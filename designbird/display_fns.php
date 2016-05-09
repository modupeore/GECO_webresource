<?php

function display_header($title) {

    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
        \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
        <html xmlns=\"http://www.w3.org/1999/xhtml\">";
    echo "<head>";
    echo "<meta http-equiv=\"content-type\" 
		content=\"text/html;charset=utf-8\" />";
    if ($title) {
        echo "<title>Bigbird_DB - $title</title>";
    }
    ?>
    <link href="bigbird_style.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" type="image/ico" href="http://bigbird.anr.udel.edu/tkeeler/bigbird_db/bigbird_private/chicken_favicon.ico"/>
    <?php
    echo "</head>";
    echo "<body>";
    echo "<div id=\"container\">";
}


function display_footer() {
    ?>
    <div id="footer"> 
        <div id="center">
            <span>
                <a href="index.php">home</a>
                |
                <a href="contact.php">contact</a>
            </span>
            <table><tr>
                    <td><a class="pic" href="http://www.udel.edu"><img src="bigbird_private/delaware.png" alt="University of Delaware - Home Page"/></a></td>
                    <td><a class="pic" href="http://www.msstate.edu"><img src="bigbird_private/mississippi.png" alt="Mississippi State University - Home Page"/></a></td>
                    <td><a class="pic" href="http://www.arizona.edu"><img src="bigbird_private/arizona.png" alt="University of Arizona - Home Page"/></a></td>
                </tr></table>
        </div>
        <div id="valid">
            <a href="http://validator.w3.org/check?uri=referer"><img src="bigbird_private/xhtml_valid.png" alt="Valid XHTML 1.0 Strict" /></a>
            <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3"><img style="border:0;"
                                                                                         src="bigbird_private/css_valid.png"
                                                                                         alt="Valid CSS!" />
            </a>

        </div>

    </div>

    <?php
    echo "</div>";
    echo "</body>";
    echo "</html>";
}



function display_php_errors() {
    // error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

function display_menu() {
    ?>
    <div id="head">
        <a href="http://birdbase.arizona.edu/birdbase/index.jsp" id="birdbase-link"><span>Birdbase</span></a>
        <div id="menu">
            <ol>
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="query.php">Query</a>
                </li>
                <li>
                    <a href="search.php">Gene Explorer</a>
                </li>
                <li>
                    <a href="compare.php">Retrieve</a>
                    <ul>
                        <li>
                            <a href="compare.php">Comparison</a>
                        </li>
                        <li>
                            <a href="single_lib.php">Single Library</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="db_edit.php?database=transcriptome&amp;table=meta_data">Manage Tables</a>
                    <ul>
                        <li>
                            <a href="db_edit.php?database=transcriptome&amp;table=meta_data">Transcriptome</a>
                            <ul>
                                <li>
                                    <a href="db_edit.php?database=transcriptome&amp;table=expression">expression</a>
                                </li>
                                <li>
                                    <a href="db_edit.php?database=transcriptome&amp;table=meta_data">meta_data</a>
                                </li>
                                <li>
                                    <a href="db_edit.php?database=transcriptome&amp;table=rna_sample">rna_sample</a>
                                </li>
                                <li>
                                    <a href="db_edit.php?database=transcriptome&amp;table=tissue">tissue</a>
                                </li>

                            </ul>
                        </li>
                        <li>
                            <a href="db_edit.php?database=morphometrics&amp;table=bird">Morphometrics</a>
                            <ul>
                                <li>
                                    <a href="db_edit.php?database=morphometrics&amp;table=bird">bird</a>
                                </li>
                                <li>
                                    <a href="db_edit.php?database=morphometrics&amp;table=mass">mass</a>
                                </li>
                                <li>
                                    <a href="db_edit.php?database=morphometrics&amp;table=morphometrics">morphometrics</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="db_edit.php?database=gene_annotations&amp;table=gene">Gene Annotations</a>
                            <ul>
                                <li>
                                    <a href="db_edit.php?database=gene_annotations&amp;table=gene">gene</a>
                                </li>
                                <li>
                                    <a href="db_edit.php?database=gene_annotations&amp;table=go_xref">go_xref</a>
                                </li>
                                <li>
                                    <a href="db_edit.php?database=gene_annotations&amp;table=ontology">ontology</a>
                                </li>
                                <li>
                                    <a href="db_edit.php?database=gene_annotations&amp;table=birdbase">birdbase</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="logout.php">Logout</a>
                </li>
            </ol> 
            <?php
            // authenticate user
            $authenticated = authenticate();
            ?>
        </div>
    </div>
    <?php
    return $authenticated;
}

function IsURLCurrentPage($url) {
    if (strpos($_SERVER['PHP_SELF'], $url) == false) {
        return false;
    } else {
        return true;
    }
}

function display_db_insert($table, $db_conn, $action) {
    $update = null;
    $query = "SELECT * FROM $table";
    $all_rows = $db_conn->query($query);
    echo '<form action="' . $action . '" method="post">';
    ?>
    <table class="border">
        <tr class="border">
            <td class="border"></td>
            <?php
            $i = 0;
            while ($i < $all_rows->field_count) {
                $meta = $all_rows->fetch_field_direct($i);
                echo '<th class="border">' . $meta->name . '</th>';
                $i++;
            }
            ?>
        </tr>
        <tr>
            <?php
            if ($update) {
                $string = "update";
            } else {
                $string = "insert";
            }
            echo '<td class="border"> <input type="submit" name="' . $string . '" value="' . $string . '"></td>';
            $i = 0;
            while ($i < $all_rows->field_count) {
                $meta = $all_rows->fetch_field_direct($i);
                if ($meta->flags & 512 || $meta->flags & 1024) {
                    // if auto_increment or timestamp, shade out input
                    echo '<td class="shaded">
                                 <input type="text"
                                 name="' . $meta->name . '"
                                 maxlength="30"
                                 size="10"
                                 style="text-align:right"
                                 disabled="disabled"</td>';
                } else {
                    echo '<td class="border"> 
                                 <input type="text" 
                                 name="' . $meta->name . '" 
                                 maxlength="30" 
                                 size="10" 
                                 style="text-align:right"></td>';
                }
                $i++;
            }
            ?>

        </tr>
    </table>
    </form>
    <?php
}

function display_tables($db_conn, $cur_tab) {
    $query = "SELECT DATABASE()";
    $result = $db_conn->query($query);
    $row = $result->fetch_row();
    $database = $row[0];
    $query = "SHOW TABLES";
    $result = $db_conn->query($query);
    $num_rows = $result->num_rows;
    ?>
    <table class="border" id="display-tables">
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
            echo "<tr>";
            $row = $result->fetch_assoc();

            $j = 0;
            while ($j < $result->field_count) {
                $meta = $result->fetch_field_direct($j);
                if ($row[$meta->name] == $cur_tab) {
                    echo "<td class=\"border\"><strong>$cur_tab</strong></td>";
                } else {
                    echo '<td class="border"> <a href="' . $_SERVER['PHP_SELF'] . '?database=' . $database . '&amp;table=' . stripslashes($row[$meta->name]) . '">' . stripslashes($row[$meta->name]) . '</a></td>';
                }
                echo '<td class="border"> <a href="describe_table.php?database=' . $database . '&amp;table=' . stripslashes($row[$meta->name]) . '" target="_blank">describe</a></td>';
                $j++;
            }
            echo "</tr>\n";
        }
        ?>
    </table>
    <?php
}

function describe_table($db_conn, $table) {
    $query = "DESCRIBE $table";
    $result = $db_conn->query($query);
    db_display_result($result);
}

function display_upload_iframe($action) {
    ?>
    <div id="upload">
        <iframe name="iframe" id="iframe"></iframe>
        <?php
        echo "<form method=\"post\" action=\"bigbird_private/erange_uploader.php\" target=\"iframe\" enctype=\"multipart/form-data\"/>";
        ?>
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />

        <ol>
            <li>
                <label for="erange_file">Upload erange file:</label>
                <input type="file" name="erange_file" id="erange_file"/>
            </li>
            <li>
                <label for="lib_id">For lib_id:</label>
                <input type="text" name="lib_id" id="lib_id"/>
            </li>
            <li>
                <input type="submit" name="upload" value="Go"/>
            </li>
        </ol>
    </form> 
    <a href="log.txt">status messages:</a>
    <div id="status_msg">
    </div>
    </div>

    <?php
}
?>