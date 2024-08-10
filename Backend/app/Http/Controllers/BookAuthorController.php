<?php

namespace App\Http\Controllers;

use App\Models\BookAuthor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class BookAuthorController extends Controller
{
    public function GetAuthor()
    {
        $authors = BookAuthor::all();
        return response()->json($authors);
    }

    public function AddAuthor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:book_authors',
        ]);

        $author = BookAuthor::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Author added successfully', 'author' => $author], 201);
    }

    public function EditAuthor(Request $request, $author_id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:book_authors,name,' . $author_id . ',author_id',
        ]);

        $author = BookAuthor::findOrFail($author_id);

        $author->update([
            'name' => $request->name ?? $author->name,
        ]);

        return response()->json(['message' => 'Author updated successfully', 'author' => $author], 200);
    }

    // public function DeleteAuthor($author_id)
    // {
    //     $author = BookAuthor::find($author_id);

    //     if (!$author) {
    //         return response()->json(['message' => 'Author not found'], 404);
    //     }

    //     $author->delete();

    //     return response()->json(['message' => 'Author deleted successfully'], 200);
    // }

     /**
     * Remove the specified author from storage.
     *
     * @param  int  $author_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteAuthor($author_id): JsonResponse
    {
        try {
            // Attempt to find the author by ID
            $author = BookAuthor::findOrFail($author_id);

            // Delete the author
            $author->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Author deleted successfully.'
            ], 200);
        } catch (QueryException $e) {
            // Handle integrity constraint violation
            if ($e->getCode() == '23000') { // SQLSTATE[23000]: Integrity constraint violation
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete author because they are associated with other records.'
                ], 400);
            }

            // Handle other query exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while trying to delete the author.'
            ], 500);
        }
    }
}
