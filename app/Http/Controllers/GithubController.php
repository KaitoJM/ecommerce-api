<?php

namespace App\Http\Controllers;

use App\Http\Services\GithubService;
use Illuminate\Http\Request;

class GithubController extends Controller
{
    public function changeLogs(GithubService $github) {
        return response()->json([
            'data' => $github->getCommits(),
        ]);
    }
}
