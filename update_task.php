<?php
/**
 * This script is to be used to receive a POST with the object information and then either updates, creates or deletes the task object
 */
require('Task.class.php');

// Action being carried out
$mode = $_POST['mode'];

// taskId for the task in question{-1 if creating a new task}
$currentTaskId = $_POST['currentTaskId'];

// instantiate class
$task = new Task($currentTaskId);
// // we are updating an existing task
if ($mode == "update"){
	  console.log($mode);
	$task -> Save();
}
// we deleting a task
elseif($mode == "delete"){
	 console.log($mode);
	$task -> DeleteTask();
}
else{
	// do nothing
}
?>