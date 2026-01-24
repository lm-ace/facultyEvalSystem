<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CriteriaSection;
use App\Models\CriteriaItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CriteriaController extends Controller
{
    public function index()
    {
        $sections = CriteriaSection::with('items')->orderBy('section_number')->get();
        $totalQuestions = CriteriaItem::count();
        return view('admin.criteria', compact('sections', 'totalQuestions'));
    }

    // --- STORE SECTION (With Strict Validation) ---
    public function storeSection(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string|max:255',
            'section_number' => 'required|integer|unique:criteria_sections,section_number',
        ], [
            'section_number.unique' => 'That Section Number (Position) is already taken. Please delete the existing section first or choose a different number.'
        ]);

        CriteriaSection::create([
            'section_name' => $request->section_name,
            'section_number' => $request->section_number,
            'position' => $request->section_number
        ]);

        return redirect()->back()->with('success', 'Category added successfully!');
    }

    // --- UPDATE SECTION (NEW) ---
    public function updateSection(Request $request, $id)
    {
        $section = CriteriaSection::findOrFail($id);

        $request->validate([
            'section_name' => 'required|string|max:255',
            // Check uniqueness BUT ignore the current section's ID (so you can save without changing the number)
            'section_number' => ['required', 'integer', Rule::unique('criteria_sections')->ignore($section->id)],
        ], [
            'section_number.unique' => 'That Section Number is already taken by another category.'
        ]);

        $section->update([
            'section_name' => $request->section_name,
            'section_number' => $request->section_number,
            'position' => $request->section_number
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    // --- DELETE SECTION (NEW) ---
    public function destroySection($id)
    {
        $section = CriteriaSection::findOrFail($id);
        
        // Because you have 'onDelete cascade' in your migration, 
        // this will automatically delete all questions inside this section.
        $section->delete();

        return redirect()->back()->with('success', 'Category and its questions have been deleted.');
    }

    // --- STORE ITEM (Updated to handle "Quick Add") ---
    public function storeItem(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:criteria_sections,id',
            'question_text' => 'required|string'
        ]);

        $currentCount = CriteriaItem::where('section_id', $request->section_id)->count();

        CriteriaItem::create([
            'section_id' => $request->section_id,
            'question_text' => $request->question_text,
            'item_number' => $currentCount + 1,
            'max_score' => 5,
            'position' => $currentCount + 1
        ]);

        return redirect()->back()->with('success', 'Question added successfully!');
    }

    // ... (UpdateItem and DestroyItem remain the same) ...
    public function updateItem(Request $request, $id)
    {
        $request->validate(['question_text' => 'required|string']);
        CriteriaItem::findOrFail($id)->update(['question_text' => $request->question_text]);
        return redirect()->back()->with('success', 'Question updated.');
    }

    public function destroyItem($id)
    {
        CriteriaItem::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Question deleted.');
    }
}