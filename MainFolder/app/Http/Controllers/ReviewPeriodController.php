<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReviewPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class ReviewPeriodController extends Controller
{
  
    public function activate($id)
    {
        Log::info("Admin is activating Review Period ID: $id");

       
        ReviewPeriod::query()->update(['is_open' => false]);

       
        $period = ReviewPeriod::findOrFail($id);
        $period->update(['is_open' => true]);

      
        Log::notice("SUCCESS: Activated Review Period - {$period->semester} | {$period->academic_year}");

        return back()->with('success', "System is now OPEN for: {$period->semester} | {$period->academic_year}");
    }

   
    public function store(Request $request)
    {
        Log::info("Admin is creating a review period. Input: " . json_encode($request->only('academic_year', 'semester')));

        $request->validate([
            'academic_year' => 'required|string', 
            'semester'      => 'required|string', 
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ]);

        ReviewPeriod::create([
            'name'          => $request->semester . ' | ' . $request->academic_year,
            'academic_year' => $request->academic_year,
            'semester'      => $request->semester,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'is_open'       => false, 
        ]);

        
        Log::notice("SUCCESS: Created Review Period - {$request->semester} | {$request->academic_year}");

        return back()->with('success', 'Review Period created successfully.');
    }

    
    public function update(Request $request, $id)
    {
        Log::info("Admin is updating Review Period ID: $id");

        $period = ReviewPeriod::findOrFail($id);

        $request->validate([
            'academic_year' => 'required|string',
            'semester'      => 'required|string',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ]);

        $period->update([
            'name'          => $request->semester . ' | ' . $request->academic_year,
            'academic_year' => $request->academic_year,
            'semester'      => $request->semester,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
        ]);

       
        Log::notice("SUCCESS: Updated Review Period - {$period->semester} | {$period->academic_year}");

        return back()->with('success', 'Review Period updated.');
    }

    
    public function destroy($id)
    {
        Log::info("Admin is deleting Review Period ID: $id");

        $period = ReviewPeriod::findOrFail($id);

        if ($period->is_open) {
            Log::warning("BLOCKED: Admin tried to delete ACTIVE period ID $id");
            return back()->with('error', 'Cannot delete the ACTIVE review period. Switch to another period first.');
        }

        if ($period->evaluations()->exists()) {
             Log::warning("BLOCKED: Admin tried to delete period ID $id which has existing evaluations.");
             return back()->with('error', 'Cannot delete: This period contains student evaluation data.');
        }

        
        $name = "{$period->semester} | {$period->academic_year}";

        $period->delete();

       
        Log::notice("SUCCESS: Deleted Review Period - {$name}");

        return back()->with('success', 'Review Period deleted.');
    }

   
    public function close()
    {
        Log::info("Admin is manually closing the active review period.");

        
        \App\Models\ReviewPeriod::query()->update(['is_open' => false]);


        Log::notice("SUCCESS: Review Period Manually Closed.");

        return back()->with('success', 'Review period closed successfully.');
    }
}