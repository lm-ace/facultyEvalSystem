@extends('layouts.app')

@section('content')
{{-- 
    1. CRITICAL: DEFINE DATA AT THE VERY TOP
    This fixes the "count(): Argument #1... null given" error.
--}}
@php
    // Current Faculty Data
    $faculty_info = [
        'id' => '2024-FAC-0012',
        'name' => 'JUAN DELA CRUZ',
        'dept' => 'CCIS',
        'period' => '1st Sem | 2025-26'
    ];

    // Dummy data just to prevent count() errors if used elsewhere
    $faculties = [1,2,3,4,5]; 
@endphp

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

{{-- Hidden Logo for PDF Generation --}}
<img id="pdfLogo" src="{{ asset('images/logo.png') }}" class="hidden">

<div x-data="{ showEvaluation: false }">
    
    {{-- NAVIGATION --}}
    <nav class="fixed top-0 left-0 right-0 z-[100] flex items-center justify-between px-10 py-2 text-white bg-[#800000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8"> 
            <div>
                <h1 class="font-bold leading-none text-base">EduRate</h1>
                <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
            </div>
        </div>
            
        {{-- USER DROPDOWN --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" type="button" class="flex items-center focus:outline-none group">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#800000] border-2 border-white/20 transition group-hover:border-white/50">
                    <i class="fa-solid fa-user text-lg"></i>
                </div>
                <i class="fa-solid fa-caret-down text-[10px] ml-2 text-white/80 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" x-cloak
                 class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl py-2 z-[110] border border-gray-100 overflow-hidden text-gray-700">
                <button type="button" onclick="showChangePasswordModal()" class="w-full text-left px-5 py-3 text-sm hover:bg-gray-50 flex items-center transition group">
                    <i class="fa-solid fa-key mr-3 text-gray-400 group-hover:text-[#800000]"></i> 
                    <span class="font-medium">Change Password</span> 
                </button>
                <hr class="border-gray-50">
                <button type="button" onclick="showLogoutModal()" class="w-full text-left px-5 py-3 text-sm text-[#E31E24] font-bold hover:bg-red-50 flex items-center transition group">
                    <i class="fa-solid fa-right-from-bracket mr-3 transform rotate-180 text-[#E31E24]"></i> 
                    <span>Log Out</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-16 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6 max-w-6xl">
            
{{-- WELCOME SECTION --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border-l-8 border-[#800000] mb-8">
                {{-- Added "Professor" before the name variable --}}
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome, Professor {{ $faculty_info['name'] }}!</h2>
                
                <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base">
                    {{-- Updated Message --}}
                    <p>
                        We appreciate your continued dedication to academic excellence. 
                        This dashboard provides a comprehensive overview of your evaluation results, 
                        student feedback, and performance metrics for the <strong>{{ $faculty_info['period'] }}</strong> review period.
                    </p>
                </div>
            </div>

            {{-- RATINGS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group border border-gray-100">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Overall Rating</p>
                        <h3 class="text-5xl font-black text-[#800000] mt-2">4.85<span class="text-xl text-gray-400 font-normal"> / 5.0</span></h3>
                    </div>
                    <div class="bg-[#800000]/10 w-16 h-16 flex items-center justify-center rounded-2xl text-[#800000]">
                        <i class="fa-solid fa-star text-3xl"></i>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group border border-gray-100">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Responses</p>
                        <h3 class="text-5xl font-black text-gray-800 mt-2">145</h3>
                    </div>
                    <div class="bg-blue-50 w-16 h-16 flex items-center justify-center rounded-2xl text-blue-600">
                        <i class="fa-solid fa-users text-3xl"></i>
                    </div>
                </div>
            </div>

            {{-- CARD 1: FACULTY INFORMATION --}}
            {{-- Styled exactly like your screenshot: Icons, Red Header, Specific Colors --}}
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 mb-8">
                <div class="bg-[#800000] px-8 py-4 flex items-center justify-between text-white">
                    <h3 class="font-bold text-base uppercase tracking-wider flex items-center gap-3">
                        <i class="fa-solid fa-id-card text-[#FFB800]"></i> Faculty Profile
                    </h3>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        
                        <div class="space-y-1">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-id-card"></i> ID Number
                            </p>
                            <p class="text-xl font-black text-[#800000] tracking-tight">{{ $faculty_info['id'] }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-user"></i> Name
                            </p>
                            <p class="text-xl font-black text-gray-800 tracking-tight leading-tight">{{ $faculty_info['name'] }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-building-columns"></i> Dept
                            </p>
                            <p class="text-xl font-black text-gray-800 tracking-tight">{{ $faculty_info['dept'] }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-calendar"></i> Period
                            </p>
                            <div class="inline-block bg-gray-100 px-3 py-1 rounded-md">
                                <p class="text-sm font-bold text-gray-600">{{ $faculty_info['period'] }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- CARD 2: EVALUATION DETAILS + ANONYMOUS FEEDBACK --}}
            <div id="capture-area" class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                <div class="bg-[#800000] px-8 py-4 flex flex-col md:flex-row justify-between items-center text-white">
                    <h3 class="font-bold text-lg uppercase tracking-wider flex items-center gap-3">
                        <i class="fa-solid fa-chart-pie text-[#FFB800]"></i> Evaluation Details
                    </h3>
                    
                    {{-- Download Controls --}}
                    <div class="flex items-center space-x-3 mt-4 md:mt-0">
                        <div id="statusContainer" class="hidden flex items-center space-x-3">
                            <div id="downloadNotif" class="bg-yellow-500 text-[#800000] px-4 py-1.5 rounded-full text-xs font-bold animate-pulse">
                                <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Downloading...
                            </div>
                            <div id="fileReady" class="hidden bg-green-500 text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-sm">
                                <i class="fa-solid fa-check mr-2"></i> Downloaded
                            </div>
                            <button id="viewBtn" onclick="viewPDF()" class="hidden bg-white text-[#800000] px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-100 shadow-md">
                                <i class="fa-solid fa-eye mr-1"></i> View
                            </button>
                        </div>
                        <button id="dlBtn" onclick="startDownload()" class="bg-[#FFB800] hover:bg-[#E6A600] text-[#800000] px-6 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition active:scale-95 shadow-lg">
                            <i class="fa-solid fa-file-pdf mr-2"></i> Generate Report
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    {{-- PROGRESS BARS --}}
                    <div class="space-y-8 mb-12">
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-gray-700">Instructional Competence</span>
                                <span class="text-xl font-black text-[#800000]">4.9 <span class="text-sm text-gray-400 font-normal">/ 5.0</span></span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-[#800000] to-red-500 h-3 rounded-full shadow-md" style="width: 98%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-gray-700">Teaching Methodology</span>
                                <span class="text-xl font-black text-[#800000]">4.7 <span class="text-sm text-gray-400 font-normal">/ 5.0</span></span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-[#800000] to-red-500 h-3 rounded-full shadow-md" style="width: 94%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- NEW: ANONYMOUS FEEDBACK SECTION --}}
                    <div class="border-t border-gray-100 pt-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-comments"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 uppercase tracking-widest text-xs">Anonymous Student Feedback</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 relative group hover:border-[#800000]/30 transition">
                                <i class="fa-solid fa-quote-left text-gray-200 text-4xl absolute top-4 left-4 -z-10 group-hover:text-[#800000]/10 transition-colors"></i>
                                <p class="text-sm text-gray-600 italic leading-relaxed z-10 relative">"The professor provides very clear examples and is approachable. I learned a lot in this subject."</p>
                            </div>

                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 relative group hover:border-[#800000]/30 transition">
                                <i class="fa-solid fa-quote-left text-gray-200 text-4xl absolute top-4 left-4 -z-10 group-hover:text-[#800000]/10 transition-colors"></i>
                                <p class="text-sm text-gray-600 italic leading-relaxed z-10 relative">"Excellent teaching style, learned a lot during lab sessions. Best prof so far!"</p>
                            </div>

                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 relative group hover:border-[#800000]/30 transition">
                                <i class="fa-solid fa-quote-left text-gray-200 text-4xl absolute top-4 left-4 -z-10 group-hover:text-[#800000]/10 transition-colors"></i>
                                <p class="text-sm text-gray-600 italic leading-relaxed z-10 relative">"Constructive feedback is given on every assignment which helps us improve."</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>
</div>

{{-- MODALS RESTORED (Fixed the "View partials.modals not found" error) --}}

{{-- LOGOUT MODAL --}}
<div id="logoutModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8 transform scale-95 transition-all duration-300 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-right-from-bracket text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Ready to Leave?</h3>
            <p class="text-gray-500 text-sm">Are you sure you want to log out?</p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="executeLogout()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl hover:bg-[#660000] transition">Yes, Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition">Cancel</button>
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
    let generatedPDFBlob = null;

    function showModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden'); 
        modal.classList.add('flex');
        setTimeout(() => { modal.querySelector('div').classList.replace('scale-95', 'scale-100'); }, 10);
    }

    function hideModal(id) {
        const modal = document.getElementById(id);
        modal.querySelector('div').classList.replace('scale-100', 'scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    function showLogoutModal() { showModal('logoutModal'); }
    function hideLogoutModal() { hideModal('logoutModal'); }
    function showChangePasswordModal() { showModal('changePasswordModal'); }
    function hideChangePasswordModal() { hideModal('changePasswordModal'); }
    function executeLogout() { window.location.href = "{{ route('home') }}"; }

    function startDownload() {
        const { jsPDF } = window.jspdf;
        const statusContainer = document.getElementById('statusContainer');
        const dlNotif = document.getElementById('downloadNotif');
        const fileReady = document.getElementById('fileReady');
        const viewBtn = document.getElementById('viewBtn');
        const dlBtn = document.getElementById('dlBtn');
        const logoImg = document.getElementById('pdfLogo');

        statusContainer.classList.remove('hidden');
        dlBtn.disabled = true; dlBtn.classList.add('opacity-50');

        setTimeout(() => {
            const doc = new jsPDF();
            // Header Color
            doc.setFillColor(128, 0, 0); doc.rect(20, 10, 170, 8, 'F');
            doc.setTextColor(255, 255, 255); doc.setFontSize(10); doc.setFont("helvetica", "bold");
            doc.text("Polytechnic University of the Philippines - Main Campus", 105, 15.5, { align: 'center' });
            
            // Logo
            if (logoImg) { doc.addImage(logoImg, 'PNG', 20, 25, 15, 15); }
            
            // Title
            doc.setTextColor(128, 0, 0); doc.setFontSize(14); doc.setFont("helvetica", "bold");
            doc.text("EduRate", 38, 31); 
            doc.setFontSize(10); doc.text("Faculty Evaluation System", 38, 37);
            doc.setDrawColor(200, 200, 200); doc.line(20, 45, 190, 45);
            
            // Report Title
            doc.setFontSize(16); doc.text("Faculty Evaluation Report", 105, 58, { align: 'center' });
            
            // Faculty Info Section (Hardcoded mostly since it's PDF generation logic)
            doc.setTextColor(0, 0, 0); doc.setFontSize(11);
            doc.text("Faculty ID:", 20, 75); doc.setFont("helvetica", "normal"); doc.text("2024-FAC-0012", 60, 75);
            doc.setFont("helvetica", "bold"); doc.text("Faculty Name:", 20, 83); doc.setFont("helvetica", "normal"); doc.text("Prof. Danilo Villamor, PhD", 60, 83);
            doc.setFont("helvetica", "bold"); doc.text("Department:", 20, 91); doc.setFont("helvetica", "normal"); doc.text("CCIS", 60, 91);
            doc.setFont("helvetica", "bold"); doc.text("Review Period:", 20, 99); doc.setFont("helvetica", "normal"); doc.text("1st Sem | 2025-2026", 60, 99);
            
            doc.line(20, 108, 190, 108);
            
            // Scores
            doc.setFont("helvetica", "bold"); doc.text("Overall Rating:", 20, 118);
            doc.setFont("helvetica", "normal"); doc.text("4.85 / 5.0", 60, 118);
            
            // Sample Comments Table
            doc.setFont("helvetica", "bold"); doc.text("Sample Feedback:", 20, 128);
            doc.autoTable({
                startY: 133, margin: { left: 20, right: 20 }, theme: 'plain',
                styles: { cellPadding: 4, fontSize: 9, font: 'helvetica', lineColor: [220, 220, 220], lineWidth: 0.1 },
                body: [
                    ["The professor provides very clear examples and is approachable."],
                    ["Excellent teaching style, learned a lot during lab sessions."],
                    ["Constructive feedback is given on every assignment."]
                ],
            });
            
            generatedPDFBlob = doc.output('bloburl');
            doc.save('Faculty_Evaluation_Report.pdf');
            
            // UI Updates
            dlNotif.classList.add('hidden'); fileReady.classList.remove('hidden'); viewBtn.classList.remove('hidden');
            dlBtn.disabled = false; dlBtn.classList.remove('opacity-50');
        }, 2000);
    }

    function viewPDF() { if (generatedPDFBlob) { window.open(generatedPDFBlob, '_blank'); } }
</script>
@endsection