<?php

namespace App\Http\Controllers;

use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class BookCategoryController extends Controller
{
    public function GetBookCategory()
    {
        $BookCategory = BookCategory::all();
        return response()->json($BookCategory);
    }

    public function AddBookCategory(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:book_categories,name',
        ]);


        $BookCategory = new BookCategory();
        $BookCategory->name = $request->name;
        $BookCategory->save();

        return response()->json(['message' => 'Book category added successfully', 'Book category' => $BookCategory], 201);
    }


    public function EditBookCategory(Request $request, $category_id)
    {
        $BookCategory = BookCategory::findOrFail($category_id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        $BookCategory->name = $request->name ?? $BookCategory->name;
        $BookCategory->save();

        return response()->json(['message' => 'Book category updated successfully', 'Book category' => $BookCategory], 200);
    }


    // public function DeleteBookCategory($category_id)
    // {
    //     $BookCategory = BookCategory::find($category_id);
    //     if (!$BookCategory) {
    //         return response()->json(['message' => 'Book category not found'], 404);
    //     }
    //     $BookCategory->delete();

    //     return response()->json(['message' => 'Book category deleted successfully'], 200);
    // }

     /**
     * Remove the specified book category from storage.
     *
     * @param  int  $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteBookCategory($category_id): JsonResponse
    {
        try {
            // Attempt to find the book category by ID
            $bookCategory = BookCategory::findOrFail($category_id);

            // Delete the book category
            $bookCategory->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Book category deleted successfully.'
            ], 200);
        } catch (QueryException $e) {
            // Handle integrity constraint violation
            if ($e->getCode() == '23000') { // SQLSTATE[23000]: Integrity constraint violation
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete book category because it is associated with other records.'
                ], 400);
            }

            // Handle other query exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while trying to delete the book category.'
            ], 500);
        }
    }
}
