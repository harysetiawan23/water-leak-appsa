<?php


namespace App\Http\Controllers\Api;



use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\NodeRecordEvent;


use App\User;
use App\LineMaster;
use App\leak_event;


use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;





class LineRecord extends Controller
{
    //

    public function eventData(Request $request){



        if($request->input('nodeId')=="SN001"){
            $callPoc = DB::select('call nodeData(?,?,?,?)',array(
                $request->input('nodeId'),
                "110",
                "110",
                $request->input('liters')
            ));
        }elseif($request->input('nodeId')=="SN002"){
            $callPoc = DB::select('call nodeData(?,?,?,?)',array(
                $request->input('nodeId'),
                "30",
                "30",
                $request->input('liters')
            ));
        }



        // $callPoc = DB::select('call nodeData(?,?,?,?)',array(
        //     $request->input('nodeId'),
        //     $request->input('flow'),
        //     $request->input('pressure'),
        //     $request->input('liters')
        // ));


        $leakChecker = DB::select('select le.* from leak_events le where le.solved = 0 and le.informed = 0');

        $lineRecord = DB::select('call lineStat()');
        $lineIds = [];
        $lineRecords = [];
        $lineGraph = [];

        foreach($leakChecker as $leakage){
            $user = User::find($leakage->user_id);
            $lineMaster = LineMaster::find($leakage->line_id);

            $leakEvent = leak_event::find($leakage->id);
            $leakEvent->informed = "1";
            $leakEvent->save();


            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
            $token= $user->fcm_token;


            $notification = [
                'body' => 'Leakage Occured Line '.$lineMaster->name,
                'sound' => true,
            ];

            $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

            $fcmNotification = [
                //'registration_ids' => $tokenList, //multple token array
                'to'        => $token, //single token
                'notification' => $notification,
                'data' => $extraNotificationData
            ];

            $headers = [
                'Authorization: key=AIzaSyBEbUAa_tsXEMFnNuRiiVhjXl8ZEr0Dsag',
                'Content-Type: application/json'
            ];


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            $result = curl_exec($ch);
            curl_close($ch);


            dd($result);
        }



        foreach($lineRecord as $lineRec){
            array_push($lineIds,$lineRec->id);
        }

        $resultLineRecords = [];
        foreach($lineRecord as $record){
            $resultLineRecords = array_add($resultLineRecords, $record->id, $record);
        }

        foreach($lineIds as $id){
            $data = ['lineId'=>$id,
            'data'=> DB::select(DB::raw('call lineHourlyRecordMax('.$id.')')),
        ];
            array_push($lineGraph,$data);
        }



        event(new NodeRecordEvent(['stat'=>$lineRecord,'graph'=>$lineGraph]));

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $database = $firebase->getDatabase();
        $ref = $database->getReference('lineRecord');
        $ref->getChild("current")->set($resultLineRecords);

        return response()->json('success',200);
    }



    public function selectTest(){
        $lineRecord = DB::select('call lineStat()');

        return response()->json($lineRecord,200);
    }

    public function history(Request $request,$hour)
    {
        $line_history = DB::table('leaks_events')
        ->where('user_id','=',$request->user()->id)
        ->limit(($hour*60))
        ->orderBy('id','desc')
        ->get();


        return response()->json($line_history,200);
    }


    public function latest(Request $request,$lineId)
    {

        $line_latest = DB::select(DB::raw('select lm.id,lm.user_id,
        (select flow_leak_ratio from leaks_events where id=(select max(id) from leaks_events where le.line_id = lm.id)) as flow_rate,
        (select pressure_leak_ratio from leaks_events where id=(select max(id) from leaks_events where le.line_id = lm.id)) as pressure_rate
        from line_masters lm
        left join leaks_events le on lm.id = le.line_id
        where lm.user_id  = '.$request->user()->id.'
        group by lm.id'));
        return response()->json($line_latest,200);
    }

    public function lineHistory(Request $request,$lineId){

        $lineHistory = DB::select('call lineRecordHistory(?)',array(
            $lineId,
        ));


        return response()->json($lineHistory,200);
    }


    public function lineHourlyRecord(Request $request,$lineId){
        $lineRecap = DB::select(DB::raw('call lineHourlyRecordMedian('.$lineId.')'));

        return response()->json(array_reverse($lineRecap), 200);
    }


    public function lineHourlyRecap(Request $request,$lineId){
        $lineTestMedian = DB::select(DB::raw('call lineHourlyRecordMedian('.$lineId.')'));
        $lineTestAvgStart = DB::select(DB::raw('call lineHourlyRecordAvg('.$lineId.',1)'));
        $lineTestAvgStop = DB::select(DB::raw('call lineHourlyRecordAvg('.$lineId.',0)'));
        $lineTestMaxStart = DB::select(DB::raw('call lineHourlyRecordMax('.$lineId.',1)'));
        $lineTestMaxStop = DB::select(DB::raw('call lineHourlyRecordMax('.$lineId.',0)'));


        $lineTestMax = [
            'startRecord'=>$lineTestMaxStart,
            'endRecord'=>$lineTestMaxStop
        ];

        $lineTestAvg = [
            'startRecord'=>$lineTestAvgStart,
            'endRecord'=>$lineTestAvgStop
        ];

        $lineRecap = [
            'avg'=>$lineTestAvg,
            'max'=>$lineTestMax,
            'median'=>$lineTestMedian
        ];


        return response()->json(array_reverse($lineRecap), 200);
    }


    // public function notification($token, $title)
    public function notification(Request $request)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token='ex1kasIqeCE:APA91bH1r4AdW4iYo5jgAD7eiVaJdeKBZXuv1EBD2uyOLfM8sd_A9BUT5UYkf_YSEj6d2iKn6RtZVf6fH54jVtuCjBfRXq8ikz0jCtxRMEJZyEj4sryr1Xb24MSe6cTla0IL6TX6C9lp';


        $notification = [
            'body' => 'this is test',
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=AIzaSyBEbUAa_tsXEMFnNuRiiVhjXl8ZEr0Dsag',
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


        dd($result);
    }
}
