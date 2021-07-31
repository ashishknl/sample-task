<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DemoTask;
use App\DemoColumn;

use Spatie\DbDumper\DbDumper;
use Config;

class UserController extends Controller
{
    
    public function index()
    {
    	 $mysqlHostName      = 'localhost';
        $mysqlUserName      = 'utvqwndkkt';
        $mysqlPassword      = 'D6JSkZzQ9t';
        $DbName             = 'utvqwndkkt';
        $backup_name        = "mybackup.sql";
        $tables             = array("demo_tasks"); //here your tables...

        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $result = $statement->fetchAll();


        $output = '';
        foreach($tables as $table)
        {
         $show_table_query = "SHOW CREATE TABLE " . $table . "";
         $statement = $connect->prepare($show_table_query);
         $statement->execute();
         $show_table_result = $statement->fetchAll();

         foreach($show_table_result as $show_table_row)
         {
          $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
         }
         $select_query = "SELECT * FROM " . $table . "";
         $statement = $connect->prepare($select_query);
         $statement->execute();
         $total_row = $statement->rowCount();

         for($count=0; $count<$total_row; $count++)
         {
          $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
          $table_column_array = array_keys($single_result);
          $table_value_array = array_values($single_result);
          $output .= "\nINSERT INTO $table (";
          $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
          $output .= "'" . implode("','", $table_value_array) . "');\n";
         }
        }
        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
           header('Pragma: public');
           header('Content-Length: ' . filesize($file_name));
           ob_clean();
           flush();
           readfile($file_name);
           unlink($file_name);



        
    }
public function showTasks()
    {
        $tasks_columns = DemoColumn::select('id','name')->get();
        $allContent=array();
        foreach($tasks_columns as $tasks_column)
        {
        	$task = DemoTask::orderBy('order')->select('id','title','order','status','column_id','desc')->where('column_id',$tasks_column->id)->get();
       
        	$allContent[$tasks_column->id.'_'.$tasks_column->name]=$task;
        }
        $tasks = DemoTask::orderBy('order')->select('id','title','order','status','column_id','desc')->get();
        $tasksCompleted = $tasks->filter(function ($task, $key) {
            return $task->status;
        })->values();
        
        $tasksNotCompleted = $tasks->filter(function ($task, $key) {
            return  ! $task->status;
        })->values();
        $allTasks=$tasks;
        $allColumns=$tasks_columns;
        $allContents=json_encode($allContent);
       
       
        return view('demos.alltasks',compact('allContents','allTasks','allColumns','tasksCompleted','tasksNotCompleted'));
    }
    public function updateTasksStatus(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|boolean',
        ]);

        $task = DemoTask::find($id);
        $task->status = $request->status;
		$task->column_id = $request->column_id;
        $task->save();
        
		
        return response('Updated Successfully.', 200);

    }
     public function updateTitleDesc(Request $request, $id)
    {
       
        $task = DemoTask::find($id);
        $task->title = $request->title;
        $task->desc = $request->desc;
        $task->save();
        
        return response('Updated Successfully.', 200);

    }
    public function deleteTask(Request $request, $id)
    {
        
        $task = DemoTask::find($id);
        $task->delete();
        return response('Deleted Successfully.', 200);

    }

    public function updateTasksOrder(Request $request)
    {
        

        $tasks = DemoTask::all();

        foreach ($tasks as $task) {
            $id = $task->id;
            foreach ($request->tasks as $tasksNew) {
                if ($tasksNew['id'] == $id) {
                    $task->update(['order' => $tasksNew['order']]);
                }
            }
        }

        return response('Updated Successfully.', 200);
    }
    public function columnInsert(Request $request){
        $name = $request->input('name');
         DemoColumn::insert(
	    ['name' =>$name]
	);
       return response('Column Inserted Successfully.', 200);
        }
        public function taskInsert(Request $request){
        $title = $request->input('title');
        $column_id = $request->input('column_id');
         DemoTask::insert(
	    ['title' =>$title, 'column_id' => $column_id]
	);
       return response('Task Inserted Successfully.', 200);
        }

    
   
}