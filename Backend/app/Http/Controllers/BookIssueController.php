<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookIssue;
use Illuminate\Http\Request;

class BookIssueController extends Controller
{

    public function GetAllIssuedBooks()
    {
        $issuedBooks = BookIssue::with(['student', 'book'])->get();

        return response()->json($issuedBooks);
    }

    public function GetIssuedBooksByStudent($student_id)
    {
        $issuedBooks = BookIssue::with(['student', 'book'])
            ->where('student_id', $student_id)
            ->get();

        return response()->json($issuedBooks);
    }

    public function IssueBook(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'book_id' => 'required|exists:books,book_id',
            'issues_date' => 'required|date',
            // 'return_date' => 'required|date|after:issues_date',
        ]);
        $existingIssue = BookIssue::where('student_id', $request->student_id)
            ->where('book_id', $request->book_id)
            ->where('status', true)
            ->first();

        if ($existingIssue) {
            return response()->json(['message' => 'This book is already issued to this student and not yet returned.'], 400);
        }

        $book = Book::findOrFail($request->book_id);

        if ($book->quantity <= 0) {
            return response()->json(['message' => 'The book is out of stock.'], 400);
        }

        $bookIssue = BookIssue::create([
            'student_id' => $request->student_id,
            'book_id' => $request->book_id,
            'issues_date' => $request->issues_date,
            'status' => true,
        ]);

        if ($bookIssue) {
            $book->quantity -= 1;
            $book->save();
            return response()->json(['message' => 'Book issued successfully', 'bookIssue' => $bookIssue], 201);
        }
    }

    public function ReturnBook(Request $request, $issue_id)
    {
        $request->validate([
            'return_date' => 'required|date|after_or_equal:issues_date',
        ]);

        $bookIssue = BookIssue::findOrFail($issue_id);

        if (!$bookIssue) {
            return response()->json(['message' => 'Book issue record not found.'], 404);
        }

        if (!$bookIssue->status) {
            return response()->json(['message' => 'This book issue is already returned.'], 400);
        }

        $bookIssue->return_date = $request->return_date;
        $bookIssue->status = false;
        $bookIssue->save();

        $book = Book::findOrFail($bookIssue->book_id);
        $book->quantity += 1;
        $book->save();

        return response()->json(['message' => 'Book returned successfully', 'bookIssue' => $bookIssue], 200);
    }

    public function DeleteBookIssue($issue_id)
    {
        $bookIssue = BookIssue::findOrFail($issue_id);

        if ($bookIssue->status) {
            $book = Book::findOrFail($bookIssue->book_id);
            $book->quantity += 1;
            $book->save();
        }

        $bookIssue->delete();

        return response()->json(['message' => 'Book issue deleted successfully'], 200);
    }
}
