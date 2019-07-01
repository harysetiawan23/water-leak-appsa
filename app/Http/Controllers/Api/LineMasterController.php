<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LineMaster;
use Validator;


class LineMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // $lineMaster = DB::select(DB::raw('select lm.*,
        // (select flow_leak_ratio from leaks_events where id=(select max(id) from leaks_events where le.line_id = lm.id)) as flow_rate,
        // (select pressure_leak_ratio from leaks_events where id=(select max(id) from leaks_events where le.line_id = lm.id)) as pressure_rate
        // from line_masters lm
        // left join leaks_events le on lm.id = le.line_id
        // where lm.user_id  = '.$request->user()->id.'
        // group by lm.id'));


        $lineMaster = DB::table('line_masters')
        ->where('user_id','=',$request->user()->id)
        ->get();


        return response()->json($lineMaster,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'start_node_id'=>'required',
            'end_node_id'=>'required',
            'distance'=>'required',
            'diameter'=>'required',
            'thicknes'=>'required',
            'manufacture'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }


        $lineMaster = new LineMaster([
            'name' => $request->input('name'),
            'start' => $request -> input('start'),
            'end' => $request->input('end'),
            'distance'=>$request->input('distance'),
            'diameter' => $request->input('diameter'),
            'thicknes' => $request -> input('thicknes'),
            'manufacture' => $request->input('manufacture'),
            'start_node_id'=>$request->input('start_node_id'),
            'end_node_id'=>$request->input('end_node_id'),
            'user_id'=> $request->user()->id

        ]);

        if(!$lineMaster->save()){
            return response()->json(['error'=>'database not connected'], 400);
        }


        return response()->json($lineMaster, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'start_node_id'=>'required',
            'end_node_id'=>'required',
            'distance'=>'required',
            'diameter'=>'required',
            'thicknes'=>'required',
            'manufacture'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $lineMaster = LineMaster::find($id);


        $lineMaster->name = $request->input("name");
        $lineMaster->start = $request->input("start");
        $lineMaster->end = $request->input("end");
        $lineMaster->start_node_id = $request->input("start_node_id");

        $lineMaster->distance = $request->input("distance");
        $lineMaster->diameter = $request->input("diameter");
        $lineMaster->thicknes = $request->input("thicknes");
        $lineMaster->manufacture = $request->input("manufacture");

        $lineMaster->end_node_id = $request->input("end_node_id");


        if(!$lineMaster->save()){
            return response()->json(['error'=>'database not connected'], 400);
        }

        return response()->json($lineMaster, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $lineMaster = LineMaster::find($id);
        if(!$lineMaster->delete()){
            return response()->json(['error'=>'database not connected'], 400);
        }else{
            return response()->json(['message'=>'success'], 200);
        }


    }


    public function lineList(Request $request)
    {
        $lineList = DB::select(DB::raw('select
        lm.*,
        (select nm.sn from node_masters nm where nm.id = lm.start_node_id) as startNodeSN,
        (select nm.sn from node_masters nm where nm.id = lm.end_node_id) as EndNodeSN,
        (select nm.phone_number from node_masters nm where nm.id = lm.start_node_id) as startNodePhone,
        (select nm.phone_number from node_masters nm where nm.id = lm.end_node_id) as endNodePhone,
        (select nm.lat from node_masters nm where nm.id = lm.start_node_id) as startNodeLat,
        (select nm.lng from node_masters nm where nm.id = lm.start_node_id) as startNodeLng,
        (select nm.lat from node_masters nm where nm.id = lm.end_node_id) as endNodeLat,
        (select nm.lng from node_masters nm where nm.id = lm.end_node_id) as endNodeLng
        from line_masters lm
        where user_id = '.$request->user()->id.'
        '));


        return response()->json($lineList,200);
    }


    public function lineListById(Request $request,$id)
    {
        $lineList = DB::select(DB::raw('select
        lm.*,
        (select nm.sn from node_masters nm where nm.id = lm.start_node_id) as startNodeSN,
        (select nm.sn from node_masters nm where nm.id = lm.end_node_id) as EndNodeSN,
        (select nm.phone_number from node_masters nm where nm.id = lm.start_node_id) as startNodePhone,
        (select nm.phone_number from node_masters nm where nm.id = lm.end_node_id) as endNodePhone,
        (select nm.lat from node_masters nm where nm.id = lm.start_node_id) as startNodeLat,
        (select nm.lng from node_masters nm where nm.id = lm.start_node_id) as startNodeLng,
        (select nm.lat from node_masters nm where nm.id = lm.end_node_id) as endNodeLat,
        (select nm.lng from node_masters nm where nm.id = lm.end_node_id) as endNodeLng
        from line_masters lm
        where user_id = '.$request->user()->id.'
        and lm.id = '.$id.'
        '));


        return response()->json($lineList,200);
    }



}
