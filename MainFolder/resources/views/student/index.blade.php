@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<img id="pdfLogo" src="{{ asset('images/logo.png') }}" class="hidden">

<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-10 py-2 text-white bg-[#800000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
    <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8"> 
        <div>
            <h1 class="font-bold leading-none text-base">EduRate</h1>
            <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
        </div>
    </div>
    
    <div class="hidden md:flex items-center space-x-6">
        <button type="button" onclick="showLogoutModal()" class="bg-white/10 px-4 py-1.5 rounded-lg text-sm font-bold hover:bg-white/20 transition flex items-center border border-white/20">
            <i class="fa-solid fa-right-from-bracket mr-2"></i> Log Out
        </button>
    </div>
</nav>

<main class="pt-24 pb-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 max-w-6xl">
        
        {{-- WELCOME MESSAGE --}}
        <div class="relative bg-white p-8 rounded-2xl shadow-sm border-l-8 border-[#800000] mb-4 overflow-hidden">

            {{-- TEXT CONTENT --}}
            <div class="pr-0 md:pr-96">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome, Juan!</h2>
                <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base">
                    <p>
                        This faculty evaluation assesses faculty performance for the current review period in accordance with established institutional standards and criteria.
                    </p>

                    <p>
                        Please complete all required sections based on observable performance and documented outcomes relevant to faculty responsibilities.
                    </p>

                    <p>Thank you for your cooperation! Happy evaluating!</p>
                </div>
            </div>

            {{-- IMAGE FIXED TO RIGHT BORDER --}}
            <div class="hidden md:block absolute right-6 bottom-0">
                <img
                    src="{{ asset('images/student-evaluation.png') }}"
                    alt="Evaluation Illustration"
                    class="w-64 h-auto">
            </div>

        </div>

        {{-- PROGRESS --}}
        <div class="relative bg-white pt-2 px-4 pb-3 rounded-2xl shadow-sm overflow-hidden mb-6">
            <h1 class="text-1xl font-bold text-[#808080]">PROGRESS</h1>

            <div class="flex justify-between items-center">
                <h1 class="text-xs text-[#808080]">Total Faculties Evaluated</h1>
                <span class="text-sm font-bold text-[#800000]">8/11</span>
            </div>

            <div class="progress" style="height: 5px;">
                <div class="progress-bar" role="progressbar" style="width: calc(100% * 8/11); background-color: #15FE38;" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

{{-- FACULTIES GRID --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    {{-- CARD 1 --}}
    <div class="group relative h-64 rounded-xl overflow-hidden cursor-pointer shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 bg-white">

        {{-- ========================================== --}}
        {{-- FRONT VIEW (Layer 0 - Fades Out on Hover)  --}}
        {{-- ========================================== --}}
        <div class="absolute inset-0 z-0 flex flex-col transition-opacity duration-300 ease-out group-hover:opacity-0">
            
            {{-- Image --}}
            <div class="relative flex-grow overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent z-10"></div>
                <img src="{{ asset('images/faculty1.jpg') }}" class="w-full h-full object-cover object-top" alt="Prof. Danilo Villamor">
            </div>

            {{-- Footer (Removed z-20 to stop conflicts) --}}
            <div class="bg-[#800000] h-14 px-4 flex items-center gap-3 shrink-0 relative">
                <div class="w-9 h-9 rounded-full border border-white/40 overflow-hidden shrink-0 bg-white shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-cover" alt="Logo">
                </div>
                <div class="flex flex-col justify-center overflow-hidden">
                    <h3 class="text-base font-bold text-white leading-none truncate mb-0.5">Prof. Danilo Villamor</h3>
                    <p class="text-white/80 text-[10px] font-medium uppercase tracking-wider truncate">COMP 101 | Intro to Computing</p>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- BACK VIEW (Layer 10 - Fades In on Hover)   --}}
        {{-- ========================================== --}}
        {{-- Added 'z-10' and 'bg-white' to cover the front completely --}}
        <div class="absolute inset-0 z-10 bg-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in flex flex-col justify-between p-5">
            
            {{-- Top Info --}}
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Prof. Danilo Villamor</h3>
                <div class="h-0.5 w-8 bg-[#800000] rounded-full mb-2"></div>
                <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">COMP 101 | Intro to Computing</p>
            </div>

            {{-- Action Button --}}
            {{-- Removed opacity-0 from text to ensure it is always visible when card appears --}}
            <div class="flex items-center justify-end gap-2 translate-y-2 group-hover:translate-y-0 transition-transform duration-300 delay-75">
                <span class="text-[#800000] font-bold text-xs uppercase tracking-wide">
                    Start Evaluating
                </span>
                <div class="w-8 h-8 rounded-full bg-[#800000] flex items-center justify-center text-white shadow-md group-hover:scale-110 transition-transform duration-200">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </div>
            </div>
            
        </div>

    </div>

    {{-- CARD 2 --}}
    <div class="group relative h-64 rounded-xl overflow-hidden cursor-pointer shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 bg-white">
        
        {{-- FRONT --}}
        <div class="absolute inset-0 z-0 flex flex-col transition-opacity duration-300 ease-out group-hover:opacity-0">
            <div class="relative flex-grow overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent z-10"></div>
                <img src="{{ asset('images/faculty2.jpg') }}" class="w-full h-full object-cover object-top" alt="Prof. Jane Smith">
            </div>
            <div class="bg-[#800000] h-14 px-4 flex items-center gap-3 shrink-0 relative">
                <div class="w-9 h-9 rounded-full border border-white/40 overflow-hidden shrink-0 bg-white shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-cover" alt="Logo">
                </div>
                <div class="flex flex-col justify-center overflow-hidden">
                    <h3 class="text-base font-bold text-white leading-none truncate mb-0.5">Prof. Jane Smith</h3>
                    <p class="text-white/80 text-[10px] font-medium uppercase tracking-wider truncate">MATH 101 | Calculus I</p>
                </div>
            </div>
        </div>
        
        {{-- BACK --}}
        <div class="absolute inset-0 z-10 bg-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in flex flex-col justify-between p-5">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Prof. Jane Smith</h3>
                <div class="h-0.5 w-8 bg-[#800000] rounded-full mb-2"></div>
                <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">MATH 101 | Calculus I</p>
            </div>
            <div class="flex items-center justify-end gap-2 translate-y-2 group-hover:translate-y-0 transition-transform duration-300 delay-75">
                <span class="text-[#800000] font-bold text-xs uppercase tracking-wide">Start Evaluating</span>
                <div class="w-8 h-8 rounded-full bg-[#800000] flex items-center justify-center text-white shadow-md group-hover:scale-110 transition-transform duration-200">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- CARD 3 --}}
    <div class="group relative h-64 rounded-xl overflow-hidden cursor-pointer shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 bg-white">
        
        {{-- FRONT --}}
        <div class="absolute inset-0 z-0 flex flex-col transition-opacity duration-300 ease-out group-hover:opacity-0">
            <div class="relative flex-grow overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent z-10"></div>
                <img src="{{ asset('images/faculty3.jpg') }}" class="w-full h-full object-cover object-top" alt="Prof. Michael Johnson">
            </div>
            <div class="bg-[#800000] h-14 px-4 flex items-center gap-3 shrink-0 relative">
                <div class="w-9 h-9 rounded-full border border-white/40 overflow-hidden shrink-0 bg-white shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-cover" alt="Logo">
                </div>
                <div class="flex flex-col justify-center overflow-hidden">
                    <h3 class="text-base font-bold text-white leading-none truncate mb-0.5">Prof. Michael Johnson</h3>
                    <p class="text-white/80 text-[10px] font-medium uppercase tracking-wider truncate">ENGL 102 | Technical Writing</p>
                </div>
            </div>
        </div>
        
        {{-- BACK --}}
        <div class="absolute inset-0 z-10 bg-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in flex flex-col justify-between p-5">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Prof. Michael Johnson</h3>
                <div class="h-0.5 w-8 bg-[#800000] rounded-full mb-2"></div>
                <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">ENGL 102 | Technical Writing</p>
            </div>
            <div class="flex items-center justify-end gap-2 translate-y-2 group-hover:translate-y-0 transition-transform duration-300 delay-75">
                <span class="text-[#800000] font-bold text-xs uppercase tracking-wide">Start Evaluating</span>
                <div class="w-8 h-8 rounded-full bg-[#800000] flex items-center justify-center text-white shadow-md group-hover:scale-110 transition-transform duration-200">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </div>
            </div>
        </div>

    </div>

</div>
        
    </div>
</main>

<footer class="bg-[#660000] text-white py-12">
    <div class="container mx-auto px-10 text-center text-xs">
        <p class="opacity-50">Polytechnic University of the Philippines - Main Campus</p>
        <p class="mt-4 opacity-40 tracking-widest uppercase font-bold">Copyright © {{ date('Y') }} | All Evaluation Data is Protected by Privacy Laws</p>
    </div>
</footer>

{{-- Logout Modal (if needed) --}}
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

@endsection