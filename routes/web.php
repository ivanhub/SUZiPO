<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TrainingTypeController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\RequestsCourseController;
use App\Http\Controllers\RequestsCityController;
use App\Http\Controllers\RequestsProviderController;
use App\Http\Controllers\RequestsProfessionController;
use App\Http\Controllers\RequestsLearningTypeController;
use App\Http\Controllers\RequestsLearningResourceController;
use App\Http\Controllers\RequestsEventsTypeController;
use App\Http\Controllers\RequestsLearnReasonController;
use App\Http\Controllers\RequestsTeachersController;
use App\Http\Controllers\RequestsCuratorController;
use App\Http\Controllers\RequestsAudienceController;
use App\Http\Controllers\RequestsDisciplineController;
use App\Http\Controllers\AllUserSapController;
use App\Http\Controllers\RequestEmployeeController;
use App\Http\Controllers\MatrixCourseController;
use App\Http\Controllers\MatrixDpoController;
use App\Http\Controllers\MatrixOtController;
use App\Http\Controllers\MatrixPoController;
use App\Http\Controllers\CourseExceptionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\Admin\UserController; 
use App\Http\Controllers\AppDemandController; 


use App\Models\Booking;

// ??????? ??? ??????????????
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
   // return view('welcome');
    return redirect('/dashboard');
});

//???????, ????
Route::get('/test-export', function() {
    $export = new \App\Exports\UsersExport();
    $users = $export->collection();
    return response()->json([
        'total' => $users->count(),
        'users' => $users->take(3)->map(function($u) {
            return ['id' => $u->id, 'name' => $u->name, 'email' => $u->email];
        })
    ]);
});


// ==========================================
// ???????? ?????????????? (Breeze)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});



Route::middleware('auth')->group(function () {
// ????????????????? ?????????????
    Route::prefix('admin')->name('admin.')->group(function () {
//      Route::resource('users', UserController::class);
        Route::get('users/export', [UserController::class, 'export'])->name('users.export'); 
        Route::post('users/export-selected', [UserController::class, 'exportSelected'])->name('users.export-selected');
        Route::resource('users', UserController::class)->middleware('permission:view_users|create_users|edit_users|delete_users');

    });

    // ???????? ??? email ???????????
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // ????????????? ??????
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // ?????????? ??????
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // ?????
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ???????
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ==========================================
    // ???? ????????
    // ==========================================
    Route::resource('course-exceptions', CourseExceptionController::class);

    Route::get('/api/request-employees-count/{requestId}', function ($requestId) {
        $count = \App\Models\RequestEmployee::where('request_id', $requestId)->count();
        return response()->json(['count' => $count]);
    });
});

// ????????? ??????? ????????? ?? ????
Route::get('/api/available-audiences', function (Illuminate\Http\Request $request) {
    $date = $request->get('date');
    $excludeBookingId = $request->get('booking_id');
    
    $query = Booking::whereDate('date', $date);
    if ($excludeBookingId) {
        $query->where('id', '!=', $excludeBookingId);
    }
    
    return $query->pluck('audience_id');
});

// ????????? ??????? ?????????????? ?? ????
Route::get('/api/available-teachers', function (Illuminate\Http\Request $request) {
    $date = $request->get('date');
    $excludeBookingId = $request->get('booking_id');
    
    $query = Booking::whereDate('date', $date);
    if ($excludeBookingId) {
        $query->where('id', '!=', $excludeBookingId);
    }
    
    return $query->pluck('teacher_id');
});

       //???????? ???????? ?? SAP (xls)
Route::post('all-users-sap/import', [AllUserSapController::class, 'import'])->name('all-users-sap.import');
Route::delete('all-users-sap/{allUserSap}/delete', [AllUserSapController::class, 'destroy'])->name('all-users-sap.delete');

Route::resource('all-users-sap', AllUserSapController::class)->parameters([
    'all-users-sap' => 'allUserSap'
]);

     // ??????
Route::resource('requests', RequestController::class);
Route::get('requests/export-form', [RequestController::class, 'exportForm'])->name('requests.export-form');

// ???????? ??? ??????????? ??????
Route::prefix('request-employees')->name('request-employees.')->group(function () {
    Route::get('{requestId}', [RequestEmployeeController::class, 'index'])->name('index');
    Route::post('{requestId}', [RequestEmployeeController::class, 'store'])->name('store');
    Route::post('{requestId}/bulk', [RequestEmployeeController::class, 'storeBulk'])->name('store-bulk'); // ????????
    Route::put('{requestId}/{employeeId}', [RequestEmployeeController::class, 'update'])->name('update');
    Route::delete('{requestId}/{employeeId}', [RequestEmployeeController::class, 'destroy'])->name('destroy');
});

    // ?????????
Route::resource('protocols', ProtocolController::class);
Route::get('/protocols', [ProtocolController::class, 'index'])->name('protocols.index');

    
Route::get('/bookings', function () {return view('bookings.index'); })->name('bookings.index');

// ???????? ??? ??????
Route::prefix('matrices')->name('matrices.')->group(function () {
    Route::resource('courses', MatrixCourseController::class);
//Route::resource('courses', MatrixCourseController::class)->parameters(['course' => 'matrixCourse']);
//    Route::resource('dpo', MatrixDpoController::class);
Route::resource('dpo', MatrixDpoController::class)->parameters(['dpo' => 'matrixDpo']);
//    Route::resource('ot', MatrixOtController::class);
Route::resource('ot', MatrixOtController::class)->parameters(['ot' => 'matrixOt']);
    Route::resource('po', MatrixPoController::class);
});
    
    // ???????????
    Route::prefix('directories')->name('directories.')->group(function () {
        Route::get('countries', [DirectoryController::class, 'countries'])->name('countries');
        Route::get('reasons-non-certification', [DirectoryController::class, 'reasonsNonCertification'])->name('reasons-non-certification');
        Route::resource('providers', RequestsProviderController::class);
        Route::get('courses', [DirectoryController::class, 'courses'])->name('courses');
        Route::get('cities', [DirectoryController::class, 'cities'])->name('cities');
        Route::resource('professions', RequestsProfessionController::class);
        Route::get('qualifications', [DirectoryController::class, 'qualifications'])->name('qualifications');
        Route::get('employees', [DirectoryController::class, 'employees'])->name('employees');
        Route::get('departments', [DirectoryController::class, 'departments'])->name('departments');
        Route::resource('learning-types', RequestsLearningTypeController::class);
        Route::get('reasons-rejection', [DirectoryController::class, 'reasonsRejection'])->name('reasons-rejection');
        Route::get('categories', [DirectoryController::class, 'categories'])->name('categories');
        Route::get('document-types', [DirectoryController::class, 'documentTypes'])->name('document-types');
        Route::get('training-type', [DirectoryController::class, 'trainingType'])->name('training-type');
        Route::get('course-authors', [DirectoryController::class, 'courseAuthors'])->name('course-authors');
        Route::get('training-directions', [DirectoryController::class, 'trainingDirections'])->name('training-directions');
        Route::get('cost-allocation', [DirectoryController::class, 'costAllocation'])->name('cost-allocation');
        Route::get('orders', [DirectoryController::class, 'orders'])->name('orders');
        Route::resource('learn-reasons', RequestsLearnReasonController::class);
        Route::get('contracts', [DirectoryController::class, 'contracts'])->name('contracts');
        Route::resource('disciplines', RequestsDisciplineController::class);
        Route::get('training-assessment-type', [DirectoryController::class, 'trainingAssessmentType'])->name('training-assessment-type');
        Route::resource('learning-resources', RequestsLearningResourceController::class);
        Route::resource('events-type', RequestsEventsTypeController::class);
        Route::resource('teachers', RequestsTeachersController::class);
        Route::resource('audiences', RequestsAudienceController::class);
        Route::resource('curators', RequestsCuratorController::class);
        Route::get('absence-types', [DirectoryController::class, 'absenceTypes'])->name('absence-types');

    // ??????
        Route::resource('cities', RequestsCityController::class);
        
        // ?????
        Route::resource('courses', RequestsCourseController::class);
   
    });

    // ??????
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('auditorium-load', [ReportController::class, 'auditoriumLoad'])->name('auditorium-load');
        Route::get('training-journal', [ReportController::class, 'trainingJournal'])->name('training-journal');
        Route::get('certificate-register', [ReportController::class, 'certificateRegister'])->name('certificate-register');
        Route::get('certificate-print', [ReportController::class, 'certificatePrint'])->name('certificate-print');
    });
    
    // ?????????????????
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::post('users/export-selected', [UserController::class, 'exportSelected'])->name('users.export-selected');
        Route::get('technical-task', [AdminController::class, 'technicalTask'])->name('technical-task');
        Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
        Route::get('global-settings', [AdminController::class, 'globalSettings'])->name('global-settings');
    });
    
    // ??????
    Route::prefix('errors')->name('errors.')->group(function () {
        Route::get('/', [ErrorController::class, 'index'])->name('index');
        Route::get('create', [ErrorController::class, 'create'])->name('create');
        Route::get('non-certified', [ErrorController::class, 'nonCertified'])->name('non-certified');
        Route::get('analysis', [ErrorController::class, 'analysis'])->name('analysis');
        Route::get('help-admin', [ErrorController::class, 'helpAdmin'])->name('help-admin');
        Route::get('help-muo', [ErrorController::class, 'helpMuo'])->name('help-muo');
        Route::get('help-srp', [ErrorController::class, 'helpSrp'])->name('help-srp');
        Route::get('help-sumo', [ErrorController::class, 'helpSumo'])->name('help-sumo');
    });




// ?????????????? ???????? ? ??????? ???????? ?? ??????
Route::get('/', function () {
    // return redirect()->route('demands.index');
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('requests', RequestController::class);
Route::get('requests/export-form', [RequestController::class, 'exportForm'])->name('requests.export-form');
Route::get('/bookings', function () {return view('bookings.index'); })->name('bookings.index');
Route::get('/demands', [AppDemandController::class, 'index'])->name('demands.index');
Route::get('/demands/{id}', [AppDemandController::class, 'show'])->name('requests.show');

