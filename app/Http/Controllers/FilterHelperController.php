<?php

namespace App\Http\Controllers;


use App\Kelas;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilterHelperController extends Controller
{

    public function taskQuery(Request $request)
    {
        $task= Task::where('unit_id','=',$request->input('unit_id'))->get();
        return response()->json([
            'task' => $task
        ]);
    }
}
