<?php

namespace App\Http\Controllers;

use App\Models\CriteriaSection;
use App\Models\CriteriaItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function criteria(){
    $sections = CriteriaSection::with('items')->orderBy('position')->get();

    $totalQuestions = CriteriaItem::count();

    return view('admin.criteria', compact('sections', 'totalQuestions'));
    }
}
