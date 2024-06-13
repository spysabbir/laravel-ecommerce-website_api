<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends BaseController
{
    public function index()
    {
        $categories = Category::all();
        return $this->sendResponse($categories, 'Categories retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->toArray(), 'Validation Error.');
        }

        $slug = Str::slug($request->name);
        $date = Carbon::now();

        $manager = new ImageManager(new Driver());
        $image_name = "Category-Image-".$date->format('Y-m-d-H-i-s').".".$request->file('image')->getClientOriginalExtension();
        $image = $manager->read($request->file('image'));
        $image->toJpeg(80)->save(base_path("public/uploads/category/").$image_name);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->description = $request->description;
        $category->image = $image_name;
        $category->save();

        return $this->sendResponse($category, 'Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError([], 'Category not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->toArray(), 'Validation Error.');
        }

        $slug = Str::slug($request->name);
        $date = Carbon::now();

        if ($request->hasFile('image')) {
            if (file_exists(base_path("public/uploads/category/").$category->image)) {
                unlink(base_path("public/uploads/category/").$category->image);
            }
            $manager = new ImageManager(new Driver());
            $image_name = "Category-Image-".$date->format('Y-m-d-H-i-s').".". $request->file('image')->getClientOriginalExtension();
            $image = $manager->read($request->file('image'));
            $image->toJpeg(80)->save(base_path("public/uploads/category/").$image_name);
            $category->image = $image_name;
        }

        $category->name = $request->name;
        $category->slug = $slug;
        $category->description = $request->description;
        $category->save();

        return $this->sendResponse($category, 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError([], 'Category not found.');
        }
        unlink(base_path("public/uploads/category/").$category->image);
        $category->delete();
        return $this->sendResponse([], 'Category deleted successfully.');
    }
}
