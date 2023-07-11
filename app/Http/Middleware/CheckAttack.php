<?php

namespace App\Http\Middleware;

use Closure;
use App\Utility;

class CheckAttack
{
    
    private static $bannedIp = [
      "5.121.133.60",
      "116.58.232.82",
      "43.228.131.115"
    ];

    private static $bannedUserAgent = [
		  "Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.1; WebCruiser/3.5)"
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   

        $ip = Utility::get_client_ip();
        $useragent = $request->server('HTTP_USER_AGENT');
        
        if (in_array($ip, Self::$bannedIp))
            abort(404);
            
        if (in_array($useragent, Self::$bannedUserAgent))
			      abort(404);

        return $next($request);
    }
}
