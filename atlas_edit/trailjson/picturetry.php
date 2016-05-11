<?php
/*  PHPlot web form example

  Parameter names and parameter array keys:
    'deposit' = Amount deposited per month.
    'intrate' = Interest rate as a percentage (e.g. 4 means 4% or 0.04)
*/

# Name of script which generates the actual plot:
define('GRAPH_SCRIPT', 'webform_img.php');
# Image size. It isn't really necessary that this script know this image
# size, but it improves page rendering.
define('GRAPH_WIDTH', 600);
define('GRAPH_HEIGHT', 400);

# Default values for the form parameters:
$param = array('deposit' => 100.00, 'intrate' => 4.0);
#Function build_url() is a general-purpose function used to generate a URL to a script with parameters. The parameters are in a PHP associative array. The return value is a relative or complete URL which might look like this: webform_img.php?deposit=100&intrate=4.0&h=400&w=600.

# Build a URL with escaped parameters:
#   $url - The part of the URL up through the script name
#   $param - Associative array of parameter names and values
# Returns a URL with parameters. Note this must be HTML-escaped if it is
# used e.g. as an href value. (The & between parameters is not pre-escaped.)
function build_url($url, $param)
{
    $sep = '?';  // Separator between URL script name and first parameter
    foreach ($param as $name => $value) {
        $url .= $sep . urlencode($name) . '=' . urlencode($value);
        $sep = '&';   // Separator between subsequent parameters
    }
    return $url;
}
#The function begin_page() creates the HTML at the top of the page. In a real application, this might include a page header.

# Output the start of the HTML page:
function begin_page($title)
{
    echo <<<END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                      "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>$title</title>
</head>
<body>
<h1>$title</h1>

END;
}
#The function end_page() creates the HTML at the end of the page. In a real application, this might include a page footer.

# Output the bottom of the HTML page:
function end_page()
{
    echo <<<END
</body>
</html>

END;
}
#The function show_descriptive_text() produces HTML text which describes the form. This will go above the form on the web page.

# Output text which describes the form.
function show_descriptive_text()
{
    echo <<<END
<p>
This page calculates the balance over time in an interest-earning savings
account when fixed monthly deposits are made and there are no withdrawals.
</p>
<p>
Fill in the values below and click on the button to display a
graph of the account balance over time.
</p>

END;
}
#The function show_form() outputs the HTML form. This includes entry boxes for the two parameters and a submit button. The form action URL is this script itself, so we use the SCRIPT_NAME value to self-reference the script.

# Output the web form.
# The form resubmits to this same script for processing.
# The $param array contains default values for the form.
# The values have already been validated as containing numbers and
# do not need escaping for HTML.
function show_form($param)
{
    $action = htmlspecialchars($_SERVER['SCRIPT_NAME']);

    echo <<<END
<form name="f1" id="f1" method="get" action="$action">
<table cellpadding="5" summary="Entry form for balance calculation">
<tr>
  <td align="right"><label for="deposit">Monthly Deposit Amount:</label></td>
  <td><input type="text" size="10" name="deposit" id="deposit"
       value="{$param['deposit']}">
</tr>
<tr>
  <td align="right"><label for="intrate">Interest Rate:</label></td>
  <td><input type="text" size="10" name="intrate" id="intrate"
      value="{$param['intrate']}">%
</tr>
<tr>
  <td colspan="2" align="center"><input type="submit" value="Display Graph"></td>
</tr>
</table>
</form>

END;
}
#The function check_form_params() performs the important task of validating the parameters received from a form submission. Each parameter is checked for presence and syntax, then converted to the appropriate PHP type. This function is also used to determine if a plot should be displayed. A plot is displayed only if valid form parameters were received.

# Check for parameters supplied to this web page.
# If there are valid parameters, store them in the array argument and
# return True.
# If there are no parameters, or the parameters are not valid, return False.
function check_form_params(&$param)
{
    $valid = True;

    if (!isset($_GET['deposit']) || !is_numeric($_GET['deposit'])
           || ($deposit = floatval($_GET['deposit'])) < 0)
        $valid = False;

    if (!isset($_GET['intrate']) || !is_numeric($_GET['intrate'])
           || ($intrate = floatval($_GET['intrate'])) < 0 || $intrate > 100)
        $valid = False;

    if ($valid) $param = compact('deposit', 'intrate');
    return $valid;
}
#The function show_graph() produces the HTML which will will invoke the second script to produce the graph. This is an image (img) tag which references the second script, including the parameters the script needs to generate the plot. This is one of several ways to pass parameters from the form handling script and the image generating script. The other way is using session variables. Using URL parameters is simpler, especially when there are only a few parameters. Note the HTML also specifies the width and height of the plot image. This is not necessary, however it helps the browser lay out the page without waiting for the image script to complete.

# Display a graph.
# The param array contains the validated values: deposit and intrate.
# This function creates the portion of the page that contains the
# graph, but the actual graph is generated by the $GRAPH_SCRIPT script.
function show_graph($param)
{
    # Include the width and height as parameters:
    $param['w'] = GRAPH_WIDTH;
    $param['h'] = GRAPH_HEIGHT;
    # URL to the graphing script, with parameters, escaped for HTML:
    $img_url = htmlspecialchars(build_url(GRAPH_SCRIPT, $param));

    echo <<<END
<hr>
<p>
Graph showing the account balance over time, with monthly deposits of
{$param['deposit']} and earning annual interest of {$param['intrate']}%:

<p><img src="$img_url" width="{$param['w']}" height="{$param['h']}"
    alt="Account balance graph.">

END;
}
#Finally, with all the functions defined, the main code is just a few lines.

# This is the main processing code.
begin_page("PHPlot: Example of a Web Form and Plot");
$params_supplied = check_form_params($param);
show_descriptive_text();
show_form($param);
if ($params_supplied) show_graph($param);
end_page();
#5.23.2. Web Form Image Script

#This section presents the second script webform_img.php, which generates the plot using PHPlot. The URL to this script, along with its parameters, is embedded in the web page produced by the main script in Section 5.23.1, “Web Form Main Script”. When the user's browser asks the web server for the image, this second script runs and generates the plot.

#The script begins with a descriptive comment and then includes the PHPlot source.
?>
<?php
/*  PHPlot web form example - image generation

    This draws the plot image for webform.php
    It expects the following parameters:
       'deposit' = Amount deposited per month. Must be >= 0.
       'intrate' = Interest rate as a percentage (e.g. 4 means 4% or 0.04)
       'w', 'h' = image width and height. (Must be between 100 and 5000)
*/
require_once 'phplot.php';
#Function check_form_params() validates the parameters supplied to the script. Two parameters are required (intrate and deposit), and two are optional (h and w). Even though the main script validated the parameters it passes to this script, it is still necessary for the script to do its own validation. That is because any accessible script can be called from any other web page, or directly from a browser, with arbitrary parameters. (Error handling details can be found below.)

# Check for parameters supplied to this web page.
# Parameters must be checked here, even though the calling script checked them,
# because there is nothing stopping someone from calling this script
# directly with arbitrary parameters.
# Parameter values are stored in the param[] array (valid or not).
# If the parameters are valid, return True, else return False.

#Function calculate_data() computes the data for the plot. This uses the parameters supplied to the script, and populates a data array suitable for PHPlot. Because the script uses the data-data format, each row in the array consists of a label (unused), X value (this is the month number), and 2 Y values (account balance without interest, and account balance with interest).

# Calculate the data for the plot:
# This is only called if the parameters are valid.
# The calculation is simple. Each month, two points are calculated: the
# cumulative deposts (balance without interest), and balance with interest.
# At time 0 the balance is 0. At the start of each month, 1/12th of
# the annual interest rate is applied to the balance, and then the deposit
# is added, and that is reported as the balance.
# We calculate for a fixed amount of 120 months (10 years).
function calculate_data($param, &$data)
{
    $deposit = $param['deposit'];
    $monthly_intrate = 1.0 + $param['intrate'] / 100.0 / 12.0;
    $balance_without_interest = 0;
    $balance = 0;
    $data = array(array('', 0, 0, 0)); // Starting point
    for ($month = 1; $month <= 120; $month++) {
        $balance_without_interest += $deposit;
        $balance = $balance * $monthly_intrate + $deposit;
        $data[] = array('', $month, $balance_without_interest, $balance);
    }
}
#Function draw_graph() uses PHPlot to actually produce the graph. This function is similar to the other code examples in this chapter. A PHPlot object is created, set up, and then told to draw the plot. If the script parameters are not valid, however, an attempt is made to draw the plot without a data array. This results in an error, which PHPlot handles by creating an image file with an error message. This method of error handling is used because the script cannot return a textual error message since it is referenced from a web page via an image (img) tag. An alternative to this error handling is to have the script return an HTTP error code such as error 500 (server error).

# Draw the graph:
function draw_graph($valid_params, $param, $data)
{
    extract($param);

    $plot = new PHPlot($w, $h);
    $plot->SetTitle('Savings with Interest');
    $plot->SetDataType('data-data');
    # Don't set data values if parameters were not valid. This will result
    # in PHPlot making an image with an error message.
    if ($valid_params) {
        $plot->SetDataValues($data);
    }
    $plot->SetLegend(array('Deposits only', 'Deposits with Interest'));
    $plot->SetLegendPixels(100, 50); // Move legend to upper left
    $plot->SetXTitle('Month');
    $plot->SetXTickIncrement(12);
    $plot->SetYTitle('Balance');
    $plot->SetYLabelType('data', 2);
    $plot->SetDrawXGrid(True);
    $plot->SetPlotType('lines');
    $plot->DrawGraph();
}
#Lastly, the main code for the image drawing script simply uses the above functions.

# This is our main processing code.
$valid_params = check_form_params($param);
if ($valid_params) calculate_data($param, $data);
draw_graph($valid_params, $param, $data);
?>