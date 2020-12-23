<?php 
$arr1 = ['tvd','tve', 'tvo'];
$arr2 = ['tvh','tvd','tvo'];

$diff = array_intersect($arr1, $arr2);
var_dump($diff);
$diff1 = array_diff($arr1,$diff);
var_dump($diff1);
?>