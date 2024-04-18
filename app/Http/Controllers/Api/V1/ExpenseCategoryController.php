<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\V1\ExpenseCategoryResource;
use App\Http\Resources\V1\ExpenseCategoryCollection;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{

    public function index()
    {
        return ExpenseCategoryResource::collection(ExpenseCategory::all());
    }

    public function store(StoreCategoryRequest $request)
    {
        ExpenseCategory::create($request->validated());
        return response()->json('Category created successfully');
    }

    public function update(StoreCategoryRequest $request, ExpenseCategory $category)
    {
        $category->update($request->validated());
        return response()->json('Category updated successfully');
    }

    public function show(ExpenseCategory $category)
    {
        return new ExpenseCategoryResource($category);
    }

    public function destroy(ExpenseCategory $category)
    {
        $category->delete();
        return response()->json('Category deleted successfully');
    }
    public function getCategorysByUserId($user_id)
    {
        return new ExpenseCategoryCollection(ExpenseCategory::where('user_id', $user_id)->get());
    }
}
