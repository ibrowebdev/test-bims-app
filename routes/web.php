<?php

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Socialite;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});
Route::get('/auth/callback', function (Request $request) {
    // dd($request->all());

    return redirect(route('verify', ['code' => $request->query('code')]));
})->name('auth.callback');

Route::get('/verify', function (Request $request) {
    // dd($request->all());
    $id = "BIMS-25-ZZSGBP";
    $secret = "4na9GXevfCCJ7z6qxXjN2TvdwJNVkFhRujSvJ0DL";
    $bims_id = "BIMS01K9PP2RZ22A7FV304RM2QYWZP";
    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post('http://account.bims.test/oauth/token', [
        'client_id' => $id,
        'client_secret' => $secret,
        'redirect_uri' => 'http://test-bims-app.test/auth/callback/',
        'code' => $request->query('code'),
        'grant_type' => 'authorization_code',
    ]);
    return redirect(route('redirect-url', ['user' => $response->json()]));
})->name('verify');

Route::get('auth', AuthController::class)->name('redirect-url');

Route::get('/go-to-bims', function () {
    $bimsClient = Config::get('services.bims');
    $id = "BIMS-25-ZZSGBP";
    $secret = "4na9GXevfCCJ7z6qxXjN2TvdwJNVkFhRujSvJ0DL";
    $bims_id = "BIMS01K9PP2RZ22A7FV304RM2QYWZP";
    $hash = sha1($id . $secret . $bims_id);
    $token = base64_encode($hash);
    $url = "http://account.bims.test/oauth/authorize?response_type=code&client_id=$id&redirect_uri=http://test-bims-app.test/auth/callback/&state=dkdk&prompt=consent";
    // $response = Http::withToken($token)->get($url);
    // return $response;
    return redirect($url);
})->name('go-to-bims');
require __DIR__.'/settings.php';
