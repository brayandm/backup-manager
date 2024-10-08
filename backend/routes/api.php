<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function () {

    Route::post('/login', 'App\Http\Controllers\AuthController@login');

    Route::post('/register', 'App\Http\Controllers\AuthController@register');

    Route::get('/first-use', 'App\Http\Controllers\AuthController@firstUse');

    Route::middleware(['check.constant.connection', 'auth:sanctum'])->group(function () {

        Route::get('/verify', 'App\Http\Controllers\AuthController@verify');

        Route::post('/logout', 'App\Http\Controllers\AuthController@logout');

        Route::post('/logoutall', 'App\Http\Controllers\AuthController@logoutall');

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::put('/user/update/profile', 'App\Http\Controllers\AuthController@updateProfile');
        Route::put('/user/update/password', 'App\Http\Controllers\AuthController@updatePassword');

        Route::group(['prefix' => 'backup-configurations'], function () {
            Route::get('/', 'App\Http\Controllers\BackupConfigurationController@index');
            Route::post('/store', 'App\Http\Controllers\BackupConfigurationController@store');
            Route::get('/show/{id}', 'App\Http\Controllers\BackupConfigurationController@show');
            Route::put('/update/{id}', 'App\Http\Controllers\BackupConfigurationController@update');
            Route::delete('/delete/{id}', 'App\Http\Controllers\BackupConfigurationController@delete');
            Route::post('/delete-multiple', 'App\Http\Controllers\BackupConfigurationController@deleteMultiple');
            Route::post('/delete-all-except', 'App\Http\Controllers\BackupConfigurationController@deleteAllExcept');
            Route::get('/backups/{id}', 'App\Http\Controllers\BackupConfigurationController@getBackupsWithBackupConfigurationId');
            Route::post('/make-backup/{id}', 'App\Http\Controllers\BackupConfigurationController@makeBackup');
            Route::post('/enable/{id}', 'App\Http\Controllers\BackupConfigurationController@enable');
            Route::post('/disable/{id}', 'App\Http\Controllers\BackupConfigurationController@disable');
        });

        Route::group(['prefix' => 'migration-configurations'], function () {
            Route::get('/', 'App\Http\Controllers\MigrationConfigurationController@index');
            Route::post('/store', 'App\Http\Controllers\MigrationConfigurationController@store');
            Route::get('/show/{id}', 'App\Http\Controllers\MigrationConfigurationController@show');
            Route::put('/update/{id}', 'App\Http\Controllers\MigrationConfigurationController@update');
            Route::delete('/delete/{id}', 'App\Http\Controllers\MigrationConfigurationController@delete');
            Route::post('/delete-multiple', 'App\Http\Controllers\MigrationConfigurationController@deleteMultiple');
            Route::post('/delete-all-except', 'App\Http\Controllers\MigrationConfigurationController@deleteAllExcept');
            Route::post('/make-migration/{id}', 'App\Http\Controllers\MigrationConfigurationController@makeMigration');
            Route::post('/enable/{id}', 'App\Http\Controllers\MigrationConfigurationController@enable');
            Route::post('/disable/{id}', 'App\Http\Controllers\MigrationConfigurationController@disable');
        });

        Route::group(['prefix' => 'storage-servers'], function () {
            Route::get('/', 'App\Http\Controllers\StorageServerController@index');
            Route::get('/names', 'App\Http\Controllers\StorageServerController@getNames');
            Route::post('/store', 'App\Http\Controllers\StorageServerController@store');
            Route::get('/show/{id}', 'App\Http\Controllers\StorageServerController@show');
            Route::put('/update/{id}', 'App\Http\Controllers\StorageServerController@update');
            Route::delete('/delete/{id}', 'App\Http\Controllers\StorageServerController@delete');
            Route::post('/delete-multiple', 'App\Http\Controllers\StorageServerController@deleteMultiple');
            Route::post('/delete-all-except', 'App\Http\Controllers\StorageServerController@deleteAllExcept');
        });

        Route::group(['prefix' => 'data-sources'], function () {
            Route::get('/', 'App\Http\Controllers\DataSourceController@index');
            Route::get('/names', 'App\Http\Controllers\DataSourceController@getNames');
            Route::get('/migration-compatible/{id}', 'App\Http\Controllers\DataSourceController@getMigrationCompatible');
            Route::post('/store', 'App\Http\Controllers\DataSourceController@store');
            Route::get('/show/{id}', 'App\Http\Controllers\DataSourceController@show');
            Route::put('/update/{id}', 'App\Http\Controllers\DataSourceController@update');
            Route::delete('/delete/{id}', 'App\Http\Controllers\DataSourceController@delete');
            Route::post('/delete-multiple', 'App\Http\Controllers\DataSourceController@deleteMultiple');
            Route::post('/delete-all-except', 'App\Http\Controllers\DataSourceController@deleteAllExcept');
            Route::get('/download/{id}', 'App\Http\Controllers\DataSourceController@download');
        });

        Route::group(['prefix' => 'backups'], function () {
            Route::get('/', 'App\Http\Controllers\BackupController@index');
            Route::delete('/delete/{id}', 'App\Http\Controllers\BackupController@delete');
            Route::post('/delete-multiple', 'App\Http\Controllers\BackupController@deleteMultiple');
            Route::post('/delete-all-except', 'App\Http\Controllers\BackupController@deleteAllExcept');
            Route::post('/restore/{id}', 'App\Http\Controllers\BackupController@restore');
            Route::get('/download/{id}', 'App\Http\Controllers\BackupController@download');
        });

        Route::group(['prefix' => 'analytics'], function () {
            Route::get('/get-overview', 'App\Http\Controllers\AnalyticsController@getOverview');
        });

        Route::group(['prefix' => 'telegram'], function () {
            Route::get('/get-settings', 'App\Http\Controllers\TelegramController@getSettings');
            Route::put('/update-settings', 'App\Http\Controllers\TelegramController@updateSettings');
        });
    });
});
