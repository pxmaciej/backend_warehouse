<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatisticController extends Controller
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
        $show = Statistic::get();
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
            'amount' => 'required',
            'price'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()],401);

        }

        Statistic::create([
            'product_id' => $request->product_id,
            'name'=> $request->name,
            'amount' => $request->amount,
            'price' => $request->price,
        ]);
        return response()->json(['200' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Statistic  $statistic
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = Statistic::join('products', 'products.id', '=', 'statistics.product_id')
        ->select('products.name as product_name','statistics.*')
        ->where('statistics.id', $id)
        ->get();

        return $show;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Statistic  $statistic
     * @return \Illuminate\Http\Response
     */
    public function edit(Statistic $statistic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Statistic  $statistic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Statistic $statistic)
    {
        $edited = Statistic::find($request->id);

        $product_id = $request->product_id;
        $name = $request->name;
        $amount = $request->amount;
        $price = $request->price;

        $edited->product_id = $product_id ;
        $edited->name = $name;
        $edited->amount = $amount;
        $edited->price = $price;
        $edited->save();

        return response()->json(['200' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Statistic  $statistic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statistic $statistic)
    {
        $destroy = Statistic::find($statistic);
        $destroy->delete();
        return response()->json(['200' => 'success']);
    }
}
