<?php

namespace App\Http\Controllers;

use App\Models\BookCategory;
use Illuminate\Http\Request;

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
            'name' => 'required|string|max:255',
        ]);

        $BookCategory->name = $request->name;
        $BookCategory->save();

        return response()->json(['message' => 'Book category updated successfully', 'Book category' => $BookCategory], 200);
    }


    public function DeleteBookCategory($category_id)
    {
        $BookCategory = BookCategory::find($category_id);
        if (!$BookCategory) {
            return response()->json(['message' => 'Book category not found'], 404);
        }
        $BookCategory->delete();

        return response()->json(['message' => 'Book category deleted successfully'], 200);
    }
}
