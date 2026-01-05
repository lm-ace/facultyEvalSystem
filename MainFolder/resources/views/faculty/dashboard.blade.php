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
        
    {{--IN LINE 30: Make a logic in here that the name of the user will appear in the Welcome, 'Name'--}}
        <div class="bg-white p-8 rounded-2xl shadow-sm border-l-8 border-[#800000] mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome, Professor Juan Dela Cruz, PhD!</h2>
            <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base">
                <p>This faculty evaluation dashboard provides an overview of evaluation results for the current review period, based on established institutional standards and approved evaluation criteria.</p>
                <p class="bg-[#800000]/5 p-3 rounded-lg border border-[#800000]/10 font-medium italic">
                    Access to this dashboard is restricted and intended solely for the concerned faculty member and authorized academic officials.
                </p>
                <p>Thank you for your continued commitment to teaching excellence and professional growth.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-xl transition-all duration-300">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Overall Rating</p>
                    <h3 class="text-5xl font-black text-[#800000] mt-2">4.85<span class="text-xl text-gray-400 font-normal"> / 5.0</span></h3>
                    <p class="text-green-600 font-bold text-sm mt-1 uppercase">Outstanding performance</p>
                </div>
                <div class="bg-[#800000]/10 p-5 rounded-2xl text-[#800000] group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-star text-4xl"></i>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-xl transition-all duration-300">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Responses</p>
                    <h3 class="text-5xl font-black text-gray-800 mt-2">145</h3>
                    <p class="text-gray-500 text-sm mt-1 uppercase font-medium">Students participated</p>
                </div>
                <div class="bg-blue-50 p-5 rounded-2xl text-blue-600 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users text-4xl"></i>
                </div>
            </div>
        </div>

        <div id="capture-area" class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
            <div class="bg-[#800000] px-8 py-4 flex flex-col md:flex-row justify-between items-center text-white">
                <h3 class="font-bold text-lg mb-2 md:mb-0 uppercase tracking-wider">Evaluation Details</h3>
                
                <div class="flex items-center space-x-3">
                    <div id="statusContainer" class="hidden flex items-center space-x-3">
                        <div id="downloadNotif" class="bg-yellow-500 text-[#800000] px-4 py-1.5 rounded-full text-xs font-bold animate-pulse">
                            <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Downloading...
                        </div>
                        <div id="fileReady" class="hidden bg-green-500 text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-sm">
                            <i class="fa-solid fa-check mr-2"></i> Downloaded
                        </div>
                        <button id="viewBtn" onclick="viewPDF()" class="hidden bg-white text-[#800000] px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-100 transition shadow-md">
                            <i class="fa-solid fa-eye mr-1"></i> View Report
                        </button>
                    </div>

                    <button id="dlBtn" onclick="startDownload()" class="bg-[#FFB800] hover:bg-[#E6A600] text-[#800000] px-6 py-2 rounded-lg font-bold text-sm transition shadow-md active:scale-95">
                        <i class="fa-solid fa-file-pdf mr-2"></i> Generate PDF Report
                    </button>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-10 pb-10 border-b border-gray-100">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Faculty ID</p>
                        <p class="text-lg font-bold text-[#800000]">2024-FAC-0012</p> </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Faculty Name</p>
                        <p class="text-lg font-bold text-gray-800">Prof. Juan Dela Cruz, PhD</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Department</p>
                        <p class="text-lg font-bold text-gray-800">College of Computer and Information Sciences</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Review Period</p>
                        <p class="text-lg font-bold text-gray-800">First Semester | 2025-2026</p>
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="font-bold text-[#800000] uppercase tracking-widest text-sm mb-6 flex items-center">
                        <span class="w-8 h-[2px] bg-[#800000] mr-3"></span> Detailed Results Breakdown
                    </h4>
                    <div class="space-y-8">
                        <div>
                            <div class="flex justify-between text-sm mb-2 font-medium text-gray-700">
                                <span>Communication and Clarity</span>
                                <span class="font-bold">4.9 / 5.0</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-[#FFB800] h-2 rounded-full shadow-sm" style="width: 98%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2 font-medium text-gray-700">
                                <span>Course Content and Material</span>
                                <span class="font-bold">4.7 / 5.0</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-[#FFB800] h-2 rounded-full shadow-sm" style="width: 94%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-[#800000] uppercase tracking-widest text-sm mb-6 flex items-center">
                        <span class="w-8 h-[2px] bg-[#800000] mr-3"></span> Student Feedback (Anonymous)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 italic text-sm text-gray-600 shadow-inner">
                            "The professor is very approachable and explains complex topics clearly."
                        </div>
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 italic text-sm text-gray-600 shadow-inner">
                            "I appreciate how the professor uses real-world examples during lectures."
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-right-from-bracket text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Ready to Leave?</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Are you sure you want to log out of the <strong>Faculty Portal</strong>?</p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="executeLogout()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98]">Yes, Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Cancel</button>
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

    // MODAL FUNCTIONS
    function showLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => { modal.querySelector('div').classList.remove('scale-95'); modal.querySelector('div').classList.add('scale-100'); }, 10);
    }
    function hideLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }
    function executeLogout() { window.location.href = "{{ route('home') }}"; }

    // PDF GENERATION LOGIC
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

            if (logoImg) {
                doc.addImage(logoImg, 'PNG', 20, 25, 15, 15);
            }
            doc.setTextColor(128, 0, 0); doc.setFontSize(14); doc.setFont("helvetica", "bold");
            doc.text("EduRate", 38, 31); 
            doc.setFontSize(10);
            doc.text("Faculty Evaluation System", 38, 37);
            doc.setDrawColor(200, 200, 200); doc.line(20, 45, 190, 45);

            // Report Title
            doc.setFontSize(16); doc.text("EduRate Faculty Evaluation Report", 105, 58, { align: 'center' });

            // Faculty Details Section with Faculty ID included
            doc.setTextColor(0, 0, 0); doc.setFontSize(11);
            doc.text("Faculty ID:", 20, 75); doc.setFont("helvetica", "normal"); doc.text("2024-FAC-0012", 60, 75);
            doc.setFont("helvetica", "bold"); doc.text("Faculty Name:", 20, 83); doc.setFont("helvetica", "normal"); doc.text("Prof. Juan Dela Cruz, PhD", 60, 83);
            doc.setFont("helvetica", "bold"); doc.text("Department:", 20, 91); doc.setFont("helvetica", "normal"); doc.text("College of Computer Science", 60, 91);
            doc.setFont("helvetica", "bold"); doc.text("Review Period:", 20, 99); doc.setFont("helvetica", "normal"); doc.text("First Semester | 2025-2026", 60, 99);
            doc.line(20, 108, 190, 108);

            doc.setFont("helvetica", "bold"); doc.text("Overall Rating:", 20, 118);
            doc.setFont("helvetica", "normal"); doc.text("4.85 / 5.0", 60, 118);
            doc.setFont("helvetica", "bold"); doc.text("Student Feedback:", 20, 128);

            // Feedback Entries
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