@extends('layouts.app')

@section('content')
{{-- 1. DATA DEFINITION --}}
@php
    $student = [
        'id' => '2024-STU-0012',
        'name' => 'JUAN DELA CRUZ',
        'dept' => 'CCIS',
        'period' => '1st Sem | 2025-26'
    ];

    $faculties = [
        ['name' => 'Prof. Danilo Villamor', 'sub' => 'COMP 101 | INTRO TO COMPUTING', 'img' => 'faculty1.jpg'],
        ['name' => 'Dr. Danica Santos', 'sub' => 'COMP 102 | WEB DEVELOPMENT', 'img' => 'faculty2.jpg'],
        ['name' => 'Prof. April Dela Cruz', 'sub' => 'COMP 103 | MULTIMEDIA', 'img' => 'faculty3.jpg'],
        ['name' => 'Prof. Michael Johnson', 'sub' => 'ENGL 102 | TECHNICAL WRITING', 'img' => 'faculty1.jpg'],
        ['name' => 'Prof. Sarah Lee', 'sub' => 'PHYS 201 | GENERAL PHYSICS', 'img' => 'faculty2.jpg'],
        ['name' => 'Prof. Robert Chen', 'sub' => 'HIST 105 | WORLD HISTORY', 'img' => 'faculty3.jpg'],
        ['name' => 'Prof. Maria Garcia', 'sub' => 'PSYC 101 | PSYCHOLOGY', 'img' => 'faculty1.jpg'],
    ];
@endphp

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div x-data="{ 
    showEvaluation: false,
    currentFaculty: 'Faculty Name',
    currentSubject: 'Subject',
    evaluatedList: [], 
    
    // Check if faculty is done
    isDone(name) {
        return this.evaluatedList.includes(name);
    },

    // Calculate Progress
    get progressPercent() {
        let total = {{ count($faculties) }}; 
        if(total === 0) return 0;
        return (this.evaluatedList.length / total) * 100;
    },

    // Open Modal Logic
    openEval(name, subject) {
        if(this.isDone(name)) return; 
        
        this.currentFaculty = name;
        this.currentSubject = subject;
        
        // Open the modal by removing 'hidden' class
        document.getElementById('evaluationModal').classList.remove('hidden');
        document.getElementById('evaluationModal').classList.add('flex');
    },

    // Close Modal Logic
    closeEval() {
        document.getElementById('evaluationModal').classList.add('hidden');
        document.getElementById('evaluationModal').classList.remove('flex');
    },

    // Submit Logic
    markAsDone() {
        if(!this.evaluatedList.includes(this.currentFaculty)) {
            this.evaluatedList.push(this.currentFaculty);
        }
        hideConfirmSubmitModal();
        showSuccessModal();
    }
}">

    {{-- NAVIGATION --}}
    <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-10 py-2 text-white bg-[#800000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8"> 
            <div>
                <h1 class="font-bold leading-none text-base">EduRate</h1>
                <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
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
                    <h2 class="text-4xl font-black text-gray-800 mb-6">Welcome, {{ $student['name'] }}!</h2>
                    <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base max-w-xl">
                        <p>This evaluation is a critical part of our institutional quality assurance. Your objective feedback helps us maintain high academic standards.</p>
                        <p class="font-bold text-gray-500">Thank you for your participation.</p>
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

            <div x-show="showEvaluation" x-cloak x-transition:enter="transition ease-out duration-500" class="space-y-8">
                
                {{-- IMPROVED UI: STUDENT DETAILS AND PROGRESS --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- Student Details --}}
                    <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                        <div class="bg-[#800000] px-8 py-3">
                            <h2 class="text-white font-black tracking-widest uppercase text-[10px] md:text-xs">Student Information</h2>
                        </div>
                        <div class="p-6 md:p-8 grid grid-cols-2 md:grid-cols-4 gap-6 h-full items-center">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-id-card"></i> ID Number
                                </p>
                                <p class="text-base font-black text-[#800000]">{{ $student['id'] }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-user"></i> Name
                                </p>
                                <p class="text-base font-black text-gray-800 leading-tight">{{ $student['name'] }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-building-columns"></i> Dept
                                </p>
                                <p class="text-base font-black text-gray-800">{{ $student['dept'] }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-calendar"></i> Period
                                </p>
                                <p class="text-xs font-bold bg-gray-100 px-2 py-1 rounded-md inline-block text-gray-600">{{ $student['period'] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Progress Bar --}}
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-center">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <h1 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Progress</h1>
                                <p class="text-2xl font-black text-[#800000]">
                                    <span x-text="evaluatedList.length"></span>/<span x-text="{{ count($faculties) }}"></span>
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-lg" x-text="Math.round(progressPercent) + '% Done'"></span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden border border-gray-100">
                            <div class="bg-gradient-to-r from-[#800000] to-red-600 h-full rounded-full transition-all duration-1000 ease-out shadow-[0_0_15px_#80000055]" 
                                 :style="'width: ' + progressPercent + '%'"></div>
                        </div>
                    </div>
                </div>

                {{-- FACULTY CARDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($faculties as $faculty)
                    <div x-data="{ hover: false }" 
                         class="relative bg-white rounded-[1.5rem] shadow-md transition-all duration-500 cursor-pointer overflow-hidden flex flex-col border border-gray-100 h-full"
                         :class="hover && !isDone('{{ $faculty['name'] }}') ? '-translate-y-3 shadow-2xl ring-4 ring-[#800000]/10' : 'translate-y-0'"
                         @mouseenter="hover = true" 
                         @mouseleave="hover = false"
                         @click="openEval('{{ $faculty['name'] }}', '{{ $faculty['sub'] }}')">
                        
                        {{-- DONE OVERLAY --}}
                        <div x-show="isDone('{{ $faculty['name'] }}')" 
                             class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-white/70 backdrop-blur-[1px] text-center p-6 border-4 border-white transition-all">
                            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-4 shadow-sm animate-bounce border border-green-100">
                                <i class="fa-solid fa-check text-3xl text-green-500"></i>
                            </div>
                            <h3 class="text-green-700 font-black text-sm tracking-widest uppercase mb-1">Evaluation</h3>
                            <h3 class="text-gray-800 font-black text-xl tracking-tight uppercase">Complete</h3>
                        </div>

                        {{-- HOVER OVERLAY --}}
                        <div x-show="hover && !isDone('{{ $faculty['name'] }}')" 
                             x-transition:enter="transition opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition opacity duration-200"
                             class="absolute inset-0 z-30 flex flex-col items-center justify-center p-6 text-center"
                             style="background-color: rgba(128, 0, 0, 0.5); backdrop-filter: blur(2px);">
                            
                            <div class="transform transition-all duration-300" 
                                 :class="hover ? 'scale-100 translate-y-0' : 'scale-90 translate-y-4'">
                                <button type="button"
                                        @click.stop="openEval('{{ $faculty['name'] }}', '{{ $faculty['sub'] }}')"
                                        class="px-7 py-3 bg-white text-[#800000] font-black rounded-xl shadow-2xl uppercase text-[11px] tracking-widest flex items-center gap-3 active:scale-95 transition-transform">
                                    <i class="fa-solid fa-pen-nib"></i>
                                    Start Evaluating
                                </button>
                            </div>
                        </div>

                        {{-- ORIGINAL IMAGE HEIGHT (h-64) --}}
                        <div class="h-64 overflow-hidden bg-gray-100">
                            <img src="{{ asset('images/' . $faculty['img']) }}" class="w-full h-full object-cover object-top">
                        </div>

                        {{-- ORIGINAL FOOTER SIZE (p-4) --}}
                        <div class="bg-[#800000] p-4 flex items-center gap-4 relative z-10">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                <img src="{{ asset('images/logo.png') }}" class="w-7 h-7 object-contain"> 
                            </div>
                            <div class="overflow-hidden">
                                <h3 class="text-white font-bold text-base truncate">{{ $faculty['name'] }}</h3> 
                                <p class="text-white/70 text-[10px] uppercase tracking-wider truncate font-medium">{{ $faculty['sub'] }}</p> 
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

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
            <div class="p-8 bg-[#800000] text-white flex justify-between items-center shrink-0">
                <div class="flex items-center gap-5">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 w-auto object-contain">
                    <div>
                        <h2 x-text="currentFaculty" class="text-3xl font-black tracking-tight leading-none"></h2>
                        <p x-text="currentSubject" class="text-[10px] opacity-70 uppercase font-bold tracking-widest mt-2"></p>
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
                    <button @click="closeEval()" class="w-12 h-12 rounded-full hover:bg-white/10 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-8 overflow-y-auto space-y-10 bg-gray-50/50">
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm border-l-4 border-blue-400">
                    <div class="flex items-start gap-4">
                        <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>
                        <div class="space-y-4 text-[13px] text-gray-600 leading-relaxed">
                            <p>This faculty evaluation is conducted to gather student feedback on teaching effectiveness, classroom management, assessment practices, and professional conduct.</p>
                            <p>In accordance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>, all information collected through this evaluation will be treated with strict confidentiality.</p>
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
                    $q_idx = 1;
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
                                    <input type="radio" x-model="ratings['q{{ $q_idx }}']" name="q{{ $q_idx }}" value="{{ $i }}" class="w-5 h-5 accent-[#800000] cursor-pointer">
                                </td>
                                @endfor
                            </tr>
                            @php $q_idx++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach

                <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Optional comments</label>
                    <textarea class="w-full p-6 border-2 border-gray-100 rounded-3xl h-32 outline-none focus:border-[#800000] transition bg-gray-50" placeholder="Type your feedback here..."></textarea>
                </div>
            </div>

            <div class="p-8 border-t bg-white flex justify-end gap-4 shrink-0">
                <button @click="closeEval()" class="px-8 py-3 font-bold text-gray-400 hover:text-gray-600 transition">Discard</button>
                <button onclick="showConfirmSubmitModal()" class="px-12 py-4 bg-[#800000] text-white font-black rounded-2xl shadow-xl hover:bg-[#660000] transition uppercase text-xs tracking-widest">
                    Submit Evaluation
                </button>
            </div>
        </div>
    </div>

    {{-- CONFIRMATION MODAL --}}
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
                <button @click="markAsDone()" class="w-full py-4 bg-[#800000] text-white font-black rounded-2xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98] uppercase text-xs tracking-widest">Yes, Submit Now</button>
                <button onclick="hideConfirmSubmitModal()" class="w-full py-3 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Review Again</button>
            </div>
        </div>
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

{{-- LOGOUT MODAL (Redirects to Homepage on Yes) --}}
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
            {{-- Updated to redirect to root '/' --}}
            <button onclick="window.location.href = '/'" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98]">Yes, Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Cancel</button>
        </div>
    </div>
</div>

{{-- CHANGE PASSWORD MODAL --}}
<div id="changePasswordModal" 
      class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden" 
         x-data="{ 
            current: '', 
            new_pass: '', 
            confirm_pass: '', 
            showOld: false, 
            showNew: false, 
            showConfirm: false,
            error: '',
            step: 1,

            validate() {
                this.error = '';
                if (!this.current || !this.new_pass || !this.confirm_pass) {
                    this.error = 'Please fill in all fields.';
                    return;
                }
                if (this.new_pass !== this.confirm_pass) {
                    this.error = 'New passwords do not match.';
                    return;
                }
                if (this.new_pass.length < 6) {
                    this.error = 'Password must be at least 6 characters.';
                    return;
                }
                this.step = 2;
            },

            submitChange() {
                this.step = 3;
            },

            resetModal() {
                this.current = '';
                this.new_pass = '';
                this.confirm_pass = '';
                this.error = '';
                this.step = 1;
                hideChangePasswordModal();
            }
         }">

        <div class="p-8 bg-[#800000] text-white flex items-center gap-4 transition-colors duration-300"
             :class="step === 3 ? 'bg-green-600' : 'bg-[#800000]'">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                <i class="fa-solid" :class="step === 3 ? 'fa-check' : 'fa-lock'"></i>
            </div>
            <div>
                <h2 class="text-xl font-black tracking-tight" x-text="step === 3 ? 'Success!' : 'Security Update'"></h2>
                <p class="text-[10px] opacity-70 uppercase font-bold" x-text="step === 3 ? 'Password updated successfully' : 'Change your portal password'"></p>
            </div>
        </div>

        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" class="p-8 space-y-5">
            <div x-show="error" class="bg-red-50 text-red-600 text-xs font-bold p-3 rounded-xl border border-red-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span x-text="error"></span>
            </div>
            <div class="space-y-2">
                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Current Password</label>
                <div class="relative">
                    <input x-model="current" :type="showOld ? 'text' : 'password'" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm">
                    <button @click="showOld = !showOld" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]"><i class="fa-solid" :class="showOld ? 'fa-eye-slash' : 'fa-eye'"></i></button>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">New Password</label>
                <div class="relative">
                    <input x-model="new_pass" :type="showNew ? 'text' : 'password'" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm">
                    <button @click="showNew = !showNew" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]"><i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i></button>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Confirm New Password</label>
                <div class="relative">
                    <input x-model="confirm_pass" :type="showConfirm ? 'text' : 'password'" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm">
                    <button @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]"><i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i></button>
                </div>
            </div>
        </div>

        <div x-show="step === 2" x-cloak class="p-8 text-center space-y-4">
            <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mx-auto text-orange-500">
                <i class="fa-solid fa-question text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Are you sure?</h3>
                <p class="text-sm text-gray-500 mt-2">You are about to update your password.</p>
            </div>
        </div>

        <div x-show="step === 3" x-cloak class="p-8 text-center space-y-4">
            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto text-green-500 shadow-inner">
                <i class="fa-solid fa-check text-4xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Password Changed!</h3>
                <p class="text-sm text-gray-500 mt-2">Your password has been successfully updated.</p>
            </div>
        </div>

        <div class="p-8 pt-0 flex gap-3">
            <template x-if="step === 1">
                <div class="flex gap-3 w-full">
                    <button @click="resetModal()" class="flex-1 py-4 font-bold text-gray-400 hover:bg-gray-50 rounded-2xl transition">Cancel</button>
                    <button @click="validate()" class="flex-[2] py-4 bg-[#800000] text-white font-black rounded-2xl shadow-xl hover:bg-[#660000] transition uppercase text-xs tracking-widest">Update Password</button>
                </div>
            </template>
            <template x-if="step === 2">
                <div class="flex gap-3 w-full">
                    <button @click="step = 1" class="flex-1 py-4 font-bold text-gray-400 hover:bg-gray-50 rounded-2xl transition">Back</button>
                    <button @click="submitChange()" class="flex-[2] py-4 bg-[#800000] text-white font-black rounded-2xl shadow-xl hover:bg-[#660000] transition uppercase text-xs tracking-widest">Yes, Update it</button>
                </div>
            </template>
            <template x-if="step === 3">
                <button @click="resetModal()" class="w-full py-4 bg-gray-800 text-white font-black rounded-2xl shadow-xl hover:bg-gray-900 transition uppercase text-xs tracking-widest">Close</button>
            </template>
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
    function showSuccessModal() {
        document.getElementById('evaluationModal').classList.remove('flex');
        document.getElementById('evaluationModal').classList.add('hidden');
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

    function showLogoutModal() { document.getElementById('logoutModal').classList.replace('hidden', 'flex'); }
    function hideLogoutModal() { document.getElementById('logoutModal').classList.replace('flex', 'hidden'); }
    function showChangePasswordModal() { document.getElementById('changePasswordModal').classList.replace('hidden', 'flex'); }
    function hideChangePasswordModal() { document.getElementById('changePasswordModal').classList.replace('flex', 'hidden'); }
</script>

<style> [x-cloak] { display: none !important; } </style>
@endsection