<?php
ob_start();
date_default_timezone_set("Asia/Kolkata");

$action = $_GET['action'];
include 'ajax.php';
$crud = new Action();

if($action == 'mark_cell'){
	$mark_cell = $crud->mark_cell();
	if($mark_cell){
		echo $mark_cell;
    }
}

if($action == 'reset_cell'){
	$reset_cell = $crud->reset_cell();
	if($reset_cell){
		echo $reset_cell;
    }
}

if($action == 'fetch_results'){
	$fetch_results = $crud->fetch_results();
	if($fetch_results){
		echo $fetch_results;
    }
}

ob_end_flush();
