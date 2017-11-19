<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Illuminate\Http\Response;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/aritcleAnalysis','EmotionController@aritcleAnalysis');


Route::get('/testPost',function(){
    $csrf_token = csrf_token();
    $form = <<<FORM
        <form action="/hello" method="POST">
            <input type="hidden" name="_token" value="{$csrf_token}">
            <input type="submit" value="Test"/>
        </form>
FORM;
    return $form;
});

// Route::post('/hello',function(){
//     return "Hello Laravel[POST]!";
// });

Route::get('/hello/{name?}',function($name="Laravel"){
    return "Hello {$name}!";
})->where('name','[A-Za-z]+');


Route::get('/hello/laravelacademy',['as'=>'academy',function(){
    return 'Hello LaravelAcademy！';
}]);


// Route::get('/testNamedRoute',function(){
//    return route('academy');
// });

Route::get('/testNamedRoute',function(){
    return redirect()->route('academy');
});

Route::resource('post','PostController');

Route::controller('request','RequestController');

Route::get('/testResponse',function(){
	$content = 'Hello LaravelAcademy！';
	$status = 200;
	$value = 'text/html;charset=utf-8';
	//设置cookie有效期为30分钟，作用路径为应用根目录，作用域名为laravel.app
    return response($content,$status)->header('Content-Type',$value)
        ->withCookie('site','LaravelAcademy.org',30,'/','laravel.app');

});

Route::get('testResponseJson',function(){
    return response()->json(['name'=>'LaravelAcademy','passwd'=>'LaravelAcademy.org']);
});

Route::get('testResponseDownload',function(){
    return response()->download(
        realpath(base_path('public/images')).'/laravel-5-1.jpg',
        'Laravel学院.jpg'
    );
});

Route::resource('test','TestController');