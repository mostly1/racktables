<?php

include ('inc/init.php');

$tabhandler['reports']['hardware'] = 'hardwareFailureReportSummary'; // register a report rendering function
$tab['reports']['hardware'] = 'Hardware Failures';      // The title of the report tab

function hardwareFailureReportSummary()
{
    //get all the info from the database and display
    $result = array();
    $result = usePreparedSelectBlade("SELECT * FROM HardwareFailures");
    //$result = usePreparedSelectBlade("SELECT t1.*,DATE(FROM_UNIXTIME(t2.uint_value)) as poweron_date FROM HardwareFailures as t1 JOIN AttributeValue as t2 where t1.object_id=t2.object_id and t2.attr_id='10023'");
    $row = $result->fetchALL(PDO::FETCH_ASSOC);

    if ( isset($_GET['csv']) ) {

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename=hardare_failure_report_'.date("Ymd-his").'.csv');
        header('Pragma: no-cache');
        header('Expires: 0');

        $outstream = fopen("php://output", "w");

        $CSV = array('ObjectID','PowerOn Date','Fail Date','Name','Component','Resolution Method','Comment');

        fputcsv( $outstream, $CSV );

        foreach ($row as $report) {
            $CSV = array();

            $CSV[0] = $report['object_id'];
            $CSV[1] = $report['poweron_date'];
            $CSV[2] = $report['fail_date'];
            $CSV[3] = $report['name'];
            $CSV[4] = $report['component'];
            $CSV[5] = $report['resolution_type'];
            $CSV[6] = str_replace('&quot;',"'",$report['comment']);

           fputcsv( $outstream, $CSV );
        }

        fclose($outstream);

        exit(0); # Exit normally after send CSV to browser

    }
    //start html
    echo "<div class=\"portlet\">" . "<h2>Failure History</h2>" .
         "<a href=\"index.php?page=reports&tab=hardware&csv\">CSV Export</a>" .
         "<table border=0 cellspacing=3 cellpadding=0 width='100%'>";
    // Load stylesheet and jquery scripts
    echo "<link rel=\"stylesheet\" href=\"/extensions/jquery/themes/racktables/style.css\" type=\"text/css\"/>";
    echo "<script type=\"text/javascript\" src=\"/extensions/jquery/jquery-latest.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"/extensions/jquery/jquery.tablesorter.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"/extensions/jquery/picnet.table.filter.min.js\"></script>";
    echo "<table id=\"reportTable\" class=\"tablesorter\">".
             "<thead>" .
               "<tr>" .
                 "<th>ObjectID</th>" .
                 "<th>PowerOn Date</th>" .
                 "<th>Failure Date</th>" .
                 "<th>Name</th>" .
                 "<th>Component</th>" .
                 "<th>Resolution Type</th>" .
                 "<th>Comments</th>" .
                "</tr>".
              "</thead>".
            "<tbody>";
    foreach ($row as $report)
    {
        echo "<tr>" .
             "<td>" . $report['object_id'] . "</td>" .
             "<td>" . $report['poweron_date'] . "</td>" .
             "<td>" . $report['fail_date'] . "</td>" .
             "<td>" . $report['name'] . "</td>" .
             "<td>" . $report['component'] . "</td>" .
             "<td>" . $report['resolution_type'] . "</td>" .
             "<td>" . $report['comment'] . "</td>" .
             "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    //jquery table sorter.
     echo '<script type="text/javascript">
             $(document).ready(function()
               {
                 $.tablesorter.defaults.widgets = ["zebra"];
                 $("#reportTable").tablesorter(
                     { headers: {
                     }, sortList: [[0,0]] }
                 );
                 $("#reportTable").tableFilter();
               }
             );
           </script>';
    "</table></div>";
}
?>
