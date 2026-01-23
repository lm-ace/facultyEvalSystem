<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CriteriaSection;
use App\Models\CriteriaItem;

class CriteriaController extends Controller
{
    public function index()
    {
        $sections = CriteriaSection::with('items')->orderBy('section_number')->get();
        return view('admin.criteria', compact('sections'));
    }

    public function storeSection(Request $request)
    {
        $request->validate(['section_name' => 'required|string|max:200']);
        
        $nextNum = CriteriaSection::max('section_number') + 1;

        CriteriaSection::create([
            'template_id' => 1,
            'section_name' => $request->section_name,
            'section_number' => $nextNum,
            'position' => $nextNum
        ]);

        return redirect()->back()->with('success', 'Category added successfully!');
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:criteria_sections,id',
            'question_text' => 'required|string',
        ]);

        $nextPos = CriteriaItem::where('section_id', $request->section_id)->max('position') + 1;

        CriteriaItem::create([
            'section_id' => $request->section_id,
            'question_text' => $request->question_text,
            'item_number' => $nextPos,
            'position' => $nextPos,
            'max_score' => 5
        ]);

        return redirect()->back()->with('success', 'Question added successfully!');
    }

    public function destroyItem($id)
    {
        CriteriaItem::destroy($id);
        return redirect()->back()->with('success', 'Question deleted.');
    }

    public function destroySection($id)
    {
        CriteriaSection::destroy($id);
        
        return redirect()->back()->with('success', 'Category and its questions deleted successfully.');
    }
}