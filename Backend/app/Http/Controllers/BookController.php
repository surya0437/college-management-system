<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function GetBook()
    {
        $books = Book::with(['category', 'author'])->get();
        return response()->json($books);
    }

    public function AddBook(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|max:10',
            'category_id' => 'exists:book_categories,category_id',
            'author_id' => 'exists:book_authors,author_id',
            'periodic_id' => 'exists:periodics,periodic_id',
        ]);


        $book = Book::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'author_id' => $request->author_id,
            'periodic_id' => $request->periodic_id,
        ]);

        return response()->json(['message' => 'Book added successfully', 'book' => $book], 201);
    }

    public function EditBook(Request $request, $book_id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:books,name,' . $book_id . ',book_id',
            'quantity' => 'required|integer|max:10',
            'category_id' => 'exists:book_categories,category_id',
            'author_id' => 'exists:book_authors,author_id',
            'periodic_id' => 'exists:periodics,periodic_id',

        ]);

        $book = Book::findOrFail($book_id);

        $book->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'author_id' => $request->author_id,
            'periodic_id' => $request->periodic_id,
        ]);

        return response()->json(['message' => 'Book updated successfully', 'book' => $book], 200);
    }

    public function DeleteBook($book_id)
    {
        $book = Book::find($book_id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}
