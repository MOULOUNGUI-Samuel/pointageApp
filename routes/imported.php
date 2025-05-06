<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

$files = File::files(resource_path('views/components/yodirh/imported'));

foreach ($files as $file) {
    $viewName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
    $cleanName = str_replace(['.blade.php', '.blade'], '', $viewName);
    
    Route::get('/imported/' . $viewName, function () use ($cleanName) {
        return view('components.yodirh.imported.' . $cleanName);
    })->name('imported.' . $viewName);
}

