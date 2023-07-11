<?php

namespace App\Providers;

use App\User;
use Illuminate\Auth\EloquentUserProvider;



class EloquentSimpleUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {

    		dd($credentials);
    		
         if (empty($credentials)) {
            return;
        }

        return User::where('username',$credentials['username'])->first();

         
    }
}