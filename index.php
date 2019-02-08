<?php
/**
 * Created by PhpStorm.
 * User: johangriesel
 * Date: 13052016
 * Time: 08:48
 * @package    ${NAMESPACE}
 * @subpackage ${NAME}
 * @author     johangriesel <info@stratusolve.com>
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Basic Task Manager</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <form action="update_task.php" method="post">
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom: 5px;;">
                            <input id="InputTaskName" type="text" placeholder="Task Name" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <textarea id="InputTaskDescription" placeholder="Description" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="deleteTask" type="button" class="btn btn-danger">Delete Task</button>
                <button id="saveTask" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6">
            <h2 class="page-header">Task List</h2>
            <!-- Button trigger modal -->
            <button id="newTask" type="button" class="btn btn-primary btn-lg" style="width:100%;margin-bottom: 5px;" data-toggle="modal" data-target="#myModal">
                Add Task
            </button>
            <div id="TaskList" class="list-group">
                <!-- Assignment: These are simply dummy tasks to show how it should look and work. You need to dynamically update this list with actual tasks -->
            </div>
        </div>
        <div class="col-md-3">

        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
	var currentTaskId = -1;
    $('#myModal').on('show.bs.modal', function (event) {
        var triggerElement = $(event.relatedTarget); // Element that triggered the modal
        var modal = $(this);
        if (triggerElement.attr("id") == 'newTask') {
            modal.find('.modal-title').text('New Task');
            $('#deleteTask').hide();
            currentTaskId = -1;
			// make the form empty
			$(this).find('form')[0].reset();
        } else {
            modal.find('.modal-title').text('Task details');
            $('#deleteTask').show();
            currentTaskId = triggerElement.attr("id");
            //console.log('Task ID: '+triggerElement.attr("id"));
        }
    });
    $('#saveTask').click(function() {
		
		//	Declare the form and the data we want to save
		var InputTaskName = $("#InputTaskName").val();
		var InputTaskDescription = $("#InputTaskDescription").val();
		// what action is happening
		var mode = "update";
		if(currentTaskId == -1)
		{
			mode = "create"
		}
		//	Now let's post the captured data to 'update_task.php';
		$.post("update_task.php", {currentTaskId: currentTaskId, InputTaskName: InputTaskName, InputTaskDescription: InputTaskDescription, mode: mode}, function(data){
			//Takes the data returned from the server and embeds in the target HTML.
			$("#TaskList").html(data);
		});
		if(currentTaskId == -1)
			alert('Task being saved');
		else
			alert('TaskId Id:'+currentTaskId + ' being updated');
		
        $('#myModal').modal('hide');
        updateTaskList();
    });
	
	// onclick of each task, the data for that task should populate the modal, to know what we editing or what we deleting at least
	$('#TaskList').on('click', '.list-group-item', function(e)
	{
		var TaskItemId = $(this).attr('id');
		var taskData = [];
		var returnData = [];
		var taskItemModal = $('#myModal');
		//	Here we'll use the short AJAX function get();
		var taskUrl = 'Task_Data.txt';
		taskData = $.get( taskUrl, function( data )
		{
			// Success
		});
		//	Results
		taskData.done(function( data ) 
		{
			returnData = JSON.parse( data );
			$.each(returnData, function(k, v)
			{
				if(v.TaskId == TaskItemId)
				{
					taskItemModal.find('#InputTaskId').val(TaskItemId);
					taskItemModal.find('#InputTaskName').val(v.TaskName);
					taskItemModal.find('#InputTaskDescription').val(v.TaskDescription);
					return false;
				}
			});
			$('#myModal').modal('show');
			// we should set current task id to this task id
			currentTaskId = TaskItemId;
		});
	});
    $('#deleteTask').click(function() {
		
		// confirm action
		var confirmDialogue = confirm("Are you sure you wish to delete task "+ currentTaskId);
		if (confirmDialogue == true)
		{
			var mode = "delete";
			//	Now let's post the captured data to 'update_task.php';
			$.post("update_task.php", {currentTaskId: currentTaskId, mode: mode}, function(data){
				//Takes the data returned from the server and embeds in the HTML.
				$("#TaskList").html(data);
			});
			alert('TaskId Id:'+currentTaskId + ' being deleted');
			$('#myModal').modal('hide');
		}
		else
		{
			// do nothing
		}
		// update task list
		updateTaskList();
    });
    function updateTaskList() {
        $.post("list_tasks.php", function( data ) {
            $( "#TaskList" ).html( data );
        });
    }
    updateTaskList();
</script>
</html>