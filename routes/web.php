<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

/*composer global require laravel/installer
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



// Forntend routs 
// home route
Route::get('/', [FrontendController::class, 'index'])->name('website.index');
// about route 
Route::get('/about', [FrontendController::class, 'about'])->name('website.about');
// category route 
Route::get('/category/{slug}', [FrontendController::class, 'category'])->name('website.category');
// Tag route 
Route::get('/tag/{slug}', [FrontendController::class, 'tag'])->name('website.tag');
// contact route 
Route::get('/contact', [FrontendController::class, 'contact'])->name('website.contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
// post route 
Route::get('/post/{slug}', [FrontendController::class, 'post'])->name('website.post');







// single post or post detais route 

// frontend routs end 

// Backend Routes start

route::group(['prefix' => '/admin', 'middleware' => ['auth']],  function () {
    route::get('/', [DashboardController::class, 'index'])->name('home');
    // category
    route::resource('category', 'CategoryController');
    // tag
    route::resource('tag', 'TagController');
    // Post 
    route::resource('post', 'PostController');
    Route::post('post/delete/{id}', 'PostController@delete')->name('post.delete');
    // user routs 
    route::resource('user', 'UserController');
    Route::post('user/delete/{id}', 'UserController@delete')->name('user.delete');
    Route::get('/profile', 'UserController@profile')->name('user.profile');
    // profile 
    Route::get('/profile/update', 'UserController@profileupdate')->name('profile.update');


    // settings route 
    Route::get('settings', 'FrontendSettingController@edit')->name('settings.edit');
    Route::post('settings/update', 'FrontendSettingController@update')->name('settings.update');



    // message route 
    route::get('message/inbox', 'ContactController@index')->name('contacet.index');
    route::get('/message/read/{id}', 'ContactController@viewmessage')->name('contacet.viewmessage');
    //team about section
    route::resource('team', 'TeamController');
});
