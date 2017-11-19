<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Requests;

class TestController extends Controller
{
    //

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
	    $article = new Article;
	    $result  = $article->take(10)->get();
	    return response()->json([
             'error_code'=>200,
             'article'=>$result
	    ]);

	 }

}
