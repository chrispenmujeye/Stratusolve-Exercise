<?php
/**
 * This class handles the modification of a task object
 */
class Task {
    public $TaskId;
    public $TaskName;
    public $TaskDescription;
    protected $TaskDataSource;
    public function __construct($Id = null) {
        $this->TaskDataSource = file_get_contents('Task_Data.txt');
		
        if (strlen($this->TaskDataSource) > 0)
            $this->TaskDataSource = json_decode($this->TaskDataSource); // Should decode to an array of Task objects
        else
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array

        if (!$this->TaskDataSource)
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
        if (!$this->LoadFromId($Id))
            $this->Create();
    }
    protected function Create() {
		
        // This function needs to generate a new unique ID for the task
		// get fields values and use htmlentities for security 
        $this->TaskId = $this->getUniqueId();
        $this->TaskName = htmlentities($_POST['InputTaskName']);
        $this->TaskDescription = htmlentities($_POST['InputTaskDescription']);
		// get task array
		$taskDataArray = $this->TaskDataSource;
		// build task subarray
		$data = array("TaskId" => $this->TaskId, "TaskName" => $this->TaskName, "TaskDescription" =>  $this->TaskDescription);
		// add task subarray to the main array
		array_push($taskDataArray, $data);
		// decode to json before writing back to file
		$jsonData = json_encode($taskDataArray);
		file_put_contents('Task_Data.txt', $jsonData);
		
    }
    protected function getUniqueId() {
        // Generate uniqueId
		$uniqueid = uniqid();
        return $uniqueid; // Placeholder return for now
    }
    protected function LoadFromId($Id = null) {
		$taskDataArray = $this->TaskDataSource;
		
        if ($Id) {
			
				 $this->TaskId = $Id;
				// Load details and check if passed taskId alread exist in the file Task_Data.txt
				 foreach ($taskDataArray as $task) {
					 
					$taskId = $task->TaskId;
					if($taskId == $Id){
						return $taskId; 
					}
				 }
	
        } else
            return null;
    }

    public function Save() {
		
		//Assignment: Code to save task here
		$taskDataArray = $this->TaskDataSource;
		$taskId = $this->TaskId;
		$this->TaskName = htmlentities($_POST['InputTaskName']);
        $this->TaskDescription = htmlentities($_POST['InputTaskDescription']);
		
		//echo "<script>console.log( 'Debug Objects: " . $taskId . "' );</script>";
		foreach($taskDataArray as &$task){
			if($task->TaskId == $taskId){
				$task->TaskName = $this->TaskName;
				$task->TaskDescription = $this->TaskDescription;
				break; // Stop the loop after we've found the item
			}
		}
		unset($task); // break the reference with the last element
		// write back to the txt file Task_Data.txt
		$jsonData = json_encode($taskDataArray);
		file_put_contents('Task_Data.txt', $jsonData);
    }
	/*
	* Function to save a task...i.e to update an existing task
	*/
    public function DeleteTask() {
		// stores the resulting array after deleting
		$tasks = array();
		$taskDataArray = $this->TaskDataSource;
		$taskId = $this->TaskId;
		
		// loop through all existing tasks and skip the task to be deleted
		foreach($taskDataArray as $subKey => $subArray){
			// prepare build the main array
			$task = array();
		    if($subArray->TaskId != $taskId){
			   //unset($taskDataArray[$subKey]);
			   $task = array('TaskId'=>$subArray -> TaskId, 'TaskName'=>$subArray -> TaskName, 'TaskDescription'=>$subArray -> TaskDescription);
			   $tasks[] = $task;
		    }
		}
		// write back to the txt file Task_Data.txt
		$jsonData = json_encode($tasks);
		file_put_contents('Task_Data.txt', $jsonData);
    }
}
?>