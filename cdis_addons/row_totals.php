<?php
//Declare Arrays 
$rack_ids = array();
$total_mem = array();
$total_cpu = array();
$total_storage = array();
$object_ids = array();

//get rack id numbers. Using $racklist array because it knows if the rack is tagged or not.  
foreach ($rackList as $rack)
	$rack_ids[] = $rack['id'];

//get object IDS based on rack -> store all into one array 
//Have to implode array into csv list for query so we dont destroy mysql. 

$rackIdList = implode(',',$rack_ids);
//Run query with csv list
if ( !empty($rackIdList) || count($rackIdList) == 0){
	$query = usePreparedSelectBlade("SELECT DISTINCT RackSpace.object_id from RackSpace,Object where Object.objtype_id=4 and RackSpace.rack_id IN ($rackIdList) and RackSpace.object_id = Object.id");
	$result = $query->fetchAll(PDO::FETCH_ASSOC);
}

//$result returns a multidimensional array. need to strip out ids. 
foreach ($result as $row)
	foreach ($row as $object)
		$object_ids[] = $object;

//Again, have to implode the array into a csv list before query.
$objectIdList = implode(',', $object_ids);
//Run Query if $objectIdList contains values. Print error if it does  not. 

if (!empty($objectIdList) || count($objectIdList) == 0){

	//Run query with csv list.
	$query = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id IN ($objectIdList) and attr_id = 17");
	$result = $query->fetchAll(PDO::FETCH_ASSOC);

	//Finally, get the values for memory
	foreach ($result as $row)
		$total_mem[] = $row['uint_value'];
}

if (!empty($objectIdList) || count($objectIdList) == 0){
       	//Run query with csv list.
       	$query = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id IN ($objectIdList) and attr_id =18");
       	$result = $query->fetchAll(PDO::FETCH_ASSOC);

       	//Finally, get the values for cpu cores
       	foreach ($result as $row)
               	$total_cpu[] = $row['uint_value'];
}

if (!empty($objectIdList) || count($objectIdList) == 0){
       	//Run query with csv list.
       	$query = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id IN ($objectIdList) and attr_id =10000");
       	$result = $query->fetchAll(PDO::FETCH_ASSOC);

       	//Finally, get the values for storage
       	foreach ($result as $row)
               	$total_storage[] = $row['uint_value'];
}
//Sum array and display.  
echo "<div class=\"portlet\">" . "<h2>Group / Room Totals</h2>" . "<table border=0 cellspacing=0 cellpadding=0 width='100%'>" .
 "<tr><th width=50% class=tdright ><strong>Total Memory:&nbsp   </strong></th><td class=tdleft>" . array_sum($total_mem) . " GB</td></tr>" .
 "<tr><th width=50% class=tdright ><strong>Total Cores:&nbsp   </strong></th><td class=tdleft>" . array_sum($total_cpu) .  " Cores</td></tr>" .
 "<tr><th width=50% class=tdright ><strong>Total Storage:&nbsp   </strong></th><td class=tdleft>". array_sum($total_storage) . " TB</td></tr>" .
 "</table></div>\n";

?>
