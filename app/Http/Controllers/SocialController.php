<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {

        $userSocial = Socialite::driver($provider)->user();

        session([
            'userId' => $userSocial->user['id']
        ]);

        return redirect()->route('home');
    }

    public function webhook()
    {
        file_put_contents(storage_path('mylog.log'), "== GET ==\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), print_r($_GET, 1) . "\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), "== END GET ==\n", FILE_APPEND);

        file_put_contents(storage_path('mylog.log'), date('d.m.y H:i:s') . "[]== POST ==\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), print_r($_POST, 1) . "\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), file_get_contents('php://input') . "\n", FILE_APPEND);
        file_put_contents(storage_path('mylog.log'), "== END POST ==\n", FILE_APPEND);


        if(isset($_GET['hub_challenge'])) {
            echo $_GET['hub_challenge'];
        }

        //return view('webhook');
    }
}
