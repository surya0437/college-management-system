<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*', 
        'sanctum/csrf-cookie', 
        'GetProgram', 
        'AddProgram', 
        'EditProgram/{program_id}', 
        'DeleteProgram/{program_id}', 
        'GetClassShifts',
        'AddClassShift', 
        'EditClassShift/{classShift_id}', 
        'DeleteClassShift/{classShift_id}',
        'GetStudent', 
        'AddStudent', 
        'EditStudent/{student_id}', 
        'DeleteStudent/{student_id}', 
        'GetTeacher', 
        'AddTeacher', 
        'EditTeacher/{teacher_id}', 
        'DeleteTeacher/{teacher_id}', 
        'GetPeriodic', 
        'AddPeriodic', 
        'EditPeriodic/{periodic_id}', 
        'DeletePeriodic/{periodic_id}', 
        'GetSubject', 
        'AddSubject', 
        'EditSubject/{subject_id}', 
        'DeleteSubject/{subject_id}', 
        'GetBookCategory', 
        'AddBookCategory', 
        'EditBookCategory/{category_id}', 
        'DeleteBookCategory/{category_id}', 
        'GetAuthor', 
        'AddAuthor', 
        'EditAuthor/{author_id}', 
        'DeleteAuthor/{author_id}', 
        'GetBook', 
        'AddBook', 
        'EditBook/{book_id}', 
        'DeleteBook/{book_id}', 
        'GetAllIssuedBooks', 
        'GetIssuedBooksByStudent/{student_id}', 
        'IssueBook', 
        'ReturnBook/{issue_id}', 
        'DeleteBookIssue/{issue_id}',
        'GetAllRequests',
        'GetRequestsByStudent/{student_id}',
        'RequestBook',
        'ApproveRequest/{requestBook_id}',
        'GetRole',
        'AddRole',
        'UpdateRole/{role_id}',
        'DeleteRole/{role_id}',
        'GetAllAdministrations',
        'GetAdministration/{administration_id}',
        'AddAdministration',
        'EditAdministration/{administration_id}',
        'DeleteAdministration/{administration_id}'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
