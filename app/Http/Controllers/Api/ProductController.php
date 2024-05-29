<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Api\BaseController as BaseController;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    public function index()
    {
        $products = Product::all();

        return $this->sendResponse($products, 'Product retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $image = $request->file('image');
        $image_name = time().'.'.$image->extension();
        $image->move(public_path('images'), $image_name);

        $sku = 'SKU-'.time();
        $slug = Str::slug($request->name, '-');

        $product = Product::create([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'sku' => $sku,
            'slug' => $slug,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $image_name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return $this->sendResponse($product, 'Product create successfully.');
    }

    public function show($id)
    {
        $product = Product::find($id);

        if(is_null($product))
        {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse($product, 'Product retrieved successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $image = $request->file('image');
        $image_name = time().'.'.$image->extension();
        $image->move(public_path('images'), $image_name);

        $slug = Str::slug($request->name, '-');

        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->slug = $slug;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->image = $image_name;
        $product->updated_at = Carbon::now();
        $product->save();

        return $this->sendResponse($product, 'Product update successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->sendResponse([], 'Product delete successfully.');
    }
}
