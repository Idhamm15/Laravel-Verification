<?php

namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller\Auth\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // protected function redirectTo()
    // {
    //     // Retrieve the authenticated user
    //     $user = $this->guard()->user();

    //     // Check the role of the authenticated user
    //     if ($user->role === 'ADMIN') {
    //         return '/admin';
    //     } elseif ($user->role === 'SUPER ADMIN') {
    //         return '/admin';
    //     } else {
    //         return '/';
    //     }
    // }

    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if(auth()->attempt(['email'=>$input["email"], 'password'=>$input['password']]))
        {
            if(auth()->user()->role == 'ADMIN' || auth()->user()->role == 'SUPER ADMIN')
            {
                return redirect('/admin');
            }
            // else if(auth()->user()->role == 'SUPER ADMIN')
            // {
            //     return redirect('/admin');
            // }
            else
            {
                return redirect('/');
            }
        }
        else
        {
            return redirect()
            ->route("login")
            ->with("error",'Incorrect email or password');
        }
    }
}
