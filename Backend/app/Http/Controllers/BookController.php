<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;

class BookController extends Controller
{
    public function GetBook()
    {
        $books = Book::all();
        return response()->json($books);
    }

    public function AddBook(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:books,name',
            'quantity' => 'required|integer',
            'category' => 'sometimes|string|max:255|',
            'author' => 'sometimes|string|max:255|',
            'periodic' => 'sometimes|string|max:255|',
        ]);


        $book = Book::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'category' => $request->category,
            'author' => $request->author,
            'periodic' => $request->periodic,
        ]);

        return response()->json(['message' => 'Book added successfully', 'book' => $book], 201);
    }

    public function EditBook(Request $request, $book_id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:books,name,' . $book_id . ',book_id',
            'quantity' => 'sometimes|integer',
            'category' => 'sometimes|string|max:255|',
            'author' => 'sometimes|string|max:255|',
            'periodic' => 'sometimes|string|max:255|',

        ]);

        $book = Book::findOrFail($book_id);

        $book->update([
            'name' => $request->name ?? $book->name,
            'quantity' => $request->quantity ?? $book->quantity,
            'category' => $request->category ?? $book->category,
            'author' => $request->author ?? $book->author,
            'periodic' => $request->periodic ?? $book->periodic,
        ]);

        return response()->json(['message' => 'Book updated successfully', 'book' => $book], 200);
    }

    // public function DeleteBook($book_id)
    // {
    //     $book = Book::find($book_id);

    //     if (!$book) {
    //         return response()->json(['message' => 'Book not found'], 404);
    //     }

    //     $book->delete();

    //     return response()->json(['message' => 'Book deleted successfully'], 200);
    // }


    /**
     * Remove the specified book from storage.
     *
     * @param  int  $book_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteBook($book_id): JsonResponse
    {
        try {
            // Attempt to find the book by ID
            $book = Book::findOrFail($book_id);

            // Delete the book
            $book->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully.'
            ], 200);
        } catch (QueryException $e) {
            // Handle integrity constraint violation
            if ($e->getCode() == '23000') { // SQLSTATE[23000]: Integrity constraint violation
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete book because it is associated with other records.'
                ], 400);
            }

            // Handle other query exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while trying to delete the book.'
            ], 500);
        }
    }
}
