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
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Expense Category API",
 *      description="API endpoints for managing expense categories",
 *      @OA\Contact(
 *          email="info@expensecategoryapi.com"
 *      ),
 *      @OA\License(
 *          name="MIT License",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 */

/**
 * @OA\Get(
 *      path="/api/v1/categories",
 *      operationId="getCategories",
 *      tags={"Categories"},
 *      summary="Get all expense categories",
 *      description="Returns a list of all expense categories",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="/ExpenseCategoryResource")
 *      )
 * )
 */

/**
 * @OA\Post(
 *      path="/api/v1/categories",
 *      operationId="createCategory",
 *      tags={"Categories"},
 *      summary="Create a new expense category",
 *      description="Creates a new expense category",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="/StoreCategoryRequest")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="/ExpenseCategoryResource")
 *      )
 * )
 */

/**
 * @OA\Put(
 *      path="/api/v1/categories/{category}",
 *      operationId="updateCategory",
 *      tags={"Categories"},
 *      summary="Update an existing expense category",
 *      description="Updates an existing expense category",
 *      @OA\Parameter(
 *          name="category",
 *          in="path",
 *          description="Category ID",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="/StoreCategoryRequest")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="/ExpenseCategoryResource")
 *      )
 * )
 */

/**
 * @OA\Get(
 *      path="/api/v1/categories/{category}",
 *      operationId="getCategory",
 *      tags={"Categories"},
 *      summary="Get an expense category",
 *      description="Returns an expense category",
 *      @OA\Parameter(
 *          name="category",
 *          in="path",
 *          description="Category ID",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="/ExpenseCategoryResource")
 *      )
 * )
 */

/**
 * @OA\Delete(
 *      path="/api/v1/categories/{category}",
 *      operationId="deleteCategory",
 *      tags={"Categories"},
 *      summary="Delete an expense category",
 *      description="Deletes an expense category",
 *      @OA\Parameter(
 *          name="category",
 *          in="path",
 *          description="Category ID",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="/ExpenseCategoryResource")
 *      )
 * )
 */

/**
 * @OA\Get(
 *      path="/api/v1/categories/user/{user_id}",
 *      operationId="getCategoriesByUserId",
 *      tags={"Categories"},
 *      summary="Get expense categories by user ID",
 *      description="Returns a list of expense categories by user ID",
 *      @OA\Parameter(
 *          name="user_id",
 *          in="path",
 *          description="User ID",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
*           @OA\JsonContent()

 *      )
 * )
 */

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
