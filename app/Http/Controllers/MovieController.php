<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class MovieController extends Controller
{
    public function searchMovies(Request $request){
        $curl=curl_init();
		curl_setopt_array($curl,array(
			CURLOPT_URL => env('OMDB').'s='.$request->title,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "{}",
		));
		$response = curl_exec($curl);
		curl_close($curl);
        $res=json_decode($response);
        $arr=[];
        if($res->Response=="True"){
            foreach($res->Search as $movie){
                $title=str_replace("&","%26",$movie->Title);
                $title2=str_replace(" ","%20",$title);
                //echo $title.'<br/>';
                $curl=curl_init();
                curl_setopt_array($curl,array(
                    CURLOPT_URL => env('OMDB').'t='.$title2,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_POSTFIELDS => "{}",
                ));
                $response2 = curl_exec($curl);
                curl_close($curl);
                $res2=json_decode($response2);
                $title=str_replace("'","",$movie->Title);
                $arr[]=['title'=>$title,'year'=>$movie->Year,'img'=>$movie->Poster,'rating'=>$res2->imdbRating,'imdbID'=>$movie->imdbID];
            }
            print_r(json_encode($arr));
        }
    }
    public function checkDuplicates(Request $request){
        $movie=Movie::where('imdbID',$request->imdbID)->first();
        if(!isset($movie->id))
            echo 0;
        else
            echo $movie->id;
    }
    public function store(Request $request){
        $input=$request->all();
		extract($input);
        $movie=new Movie;
        $movie->user_id=Auth::id();
        $movie->title=$title;
        $movie->imdbID=$imdbID;
        $movie->year=$year;
        $movie->rated=$rate;
        $movie->poster=$img;
        $movie->save();
        return $movie;
    }
    public function index(){
        $user_id=Auth::id();
        $movies=Movie::select('id','title','year','rated','poster')->where('user_id',$user_id)->orderBy('title','ASC')->get();
        return $movies;
    }
    public function destroy(Request $request){
        $movie=Movie::find($request->id);
        $movie->delete();
        return $movie;
    }
}
