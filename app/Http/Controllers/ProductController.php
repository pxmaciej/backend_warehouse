<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
        $show = Product::get();
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
            'name' => 'required|string',
            'category' => 'required|string',
            'company' => 'required|string',
            'amount' => 'required',
            'price'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()],401);

        }

        Product::create([
            'name' => $request->name,
            'category'=> $request->category,
            'company' => $request->company,
            'amount' => $request->amount,
            'price' => $request->price,
        ]);
        return response()->json(['200' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = Product::where('id', $id)->get();
        return $show;
    }

    public function order($id)
    {
        $show = Product::find($id)
        ->orders()
        ->get();
        return $show;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id_product)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $edited = Product::find($request->id_product);


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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $destroy = Product::find($product);
        $destroy->delete();
        return response()->json(['200' => 'success']);
    }
}
