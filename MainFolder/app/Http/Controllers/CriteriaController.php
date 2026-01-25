<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CriteriaSection;
use App\Models\CriteriaItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ReviewPeriod;
use Illuminate\Support\Facades\Log; 

class CriteriaController extends Controller
{
    public function index()
    {
        $sections = CriteriaSection::with('items')->orderBy('section_number')->get();
        $totalQuestions = CriteriaItem::count();
        return view('admin.criteria', compact('sections', 'totalQuestions'));
    }

    private function checkEvaluationStatus()
    {
        if (ReviewPeriod::where('is_open', true)->exists()) {
            
            Log::warning("BLOCKED: Admin tried to modify criteria while an evaluation period is active.");
            
            abort(redirect()->back()->with('error', 'Action Blocked: You cannot modify criteria while an evaluation period is currently OPEN. Please close the evaluation period first.'));
        }
    }

    public function storeSection(Request $request)
    {
        $this->checkEvaluationStatus();

        Log::info("Admin is creating a new criteria category. Input: " . json_encode($request->only('section_name', 'section_number')));

        $request->validate([
            'section_name' => 'required|string|max:255',
            'section_number' => 'required|integer|unique:criteria_sections,section_number',
        ], [
            'section_number.unique' => 'That Section Number (Position) is already taken. Please delete the existing section first or choose a different number.'
        ]);

        $section = CriteriaSection::create([
            'section_name' => $request->section_name,
            'section_number' => $request->section_number,
            'position' => $request->section_number
        ]);

        Log::notice("SUCCESS: Created Criteria Category - {$section->section_name} (Position: {$section->section_number})");

        return redirect()->back()->with('success', 'Category added successfully!');
    }

    public function updateSection(Request $request, $id)
    {
        $this->checkEvaluationStatus();

        Log::info("Admin is updating Criteria Category ID: $id");

        $section = CriteriaSection::findOrFail($id);

        $request->validate([
            'section_name' => 'required|string|max:255',
            'section_number' => ['required', 'integer', Rule::unique('criteria_sections')->ignore($section->id)],
        ], [
            'section_number.unique' => 'That Section Number is already taken by another category.'
        ]);

        $section->update([
            'section_name' => $request->section_name,
            'section_number' => $request->section_number,
            'position' => $request->section_number
        ]);

        Log::notice("SUCCESS: Updated Criteria Category - {$section->section_name}");

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function destroySection($id)
    {
        $this->checkEvaluationStatus();

        Log::info("Admin is deleting Criteria Category ID: $id");

        $section = CriteriaSection::findOrFail($id);
        
        $name = $section->section_name;
        
        $section->delete();

        Log::notice("SUCCESS: Deleted Criteria Category - {$name}");

        return redirect()->back()->with('success', 'Category and its questions have been deleted.');
    }

    public function storeItem(Request $request)
    {
        $this->checkEvaluationStatus();

        Log::info("Admin is adding a question to Section ID: {$request->section_id}");

        $request->validate([
            'section_id' => 'required|exists:criteria_sections,id',
            'question_text' => 'required|string'
        ]);

        $currentCount = CriteriaItem::where('section_id', $request->section_id)->count();

        $item = CriteriaItem::create([
            'section_id' => $request->section_id,
            'question_text' => $request->question_text,
            'item_number' => $currentCount + 1,
            'max_score' => 5,
            'position' => $currentCount + 1
        ]);

        Log::notice("SUCCESS: Added Question to Section {$request->section_id} - \"{$request->question_text}\"");

        return redirect()->back()->with('success', 'Question added successfully!');
    }

    public function updateItem(Request $request, $id)
    {
        $this->checkEvaluationStatus();

        Log::info("Admin is updating Question ID: $id");

        $request->validate(['question_text' => 'required|string']);
        
        $item = CriteriaItem::findOrFail($id);
        $item->update(['question_text' => $request->question_text]);
        
        Log::notice("SUCCESS: Updated Question ID $id");

        return redirect()->back()->with('success', 'Question updated.');
    }

    public function destroyItem($id)
    {
        $this->checkEvaluationStatus();

        Log::info("Admin is deleting Question ID: $id");

        $item = CriteriaItem::findOrFail($id);
        $item->delete();

        Log::notice("SUCCESS: Deleted Question ID $id");

        return redirect()->back()->with('success', 'Question deleted.');
    }
}