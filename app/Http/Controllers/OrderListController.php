<?php

namespace App\Http\Controllers;

use App\Models\OrderList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderListController extends Controller
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
        $show = OrderList::get();
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
            'order_id' => 'required',
            'amount' => 'required',
            'price'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()],401);

        }

        OrderList::create([
            'product_id' => $request->product_id,
            'order_id'=> $request->order_id,
            'amount' => $request->amount,
            'price' => $request->price,
        ]);
        return response()->json(['200' => 'success']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderList  $orderList
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = OrderList::join('products', 'products.id', '=', 'order_lists.product_id')
        ->join('orders', 'orders.id', '=', 'order_lists.order_id')
        ->select('order_lists.*', 'products.name', 'orders.*')
        ->where('order_lists.id', $id)
        ->get();

        return $show;
    }

    /**
     * Display the specified resource where order_id = param.
     *
     * @param  \App\Models\OrderList  $orderList
     * @return \Illuminate\Http\Response
     */
    public function order($id)
    {
        $show = OrderList::join('products', 'products.id', '=', 'order_lists.product_id')
        ->join('orders', 'orders.id', '=', 'order_lists.order_id')
        ->select('order_lists.*', 'products.name', 'orders.*')
        ->where('order_lists.order_id', $id)
        ->get();

        return $show;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderList  $orderList
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderList $orderList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderList  $orderList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderList $orderList)
    {
        $edited = OrderList::find($request->order_id);

        $name = $request->name;
        $category = $request->category;
        $company = $request->company;
        $amount = $request->amount;
        $price = $request->price;

        $edited->name = $name;
        $edited->category = $category;
        $edited->company = $company;
        $edited->amount = $amount;
        $edited->price = $price;
        $edited->save();

        return response()->json(['200' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderList  $orderList
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderList $orderList)
    {
        $destroy = OrderList::find($orderList);
        $destroy->delete();
        return response()->json(['200' => 'success']);
    }
}
