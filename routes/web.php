<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ConfigurationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\TemplateController;

Route::get('/', function () {
	return redirect('/dashboard');
})->middleware('auth');

// Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
// Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
// Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
// Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	// Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::get('/tables', [PageController::class, 'tables'])->name('tables');

	Route::get('/billing', [PageController::class, 'billing'])->name('billing');

	Route::get('/user-management', [PageController::class, 'userManagement'])->name('user-management');

	Route::post('logout', [LoginController::class, 'logout'])->name('logout');

	Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
	Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
	Route::post('/campaigns/store', [CampaignController::class, 'store'])->name('campaigns.store');
	Route::get('/campaigns/edit/{campaign}', [CampaignController::class, 'edit'])->name('campaigns.edit');
	Route::post('/campaigns/update/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
	Route::delete('/campaigns/destroy/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
	Route::post('/campaigns/proof', [CampaignController::class, 'proof'])->name('campaigns.proof');

	Route::get('/channels', [ChannelController::class, 'index'])->name('channels.index');
	Route::get('/channels/create', [ChannelController::class, 'create'])->name('channels.create');
	Route::post('/channels/store', [ChannelController::class, 'store'])->name('channels.store');
	Route::get('/channels/edit/{channel}', [ChannelController::class, 'edit'])->name('channels.edit');
	Route::post('/channels/update/{channel}', [ChannelController::class, 'update'])->name('channels.update');
	Route::delete('/channels/destroy/{channel}', [ChannelController::class, 'destroy'])->name('channels.destroy');

	Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
	Route::get('/templates/create', [TemplateController::class, 'create'])->name('templates.create');
	Route::post('/templates/store', [TemplateController::class, 'store'])->name('templates.store');
	Route::get('/templates/edit/{template}', [TemplateController::class, 'edit'])->name('templates.edit');
	Route::post('/templates/update/{template}', [TemplateController::class, 'update'])->name('templates.update');
	Route::delete('/templates/destroy/{template}', [TemplateController::class, 'destroy'])->name('templates.destroy');

	Route::get('/configurations', [ConfigurationController::class, 'index'])->name('configurations.index');
	Route::get('/configurations/create', [ConfigurationController::class, 'create'])->name('configurations.create');
	Route::post('/configurations/store', [ConfigurationController::class, 'store'])->name('configurations.store');
	Route::get('/configurations/edit/{configuration}', [ConfigurationController::class, 'edit'])->name('configurations.edit');
	Route::post('/configurations/update/{configuration}', [ConfigurationController::class, 'update'])->name('configurations.update');
	Route::delete('/configurations/destroy/{configuration}', [ConfigurationController::class, 'destroy'])->name('configurations.destroy');

	Route::get('/queries', [QueryController::class, 'index'])->name('queries.index');
	Route::get('/queries/create', [QueryController::class, 'create'])->name('queries.create');
	Route::post('/queries/store', [QueryController::class, 'store'])->name('queries.store');
	Route::get('/queries/edit/{query}', [QueryController::class, 'edit'])->name('queries.edit');
	Route::post('/queries/update/{query}', [QueryController::class, 'update'])->name('queries.update');
	Route::delete('/queries/destroy/{query}', [QueryController::class, 'destroy'])->name('queries.destroy');
});

Route::post('/get-chanel', [ChannelController::class, 'getChannel'])->name('get-chanel');

Route::get('/nexus', function () {
	$token = getNexusResponse();
	return response()->json($token);
});

Route::get('/sendNotification', function () {
	$token = sendNotification();

	return response()->json($token);
});
