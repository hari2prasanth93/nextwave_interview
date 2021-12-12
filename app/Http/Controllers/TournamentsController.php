<?php

namespace App\Http\Controllers;

use App\Models\DeviceId;
use App\Models\Score;
use App\Models\Tournament;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;


class TournamentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $jwt_token=$this->request->header('credentials');
        $key = env("JWT_SECRET_KEY");
        $this->jwt_token_decoded = JWT::decode($jwt_token,$key,['HS256']);
    }

    public function tournamentList(){

        $user = Auth::user();
        $device_id = $this->jwt_token_decoded->device_id;

        $device_data = DeviceId::find($device_id);

        return Tournament::where('platform',$device_data->platform)->orWhere('platform','all')->get();
    }

    public function addScore($id){
        $this->validate($this->request, [
            'score'     => 'required'
        ]);
        $user = Auth::user();
        $device_id = $this->jwt_token_decoded->device_id;

        $device_data = DeviceId::find($device_id);
        if(Tournament::where('id',$id)->where(function ($query) use($device_data){
            $query->where('platform',$device_data->platform);
            $query->orWhere('platform','all');
        })->exists()){
          $add_score = Score::firstOrCreate(['tournament_id'=>$id,'user_id'=>$user->id],['score'=>$this->request->input('score')]);
          if(!$add_score->wasRecentlyCreated){
            $add_score->score += $this->request->input('score'); 
            $add_score->save();
          }
          return  response()->json(['score'=>$add_score->score]);
        }else{
            return response()->json(['message'=>'Tournament not exists']);
        }
       
    }

    public function leaderBoard($id){
        $user = Auth::user();
        $device_id = $this->jwt_token_decoded->device_id;

        $device_data = DeviceId::find($device_id);
        if(Tournament::where('id',$id)->where(function ($query) use($device_data){
            $query->where('platform',$device_data->platform);
            $query->orWhere('platform','all');
        })->exists()){

            $score_list = Score::where('tournament_id',$id)->where(function ($query){
                $query->whereBetween('grade',['1','10']);
                $query->orWhere('user_id',Auth::user()->id);
            })->orderBy('grade','ASC')->with('user')->get();

            $result=array();
            foreach($score_list as $datum){
                $result[]=array(
                    'rank'=>$datum['grade'],
                    'score'=>$datum['score'],
                    'username'=>$datum['user']['username']
                );
            }

            return $result;

        }else{
            return response()->json(['message'=>'Tournament not exists']);
        }
    }
}
