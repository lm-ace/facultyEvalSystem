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
    
<div class="flex items-center space-x-4">
        <span class="text-xs font-medium opacity-70 hidden sm:inline tracking-wider uppercase">System Administrator</span>
        <button type="button" onclick="showLogoutModal()" class="bg-white/10 px-4 py-1.5 rounded-lg text-sm font-bold hover:bg-white/20 transition flex items-center border border-white/20">
            <i class="fa-solid fa-right-from-bracket mr-2"></i> Log Out
        </button>
    </div>
</nav>

<div class="fixed top-[48px] left-0 right-0 z-40 bg-white border-b border-gray-200 shadow-sm px-10 py-3">
    <div class="max-w-7xl mx-auto flex items-center space-x-8 text-xs font-bold uppercase tracking-widest">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-[#800000]">Dashboard</a>
        <a href="{{ route('admin.departments') }}" class="text-[#800000] border-b-2 border-[#800000] pb-1">Departments</a>
        <a href="#" class="text-gray-400 hover:text-[#800000]">Criteria</a>
        <a href="#" class="text-gray-400 hover:text-[#800000]">Reports</a>
    </div>
</div>

<main class="pt-36 pb-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 max-w-7xl">
        
        <div class="mb-8">
            <a href="{{ route('admin.departments') }}" class="text-[#800000] text-xs font-bold uppercase hover:underline flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to CCIS Sections
            </a>
            <h2 class="text-3xl font-black text-gray-800 uppercase tracking-tight">Section: {{ str_replace('-', ' ', $section) }}</h2>
            <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">Academic Year 2025-2026 | 1st Semester</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-6 py-4 flex justify-between items-center text-white">
                        <h3 class="font-bold text-xs uppercase tracking-widest">Student Progress</h3>
                        <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full">30 Students</span>
                    </div>
                    <div class="p-0 max-h-[600px] overflow-y-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b sticky top-0">
                                <tr>
                                    <th class="px-6 py-3">Student Name</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-700">Abad, Juan M.</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-[9px] font-bold">DONE</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-700">Dela Cruz, Maria</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-[9px] font-bold">PENDING</span>
                                    </td>
                                </tr>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-6 py-4 flex justify-between items-center text-white">
                        <h3 class="font-bold text-xs uppercase tracking-widest">Faculty Evaluation Results</h3>
                        <div id="downloadNotif" class="hidden animate-pulse bg-yellow-500 text-[#800000] px-3 py-1 rounded-full text-[9px] font-bold">
                            <i class="fa-solid fa-circle-notch fa-spin mr-1"></i> Generating...
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-5 bg-gray-50 rounded-2xl border border-gray-100 group transition-all">
                            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                                <div class="bg-gray-200 w-12 h-12 rounded-full flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-user-tie text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-800 uppercase text-sm">Prof. Ricardo Dalisay</h4>
                                    <p class="text-[10px] text-gray-400 font-bold">Object Oriented Programming</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-right mr-4">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase">Student Votes</p>
                                    <p class="text-sm font-black text-[#800000]">25 / 30</p>
                                </div>
                                <button disabled class="bg-gray-200 text-gray-400 px-4 py-2 rounded-xl text-[10px] font-bold uppercase cursor-not-allowed">
                                    Incomplete
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center justify-between p-5 bg-white rounded-2xl border-2 border-[#800000]/10 shadow-sm group hover:border-[#800000]/30 transition-all">
                            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                                <div class="bg-[#800000]/10 w-12 h-12 rounded-full flex items-center justify-center text-[#800000]">
                                    <i class="fa-solid fa-user-tie text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-800 uppercase text-sm">Prof. Juan Dela Cruz, PhD</h4>
                                    <p class="text-[10px] text-gray-400 font-bold">Web Development</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-right mr-4">
                                    <p class="text-[9px] font-bold text-green-600 uppercase">Status</p>
                                    <p class="text-sm font-black text-green-600">30 / 30</p>
                                </div>
                                <button onclick="generateProfessorPDF('Prof. Juan Dela Cruz, PhD', 'Web Development')" class="bg-[#FFB800] hover:bg-[#E6A600] text-[#800000] px-5 py-2.5 rounded-xl text-[10px] font-black uppercase shadow-md transition active:scale-95">
                                    <i class="fa-solid fa-file-pdf mr-1"></i> Generate Results
                                </button>
                            </div>
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

<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-shield-halved text-[#800000] text-2xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-xl font-black text-gray-800 mb-2 uppercase">Admin Logout</h3>
            <p class="text-gray-500 text-xs">Are you sure you want to end your session?</p>
        </div>
        <div class="flex flex-col space-y-3">
            <a href="{{ route('home') }}" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl text-center shadow-lg hover:bg-[#660000] transition">Logout</a>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition">Cancel</button>
        </div>
    </div>
</div>

<script>
    function generateProfessorPDF(name, subject) {
        const { jsPDF } = window.jspdf;
        const notif = document.getElementById('downloadNotif');
        notif.classList.remove('hidden');

        setTimeout(() => {
            const doc = new jsPDF();
            const logoImg = document.getElementById('pdfLogo');

            doc.setFillColor(128, 0, 0);
            doc.rect(20, 10, 170, 8, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(10);
            doc.text("Polytechnic University of the Philippines - Main Campus", 105, 15.5, { align: 'center' });

            if (logoImg) doc.addImage(logoImg, 'PNG', 20, 25, 15, 15);
            doc.setTextColor(128, 0, 0);
            doc.setFontSize(14); doc.setFont("helvetica", "bold");
            doc.text("EduRate", 38, 31);
            doc.setFontSize(10); doc.text("Faculty Evaluation System", 38, 37);

            doc.setTextColor(0, 0, 0);
            doc.setFontSize(16); doc.text("Section Evaluation Report", 105, 58, { align: 'center' });
            doc.setFontSize(11);
            doc.text("Faculty Name: " + name, 20, 75);
            doc.text("Subject: " + subject, 20, 83);
            doc.text("Section: BSIT 3-3", 20, 91);
            doc.text("Overall Rating: 4.85 / 5.0", 20, 105);

            doc.save('Evaluation_' + name.replace(/\s+/g, '_') + '.pdf');
            notif.classList.add('hidden');
        }, 1500);
    }

    function showLogoutModal() { document.getElementById('logoutModal').classList.remove('hidden'); document.getElementById('logoutModal').classList.add('flex'); }
    function hideLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('flex'); }
</script>
@endsection