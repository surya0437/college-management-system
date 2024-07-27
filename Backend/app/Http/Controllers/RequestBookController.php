<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\RequestBook;
use Illuminate\Http\Request;

class RequestBookController extends Controller
{

    public function GetAllRequests()
    {
        $requests = RequestBook::with(['student', 'book'])->get();
        return response()->json($requests, 200);
    }


    public function GetRequestByStudent($student_id)
    {
        $requests = RequestBook::where('student_id', $student_id)->with(['student', 'book'])->get();
        return response()->json($requests, 200);
    }


    public function RequestBook(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'book_id' => 'required|exists:books,book_id',
        ]);

        $book = Book::findOrFail($request->book_id);

        if ($book->quantity <= 0) {
            return response()->json(['message' => 'The book is out of stock.'], 400);
        }

        // Check if the book is already requested by the same student
        $existingRequest = RequestBook::where('student_id', $request->student_id)
            ->where('book_id', $request->book_id)
            ->first();

        if ($existingRequest) {
            return response()->json(['message' => 'You have already requested for this book.'], 400);
        }

        $requestBook = RequestBook::create([
            'student_id' => $request->student_id,
            'book_id' => $request->book_id
        ]);

        return response()->json(['message' => 'Book requested successfully.', 'requestBook' => $requestBook], 201);
    }


    public function ApproveRequest($requestBook_id, Request $request)
    {
        $requestBook = RequestBook::findOrFail($requestBook_id);

        $existingIssue = BookIssue::where('student_id', $requestBook->student_id)
            ->where('book_id', $requestBook->book_id)
            ->where('status', true)
            ->first();

        if ($existingIssue) {
            return response()->json(['message' => 'This book is already issued to this student and not yet returned.'], 400);
        }

        $book = Book::findOrFail($requestBook->book_id);

        $bookIssue = BookIssue::create([
            'student_id' => $requestBook->student_id,
            'book_id' => $requestBook->book_id,
            'issues_date' => $request->issues_date,
            'status' => true,
        ]);

        if ($bookIssue) {
            $book->quantity -= 1;
            $book->save();
        }

        $requestBook->status = true;
        $requestBook->save();

        return response()->json(['message' => 'Request status updated successfully', 'requestBook' => $requestBook], 200);
    }
}
