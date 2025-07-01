<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend;
use App\Events\ConsultationEvent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes(['register' => false]);

Route::middleware('role:admin')->group(function () {
    Route::get('dashboard', [Backend\DashboardController::class, 'index'])->name('dashboard');
    Route::resources([
        'foods' => Backend\FoodController::class,
        'snacks' => Backend\SnackController::class,
        'drinks' => Backend\DrinkController::class,
        'sports' => Backend\SportController::class,
        'educations' => Backend\EducationController::class,
        'users' => Backend\UserController::class,
        'profile' => Backend\ProfileController::class,
        'change-password' => Backend\ChangePasswordController::class,
    ]);

    Route::prefix('histories')->name('histories.')->group(function () {
        // BMI, BMR and TDEE History Routes
        Route::get('bmi', [Backend\HistoryController::class, 'bmi'])->name('bmi');
        Route::delete('bmi/{id}', [Backend\HistoryController::class, 'bmiDestroy'])->name('bmiDestroy');
        Route::get('bmr', [Backend\HistoryController::class, 'bmr'])->name('bmr');
        Route::delete('bmr/{id}', [Backend\HistoryController::class, 'bmrDestroy'])->name('bmrDestroy');
        // Breakfast, Lunch, Dinner, Snack, Drink, History Routes
        Route::get('breakfast', [Backend\HistoryController::class, 'breakfast'])->name('breakfast');
        Route::delete('breakfast/{id}', [Backend\HistoryController::class, 'breakfastDestroy'])->name('breakfastDestroy');
        Route::get('lunch', [Backend\HistoryController::class, 'lunch'])->name('lunch');
        Route::delete('lunch/{id}', [Backend\HistoryController::class, 'lunchDestroy'])->name('lunchDestroy');
        Route::get('dinner', [Backend\HistoryController::class, 'dinner'])->name('dinner');
        Route::delete('dinner/{id}', [Backend\HistoryController::class, 'dinnerDestroy'])->name('dinnerDestroy');
        Route::get('snack', [Backend\HistoryController::class, 'snack'])->name('snack');
        Route::delete('snack/{id}', [Backend\HistoryController::class, 'snackDestroy'])->name('snackDestroy');
        Route::get('drink', [Backend\HistoryController::class, 'drink'])->name('drink');
        Route::delete('drink/{id}', [Backend\HistoryController::class, 'drinkDestroy'])->name('drinkDestroy');
        
        //Sport History Routes
        Route::get('sport', [Backend\HistoryController::class, 'sport'])->name('sport');
        Route::delete('sport/{id}', [Backend\HistoryController::class, 'sportDestroy'])->name('sportDestroy');

        // Education History Routes
        Route::get('educations', [Backend\EducationHistoryActivityController::class, 'index'])->name('educations');
        Route::put('educations/{id}', [Backend\EducationHistoryActivityController::class, 'update'])->name('educationsUpdate');
        Route::delete('educations/{id}', [Backend\EducationHistoryActivityController::class, 'destroy'])->name('educationsDestroy');

        // Export data
        Route::get('export', [Backend\HistoryController::class, 'export'])->name('export');
    });


    Route::prefix('consultations')->name('consultations.')->group(function () {
        Route::get('/', [Backend\ConsultationController::class, 'index'])->name('index');
        Route::get('/list', [Backend\ConsultationController::class, 'list'])->name('list');
        Route::get('/new', [Backend\ConsultationController::class, 'new'])->name('new');
        Route::get('/person/{id}', [Backend\ConsultationController::class, 'person'])->name('person');
        Route::get('/content/{id}', [Backend\ConsultationController::class, 'content'])->name('content');
        Route::post('/send', [Backend\ConsultationController::class, 'send'])->name('send');
        Route::post('/attachment', [Backend\ConsultationController::class, 'attachment'])->name('attachment');
        Route::post('/delete-all', [Backend\ConsultationController::class, 'deleteAll'])->name('delete-all');
    });
});
