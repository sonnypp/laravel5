<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{
    //
    public function getBasetest(Request $request)
    {
        $input = $request->input('test');
        echo $input;
    }
    public function getMethod(Request $request)
    {
	    //非get请求不能访问
	    if(!$request->isMethod('get')){
	        abort(404);
	    }
	    $method = $request->method();
	    echo $method;
	}

	public function getInputData(Request $request)
	{

	    $allData = $request->all();
	    $onlyData = $request->only('name','hello');
	    $exceptData = $request->except('hello');

	    echo '<pre>';
	    print_r($allData);
	    print_r($onlyData);
	    print_r($exceptData);
	    echo '</pre>';
	}

	public function getCookie(Request $request){
	    $cookies = $request->cookie();
	    dd($cookies);
	}

	//文件上传表单
	public function getFileupload()
	{
	    $postUrl = '/request/fileupload';
	    $csrf_field = csrf_field();
	    $html = <<<CREATE
			<form action="$postUrl" method="POST" enctype="multipart/form-data">
				$csrf_field
			<input type="file" name="file"><br/><br/>
			<input type="submit" value="提交"/>
			</form>
CREATE;
	    return $html;
	}
	//文件上传处理
    public function postFileupload(Request $request)
    {
	    if($request->isMethod('POST')){  
            //var_dump($_FILES);  
            $file = $request->file('file');  
  
            //判断文件是否上传成功  
            if($file->isValid()){  
                //获取原文件名  
                $originalName = $file->getClientOriginalName();  
                //扩展名  
                $ext = $file->getClientOriginalExtension();  
                //文件类型  
                $type = $file->getClientMimeType();  
                //临时绝对路径  
                $realPath = $file->getRealPath();  
  
                $filename = date('Y-m-d-H-i-S').'-'.uniqid().'-'.$ext;  
  
                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));  
  
                var_dump($bool);  
            }  
            exit;  
        }  
	}


}
