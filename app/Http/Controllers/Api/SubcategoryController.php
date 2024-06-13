<?php

namespace App\Http\Controllers\Api;

use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class SubcategoryController extends BaseController
{
    public function index()
    {
        $subcategories = Subcategory::all();
        return $this->sendResponse($subcategories, 'Categories retrieved successfully.');
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
        $image_name = "Subcategory-Image-".$date->format('Y-m-d-H-i-s').".".$request->file('image')->getClientOriginalExtension();
        $image = $manager->read($request->file('image'));
        $image->toJpeg(80)->save(base_path("public/uploads/subcategory/").$image_name);

        $subcategory = new Subcategory();
        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->name;
        $subcategory->slug = $slug;
        $subcategory->description = $request->description;
        $subcategory->image = $image_name;
        $subcategory->save();

        return $this->sendResponse($subcategory, 'Subcategory created successfully.');
    }

    public function update(Request $request, $id)
    {
        $subcategory = Subcategory::find($id);
        if (is_null($subcategory)) {
            return $this->sendError([], 'Subcategory not found.');
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
            if (file_exists(base_path("public/uploads/subcategory/").$subcategory->image)) {
                unlink(base_path("public/uploads/subcategory/").$subcategory->image);
            }
            $manager = new ImageManager(new Driver());
            $image_name = "Subcategory-Image-".$date->format('Y-m-d-H-i-s').".". $request->file('image')->getClientOriginalExtension();
            $image = $manager->read($request->file('image'));
            $image->toJpeg(80)->save(base_path("public/uploads/subcategory/").$image_name);
            $subcategory->image = $image_name;
        }

        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->name;
        $subcategory->slug = $slug;
        $subcategory->description = $request->description;
        $subcategory->save();

        return $this->sendResponse($subcategory, 'Subcategory updated successfully.');
    }

    public function destroy($id)
    {
        $subcategory = Subcategory::find($id);
        if (is_null($subcategory)) {
            return $this->sendError([], 'Subcategory not found.');
        }
        unlink(base_path("public/uploads/subcategory/").$subcategory->image);
        $subcategory->delete();
        return $this->sendResponse([], 'Subcategory deleted successfully.');
    }
}
