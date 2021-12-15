<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlertController extends Controller
{

    public function __construct() 
    {
       // $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $show = Alert::get();
        return response()->json([$show]);
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
            'product_id' => 'required',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()],401);

        }

        Alert::create([
            'product_id' => $request->product_id,
            'name'=> $request->name,
        ]);
        return response()->json(['200' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = Alert::join('products', 'products.id', '=', 'alerts.product_id')
        ->select('products.name as product_name','alerts.*')
        ->where('alerts.id', $id)
        ->get();

        return $show;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function edit(Alert $alert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alert $alert)
    {
        $edited = Alert::find($request->id);

        $product_id = $request->product_id;
        $name = $request->name;
        
        $edited->product_id = $product_id ;
        $edited->name = $name;
        $edited->save();

        return response()->json(['200' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function destroy($alert)
    {
        $destroy = Alert::find($statistic);
        $destroy->delete();
        return response()->json(['200' => 'success']);
    }
}
