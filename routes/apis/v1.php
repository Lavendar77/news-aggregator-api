<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/articles', ArticleController::class);
