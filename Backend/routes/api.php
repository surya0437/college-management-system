<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PeriodicController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\BookIssueController;
use App\Http\Controllers\BookAuthorController;
use App\Http\Controllers\BookCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Programs API Routes

Route::get('/GetProgram', [ProgramController::class, 'GetProgram']);
Route::post('/AddProgram', [ProgramController::class, 'AddProgram']);
Route::put('/EditProgram/{program_id}', [ProgramController::class, 'EditProgram']);
Route::delete('/DeleteProgram/{program_id}', [ProgramController::class, 'DeleteProgram']);

// Students API Routes

Route::get('/GetStudent', [StudentsController::class, 'GetStudent']);
Route::post('/AddStudent', [StudentsController::class, 'AddStudent']);
Route::put('/EditStudent/{student_id}', [StudentsController::class, 'EditStudent']);
Route::delete('/DeleteStudent/{student_id}', [StudentsController::class, 'DeleteStudent']);

// Teachers API Routes

Route::get('/GetTeacher', [TeacherController::class, 'GetTeacher']);
Route::post('/AddTeacher', [TeacherController::class, 'AddTeacher']);
Route::put('/EditTeacher/{teacher_id}', [TeacherController::class, 'EditTeacher']);
Route::delete('/DeleteTeacher/{teacher_id}', [TeacherController::class, 'DeleteTeacher']);

// Periodic API Routes

Route::get('/GetPeriodic', [PeriodicController::class, 'GetPeriodic']);
Route::post('/AddPeriodic', [PeriodicController::class, 'AddPeriodic']);
Route::put('/EditPeriodic/{periodic_id}', [PeriodicController::class, 'EditPeriodic']);
Route::delete('/DeletePeriodic/{periodic_id}', [PeriodicController::class, 'DeletePeriodic']);

// Subjects API Routes

Route::get('/GetSubject', [SubjectController::class, 'GetSubject']);
Route::post('/AddSubject', [SubjectController::class, 'AddSubject']);
Route::put('/EditSubject/{subject_id}', [SubjectController::class, 'EditSubject']);
Route::delete('/DeleteSubject/{subject_id}', [SubjectController::class, 'DeleteSubject']);


// Book category API Routes

Route::get('/GetBookCategory', [BookCategoryController::class, 'GetBookCategory']);
Route::post('/AddBookCategory', [BookCategoryController::class, 'AddBookCategory']);
Route::put('/EditBookCategory/{category_id}', [BookCategoryController::class, 'EditBookCategory']);
Route::delete('/DeleteBookCategory/{category_id}', [BookCategoryController::class, 'DeleteBookCategory']); 

// Book author API Routes

Route::get('/GetAuthor', [BookAuthorController::class, 'GetAuthor']);
Route::post('/AddAuthor', [BookAuthorController::class, 'AddAuthor']);
Route::put('/EditAuthor/{author_id}', [BookAuthorController::class, 'EditAuthor']);
Route::delete('/DeleteAuthor/{author_id}', [BookAuthorController::class, 'DeleteAuthor']);

// Book API Routes

Route::get('/GetBook', [BookController::class, 'GetBook']);
Route::post('/AddBook', [BookController::class, 'AddBook']);
Route::put('/EditBook/{book_id}', [BookController::class, 'EditBook']);
Route::delete('/DeleteBook/{book_id}', [BookController::class, 'DeleteBook']);

// Book issues API Routes

Route::get('/GetAllIssuedBooks', [BookIssueController::class, 'GetAllIssuedBooks']);
Route::get('/GetIssuedBooksByStudent/{student_id}', [BookIssueController::class, 'GetIssuedBooksByStudent']);
Route::post('/IssueBook', [BookIssueController::class, 'IssueBook']);
Route::put('/ReturnBook/{issue_id}', [BookIssueController::class, 'ReturnBook']);
Route::delete('/DeleteBookIssue/{issue_id}', [BookIssueController::class, 'DeleteBookIssue']);