<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use App\Models\Faculty;
use App\Models\ReviewPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class facultyController extends Controller
{
    public function show()
    {

        $faculty = auth()->user()->faculty;

        if (!$faculty) {
            abort(403, 'No faculty found');
        }

        $fullName = $faculty->first_name . ' ' . $faculty->last_name;
        if (!empty($faculty->suffix)) {
            $fullName .= ', ' . $faculty->suffix;
        }

        $facID = $faculty->faculty_code;
        $deptCode = $faculty->department->code;

        $evaluations = Evaluation::where('faculty_id', $faculty->id)
            ->where('completed', true)
            ->get();

        $totalEvaluations = $evaluations->count();

        $rawAverage = $evaluations->avg('overall_rating');
        $averageRating = number_format($rawAverage ?? 0, 2);

        $sectionAverages = EvaluationResponse::join('evaluations', 'evaluation_responses.evaluation_id', '=', 'evaluations.id')
            ->join('criteria_items', 'evaluation_responses.criteria_item_id', '=', 'criteria_items.id')
            ->join('criteria_sections', 'criteria_items.section_id', '=', 'criteria_sections.id')
            ->where('evaluations.faculty_id', $faculty->id)
            ->where('evaluations.completed', true)
            ->select(
                'criteria_sections.section_name',
                DB::raw('AVG(evaluation_responses.score) as avg_score')
            )
            ->groupBy('criteria_sections.id', 'criteria_sections.section_name')
            ->get();

         $currentReviewPeriod = ReviewPeriod::where('is_open', true)->first();
            if (!$currentReviewPeriod) {
            $currentReviewPeriod = ReviewPeriod::orderBy('start_date', 'desc')->first();
        }

        $reviewPeriodDisplay = $currentReviewPeriod
            ? "{$currentReviewPeriod->semester} | {$currentReviewPeriod->academic_year}"
            : "No active review period";

        $feedbacks = Evaluation::where('faculty_id', $faculty->id)
            ->whereNotNull('feedback_text')
            ->pluck('feedback_text')
            ->toArray();

        return view('faculty.dashboard', compact(
            'faculty',
            'facID',
            'deptCode',
            'fullName',
            'averageRating',
            'totalEvaluations', 
            'sectionAverages', 
            'reviewPeriodDisplay',
            'feedbacks'
        ));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password_hash' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password successfully updated!');
    }
}
