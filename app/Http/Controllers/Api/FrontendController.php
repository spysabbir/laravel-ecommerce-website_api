<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Api\BaseController as BaseController;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends BaseController
{
    public function index()
    {
        return $this->sendResponse('Welcome to the Laravel 11 API.', 'API is working.');
    }

    public function product()
    {
        $products = Product::all();

        return $this->sendResponse($products, 'Product retrieved successfully.');
    }

    public function productById($id)
    {
        $product = Product::find($id);

        if(is_null($product))
        {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse($product, 'Product retrieved.');
    }
}
