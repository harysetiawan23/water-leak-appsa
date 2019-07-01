<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\NodeMaster;
use Illuminate\Http\Request;
use Validator;

class NodeMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return response()->json("SUCK",200);
    }

    public function getAll(Request $request){
        $nodeMaster = DB::select("select nr.*
        from node_masters nr
        where nr.user_id = ?", [$request->user()->id]);
        return response()->json($nodeMaster, 200);
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

        $validator = Validator::make($request->all(), [
            'sn' => 'required|unique:node_masters,sn',
            'phone_number' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'isStartNode' => 'required',
            'pressOffset'=>'required',
            'liquidFlowKonstanta'=>'required',
            'flow_rate_model'=>'required',
            'pressure_tranducer_model'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['user_id'] = auth()->user()->id;
        $nodeMaster = new NodeMaster($input);

        if (!$nodeMaster->save()) {
            return response()->json(['error' => 'database not connected'], 200);
        }

        return response()->json($nodeMaster, 200);
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
        return response()->json(NodeMaster::find($id), 200);
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
        $validator = Validator::make($request->all(), [
            'sn' => 'required',
            'phone_number' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'isStartNode' => 'required',
            'pressOffset'=>'required',
            'liquidFlowKonstanta'=>'required',
            'flow_rate_model'=>'required',
            'pressure_tranducer_model'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $nodeMaster = NodeMaster::find($id);

        $nodeMaster->sn = $request->input('sn');
        $nodeMaster->phone_number = $request->input('phone_number');
        $nodeMaster->lat = $request->input('lat');
        $nodeMaster->lng = $request->input('lng');
        $nodeMaster->isStartNode = $request->input('isStartNode');
        $nodeMaster->pressOffset = $request->input('pressOffset');
        $nodeMaster->liquidFlowKonstanta = $request->input('liquidFlowKonstanta');
        $nodeMaster->flow_rate_model = $request->input('flow_rate_model');
        $nodeMaster->pressure_tranducer_model = $request->input('pressure_tranducer_model');


        $saved =  $nodeMaster->save();


        if (!$saved) {
            return response()->json(['error' => 'database not connected'], 200);
        }else{
            return response()->json($nodeMaster, 200);
        }


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
        $nodeMaster = NodeMaster::find($id);


        if (!$nodeMaster->delete()) {
            return response()->json(['error' => 'database not connected'], 200);
        }

        return response()->json(['success'=>'true'], 200);
    }
}
