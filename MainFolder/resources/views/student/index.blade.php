@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div x-data="{ 
    showEvaluation: {{ $submissionValidation ?? false ? 'true' : 'false' }}
 }">

    <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4 md:px-10 py-3 text-white bg-[#800000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 md:h-10">
            <div>
                <h1 class="font-bold leading-none text-sm md:text-base">EduRate</h1>
                <p class="text-[8px] md:text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
            </div>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center focus:outline-none bg-white/10 rounded-full pr-3 pl-1 py-1 transition hover:bg-white/20">
                <div class="w-8 h-8 md:w-9 md:h-9 bg-white rounded-full flex items-center justify-center text-[#800000] border-2 border-white/20">
                    <i class="fa-solid fa-user text-sm md:text-base"></i>
                </div>
                <span class="ml-2 text-xs font-bold hidden md:block">Account</span>
                <i class="fa-solid fa-caret-down text-[10px] ml-2 text-white/80 transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl py-2 z-50 border border-gray-100 overflow-hidden text-gray-700">

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

    <main class="pt-24 pb-20 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 md:px-6 max-w-6xl">

            <div class="relative bg-white p-6 md:p-12 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border-l-8 md:border-l-[10px] border-[#800000] mb-8 overflow-hidden">
                <div class="relative z-10 pr-0 md:pr-96">
                    
                    <h2 class="text-2xl md:text-4xl font-black text-gray-800 mb-2 md:mb-4 leading-tight">Welcome, {{ $studentName }}!</h2>

                    @if($isEvaluationOpen && $activePeriod)
                        
                        <div class="inline-flex items-center gap-2 bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-[10px] md:text-xs font-bold uppercase tracking-widest mb-4 border border-green-200">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span>{{ $activePeriod->semester }} | {{ $activePeriod->academic_year }}</span>
                        </div>

                        <div class="space-y-3 md:space-y-4 text-gray-600 leading-relaxed text-sm md:text-base max-w-xl">
                            <p>This evaluation is a critical part of our institutional quality assurance. Your objective feedback helps us maintain high academic standards.</p>
                            <p>Please complete all sections based on your actual classroom experience this term.</p>
                            <p class="font-bold text-gray-500 text-xs md:text-sm">Thank you for your participation.</p>
                        </div>

                        <div class="mt-6 md:mt-8" x-show="!showEvaluation">
                            <button @click="showEvaluation = true" class="w-full md:w-auto px-8 md:px-12 py-3 md:py-4 bg-[#800000] text-white font-bold rounded-xl md:rounded-2xl shadow-xl hover:bg-[#660000] transition-all transform active:scale-95 flex items-center justify-center gap-3 text-sm md:text-base">
                                Proceed to Evaluation
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>

                    @else

                        <div class="inline-flex items-center gap-2 bg-gray-100 text-gray-500 px-3 py-1.5 rounded-lg text-[10px] md:text-xs font-bold uppercase tracking-widest mb-4 border border-gray-200">
                            <i class="fa-solid fa-lock"></i>
                            <span>System Closed</span>
                        </div>

                        <div class="space-y-3 md:space-y-4 text-gray-500 leading-relaxed text-sm md:text-base max-w-xl">
                            <p class="text-[#800000] font-bold">The faculty evaluation period is currently closed.</p>
                            <p>You cannot submit evaluations at this time. Please wait for an announcement regarding the schedule for the next academic term.</p>
                            
                            <div class="flex items-center gap-2 text-xs font-bold bg-gray-50 p-3 rounded-xl border border-gray-100 w-fit mt-2">
                                <i class="fa-solid fa-circle-info text-blue-400"></i>
                                <span>No actions required from you right now.</span>
                            </div>
                        </div>

                    @endif

                </div>

                <div class="hidden md:block absolute right-8 bottom-0 z-0 opacity-90">
                    <img src="{{ asset('images/student-evaluation.png') }}" alt="Student Illustration" class="w-64 lg:w-72 h-auto {{ !$isEvaluationOpen ? 'grayscale opacity-50' : '' }}">
                </div>
            </div>

            <div x-show="showEvaluation" x-cloak x-transition:enter="transition ease-out duration-700" class="space-y-6 md:space-y-8">

                <div class="relative bg-white p-5 md:p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <h1 class="text-[10px] md:text-xs font-black text-gray-400 uppercase tracking-widest">Your Progress</h1>
                            <p class="text-[10px] text-gray-500 mt-1">
                                Finished <span class="font-bold text-[#800000]">{{ $completedCount }}</span> / <span class="font-bold text-gray-800">{{ $totalToEvaluate }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-xl md:text-2xl font-black text-[#800000] leading-none">{{ $percentage }}%</span>
                        </div>
                    </div>

                    <div class="w-full bg-gray-100 rounded-full h-2 md:h-3 overflow-hidden mt-2">
                        <div class="bg-[#800000] h-full rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_#80000055]" style="width: {{ $percentage }}%">
                        </div>
                    </div>

                    <div class="mt-3 flex justify-end">
                        @if($totalToEvaluate == 0)
                        <span class="bg-gray-100 text-gray-500 text-[9px] font-bold px-3 py-1 rounded-lg uppercase tracking-wider">Evaluation is Closed</span>
                        @elseif($percentage >= 100)
                        <span class="bg-green-100 text-green-700 text-[9px] font-bold px-3 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1">
                            <i class="fa-solid fa-check-circle"></i> Complete
                        </span>
                        @else
                        <span class="bg-yellow-100 text-yellow-700 text-[9px] font-bold px-3 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1">
                            <i class="fa-solid fa-clock"></i> Pending
                        </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach($enrolledSubjects as $subject)
                    <div x-data="{ hover: false }" x-init="if(window.innerWidth < 768) hover = true" @mouseenter="hover = true" @mouseleave="if(window.innerWidth >= 768) hover = false" class="relative bg-white rounded-[1.5rem] shadow-md transition-all duration-500 overflow-hidden flex flex-col border border-gray-100 h-full group" :class="hover ? '-translate-y-1 md:-translate-y-3 shadow-xl ring-2 ring-[#800000]/10' : 'translate-y-0'">

                        @if(!$subject->is_evaluated)
                        <div x-show="hover" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 z-30 flex flex-col items-center justify-center p-6 text-center bg-[#800000]/60 backdrop-blur-[2px]">

                            <div class="transform transition-all duration-300" :class="hover ? 'scale-100 translate-y-0' : 'scale-90 translate-y-4'">
                                <button onclick="showEvaluationModal('{{ $subject->first_name }} {{ $subject->last_name }}', '{{ $subject->subject_code }}', {{ $subject->offering_id }})" class="px-6 py-3 bg-white text-[#800000] font-black rounded-xl shadow-2xl uppercase text-[10px] md:text-[11px] tracking-widest flex items-center gap-2 md:gap-3 active:scale-95 transition-transform hover:bg-gray-50">
                                    <i class="fa-solid fa-pen-nib"></i>
                                    Evaluate Now
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-green-900/40 backdrop-blur-sm">
                            <span class="bg-white text-green-700 font-bold px-4 py-2 rounded-xl text-xs uppercase tracking-widest shadow-lg">
                                <i class="fa-solid fa-check-circle mr-1"></i> Completed
                            </span>
                        </div>
                        @endif

                        <div class="h-56 md:h-64 overflow-hidden bg-gray-100 relative">
                            <img src="{{ asset('images/' . $subject->profile_picture) }}" class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105 {{ $subject->is_evaluated ? 'grayscale' : '' }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($subject->first_name . ' ' . $subject->last_name) }}&background=random&size=500'">
                        </div>

                        <div class="bg-[#800000] p-4 flex items-center gap-3 md:gap-4 relative z-10 mt-auto">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center flex-shrink-0 shadow-sm text-[#800000] font-bold text-xs">
                                {{ substr($subject->subject_code, 0, 3) }}
                            </div>
                            <div class="overflow-hidden">
                                <h3 class="text-white font-bold text-sm md:text-base truncate">{{ $subject->first_name }} {{ $subject->last_name }}</h3>
                                <p class="text-white/70 text-[9px] md:text-[10px] uppercase tracking-wider truncate font-medium">{{ $subject->subject_code }} | {{ Str::limit($subject->subject_name, 20) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </main>
</div>

<div id="evaluationModal" x-data="{ 
        ratings: {}, 
        get averageRating() {
            let keys = Object.keys(this.ratings);
            if (keys.length === 0) return '0.0';
            let sum = keys.reduce((acc, key) => acc + parseInt(this.ratings[key]), 0);
            return (sum / keys.length).toFixed(1);
        }
    }" class="fixed inset-0 z-[100] hidden items-end md:items-center justify-center bg-black/80 backdrop-blur-sm p-0 md:p-4">

    <div class="bg-white w-full md:w-11/12 max-w-5xl rounded-t-[2rem] md:rounded-[2.5rem] shadow-2xl flex flex-col h-[90vh] md:max-h-[90vh] overflow-hidden animate-slide-up md:animate-none">
        <form id="evalForm" action="{{ route('student.evaluate.store') }}" method="POST" class="flex flex-col h-full overflow-hidden">
            @csrf
            <input type="hidden" name="offering_id" id="evalOfferingId">

            <div class="p-5 md:p-8 bg-[#800000] text-white flex flex-col md:flex-row justify-between items-center shrink-0 gap-4 md:gap-0">
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 md:h-14 w-auto object-contain">
                    <div class="overflow-hidden">
                        <h2 id="evalFacultyName" class="text-xl md:text-3xl font-black tracking-tight leading-none truncate">Faculty Name</h2>
                        <p id="evalFacultySub" class="text-[9px] md:text-[10px] opacity-70 uppercase font-bold tracking-widest mt-1 md:mt-2 truncate"></p>
                    </div>
                </div>

                <div class="flex items-center justify-between w-full md:w-auto gap-4">
                    <div class="bg-white/10 px-4 md:px-5 py-2 rounded-2xl border border-white/20 flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-[8px] font-black uppercase tracking-widest opacity-60">Avg</p>
                            <p class="text-xl md:text-2xl font-black leading-none" x-text="averageRating">0.0</p>
                        </div>
                        <i class="fa-solid fa-star text-yellow-400 text-lg md:text-xl"></i>
                    </div>

                    <button type="button" onclick="hideEvaluationModal()" class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-lg md:text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-4 md:p-8 overflow-y-auto space-y-6 md:space-y-10 bg-gray-50/50 flex-1 min-h-0">

                <div class="bg-white border border-gray-100 rounded-2xl md:rounded-3xl p-4 md:p-6 shadow-sm border-l-4 border-blue-400 flex gap-3 text-xs md:text-[13px] text-gray-600">
                    <i class="fa-solid fa-circle-info text-blue-500 text-lg md:mt-0"></i>
                    <div class="leading-relaxed">
                        <p><strong>Confidential:</strong> Your evaluation helps improve teaching effectiveness. Data is protected by the Data Privacy Act.</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl md:rounded-3xl p-4 md:p-6 shadow-sm">
                    <p class="font-bold text-gray-800 mb-3 text-xs md:text-sm">Rating Scale Guide:</p>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2 md:gap-3">
                        <div class="px-2 py-1.5 bg-red-50 border border-red-200 rounded-lg text-center font-bold text-[9px] md:text-[10px] text-red-600">1 - Strongly Disagree</div>
                        <div class="px-2 py-1.5 bg-orange-50 border border-orange-200 rounded-lg text-center font-bold text-[9px] md:text-[10px] text-orange-600">2 - Disagree</div>
                        <div class="px-2 py-1.5 bg-yellow-50 border border-yellow-200 rounded-lg text-center font-bold text-[9px] md:text-[10px] text-yellow-700">3 - Neutral</div>
                        <div class="px-2 py-1.5 bg-green-50 border border-green-200 rounded-lg text-center font-bold text-[9px] md:text-[10px] text-green-700">4 - Agree</div>
                        <div class="px-2 py-1.5 bg-teal-50 border border-teal-200 rounded-lg text-center font-bold text-[9px] md:text-[10px] text-teal-700 col-span-2 md:col-span-1">5 - Strongly Agree</div>
                    </div>
                </div>

                @php
                    $icons = [
                        1 => 'fa-book-open',
                        2 => 'fa-users-gear',
                        3 => 'fa-clipboard-check',
                        4 => 'fa-user-tie'
                    ];
                @endphp

                @foreach($criteria as $section)
                <div class="bg-white rounded-2xl md:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-5 md:px-8 py-3 md:py-4 flex items-center gap-3 text-white uppercase font-black tracking-widest text-[10px] md:text-[11px]">
                        <i class="fa-solid {{ $icons[$section->section_number] ?? 'fa-star' }}"></i>
                        <span>{{ $section->section_name }}</span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <div class="hidden md:flex text-[10px] uppercase text-gray-400 bg-gray-50/50 font-black py-4 px-8">
                            <div class="flex-1">Performance Criteria</div>
                            <div class="flex w-[400px] justify-between text-center px-4">
                                <span class="w-12">1</span><span class="w-12">2</span><span class="w-12">3</span><span class="w-12">4</span><span class="w-12">5</span>
                            </div>
                        </div>

                        @foreach($section->items as $item)
                        <div class="p-4 md:px-8 md:py-4 hover:bg-gray-50/50 transition flex flex-col md:flex-row md:items-center gap-3 md:gap-0">
                            <div class="flex-1 text-sm text-gray-700 font-medium md:font-normal">
                                <span class="md:hidden text-[10px] font-bold text-gray-400 mr-2">{{ $loop->parent->iteration }}.{{ $loop->iteration }}</span>
                                {{ $item->question_text }}
                            </div>

                            <div class="flex justify-between md:w-[400px] md:px-4 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 md:border-none">
                                @for($i=1; $i<=5; $i++) 
                                <label class="flex flex-col items-center gap-1 cursor-pointer group">
                                    <span class="md:hidden text-[9px] font-bold text-gray-400 group-hover:text-[#800000]">{{$i}}</span>
                                    <input type="radio" 
                                           name="ratings[{{ $item->id }}]" 
                                           value="{{ $i }}" 
                                           x-model="ratings['{{ $item->id }}']" 
                                           class="w-6 h-6 md:w-5 md:h-5 accent-[#800000] cursor-pointer" 
                                           required>
                                </label>
                                @endfor
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="bg-white p-5 md:p-8 rounded-2xl md:rounded-[2rem] shadow-sm border border-gray-100">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 md:mb-4">Optional comments</label>
                    <textarea name="comments" class="w-full p-4 md:p-6 border-2 border-gray-100 rounded-2xl md:rounded-3xl h-24 md:h-32 outline-none focus:border-[#800000] transition bg-gray-50 text-sm" placeholder="Type your feedback here..."></textarea>
                </div>
            </div>

            <div class="p-4 md:p-8 border-t bg-white flex justify-between md:justify-end gap-3 shrink-0 pb-8 md:pb-8">
                <button type="button" onclick="hideEvaluationModal()" class="w-1/3 md:w-auto px-4 md:px-8 py-3 font-bold text-gray-400 hover:text-gray-600 transition text-sm">Discard</button>
                <button type="button" onclick="showConfirmSubmitModal()" class="w-2/3 md:w-auto px-6 md:px-12 py-3 md:py-4 bg-[#800000] text-white font-black rounded-xl md:rounded-2xl shadow-xl hover:bg-[#660000] transition uppercase text-[10px] md:text-xs tracking-widest">
                    Submit Evaluation
                </button>
            </div>
        </form>
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-6 md:p-8 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-16 h-16 md:w-20 md:h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-right-from-bracket text-[#800000] text-2xl md:text-3xl"></i>
        </div>
        <div class="text-center mb-6 md:mb-8">
            <h3 class="text-xl md:text-2xl font-black text-gray-800 mb-2">Ready to Leave?</h3>
            <p class="text-gray-500 text-sm">Are you sure you want to log out?</p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="executeLogout()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition">Yes, Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition">Cancel</button>
        </div>
    </div>
</div>

<div id="confirmSubmitModal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-sm bg-white rounded-[2rem] shadow-2xl p-6 md:p-8 border-t-8 border-[#800000]">
        <div class="bg-orange-50 w-16 h-16 md:w-20 md:h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-paper-plane text-orange-500 text-2xl md:text-3xl"></i>
        </div>
        <div class="text-center mb-6 md:mb-8">
            <h3 class="text-xl md:text-2xl font-black text-gray-800 mb-2">Final Review</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Submit this evaluation? <br>
                <span class="font-bold text-[#800000]">This cannot be undone.</span>
            </p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="processFinalSubmission()" class="w-full py-3 md:py-4 bg-[#800000] text-white font-black rounded-xl md:rounded-2xl shadow-lg hover:bg-[#660000] transition uppercase text-xs tracking-widest">Yes, Submit Now</button>
            <button onclick="hideConfirmSubmitModal()" class="w-full py-3 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition">Review Again</button>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('student.changePassword') }}" id="changePasswordForm">
    @csrf
    <div id="changePasswordModal" class="fixed inset-0 z-[120] {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-data="{ 
             showOld: false, 
             showNew: false, 
             showConfirm: false,
             newPassword: '',
             confirmPassword: '',
             get passwordMatch() { 
                 return this.newPassword === this.confirmPassword; 
             },
             get isValid() {
                 return this.newPassword.length >= 8 && this.passwordMatch && this.newPassword !== '';
             }
         }">

        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden">
            <div class="p-8 bg-[#800000] text-white flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-lock text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black tracking-tight">Security Update</h2>
                    <p class="text-[9px] opacity-70 uppercase font-bold">Change password</p>
                </div>
            </div>

            <div class="p-8 space-y-5">
                @if (session('error'))
                <div class="p-3 bg-red-100 text-red-700 rounded-xl text-xs font-bold flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ session('error') }}
                </div>
                @endif

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Current Password</label>
                    <div class="relative">
                        <input name="current_password" :type="showOld ? 'text' : 'password'" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm @error('current_password') border-red-500 bg-red-50 @enderror">
                        <button type="button" @click="showOld = !showOld" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]">
                            <i class="fa-solid" :class="showOld ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('current_password') <span class="text-red-500 text-[10px] font-bold ml-2">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">New Password</label>
                    <div class="relative">
                        <input name="new_password" x-model="newPassword" :type="showNew ? 'text' : 'password'" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm @error('new_password') border-red-500 bg-red-50 @enderror">
                        <button type="button" @click="showNew = !showNew" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]">
                            <i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <p x-show="newPassword.length > 0 && newPassword.length < 8" class="text-orange-500 text-[10px] font-bold ml-2" x-cloak>Must be at least 8 characters</p>
                    @error('new_password') <span class="text-red-500 text-[10px] font-bold ml-2">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Confirm New Password</label>
                    <div class="relative">
                        <input name="new_password_confirmation" x-model="confirmPassword" :type="showConfirm ? 'text' : 'password'" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]">
                            <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <p x-show="confirmPassword.length > 0 && !passwordMatch" class="text-red-500 text-[10px] font-bold ml-2" x-cloak>Passwords do not match</p>
                </div>
            </div>

            <div class="p-8 pt-0 flex gap-3">
                <button type="button" onclick="hideChangePasswordModal()" class="flex-1 py-4 font-bold text-gray-400 hover:bg-gray-50 rounded-2xl transition">Cancel</button>

                <button type="button" onclick="showPasswordConfirm()" :disabled="!isValid" :class="isValid ? 'bg-[#800000] hover:bg-[#660000] shadow-xl' : 'bg-gray-300 cursor-not-allowed'" class="flex-[2] py-4 text-white font-black rounded-2xl transition uppercase text-xs tracking-widest">
                    Update Password
                </button>
            </div>
        </div>
    </div>
</form>

<div id="confirmPasswordModal" class="fixed inset-0 z-[130] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-[2.5rem] shadow-2xl p-8 mx-4 border-t-8 border-[#800000]">
        <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-shield-halved text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Security Check</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Are you sure you want to change your password? <br>
                <span class="font-bold text-[#800000]">You will need to use your new credentials on your next login.</span>
            </p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="submitPasswordChange()" class="w-full py-4 bg-[#800000] text-white font-black rounded-2xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98] uppercase text-xs tracking-widest">
                Yes, Update Now
            </button>
            <button onclick="hidePasswordConfirm()" class="w-full py-3 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">
                Cancel
            </button>
        </div>
    </div>
</div>

<div id="successModal" class="fixed inset-0 z-[150] {{ session('success') ? 'flex' : 'hidden' }} items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-[2.5rem] shadow-2xl p-8 mx-4 text-center transform transition-all scale-100 border-t-8 border-green-500">

        <div class="bg-green-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <i class="fa-solid fa-check text-green-600 text-4xl"></i>
        </div>

        <h3 class="text-2xl font-black text-gray-800 mb-2">Success!</h3>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            {{ session('success') ?? 'Your password has been successfully updated.' }}
        </p>

        <button onclick="document.getElementById('successModal').classList.replace('flex', 'hidden')" class="w-full py-4 bg-green-500 text-white font-black rounded-2xl shadow-lg hover:bg-green-600 transition active:scale-[0.98] uppercase text-xs tracking-widest">
            Ok
        </button>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<footer class="bg-[#660000] text-white py-8 md:py-12">
    <div class="container mx-auto px-6 text-center text-[10px] md:text-xs">
        <p class="opacity-50">Polytechnic University of the Philippines - Main Campus</p>
        <p class="mt-2 md:mt-4 opacity-40 tracking-widest uppercase font-bold">Copyright © {{ date('Y') }} | EduRate</p>
    </div>
</footer>

<script>
    function showEvaluationModal(n, s, id) {
        document.getElementById('evalFacultyName').innerText = n;
        document.getElementById('evalFacultySub').innerText = s;
        document.getElementById('evalOfferingId').value = id;
        document.getElementById('evaluationModal').classList.replace('hidden', 'flex');
    }

    function hideEvaluationModal() {
        document.getElementById('evaluationModal').classList.replace('flex', 'hidden');
    }

    function showConfirmSubmitModal() {
        document.getElementById('confirmSubmitModal').classList.replace('hidden', 'flex');
    }

    function hideConfirmSubmitModal() {
        document.getElementById('confirmSubmitModal').classList.replace('flex', 'hidden');
    }

    function processFinalSubmission() {
        hideConfirmSubmitModal();
        document.getElementById('evalForm').submit();
    }

    function showLogoutModal() {
        document.getElementById('logoutModal').classList.replace('hidden', 'flex');
    }

    function hideLogoutModal() {
        document.getElementById('logoutModal').classList.replace('flex', 'hidden');
    }

    function executeLogout() {
        document.getElementById('logout-form').submit();
    }

    // --- PASSWORD MODAL FUNCTIONS ---

    function showChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('hidden', 'flex');
    }

    function hideChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('flex', 'hidden');
    }

    function showPasswordConfirm() {
        document.getElementById('changePasswordModal').classList.replace('flex', 'hidden');
        document.getElementById('confirmPasswordModal').classList.replace('hidden', 'flex');
    }

    function hidePasswordConfirm() {
        document.getElementById('confirmPasswordModal').classList.replace('flex', 'hidden');
        document.getElementById('changePasswordModal').classList.replace('hidden', 'flex');
    }

    function submitPasswordChange() {
        document.getElementById('confirmPasswordModal').classList.replace('flex', 'hidden');
        document.getElementById('changePasswordForm').submit();
    }

</script>

<style>
    [x-cloak] {
        display: none !important;
    }

    @keyframes slide-up {
        from {
            transform: translateY(100%);
        }

        to {
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slide-up 0.3s ease-out forwards;
    }

    @media (min-width: 768px) {
        .animate-slide-up {
            animation: none;
        }
    }

</style>
@endsection