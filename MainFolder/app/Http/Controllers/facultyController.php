<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationResponse;
use App\Models\Faculty;
use App\Models\ReviewPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class facultyController extends Controller
{
    public function show(){
      
        $faculty = auth()->user()->faculty; 

        if(!$faculty){
            abort(403, 'No faculty found');
        }

        $fullName = $faculty->first_name; 

        if(!empty($faculty->middle_name)){
            $fullName .= ' ' . $faculty->middle_name;
        }

        $fullName .= ' ' . $faculty->last_name;

        if(!empty($faculty->suffix)){
            $fullName .= ', ' .$faculty->suffix;
        }

        $facID = $faculty->faculty_code;
        $deptCode = $faculty->department->code;

        $summary = EvaluationResponse::join('evaluations', 'evaluation_responses.evaluation_id', '=', 'evaluations.id')
            ->where('evaluations.faculty_id', $faculty->id)
            ->selectRaw('AVG(evaluation_responses.score) as average_rating, COUNT(evaluation_responses.id) as total_evaluations')
            ->first();

        $averageRating = $summary->average_rating ?? 0; 
        $totalEvaluations = $summary->total_evaluations ?? 0; 

        //this will format the result in 2 decimal places
        $averageRating = number_format($averageRating,2);

        $currentReviewPeriod = ReviewPeriod::where('is_open', true)->first();
        if(!$currentReviewPeriod){
            $currentReviewPeriod = ReviewPeriod::orderBy('start_date','desc')->first();
        }

        $reviewPeriodDisplay = $currentReviewPeriod ? "{$currentReviewPeriod->name} | {$currentReviewPeriod->academic_year}" : "No active review period";
        
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