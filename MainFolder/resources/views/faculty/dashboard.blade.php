@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

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

            {{-- Dropdown Menu --}}
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 x-cloak
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
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome, Professor Juan Dela Cruz, PhD!</h2>
                <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base">
                    <p>This faculty evaluation dashboard provides an overview of evaluation results for the current review period.</p>
                    <p class="bg-[#800000]/5 p-3 rounded-lg border border-[#800000]/10 font-medium italic">
                        Access to this dashboard is restricted and intended solely for authorized academic officials.
                    </p>
                </div>
            </div>

            {{-- RATINGS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Overall Rating</p>
                        <h3 class="text-5xl font-black text-[#800000] mt-2">4.85<span class="text-xl text-gray-400 font-normal"> / 5.0</span></h3>
                    </div>
                    <div class="bg-[#800000]/10 p-5 rounded-2xl text-[#800000]">
                        <i class="fa-solid fa-star text-4xl"></i>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Responses</p>
                        <h3 class="text-5xl font-black text-gray-800 mt-2">145</h3>
                    </div>
                    <div class="bg-blue-50 p-5 rounded-2xl text-blue-600">
                        <i class="fa-solid fa-users text-4xl"></i>
                    </div>
                </div>
            </div>

            {{-- EVALUATION DETAILS --}}
            <div id="capture-area" class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                <div class="bg-[#800000] px-8 py-4 flex flex-col md:flex-row justify-between items-center text-white">
                    <h3 class="font-bold text-lg uppercase tracking-wider">Evaluation Details</h3>
                    <div class="flex items-center space-x-3">
                        <div id="statusContainer" class="hidden flex items-center space-x-3">
                            <div id="downloadNotif" class="bg-yellow-500 text-[#800000] px-4 py-1.5 rounded-full text-xs font-bold animate-pulse">
                                <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Downloading...
                            </div>
                            <div id="fileReady" class="hidden bg-green-500 text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-sm">
                                <i class="fa-solid fa-check mr-2"></i> Downloaded
                            </div>
                            <button id="viewBtn" onclick="viewPDF()" class="hidden bg-white text-[#800000] px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-100 shadow-md">
                                <i class="fa-solid fa-eye mr-1"></i> View Report
                            </button>
                        </div>
                        <button id="dlBtn" onclick="startDownload()" class="bg-[#FFB800] hover:bg-[#E6A600] text-[#800000] px-6 py-2 rounded-lg font-bold text-sm transition active:scale-95">
                            <i class="fa-solid fa-file-pdf mr-2"></i> Generate PDF Report
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10 pb-10 border-b border-gray-100 text-gray-800">
                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Faculty ID</p><p class="font-bold text-[#800000]">2024-FAC-0012</p></div>
                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Faculty Name</p><p class="font-bold">Prof. Juan Dela Cruz, PhD</p></div>
                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Department</p><p class="font-bold">CCIS</p></div>
                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Review Period</p><p class="font-bold">1st Sem | 2025-2026</p></div>
                    </div>

                    {{-- PROGRESS BARS --}}
                    <div class="mb-10">
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between text-sm mb-2 font-medium"><span>Communication</span><span>4.9 / 5.0</span></div>
                                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-[#FFB800] h-2 rounded-full" style="width: 98%"></div></div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-2 font-medium"><span>Content</span><span>4.7 / 5.0</span></div>
                                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-[#FFB800] h-2 rounded-full" style="width: 94%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- MODALS --}}

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
            doc.setFillColor(128, 0, 0); doc.rect(20, 10, 170, 8, 'F');
            doc.setTextColor(255, 255, 255); doc.setFontSize(10); doc.setFont("helvetica", "bold");
            doc.text("Polytechnic University of the Philippines - Main Campus", 105, 15.5, { align: 'center' });
            if (logoImg) { doc.addImage(logoImg, 'PNG', 20, 25, 15, 15); }
            doc.setTextColor(128, 0, 0); doc.setFontSize(14); doc.setFont("helvetica", "bold");
            doc.text("EduRate", 38, 31); 
            doc.setFontSize(10); doc.text("Faculty Evaluation System", 38, 37);
            doc.setDrawColor(200, 200, 200); doc.line(20, 45, 190, 45);
            doc.setFontSize(16); doc.text("EduRate Faculty Evaluation Report", 105, 58, { align: 'center' });
            doc.setTextColor(0, 0, 0); doc.setFontSize(11);
            doc.text("Faculty ID:", 20, 75); doc.setFont("helvetica", "normal"); doc.text("2024-FAC-0012", 60, 75);
            doc.setFont("helvetica", "bold"); doc.text("Faculty Name:", 20, 83); doc.setFont("helvetica", "normal"); doc.text("Prof. Juan Dela Cruz, PhD", 60, 83);
            doc.setFont("helvetica", "bold"); doc.text("Department:", 20, 91); doc.setFont("helvetica", "normal"); doc.text("College of Computer Science", 60, 91);
            doc.setFont("helvetica", "bold"); doc.text("Review Period:", 20, 99); doc.setFont("helvetica", "normal"); doc.text("First Semester | 2025-2026", 60, 99);
            doc.line(20, 108, 190, 108);
            doc.setFont("helvetica", "bold"); doc.text("Overall Rating:", 20, 118);
            doc.setFont("helvetica", "normal"); doc.text("4.85 / 5.0", 60, 118);
            doc.setFont("helvetica", "bold"); doc.text("Student Feedback:", 20, 128);
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
            doc.save('Faculty_Evaluation_Report_2025.pdf');
            dlNotif.classList.add('hidden'); fileReady.classList.remove('hidden'); viewBtn.classList.remove('hidden');
        }, 2000);
    }

    function viewPDF() { if (generatedPDFBlob) { window.open(generatedPDFBlob, '_blank'); } }
</script>
@endsection