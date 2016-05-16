<?php
# PHPlot Example: Bar chart, 3 data sets, shaded
session_start();
require_once 'phplot.php';
$plot = new PHPlot(800, 600);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($_SESSION['kaksdata']);

# Main plot title:
$plot->SetTitle('Shaded Bar Chart with '.$_SESSION['select'].' gene');

# Make a legend for the 3 data sets plotted:
$plot->SetLegend(array('Ross', 'Illinois'));

# Turn off X tick labels and ticks because they don't apply here:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->DrawGraph();
