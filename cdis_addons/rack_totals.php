<?php
//Declare needed arrays
$total_mem = array();
$total_cpu = array();
$total_storage = array();
$object_ids = array();

//setup first query to get object_ids of only "servers" not switches or server chassis. 
if ( !empty($rackData['id'])){
$query = usePreparedSelectBlade("SELECT DISTINCT RackSpace.object_id from RackSpace,Object where Object.objtype_id=4 and RackSpace.rack_id = '$rackData[id]' and RackSpace.object_id = Object.id");
$result = $query->fetchAll(PDO::FETCH_NUM);

//since a multidimensional array is returned, 
//we need to strip out the values into a single dimensional array. 
foreach ($result as $row)
	foreach($row as $row2)
		$object_ids[] = $row2;
}
//After objects are stored, implode the array into a csv list. This keeps the mysql queries to a minimum.
$objectIdList = implode(',',$object_ids);
//check to make sure we have all of our ids before running the querys.
if (!empty($object_ids)){ 
$query = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id IN ($objectIdList) and attr_id = 17");	
$result = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
	$total_mem[] = $row['uint_value'];
}

if (!empty($object_ids)){
$query = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id IN ($objectIdList) and attr_id = 18");
$result = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
       	$total_cpu[] = $row['uint_value'];
}

if (!empty($object_ids)){
$query = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id IN ($objectIdList) and attr_id = 10000");
$result = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
       	$total_storage[] = $row['uint_value'];
}

//Sum array and display on page under summary. This needs more work for text to look right. 
echo "<div class=\"portlet\">" . "<h2>Rack Totals</h2>" . "<table border=0 cellspacing=0 cellpadding=0 width='100%'>" .
 "<tr><th width=50% class=tdright ><strong>Total Memory:&nbsp   </strong></th><td class=tdleft>" . array_sum($total_mem) . " GB</td></tr>" .
 "<tr><th width=50% class=tdright ><strong>Total Cores:&nbsp   </strong></th><td class=tdleft>" . array_sum($total_cpu) .  " Cores</td></tr>" .
 "<tr><th width=50% class=tdright ><strong>Total Storage:&nbsp   </strong></th><td class=tdleft>". array_sum($total_storage) . " TB</td></tr>" .
 "</table></div>\n";

?>
