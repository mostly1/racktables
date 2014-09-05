<?php
$children = getEntityRelatives ('children', 'object', $object_id);
$obj_id = array();
foreach ($children as $child)
	$obj_id[] = $child['entity_id'];
$total_mem = array();
$total_cpu = array();
$total_storage = array();
for ($i=0;$i<count($obj_id);$i++){
	$result = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id = '$obj_id[$i]' and attr_id = 17");
	$row = $result->fetch();
	$total_mem[] = $row['uint_value'];
}
for ($i=0;$i<count($obj_id);$i++){
        $result = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id = '$obj_id[$i]' and attr_id = 18");
       	$row = $result->fetch();
       	$total_cpu[] = $row['uint_value'];
}
for ($i=0;$i<count($obj_id);$i++){
        $result = usePreparedSelectBlade("SELECT uint_value FROM AttributeValue WHERE object_id = '$obj_id[$i]' and attr_id = 10000");
       	$row = $result->fetch();
       	$total_storage[] = $row['uint_value'];
}
if (array_sum($total_mem) > 0)
{
echo "<strong>Total Memory: </strong>" . array_sum($total_mem) . " GB<br>";
echo "<strong>Total CPU: </strong>" . array_sum($total_cpu) .  " Cores<br>";
echo "<strong>Total Storage: </strong>" . array_sum($total_storage) . " TB". "<br>";
}
//echo "</table>\n";


?>
