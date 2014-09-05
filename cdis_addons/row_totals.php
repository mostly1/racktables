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
$query = usePreparedSelectBlade("SELECT DISTINCT RackSpace.object_id from RackSpace,Object where Object.objtype_id=4 and RackSpace.rack_id IN ($rackIdList) and RackSpace.object_id = Object.id");
$result = $query->fetchAll(PDO::FETCH_ASSOC);

//$result returns a multidimensional array. need to strip out ids. 
foreach ($result as $row)
	foreach ($row as $object)
		$object_ids[] = $object;

//Again, have to implode the array into a csv list before query.
$objectIdList = implode(',', $object_ids);
//Run Query if $objectIdList contains values. Print error if it does  not. 

if (count($objectIdList) == 0)
{

	echo "ERROR: No objects given for query";

}else{
	//Run query with csv list.
	$query = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id IN ($objectIdList) and attr_id = 17");
	$result = $query->fetchAll(PDO::FETCH_ASSOC);

	//Finally, get the values
	foreach ($result as $row)
		$total_mem[] = $row['uint_value'];

}

//Sum array and display.  
echo array_sum($total_mem);



?>
