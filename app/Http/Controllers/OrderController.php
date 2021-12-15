<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function __construct() 
    {
        //$this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $show = Order::get();
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
            'nameBuyer' => 'required|string',
            'dateOrder' => 'required',
            'dateDeliver' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()],401);
        }


        Order::create([
            'nameBuyer' => $request->nameBuyer,
            'dateOrder'=> $request->dateOrder,
            'dateDeliver' => $request->dateDeliver,
        ]);
        return response()->json(['200' => 'success']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = Order::where('id', $id)->get();
        return $show;
    }

    /**
     * Display the specified resource in relationship.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function product($id)
    {
        $show = Order::find($id)
        ->products()
        ->get();
        return $show;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $edited = Order::find($request->id);


        $nameBuyer = $request->nameBuyer;
        $dateOrder = $request->dateOrder;
        $dateDeliver = $request->dateDeliver;

        $edited->nameBuyer = $nameBuyer;
        $edited->dateOrder = $dateOrder;
        $edited->dateDeliver = $dateDeliver;
        $edited->save();

        return response()->json(['200' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($order)
    {
        $destroy = Order::find($order);
        $destroy->delete();
        return response()->json(['200' => 'success']);
    }
}
