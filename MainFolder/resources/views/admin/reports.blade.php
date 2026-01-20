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
        <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-chart-pie mr-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.departments') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-sitemap mr-2"></i> Departments
        </a>
        <a href="{{ route('admin.criteria') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-list-check mr-2"></i> Criteria
        </a>
        <a href="{{ route('admin.reports') }}" class="flex items-center text-[#800000] border-b-2 border-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-file-contract mr-2"></i> Reports
        </a>
    </div>
</div>

<main class="pt-36 pb-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 max-w-7xl">
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border-l-8 border-[#800000] mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Faculty Performance Reports</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">Comprehensive evaluation reports and analytics for faculty performance assessment.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button onclick="generateFullReport()" class="bg-[#800000] hover:bg-[#660000] text-white px-6 py-3 rounded-xl font-bold shadow-md transition active:scale-95 flex items-center">
                        <i class="fa-solid fa-file-pdf mr-2"></i> Generate Full Report
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                <h3 class="font-bold text-gray-800 text-sm flex items-center uppercase tracking-widest">
                    <i class="fa-solid fa-filter mr-3 text-[#800000]"></i> Filter Reports
                </h3>
                
                <div class="flex flex-wrap gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Department</label>
                        <select id="departmentFilter" onchange="filterReports()" class="mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#800000] outline-none text-xs font-bold text-gray-700 min-w-[200px]">
                            <option value="all">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }} ({{ $department->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Semester</label>
                        <select id="semesterFilter" class="mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#800000] outline-none text-xs font-bold text-gray-700">
                            <option value="1st Semester 2025-2026">1st Semester 2025-2026</option>
                            <option value="2nd Semester 2024-2025">2nd Semester 2024-2025</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-8">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-sm uppercase tracking-wider">Faculty Performance Summary</h3>
                <span class="text-[10px] bg-white/20 px-3 py-1 rounded-full font-bold">{{ $faculties->count() }} Faculty Members</span>
            </div>
            
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b">
                        <tr>
                            <th class="px-6 py-4">Faculty Member</th>
                            <th class="px-6 py-4">Department</th>
                            <th class="px-6 py-4 text-center">Overall Rating</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($faculties as $faculty)
                        <tr class="hover:bg-gray-50 transition faculty-row" data-dept-id="{{ $faculty->department_id }}">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $faculty->first_name }} {{ $faculty->last_name }}</div>
                                <div class="text-gray-400 text-[9px] font-normal italic">{{ $faculty->faculty_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[9px] font-bold border border-blue-100">
                                    {{ $faculty->department->code ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center">
                                    <span class="font-black text-[#800000] text-sm mr-2">{{ number_format($faculty->overall_rating ?? 0, 2) }}</span>
                                    <div class="flex">
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star-half-stroke text-yellow-500 text-[10px]"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="viewFacultyReport(this)" 
                                        data-faculty-id="{{ $faculty->faculty_code }}"
                                        data-faculty-name="{{ $faculty->first_name }} {{ $faculty->last_name }}"
                                        data-department="{{ $faculty->department->code ?? 'N/A' }}"
                                        data-rating="{{ number_format($faculty->overall_rating ?? 0, 2) }}"
                                        class="bg-[#800000] hover:bg-[#660000] text-white px-4 py-2 rounded-lg text-[9px] font-bold uppercase shadow-sm transition active:scale-95">
                                    <i class="fa-solid fa-eye mr-1"></i> View
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">No faculty records found in database.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<div id="facultyReportModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000] max-h-[90vh] overflow-y-auto">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Faculty Performance Report</h3>
            <p id="facultyName" class="text-gray-500 text-sm font-bold"></p>
        </div>
        
        <div class="space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Overall Rating</p>
                    <p id="reportOverall" class="text-2xl font-black text-[#800000] mt-2"></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Status</p>
                    <p class="text-xl font-black text-green-600 mt-2">Active</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Rank</p>
                    <p class="text-2xl font-black text-gray-600 mt-2">-</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Responses</p>
                    <p class="text-2xl font-black text-gray-600 mt-2">-</p>
                </div>
            </div>
            
            <div>
                <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                    <i class="fa-solid fa-chart-simple mr-2"></i> Evaluation Breakdown
                </h4>
                <div class="space-y-3">
                    <div class="w-full bg-gray-100 p-3 rounded text-center text-xs text-gray-500">
                        Detailed breakdown requires connecting the Evaluation model.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-3 mt-8">
            <button onclick="downloadIndividualReport()" class="flex-1 py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98] text-sm">
                <i class="fa-solid fa-file-pdf mr-2"></i> Download PDF
            </button>
            <button onclick="hideFacultyReportModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98] text-sm">Close</button>
        </div>
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-shield-halved text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Admin Logout</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Confirm if you want to end your current administrative session.</p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="executeLogout()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98]">Confirm Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Cancel</button>
        </div>
    </div>
</div>

<script>
    // -------------------------------------------------------------
    // DATA INJECTION: Pass PHP Database Variables to JavaScript
    // -------------------------------------------------------------
    const dbFaculties = @json($faculties); 
    const dbDepartments = @json($departments); 

    let currentFacultyData = null;

    // --- Modal Logic ---
    function showLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }
    function hideLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.add('hidden'); modal.classList.remove('flex');
    }
    function executeLogout() { window.location.href = "/"; }

    // --- Filter Logic ---
    function filterReports() {
        const deptId = document.getElementById('departmentFilter').value;
        const rows = document.querySelectorAll('.faculty-row');
        
        rows.forEach(row => {
            const rowDeptId = row.getAttribute('data-dept-id');
            if (deptId === 'all' || rowDeptId == deptId) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // --- View Report Logic ---
    function viewFacultyReport(button) {
        currentFacultyData = {
            id: button.getAttribute('data-faculty-id'),
            name: button.getAttribute('data-faculty-name'),
            department: button.getAttribute('data-department'),
            rating: button.getAttribute('data-rating')
        };
        
        document.getElementById('facultyName').textContent = currentFacultyData.name + ' | ' + currentFacultyData.id;
        document.getElementById('reportOverall').textContent = currentFacultyData.rating;
        
        const modal = document.getElementById('facultyReportModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }

    function hideFacultyReportModal() {
        const modal = document.getElementById('facultyReportModal');
        modal.classList.add('hidden'); modal.classList.remove('flex');
    }

    // -------------------------------------------------------------
    // DYNAMIC PDF GENERATION (FULL REPORT)
    // -------------------------------------------------------------
    function generateFullReport() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const logoImg = document.getElementById('pdfLogo');
        const today = new Date();
        const formattedDate = today.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        
        if (logoImg) { doc.addImage(logoImg, 'PNG', 20, 15, 15, 15); }
        
        doc.setFontSize(20); doc.setFont("helvetica", "bold"); doc.setTextColor(128, 0, 0);
        doc.text("EduRate Faculty Evaluation Report", 105, 25, { align: 'center' });
        
        doc.setFontSize(11); doc.setTextColor(100, 100, 100);
        doc.text(`Generated on: ${formattedDate}`, 105, 32, { align: 'center' });
        
        doc.setDrawColor(128, 0, 0); doc.setLineWidth(0.5); doc.line(20, 40, 190, 40);
        
        // Summary Section
        doc.setFontSize(14); doc.setTextColor(0, 0, 0);
        doc.text("Comprehensive Summary", 20, 55);
        
        doc.setFontSize(10);
        doc.text(`Total Faculty Members: ${dbFaculties.length}`, 20, 65);
        
        // Calculate Average
        let totalRating = 0;
        dbFaculties.forEach(fac => { totalRating += parseFloat(fac.overall_rating || 0); });
        const avgRating = dbFaculties.length > 0 ? (totalRating / dbFaculties.length).toFixed(2) : "0.00";
        doc.text(`System Average Rating: ${avgRating} / 5.0`, 20, 72);

        // Map DB data for AutoTable
        const tableBody = dbFaculties.map(fac => [
            `${fac.first_name} ${fac.last_name}`,
            fac.department ? fac.department.code : 'N/A', // Dynamic Department
            parseFloat(fac.overall_rating || 0).toFixed(2),
            "Active"
        ]);

        doc.autoTable({
            startY: 90,
            head: [['Faculty Name', 'Department', 'Rating', 'Status']],
            body: tableBody,
            margin: { left: 20, right: 20 },
            theme: 'grid',
            headStyles: { fillColor: [128, 0, 0], textColor: 255 }
        });
        
        doc.save(`Comprehensive_Report_${formattedDate.replace(/[/]/g, '-')}.pdf`);
    }

    // -------------------------------------------------------------
    // INDIVIDUAL PDF REPORT
    // -------------------------------------------------------------
    function downloadIndividualReport() {
        if (!currentFacultyData) { alert('No data loaded.'); return; }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        doc.setFontSize(18); doc.setTextColor(128, 0, 0);
        doc.text("Individual Faculty Report", 105, 20, { align: 'center' });
        
        doc.setFontSize(12); doc.setTextColor(0,0,0);
        doc.text(`Faculty Name: ${currentFacultyData.name}`, 20, 40);
        doc.text(`Faculty ID: ${currentFacultyData.id}`, 20, 50);
        doc.text(`Department: ${currentFacultyData.department}`, 20, 60);
        doc.text(`Overall Rating: ${currentFacultyData.rating}`, 20, 70);
        
        doc.save(`Report_${currentFacultyData.id}.pdf`);
    }
</script>
@endsection