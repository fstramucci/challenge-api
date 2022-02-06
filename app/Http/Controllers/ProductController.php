<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        if ($product->photo) {
            $url_photo = $this->upload($request->file('photo'));
            $product->photo = $url_photo;
        }
        
        $result = $product->save();

        if ($result) {
            return response()->json(['message' => 'Product created succesfully'], 201);
        }
        return response()->json(['message' => 'Error creating Product'], 500);
    }

    private function upload($photo) {
        $path_info = pathinfo($photo->getClientOriginalName());
        $products_path = 'images/products';

        $rename = uniqid() . '.' . $path_info['extension'];
        $photo->move(public_path() . "/$products_path", $rename);
        return "$products_path/$rename";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if ($product) {
            return new ProductResource($product);
        }
        return response()->json(['message' => 'Product not found'], 404);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
