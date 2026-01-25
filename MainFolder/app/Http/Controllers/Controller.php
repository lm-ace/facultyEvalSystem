<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function logActivity($action, $module, $description)
    {
        $user = Auth::user();
        $adminName = $user ? $user->username : 'System'; 

        $message = "AUDIT: [$module] $action - $description (By: $adminName | IP: " . request()->ip() . ")";

        if ($action === 'Deleted') {
            Log::notice($message); 
        } elseif ($action === 'Error') {
            Log::error($message);
        } else {
            Log::info($message);
        }
    }
}