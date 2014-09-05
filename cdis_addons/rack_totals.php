<?php

//Declare needed arrays
$total_mem = array();
$total_cpu = array();
$total_storage = array();
$object_ids = array();

//setup first query to get object_ids of only "servers" not switches or server chassis. 
$query = usePreparedSelectBlade("SELECT DISTINCT RackSpace.object_id from RackSpace,Object where Object.objtype_id=4 and RackSpace.rack_id = '$rackData[id]' and RackSpace.object_id = Object.id");
$result = $query->fetchAll(PDO::FETCH_NUM);

//since a multidimensional array is returned, 
//we need to strip out the values into a single dimensional array. 
foreach ($result as $row)
	foreach($row as $row2)
		$object_ids[] = $row2;
	
/*MEMORY*/	
for ($i=0;$i<count($object_ids);$i++){
	$result = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id = $object_ids[$i] and attr_id = 17");
	$row = $result->fetch();
	$total_mem[] = $row['uint_value'];

}
/*CPU CORES*/
for ($i=0;$i<count($object_ids);$i++){
       	$result = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id = '$object_ids[$i]' and attr_id = 18");
       	$row = $result->fetch();
       	$total_cpu[] = $row['uint_value'];
}
/*STORAGE*/
for ($i=0;$i<count($object_ids);$i++){
       	$result = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id = '$object_ids[$i]' and attr_id = 10000");
       	$row = $result->fetch();
       	$total_storage[] = $row['uint_value'];

}

//Display on page under summary. This needs more work for text to look right. 
echo "<div class=\"portlet\">" . "<h2>Rack Totals</h2>" .
 "<strong>Total Memory:   </strong>" . array_sum($total_mem) . " GB<br>" .
 "<strong>Total Cores:    </strong>" . array_sum($total_cpu) .  " Cores<br>" .
 "<strong>Total Storage:  </strong>" . array_sum($total_storage) . " TB". "<br>" .
 "</div>\n";
?>

