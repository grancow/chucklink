<?php
function number_of_links($project_id, $sort, $up, $f1, $f2, $f3, $f4, $rpp) {
	$page_links = '';
	$tmp = array();
	$tmp[] = 20;
	$tmp[] = 50;
	$tmp[] = 100;
	for (int i=0; i<3; i++){
	$page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?id='.$project_id;
	if ($sort) {$page_links .= '&sort='.$sort.'&up='.$up;}
	if ($f1) {$page_links .= '&f1='.$f1;}
	if ($f2) {$page_links .= '&f2='.$f2;}
	if ($f3) {$page_links .= '&f3='.$f3;}
	if ($f4) {$page_links .= '&f4='.$f4;}
	$page_links .= '&rpp=' . $tmp[i] .  '">' . $tmp[i] . '</a>';
	}
	return $page_links;
}
?>