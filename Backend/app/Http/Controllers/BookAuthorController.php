<?php

namespace App\Http\Controllers;

use App\Models\BookAuthor;
use Illuminate\Http\Request;

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
            'name' => 'required|string|max:255|unique:book_authors,name,' . $author_id . ',author_id',
        ]);

        $author = BookAuthor::findOrFail($author_id);

        $author->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Author updated successfully', 'author' => $author], 200);
    }

    public function DeleteAuthor($author_id)
    {
        $author = BookAuthor::find($author_id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $author->delete();

        return response()->json(['message' => 'Author deleted successfully'], 200);
    }
}
