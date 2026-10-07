<?php

use Illuminate\Support\Facades\Route;
use JeffersonGoncalves\MailEditor\Http\Controllers\CountdownController;
use JeffersonGoncalves\MailEditor\Http\Controllers\PreviewController;

Route::middleware(config('mail-editor.preview_route_middleware', ['web', 'auth']))
    ->prefix('mail-editor')
    ->name('mail-editor.')
    ->group(function () {
        Route::get('/preview', PreviewController::class)->name('preview');
        Route::get('/countdown', CountdownController::class)->name('countdown');
    });
