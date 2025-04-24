<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\JsonResponseBuilder;
use App\Models\Category;

class CategoryController extends Controller
{

    use JsonResponseBuilder;

    public function index()
    {
        $categories = Category::all();
        return $this->successResponse('Categories retrieved successfully', $categories);
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return $this->successResponse('Categories Created successfully', $category, 201);
    }

    public function update(CategoryRequest $request, $id)
    {

        $category = Category::find($id);
        if (!$category) {
            return $this->errorResponse('Resource not found', null, 404);
        }
        $category->update($request->validated());
        return $this->successResponse('Categories Updated successfully', $category);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->errorResponse('Resource not found', null, 404);
        }

        $category->delete();
        return $this->successResponse('Categories Deleted successfully');
    }


}
