<?php

namespace Selfreliance\AccessLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;

use App\Models\Log_Access;
class AccessLogController extends Controller
{
    public function index(Request $request)
    {
        $status_code    = $request->input('status_code');
        $search    = $request->input('search');
    	$logs = Log_Access::
        where('status_code', '!=', 200)
        ->where(function ($query) use ($status_code) {
            if($status_code != ''){
                $query->where("status_code", $status_code);
            }
        })
        ->where(function ($query) use ($search) {
            if($search != ''){
                $query->where('id', 'LIKE', "%$search%")
                    ->orWhere('method', 'LIKE', "%$search%")
                    ->orWhere('request_json', 'LIKE', "%$search%")
                    ->orWhere('response_json', 'LIKE', "%$search%")
                    ->orWhere('request_parameters', 'LIKE', "%$search%")
                    ->orWhere('user_agent', 'LIKE', "%$search%")
                    ->orWhere('request_uri', 'LIKE', "%$search%")
                    ->orWhere('ip', 'LIKE', "%$search%");
            }
        })
        ->orderBy('id','desc')
        ->paginate(10);

        $logs->appends(['status_code' => $status_code]);
        $logs->appends(['search' => $search]);

    	$logs->each(function($row){
    		switch ($row->status_code) {
    			case '200':
    				$row->status_color = "success";
    				break;

                case '422':
                case '404':
                    $row->status_color = "warning";
                    break;
                
                case '419':
    			case '500':
    				$row->status_color = "red";
    				break;
    			case '302':
                    $row->status_color = "inverse";
                    break;
    			default:
    				# code...
    				break;
    		}

            if($row->ip == ''){
                $row->ip = "system";
            }

            if($row->user_id){
                $user = User::where('id',$row->user_id)->first();
                $row->user_name = $user->name;
            }
    		
    	});

        $CodesStatus = Log_Access::distinct()->get(['status_code']);
        $CodesStatus->each(function($row){
            // $row->count = Log_Access::where('status_code', $row->status_code)->count();

            switch ($row->status_code) {
                case '200':
                    $row->status_text = "Успешная загрузка";
                    break;

                case '422':
                    $row->status_text = "Ошибка валидации";
                    break;

                case '404':
                    $row->status_text = "Страница не найдена";
                    break;

                case '500':
                    $row->status_text = "Server error";
                    break;
                
                case '405':
                    $row->status_text = "Not allowed";
                    break;
                
                case '419':
                    $row->status_text = "Token missing";
                    break;

                case '302':
                    $row->status_text = "Перенаправление";
                    break;
                default:
                    $row->status_text = "undefined";
                    break;
            }
        });
    	return view('accesslog::show')->with([
            "logs"               => $logs,
            "CodesStatus"        => $CodesStatus, 
            "Filter_status_code" => $status_code,
            "Filter_search"      => $search
    	]);
    }

    public function destroy($id){
        $Log = Log_Access::where('id', $id)->delete();
        return redirect()->back();
    }
}