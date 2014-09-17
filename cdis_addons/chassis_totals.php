<?php
//Declare Arrays
$obj_id = array();
$total_mem = array();
$total_cpu = array();
$total_storage = array();

//Get ObjectIDs that are in this chassis (children).
$children = getEntityRelatives ('children', 'object', $object_id);
foreach ($children as $child)
	$obj_id[] = $child['entity_id'];

//Setting up loops to get the attribute values of the object. Since it is returned as a multi dimentional array, 
//it is split into a single dimentional array for math to be performed. 
/*MEMORY*/
for ($i=0;$i<count($obj_id);$i++){
	$result = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id = '$obj_id[$i]' and attr_id = 17");
	$row = $result->fetch();
	$total_mem[] = $row['uint_value'];
}


/*CPU CORES*/
for ($i=0;$i<count($obj_id);$i++){
        $result = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id = '$obj_id[$i]' and attr_id = 18");
       	$row = $result->fetch();
       	$total_cpu[] = $row['uint_value'];
}
/*STORAGE*/
for ($i=0;$i<count($obj_id);$i++){
        $result = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id = '$obj_id[$i]' and attr_id = 10000");
       	$row = $result->fetch();
       	$total_storage[] = $row['uint_value'];
}
//Since the renderObject function is called when showing the server summary also, 
//this makes our totals show up zero. Instead of displaying all empty results, 
//we check for all 3 arrays to be empty. 

if (!count($total_mem) == 0 && !count($total_cpu) == 0 && !count($total_storage) == 0)
{

//display on page under summary. This needs more work for text to look better. 
echo "<div class=\"portlet\">" . "<h2>Chassis Totals</h2>" . "<table border=0 cellspacing=0 cellpadding=0 width='100%'>" .
 "<tr><th width=50% class=tdright ><strong>Total Memory:&nbsp   </strong></th><td class=tdleft>" . array_sum($total_mem) . " GB</td></tr>" .
 "<tr><th width=50% class=tdright ><strong>Total Cores:&nbsp   </strong></th><td class=tdleft>" . array_sum($total_cpu) .  " Cores</td></tr>" .
 "<tr><th width=50% class=tdright ><strong>Total Storage:&nbsp   </strong></th><td class=tdleft>". array_sum($total_storage) . " TB</td></tr>" .
 "</table></div>\n";
}




/*///////////////////////Test code - sorta works - weird access violation error////////////////////
$objectidList = implode(',',$obj_id);
$query  = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id IN ($objectidList) and attr_id = 17");
$result = $query->fetchAll(PDO::FETCH_ASSOC);
print_r($query);
foreach ($result as $row)
	$total_mem[] = $row['uint_value'];
print_r($total_mem);
*/



?>
