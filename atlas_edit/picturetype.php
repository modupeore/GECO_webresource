<?php
# PHPlot Example: Bar chart, 3 data sets, shaded
require_once 'phplot.php';
$s1 = array("gene-1", -3, 4); $s2 = array("gene-1", 4, 6);
$data = array(
  $s1, $s2,
);
print_r($data);/*
$plot = new PHPlot(800, 600);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($data);

# Main plot title:
$plot->SetTitle('Shaded Bar Chart with 3 Data Sets');

# Make a legend for the 3 data sets plotted:
$plot->SetLegend(array('Ross', 'Illinois'));

# Turn off X tick labels and ticks because they don't apply here:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

$plot->DrawGraph();
*/