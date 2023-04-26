<?php

namespace App\Http\Middleware;

use Closure;

class AuthenChatHub
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $key = ['052cf91d863c20b445c974c970bda607'];
    public function handle($request, Closure $next)
    {
        $chatHubKey = $request->header('chat-hub-key');
        if($chatHubKey == null || !in_array($chatHubKey, $this->key)){
            return redirect('home');
        }
        return $next($request);
    }
}
