<?php

namespace Selfreliance\AccessLog\Middleware;

use Closure;

use Selfreliance\AccessLog\Models\Log_Access;
use Illuminate\Support\Facades\Auth;
class LogAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $this->log($request, $response);
    }

    protected function log($request, $response){
        // dd($request->json()->all());
        // if($request->routeIs('push_transaction') OR $request->routeIs('push_transaction_block')){
        if($request->routeIs('push_transaction') OR $request->routeIs('push_transaction_block')){
             
        }else{
            $log                     = new Log_Access;
            $log->user_id            = (Auth::check())?Auth::user()->id:0;
            $log->method             = $request->method();
            $log->status_code        = $response->getStatusCode();
            $log->request_parameters = http_build_query($request->query());
            $log->request_json       = json_encode($request->all());
            if($response->headers->get('content-type') == 'application/json')
            {
                $log->response_json = json_encode($response->original);    
                $log->is_json       = 1;
            }else{
                if(isset($response->exception->validator)){
                    $log->response_json  = json_encode($response->exception->validator->errors());
                    $log->status_code = $response->exception->status;
                }else{
                    // \Log::info('response', [$response]);
                    // if(strlen($response->getContent()) < 255){
                    //     $log->response_json = json_encode($response->getContent());
                    // }
                }
                $log->is_json       = 0;
            }
            if($request->header('User-Agent')){
                $log->user_agent     = $request->header('User-Agent');
            }else{
                $log->user_agent     = "";
            }
            $log->request_uri        = $request->path();
            $log->ip                 = real_ip();
            $array = [
                'libs/bootstrap/css/bootstrap.min.css.map',
                'libs/selectpicker/js/bootstrap-select.js.map',
                'check_auth',
            ];
            if(!in_array($log->request_uri, $array)){
                $log->save();
            }
        }

        // $log = "{$ip: [{$status}] {$method}@{$url} - {$duration}ms";

        // dump($log);
    }

    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}
