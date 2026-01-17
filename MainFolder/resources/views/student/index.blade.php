@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div x-data="{ showEvaluation: false }">

    {{-- NAVIGATION --}}
    <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-10 py-2 text-white bg-[#800000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8">
            <div>
                <h1 class="font-bold leading-none text-base">EduRate</h1>
                <p class="text-[9px] stracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
            </div>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center focus:outline-none">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#800000] border-2 border-white/20">
                    <i class="fa-solid fa-user text-lg"></i>
                </div>
                <i class="fa-solid fa-caret-down text-[10px] ml-2 text-white/80 transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl py-2 z-50 border border-gray-100 overflow-hidden text-gray-700">

                <button onclick="showChangePasswordModal()" class="w-full text-left px-5 py-3 text-sm hover:bg-gray-50 flex items-center transition group">
                    <i class="fa-solid fa-key mr-3 text-gray-400 group-hover:text-[#800000]"></i>
                    <span class="font-medium">Change Password</span>
                </button>

                <hr class="border-gray-50">

                <button onclick="showLogoutModal()" class="w-full text-left px-5 py-3 text-sm text-[#E31E24] font-bold hover:bg-red-50 flex items-center transition group">
                    <i class="fa-solid fa-right-from-bracket mr-3 transform rotate-180 text-[#E31E24]"></i>
                    <span>Log Out</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-16 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6 max-w-6xl">

            {{-- WELCOME MESSAGE --}}
            <div class="relative bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border-l-[10px] border-[#800000] mb-8 overflow-hidden">
                <div class="relative z-10 pr-0 md:pr-96">
                    <h2 class="text-4xl font-black text-gray-800 mb-6">Welcome, Juan Dela Cruz!</h2>
                    <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base max-w-xl">
                        <p>This evaluation is a critical part of our institutional quality assurance. Your objective feedback helps us maintain high academic standards and ensures the continuous improvement of our teaching methods.</p>
                        <p>Please complete all sections based on your actual classroom experience this term.</p>
                        <p class="font-bold text-gray-500">Thank you for your participation. Your feedback is highly valued.</p>
                    </div>

                    <div class="mt-8" x-show="!showEvaluation">
                        <button @click="showEvaluation = true" class="px-12 py-4 bg-[#800000] text-white font-bold rounded-2xl shadow-xl hover:bg-[#660000] transition-all transform active:scale-95 flex items-center gap-3">
                            Proceed to Evaluation
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div class="hidden md:block absolute right-8 bottom-0 z-0 opacity-90">
                    <img src="{{ asset('images/student-evaluation.png') }}" alt="Student Illustration" class="w-72 h-auto">
                </div>
            </div>

            <div class="relative bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <h1 class="text-xs font-black text-gray-400 uppercase tracking-widest">Your Progress</h1>
                        <p class="text-[10px] text-gray-500 mt-0.5">
                            You have finished <span class="font-bold text-[#800000]">{{ $completedCount }}</span>
                            out of <span class="font-bold text-gray-800">{{ $totalToEvaluate }}</span> instructors.
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black text-[#800000] leading-none">{{ $percentage }}%</span>
                    </div>
                </div>

                {{-- Dynamic Progress Bar --}}
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden mt-2">
                    <div class="bg-[#800000] h-full rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_#80000055]"
                        style="width: {{ $percentage }}%">
                    </div>
                </div>

                {{-- Status Badge Logic --}}
                <div class="mt-3 flex justify-end">
                    @if($totalToEvaluate == 0)
                    <span class="bg-gray-100 text-gray-500 text-[9px] font-bold px-3 py-1 rounded-lg uppercase tracking-wider">
                        No subjects enrolled
                    </span>
                    @elseif($percentage >= 100)
                    <span class="bg-green-100 text-green-700 text-[9px] font-bold px-3 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-check-circle"></i> All Complete
                    </span>
                    @else
                    <span class="bg-yellow-100 text-yellow-700 text-[9px] font-bold px-3 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-clock"></i> Pending
                    </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                {{-- LOOP THROUGH DATABASE SUBJECTS --}}
                @foreach($enrolledSubjects as $subject)
                <div x-data="{ hover: false }" 
                     @mouseenter="hover = true" 
                     @mouseleave="hover = false"
                     class="relative bg-white rounded-[1.5rem] shadow-md transition-all duration-500 overflow-hidden flex flex-col border border-gray-100 h-full"
                     :class="hover ? '-translate-y-3 shadow-2xl ring-4 ring-[#800000]/10' : 'translate-y-0'">
                    
                    {{-- 1. IF NOT EVALUATED: SHOW START BUTTON --}}
                    @if(!$subject->is_evaluated)
                    <div x-show="hover" 
                         class="absolute inset-0 z-30 flex flex-col items-center justify-center p-6 text-center"
                         style="background-color: rgba(128, 0, 0, 0.5); backdrop-filter: blur(2px);">
                        
                        <div class="transform transition-all duration-300" :class="hover ? 'scale-100 translate-y-0' : 'scale-90 translate-y-4'">
                            {{-- THIS PASSES THE REAL ID SO IT DOES NOT AUTO-COMPLETE --}}
                            <button onclick="showEvaluationModal('{{ $subject->first_name }} {{ $subject->last_name }}', '{{ $subject->subject_code }}', {{ $subject->offering_id }})"
                                class="px-7 py-3 bg-white text-[#800000] font-black rounded-xl shadow-2xl uppercase text-[11px] tracking-widest flex items-center gap-3 active:scale-95 transition-transform">
                                <i class="fa-solid fa-pen-nib"></i>
                                Start Evaluating
                            </button>
                        </div>
                    </div>
                    
                    @else
                    {{-- 2. IF COMPLETED: SHOW GREEN CHECK --}}
                    <div class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-green-900/40 backdrop-blur-sm">
                        <span class="bg-white text-green-700 font-bold px-4 py-2 rounded-xl text-xs uppercase tracking-widest shadow-lg">
                            <i class="fa-solid fa-check-circle mr-1"></i> Completed
                        </span>
                    </div>
                    @endif

                    {{-- 3. DISPLAY IMAGE FROM DATABASE (Original Look) --}}
                    <div class="h-64 overflow-hidden bg-gray-100">
                        {{-- Make sure your images are in public/images/ --}}
                        <img src="{{ asset('images/' . $subject->profile_picture) }}" 
                             class="w-full h-full object-cover object-top {{ $subject->is_evaluated ? 'grayscale' : '' }}">
                    </div>

                    {{-- 4. INFO BAR --}}
                    <div class="bg-[#800000] p-4 flex items-center gap-4 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center flex-shrink-0 shadow-sm">
                            <span class="font-bold text-[#800000] text-xs">{{ substr($subject->subject_code, 0, 3) }}</span>
                        </div>
                        <div class="overflow-hidden">
                            <h3 class="text-white font-bold text-base truncate">{{ $subject->first_name }} {{ $subject->last_name }}</h3> 
                            <p class="text-white/70 text-[10px] uppercase tracking-wider truncate font-medium">{{ $subject->subject_code }} | {{ Str::limit($subject->subject_name, 20) }}</p> 
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</div>

{{-- EVALUATION MODAL --}}
<div id="evaluationModal"
    x-data="{ 
        ratings: {}, 
        get averageRating() {
            let keys = Object.keys(this.ratings);
            if (keys.length === 0) return '0.0';
            let sum = keys.reduce((acc, key) => acc + parseInt(this.ratings[key]), 0);
            return (sum / keys.length).toFixed(1);
        }
    }"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">

    <div class="bg-white w-full max-w-5xl rounded-[2.5rem] shadow-2xl flex flex-col max-h-[95vh] overflow-hidden">

        <form id="evalForm" action="{{ route('student.evaluate.store') }}" method="POST" class="flex flex-col h-full overflow-hidden">
            @csrf
            <input type="hidden" name="offering_id" id="evalOfferingId">

            <div class="p-8 bg-[#800000] text-white flex justify-between items-center shrink-0">
                <div class="flex items-center gap-5">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 w-auto object-contain">
                    <div>
                        <h2 id="evalFacultyName" class="text-3xl font-black tracking-tight leading-none">Faculty Name</h2>
                        <p id="evalFacultySub" class="text-[10px] opacity-70 uppercase font-bold tracking-widest mt-2"></p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="bg-white/10 px-5 py-2 rounded-2xl border border-white/20 flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-[8px] font-black uppercase tracking-widest opacity-60">Ratings</p>
                            <p class="text-2xl font-black leading-none" x-text="averageRating">0.0</p>
                        </div>
                        <i class="fa-solid fa-star text-yellow-400 text-xl"></i>
                    </div>

                    <button type="button" onclick="hideEvaluationModal()" class="w-12 h-12 rounded-full hover:bg-white/10 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-8 overflow-y-auto space-y-10 bg-gray-50/50 flex-1 min-h-0">
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm border-l-4 border-blue-400">
                    <div class="flex items-start gap-4">
                        <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>
                        <div class="space-y-4 text-[13px] text-gray-600 leading-relaxed">
                            <p>This faculty evaluation is conducted to gather student feedback on teaching effectiveness.</p>
                            <p>In accordance with the <strong>Data Privacy Act</strong>, all information is strictly confidential.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                    <p class="font-bold text-gray-800 mb-4">Rating Scale Guide:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                        <div class="px-3 py-2 bg-red-50 border-2 border-red-500 rounded-xl text-center font-bold text-[10px] text-red-600">1 - Strongly Disagree</div>
                        <div class="px-3 py-2 bg-orange-50 border-2 border-orange-400 rounded-xl text-center font-bold text-[10px] text-orange-600">2 - Disagree</div>
                        <div class="px-3 py-2 bg-yellow-50 border-2 border-yellow-500 rounded-xl text-center font-bold text-[10px] text-yellow-700">3 - Neutral</div>
                        <div class="px-3 py-2 bg-green-50 border-2 border-green-500 rounded-xl text-center font-bold text-[10px] text-green-700">4 - Agree</div>
                        <div class="px-3 py-2 bg-teal-50 border-2 border-teal-500 rounded-xl text-center font-bold text-[10px] text-teal-700">5 - Strongly Agree</div>
                    </div>
                </div>

                @php
                $sections = [
                ['title' => 'SECTION 1: INSTRUCTIONAL COMPETENCE', 'icon' => 'fa-book-open', 'questions' => ["Demonstrates mastery of the subject.", "Explains concepts clearly and makes them easy to understand.", "Used relevant examples or real-world applications to illustrate lessons.", "Encourages student participation and questions during discussion.", "Uses effective teaching aids (PPT, visual aids, online resources) to enhance learning."]],
                ['title' => 'SECTION 2: CLASSROOM MANAGEMENT', 'icon' => 'fa-users-gear', 'questions' => ["Starts and ends classes on time.", "Maintains an orderly and conductive learning environment.", "Manages class time effectively.", "Is approachable and available for consultation.", "Implements class policies fairly and consistently."]],
                ['title' => 'SECTION 3: ASSESSMENT AND FEEDBACK', 'icon' => 'fa-clipboard-check', 'questions' => ["Provides clear guidelines for assignments.", "Returns quizzes and projects in a timely manner.", "Gives constructive feedback.", "Computes grades fairly.", "Assessments align with learning objectives."]],
                ['title' => 'SECTION 4: PROFESSIONALISM', 'icon' => 'fa-user-tie', 'questions' => ["Shows respect for students.", "Demonstrates enthusiasm in teaching.", "Accepts constructive criticism.", "Adheres to school policies.", "Maintains professional appearance."]]
                ];
                $global_q_id = 1;
                @endphp

                @foreach($sections as $section)
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-8 py-4 flex items-center gap-3 text-white uppercase font-black tracking-widest text-[11px]">
                        <i class="fa-solid {{ $section['icon'] }}"></i>
                        <span>{{ $section['title'] }}</span>
                    </div>

                    <table class="w-full text-left">
                        <thead class="text-[10px] uppercase text-gray-400 border-b bg-gray-50/50 font-black">
                            <tr>
                                <th class="py-4 px-8">Performance Criteria</th>
                                @for($i=1; $i<=5; $i++) <th class="py-4 text-center w-20">{{$i}}</th> @endfor
                            </tr>
                        </thead>
                        <tbody class="text-[13px] text-gray-700">
                            @foreach($section['questions'] as $q)
                            <tr class="border-b last:border-0 hover:bg-gray-50/50 transition">
                                <td class="py-6 px-8 font-medium">{{ $q }}</td>
                                @for($i=1; $i<=5; $i++)
                                    <td class="text-center">
                                    {{-- FORM INPUT: name="ratings[ID]" --}}
                                    <input type="radio"
                                            name="ratings[{{ $global_q_id }}]"
                                            value="{{ $i }}"
                                            x-model="ratings['{{ $global_q_id }}']"
                                            class="w-5 h-5 accent-[#800000] cursor-pointer"
                                            required>
                                    </td>
                                    @endfor
                            </tr>
                            @php $global_q_id++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Optional comments</label>
                    <textarea name="comments" class="w-full p-6 border-2 border-gray-100 rounded-3xl h-32 outline-none focus:border-[#800000] transition bg-gray-50" placeholder="Type your feedback here..."></textarea>
                </div>
            </div>

            <div class="p-8 border-t bg-white flex justify-end gap-4 shrink-0">
                <button type="button" onclick="hideEvaluationModal()" class="px-8 py-3 font-bold text-gray-400 hover:text-gray-600 transition">Discard</button>
                <button type="button" onclick="showConfirmSubmitModal()" class="px-12 py-4 bg-[#800000] text-white font-black rounded-2xl shadow-xl hover:bg-[#660000] transition uppercase text-xs tracking-widest">
                    Submit Evaluation
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SUCCESS MODAL --}}
<div id="successModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/70 backdrop-blur-md p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 flex flex-col items-center text-center overflow-hidden">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 mb-6">
        <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mb-5 shadow-inner border border-green-100">
            <i class="fa-solid fa-circle-check text-5xl"></i>
        </div>
        <h2 class="text-3xl font-black text-[#800000] mb-2 tracking-tight">Congratulations!</h2>
        <p class="text-gray-500 text-sm mb-8 max-w-[280px]">You have successfully completed the faculty evaluation for this instructor.</p>
        <div class="w-full bg-gray-50 p-6 rounded-[1.5rem] mb-6 border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Rate this system <span class="font-normal">(optional)</span></p>
            <div class="flex justify-center gap-3 text-3xl text-gray-200" x-data="{ rating: 0 }">
                @for($i=1; $i<=5; $i++)
                    <i class="fa-solid fa-star cursor-pointer transition-all duration-300 hover:scale-110"
                    :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'"
                    @click="rating = {{ $i }}"></i>
                    @endfor
            </div>
        </div>
        <div class="w-full mb-8 text-left">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-3">Additional Feedback <span class="font-normal">(optional)</span></p>
            <textarea class="w-full p-4 border-2 border-gray-100 rounded-[1.5rem] h-24 text-sm focus:ring-0 focus:border-[#800000] outline-none bg-white transition shadow-sm" placeholder="Tell us how we can improve..."></textarea>
        </div>
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3">
            <button onclick="hideSuccessModal()" class="w-full py-4 bg-[#800000] text-white font-black rounded-xl shadow-lg hover:bg-[#660000] transition transform active:scale-95 uppercase text-[10px] tracking-widest">Submit Feedback</button>
            <button onclick="hideSuccessModal()" class="w-full py-4 bg-white text-gray-700 font-bold rounded-xl border-2 border-gray-100 hover:bg-gray-50 transition transform active:scale-95 uppercase text-[10px] tracking-widest">Back to Dashboard</button>
        </div>
    </div>
</div>

{{-- LOGOUT MODAL --}}
<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-right-from-bracket text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Ready to Leave?</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Are you sure you want to log out of the <strong>Student Portal</strong>?</p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="executeLogout()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98]">Yes, Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Cancel</button>
        </div>
    </div>
</div>

{{-- SUBMISSION CONFIRMATION MODAL --}}
<div id="confirmSubmitModal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-[2.5rem] shadow-2xl p-8 mx-4 border-t-8 border-[#800000]">
        <div class="bg-orange-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-paper-plane text-orange-500 text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Final Review</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Are you sure you want to submit this evaluation? <br>
                <span class="font-bold text-[#800000]">This action cannot be undone.</span>
            </p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="processFinalSubmission()" class="w-full py-4 bg-[#800000] text-white font-black rounded-2xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98] uppercase text-xs tracking-widest">Yes, Submit Now</button>
            <button onclick="hideConfirmSubmitModal()" class="w-full py-3 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Review Again</button>
        </div>
    </div>
</div>

{{-- CHANGE PASSWORD MODAL --}}
<div id="changePasswordModal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden" x-data="{ showOld: false, showNew: false, showConfirm: false }">
        <div class="p-8 bg-[#800000] text-white flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center"><i class="fa-solid fa-lock text-xl"></i></div>
            <div>
                <h2 class="text-xl font-black tracking-tight">Security Update</h2>
                <p class="text-[10px] opacity-70 uppercase font-bold">Change your portal password</p>
            </div>
        </div>
        <div class="p-8 space-y-5">
            <div class="space-y-2">
                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Current Password</label>
                <div class="relative">
                    <input :type="showOld ? 'text' : 'password'" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm">
                    <button @click="showOld = !showOld" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]"><i class="fa-solid" :class="showOld ? 'fa-eye-slash' : 'fa-eye'"></i></button>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">New Password</label>
                <div class="relative">
                    <input :type="showNew ? 'text' : 'password'" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm">
                    <button @click="showNew = !showNew" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]"><i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i></button>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Confirm New Password</label>
                <div class="relative">
                    <input :type="showConfirm ? 'text' : 'password'" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm">
                    <button @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]"><i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i></button>
                </div>
            </div>
        </div>
        <div class="p-8 pt-0 flex gap-3">
            <button onclick="hideChangePasswordModal()" class="flex-1 py-4 font-bold text-gray-400 hover:bg-gray-50 rounded-2xl transition">Cancel</button>
            <button onclick="hideChangePasswordModal()" class="flex-[2] py-4 bg-[#800000] text-white font-black rounded-2xl shadow-xl hover:bg-[#660000] transition uppercase text-xs tracking-widest">Update Password</button>
        </div>
    </div>
</div>

<footer class="bg-[#660000] text-white py-12">
    <div class="container mx-auto px-10 text-center text-xs">
        <p class="opacity-50">Polytechnic University of the Philippines - Main Campus</p>
        <p class="mt-4 opacity-40 tracking-widest uppercase font-bold">Copyright © {{ date('Y') }} | All Evaluation Data is Protected by Privacy Laws</p>
    </div>
</footer>

<script>
    // 1. UPDATED: Added 'id' parameter to capture the Offering ID
    function showEvaluationModal(n, s, id) {
        document.getElementById('evalFacultyName').innerText = n;
        document.getElementById('evalFacultySub').innerText = s;

        //Pass the ID to the hidden input in the form
        document.getElementById('evalOfferingId').value = id;

        document.getElementById('evaluationModal').classList.replace('hidden', 'flex');
    }

    // 2. UPDATED: Now submits the form to the database
    function processFinalSubmission() {
        hideConfirmSubmitModal();

        //Triggers the form submission to the Route
        document.getElementById('evalForm').submit();
    }

    function hideEvaluationModal() {
        document.getElementById('evaluationModal').classList.replace('flex', 'hidden');
    }

    function showSuccessModal() {
        hideEvaluationModal();
        document.getElementById('successModal').classList.replace('hidden', 'flex');
    }

    function hideSuccessModal() {
        document.getElementById('successModal').classList.replace('flex', 'hidden');
    }

    function showConfirmSubmitModal() {
        document.getElementById('confirmSubmitModal').classList.replace('hidden', 'flex');
    }

    function hideConfirmSubmitModal() {
        document.getElementById('confirmSubmitModal').classList.replace('flex', 'hidden');
    }

    function showLogoutModal() {
        document.getElementById('logoutModal').classList.replace('hidden', 'flex');
    }

    function hideLogoutModal() {
        document.getElementById('logoutModal').classList.replace('flex', 'hidden');
    }

    function showChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('hidden', 'flex');
    }

    function hideChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('hidden', 'flex');
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection