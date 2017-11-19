<?php

namespace App\Http\Controllers;

use App\Article;
use App\User;
use App\Users_emotions;
use App\Emotions;
use App\Notes_emotions;
use App\Articles_emotions;  
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmotionController extends Controller
{
    /**
     * 情绪分析
     * 情绪表只要根据刚更新article_emotions 或者 note_emotions 来判断要不要插入删除
     * @param  [type] $note_emotions    [description]
     * @param  [type] $article_emotions [description]
     * @return [type]                   [description]
     */
    public function emotionAnalysis($note_emotions=null,$article_emotions=null) 
    {
    	if ($note_emotions == null && $article_emotions ==null) {
    		return 0;
    	} else {
    		$emotion = new Emotions();
    		$result = $emtion->where('content',$note_emotions)->orWhere('content',$article_emotions)->get();
    		if($result != null) {
    			return $result->id;
    		} else {
    			if($note_emotions != null) {
    				$emotion -> content = $note_emotions;
    			} elseif ($article_emotions != null) {
    				$emotion -> content = $article_emotions;
    			}
    			$emotion -> save();
    		}
    		$result = $emtion->where('content',$note_emotions)->orWhere('content',$article_emotions)->get();
    		return $result->id;
    	}
    }

    /**
     *note的分析，实时更新，就是每次插入一条数据的时候就直接分析该条note的情绪，并更新note的情绪表。
     *然后更新情绪表后就去添加到emotions总表中去  ,这里要数组形式传入$note_data
     * @param  [type]  $note_data [description]
     * @param  integer $id        [description]
     * @return [type]             [description]
     */
    public function noteAnalysis($note_data,$id=0)
    {
    	$note  = new Note();    //记录
    	$note_content = $note_data['content'];  
    	$note_emotions = new Notes_emotions();  //记录的情绪表
    	$API_TOKEN = "1DwUEeoy.19320.SIh8ADsPbOAT";  //key
        $data =   $note_content;
	    $SENTIMENT_URL = 'http://api.bosonnlp.com/sentiment/analysis';   //情绪分析  只能分析到负面概率 和非负面概率
		$ch = curl_init();
		curl_setopt_array($ch, array(
		CURLOPT_URL => $SENTIMENT_URL,
		CURLOPT_HTTPHEADER => array(
			"Accept:application/json",
			"Content-Type: application/json",
			"X-Token: $API_TOKEN",
		),
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_RETURNTRANSFER => true,
		));
		$result = json_decode(curl_exec($ch));

		curl_close($ch);
		/**
		  关键提取
		**/
		$SENTIMENT_URL = 'http://api.bosonnlp.com/keywords/analysis';   //提取关键词  按权重去排序
		$ch = curl_init();
		curl_setopt_array($ch, array(
		  CURLOPT_URL => $SENTIMENT_URL,
		  CURLOPT_HTTPHEADER => array(
		   "Accept:application/json",
		   "Content-Type: application/json",
		   "X-Token: $API_TOKEN",
		  ),
		  CURLOPT_POST => true,
		  CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
		  CURLOPT_RETURNTRANSFER => true,
		)); 

		$result = json_decode(curl_exec($ch));   //提取前5个关键词 弄成字符串然后加入到数据库中去
		
		curl_close($ch);

    }


    /**
     * 文章情绪分析函数
     * @param  [type] $article_data [description]
     * @return [type]               [description]
     */
    public function aritcleAnalysis()
    {
    	// $article = new Article();  //文章
    	// $article_title = $article_data['title'];
    	// $article_content = $article_data['content'];
    	// $article_emotions = new Articles_emotions();  //文章情绪
        $API_TOKEN = "1DwUEeoy.19320.SIh8ADsPbOAT";  //key
        $data =  "rticle_content";
	    $SENTIMENT_URL = 'http://api.bosonnlp.com/sentiment/analysis';   //情绪分析  只能分析到负面概率 和非负面概率
		$ch = curl_init();
		curl_setopt_array($ch, array(
		CURLOPT_URL => $SENTIMENT_URL,
		CURLOPT_HTTPHEADER => array(
			"Accept:application/json",
			"Content-Type: application/json",
			"X-Token: $API_TOKEN",
		),
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_RETURNTRANSFER => true,
		));
		$result = json_decode(curl_exec($ch));//提取前5个关键词 弄成字符串然后加入到数据库中去
		echo $result[0][1];
		curl_close($ch);
		echo "<br />";
		/**
		  关键提取
		**/
		$SENTIMENT_URL = 'http://api.bosonnlp.com/keywords/analysis';   //提取关键词  按权重去排序

		$ch = curl_init();
		curl_setopt_array($ch, array(
		  CURLOPT_URL => $SENTIMENT_URL,
		  CURLOPT_HTTPHEADER => array(
		   "Accept:application/json",
		   "Content-Type: application/json",
		   "X-Token: $API_TOKEN",
		  ),
		  CURLOPT_POST => true,
		  CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
		  CURLOPT_RETURNTRANSFER => true,
		)); 
		$result = json_decode(curl_exec($ch));//提取前5个关键词 弄成字符串然后加入到数据库中去
        for ($i=0; $i < 100; $i++) { 
            # code...
            echo $result[$i][1]."<br />";
        }
		curl_close($ch);
    }

    /**
     * 用户情绪更新
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function updateEmotion($id)
    {
    	$note_emotions = new Notes_emotions();  //记录的情绪表  用户的情绪表可以根据用户的记录体现出来
    	$user_emotions = new Users_emotions();  //用户情绪表
    	$note = new Note();    //note表

    }

    /**
     * 用户情绪与文章的匹配
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function matchArticle($id)
    {
    	$user_emotions = new Users_emotions();    //该用户的情绪表
    	$today_emotions = $user_emotions->where('user_id',$id)->orderBy('modify_at', 'DESC')->first();   //获取该用户最新的情绪
    	if($today_emotions == null) {
    		return "<script>alert('用户不存在');location.href='http://111.231.18.37/learnlaravel5/public';</script>";
    	}
    	$article_emotions = new Articles_emotions();   //文章情绪表
        $article = new Article();    //文章   文章情绪表映射到文章
    	$result = $article_emotions->where('emotion_id',$today_emotions->emotion_id)-> orderBy(\DB::raw('RAND()')) -> take(10)->get(['article_id']);   //随机匹配数据
    	if ($result == null) {
    		$article_list = $article -> orderBy(\DB::raw('RAND()')) -> take(10) -> get(['id','title','author','publish_time','url','content']);
    	} else {
    	    $article_list = $article->whereIn('id',$result)->get(['id','title','author','publish_time','url','content']);
    	}
    	return json_encode($article_list);
    }
}
