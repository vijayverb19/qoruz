<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 

class taskController extends Controller
{
    
    public function getAllTask(Request $request) {

    	
    	$tasks  = DB::table('tasks')->where([['is_deleted', '=', '0']]);

    	if($request->has('id')){          

          	$tasks = $tasks->where('id','=',$request->input('id'));

       	}


    	if($request->has('filter_by')){

	    	$filter =	$request->input('filter_by');

			switch ($filter) {

	    			case 'today':
	    					
	    				$tasks = $tasks->whereDate('due_date', Carbon::today());

	    			break;

	    			case 'week':

	    				$tasks = $tasks->whereBetween('due_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
	    				
	    			break;

	    			case 'nextweek':

	    				$tasks = $tasks->where('due_date', '=', Carbon::now()->subWeek());
	    				
	    			break;

	    			case 'overdue':

	    				$tasks = $tasks->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(7)]);
				
	    			break;
	    				
					case 'month':

	    				$tasks = $tasks->whereMonth('due_date', date('m')) ->whereYear('due_date', date('Y'));
	    				
	    			break;
	    			
	    			default:
	    				
	    			break;
	    		}	        
          
      	}

        if($request->has('title')){

          $search_key =  $request->input('title');

          $tasks =  $tasks->where(function ($query) use ($search_key) {
               $query->where('title','like', '%'.$search_key.'%');
           });
       	}
    	
		$tasks  = $tasks->orderBy('due_date', 'asc')->get()->toJson(JSON_PRETTY_PRINT);

    	return response($tasks, 200);

    }


   	public function insertTask(Request $request)
    {
        
        $task = new Task;

		$request->due_date = Carbon::parse($request->due_date)->format('Y-m-d');

        $validator = $request->validate(['title' 		=> 'required',
    							  		  'due_date' 	=> 'required | date ',
  										]);

        $task->title         = $request->title;
        $task->due_date      = $request->due_date;
        $task->status        = 1;
        $task->is_deleted	 = 0;
        
        $task->save();
        
        return response()->json([
            'message' => 'Successfully Added Task!'
        ], 201);    	
    }

    public function insertSubTask(Request $request)
    {
        
        $task = new Subtask;

		$request->due_date = Carbon::parse($request->due_date)->format('Y-m-d');

        $validator = $request->validate([ 'parent_id'	=> 'required',
        								  'title' 		=> 'required',
    							  		  'due_date' 	=> 'required | date ',
  										]);

        $task->parent_id     = $request->parent_id;
        $task->title         = $request->title;
        $task->due_date      = $request->due_date;
        $task->status        = 1;
        $task->is_deleted	 = 0;
        
        $task->save();
        
        return response()->json([
            'message' => 'Successfully Added Subtask!'
        ], 201);    	
    }


    public function updateTask(Request $request)
    {
        
        $task = new Task;

		$request->due_date = Carbon::parse($request->due_date)->format('Y-m-d');

        $validator = $request->validate(['title' 		=> 'required',
    							  		  'due_date' 	=> 'required | date ',
  										]);

        $task->title         = $request->title;
        $task->due_date      = $request->due_date;
        $task->status        = $request->status;
        $task->is_deleted	 = 0;
        
        $task->save();
        
        return response()->json([
            'message' => 'Successfully Updated Task!'
        ], 201);    	
    }

    public function deleteTask(Request $request)
    {
        
        DB::table('tasks') 
        ->where('id','=', $request->id)
        ->update(['is_deleted' => 1]);

        return response()->json(['message' => 'Deleted Task!'], 201); 
   	
    }



    // public function deleteOldTask(Request $request)
    // {
        
      
    //    $affected = 	DB::table('tasks') 
    //         ->whereRaw('DATE(due_date) < DATE_SUB(CURDATE(), INTERVAL 30 DAY)')
    //         ->delete();

	   //  return response()->json(['message' => 'Number of Tasks Deleted - '.$affected], 201); 
   	
    // }

}
