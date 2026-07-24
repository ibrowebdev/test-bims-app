<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->input('user')['user'];
        $user = User::updateOrCreate(
            [
                'email' => $user['email'],
            ],
            [
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'password' => 'password',
            ],
        );
        Auth::login($user);
        return redirect(route('dashboard'));
//   "user": {
//       "id": 6212,
//       "bims_id": "BIMS23101STD27069"
//       "first_name": "John",
//       "last_name": "Doe",
//       "email": "johndoe3@gmail.com",
//       "phone": "07011227815",
    }
}
