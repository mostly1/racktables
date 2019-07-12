<?php

include ('inc/init.php');

$tabhandler['object']['hardware'] = 'hardwareFailure'; // register a report rendering function
$tab['object']['hardware'] = 'Hardware Failures';      // The title of the report tab

function hardwareFailure()
{
    //get Object ID from page we are on dynamically
    $object_id = getBypassValue();
    // get attribute values that apply to this object type.
    $values = getAttrValuesSorted ($object_id);
    // pull out only the "chapter" records i created for hardware failures in the dictionary.
    foreach ($values as $record)
    {
        if ($record['id'] == 10000){

        $chapter_hardware = readChapter ($record['id'], 'o');
        $chapter_hardware[0] = '-- NOT SET --';
        $chapter_hardware = cookOptgroups ($chapter_hardware, $object['objtype_id'], $record['key']);
        }
    }
    foreach ($values as $record)
    {
        if ($record['id'] == 10001){

        $chapter_resType = readChapter ($record['id'], 'o');
        $chapter_resType[0] = '-- NOT SET --';
        $chapter_resType = cookOptgroups ($chapter_resType, $object['objtype_id'], $record['key']);
        }
    }


    //Start html stuff and form declaration.
    echo "<div class=\"portlet\">" . "<h2>Enter Hardware Failures Here:</h2>" . "<table border=0 cellspacing=3 cellpadding=0 width='100%'>" .
    "<form action=hardwareFailurePost.php method=post>" .
    // enter object id into a field and hide it so we can parse it with the submission form.
    "<input type=hidden name=obj_id value=" .$object_id. ">\n".
    "<tr><th width=50% class=tdright ><strong>Date (Format Y-m-d):&nbsp   </strong></th><td class=tdleft><input id=\"hw.date\" name=\"hw_date\" type=\"text\" value=" .date('Y-m-d')."></td></tr>".
    "<tr><th width=50% class=tdright ><strong>Failed Component:&nbsp   </strong></th><td class=tdleft>";
    printNiftySelect ($chapter_hardware, array ('name' => "comp_value"), $record['key']);
    echo "</td></tr>\n" .
    "<tr><th width=50% class=tdright ><strong>Resolution Method:&nbsp   </strong></th><td class=tdleft>";
    printNiftySelect ($chapter_resType, array ('name' => "resType"), $record['key']);
    echo "</td></tr>\n" .
    "<tr><th width=50% class=tdright ><strong>Comments:&nbsp   </strong></th><td class=tdleft><textarea name=\"comp_comment\" rows=5 cols=40></textarea></td></tr>".
    "<tr><th width=50% class=tdright ><input type=\"submit\"></th>" .
    "</form>" . "</table></div>\n";

    //start jquery tablesorter
    echo "<div class=\"portlet\">" . "<h2>Past Failures</h2>" . "<table border=0 cellspacing=3 cellpadding=0 width='100%'>";
    // Load stylesheet and jquery scripts
    echo "<link rel=\"stylesheet\" href=\"/extensions/jquery/themes/racktables/style.css\" type=\"text/css\"/>";
    echo "<script type=\"text/javascript\" src=\"/extensions/jquery/jquery-latest.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"/extensions/jquery/jquery.tablesorter.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"/extensions/jquery/picnet.table.filter.min.js\"></script>";
    echo "<table id=\"reportTable\" class=\"tablesorter\">".
             "<thead>" .
               "<tr>" .
                 "<th>ObjectID</th>" .
                 "<th>Date</th>" .
                 "<th>Name</th>" .
                 "<th>Component</th>" .
                 "<th>Resolution Method</th>" .
                 "<th>Comments</th>" .
                "</tr>".
              "</thead>".
            "<tbody>";
    //get all the info for this ID from the database and display
    $result = array();
    $result = usePreparedSelectBlade("SELECT * FROM HardwareFailures where object_id = '$object_id'");
    $row = $result->fetchALL(PDO::FETCH_ASSOC);
    foreach ($row as $report)
    {
        echo "<tr>" .
             "<td>" . $report['object_id'] . "</td>" .
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
