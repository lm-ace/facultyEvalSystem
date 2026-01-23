@extends('layouts.app')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<img id="pdfLogo" src="{{ asset('images/logo.png') }}" class="hidden">

{{-- Navigation --}}
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

{{-- Sub-Nav --}}
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

        {{-- Filter Section --}}
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                <h3 class="font-bold text-gray-800 text-sm flex items-center uppercase tracking-widest">
                    <i class="fa-solid fa-filter mr-3 text-[#800000]"></i> Filter Reports
                </h3>
                
                <form action="{{ route('admin.reports') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    {{-- Department Filter --}}
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Department</label>
                        <select name="department" id="departmentFilter" class="mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#800000] outline-none text-xs font-bold text-gray-700 min-w-[200px]">
                            <option value="all">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }} ({{ $department->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Semester Filter (Restored) --}}
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Semester</label>
                        <select name="semester" id="semesterFilter" class="mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#800000] outline-none text-xs font-bold text-gray-700 min-w-[200px]">
                            <option value="all">All Semesters</option>
                            @if(isset($semesters))
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ request('semester') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-xl text-xs font-bold hover:bg-gray-700">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table Section --}}
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
                            <th class="px-6 py-4 text-center">Overall Rating (Max 5.0)</th>
                            <th class="px-6 py-4 text-center">Responses</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($faculties as $faculty)
                            @php
                                $rating = $faculty->overall_rating ?? 0;
                                $status = 'N/A';
                                $statusColor = 'bg-gray-100 text-gray-600';

                                if($rating >= 4.50) { $status = 'Outstanding'; $statusColor = 'bg-green-100 text-green-700'; }
                                elseif($rating >= 3.50) { $status = 'Very Satisfactory'; $statusColor = 'bg-blue-100 text-blue-700'; }
                                elseif($rating >= 2.50) { $status = 'Satisfactory'; $statusColor = 'bg-yellow-100 text-yellow-700'; }
                                elseif($rating >= 1.50) { $status = 'Needs Improvement'; $statusColor = 'bg-orange-100 text-orange-700'; }
                                elseif($rating > 0) { $status = 'Poor'; $statusColor = 'bg-red-100 text-red-700'; }
                            @endphp
                        <tr class="hover:bg-gray-50 transition faculty-row">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $faculty->first_name }} {{ $faculty->last_name }}</div>
                                <div class="text-gray-400 text-[9px] font-normal italic">{{ $faculty->faculty_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-[9px] font-bold border border-gray-200">
                                    {{ $faculty->department->code ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-black text-[#800000] text-sm">{{ number_format($rating, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-gray-800">{{ $faculty->evaluations->count() }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="{{ $statusColor }} px-3 py-1 rounded-lg text-[9px] font-bold">{{ $status }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="viewFacultyReport(this)" 
                                        data-id="{{ $faculty->faculty_code }}"
                                        data-name="{{ $faculty->first_name }} {{ $faculty->last_name }}"
                                        data-dept="{{ $faculty->department->code ?? 'N/A' }}"
                                        data-rating="{{ number_format($rating, 2) }}"
                                        data-responses="{{ $faculty->evaluations->count() }}"
                                        data-status="{{ $status }}"
                                        class="bg-[#800000] hover:bg-[#660000] text-white px-4 py-2 rounded-lg text-[9px] font-bold uppercase shadow-sm transition active:scale-95">
                                    <i class="fa-solid fa-eye mr-1"></i> View
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-400 italic">No faculty data found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

{{-- Modal (Rank Removed) --}}
<div id="facultyReportModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000]">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Performance Card</h3>
            <p id="facultyName" class="text-gray-500 text-sm font-bold"></p>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-2xl text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Overall Rating</p>
                <p id="reportOverall" class="text-3xl font-black text-[#800000] mt-1"></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-2xl text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Total Responses</p>
                <p id="reportResponses" class="text-3xl font-black text-gray-800 mt-1"></p>
            </div>
        </div>

        <div class="text-center mb-8">
            <span id="reportStatusBadge" class="inline-block px-4 py-2 rounded-full text-xs font-bold uppercase bg-gray-100 text-gray-700"></span>
        </div>
        
        <div class="flex space-x-3">
            <button onclick="downloadIndividualPDF()" class="flex-1 py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98] text-sm">
                <i class="fa-solid fa-file-pdf mr-2"></i> Download PDF
            </button>
            <button onclick="hideFacultyReportModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98] text-sm">Close</button>
        </div>
    </div>
</div>

{{-- Logout Modal --}}
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
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98]">Confirm Logout</button>
            </form>
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
    let currentData = {};

    function showLogoutModal() { document.getElementById('logoutModal').classList.remove('hidden'); document.getElementById('logoutModal').classList.add('flex'); }
    function hideLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('flex'); }

    function viewFacultyReport(btn) {
        currentData = {
            id: btn.dataset.id,
            name: btn.dataset.name,
            dept: btn.dataset.dept,
            rating: btn.dataset.rating,
            responses: btn.dataset.responses,
            status: btn.dataset.status
        };

        document.getElementById('facultyName').innerText = currentData.name;
        document.getElementById('reportOverall').innerText = currentData.rating;
        document.getElementById('reportResponses').innerText = currentData.responses;
        document.getElementById('reportStatusBadge').innerText = currentData.status;

        const modal = document.getElementById('facultyReportModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }

    function hideFacultyReportModal() {
        document.getElementById('facultyReportModal').classList.add('hidden');
        document.getElementById('facultyReportModal').classList.remove('flex');
    }

    function generateFullReport() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const date = new Date().toLocaleDateString();

        doc.setFontSize(18);
        doc.setTextColor(128, 0, 0);
        doc.text("Faculty Performance Summary Report", 14, 20);
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text("Generated: " + date, 14, 26);

        // Get table data from the DOM
        const rows = [];
        document.querySelectorAll('tbody tr').forEach(tr => {
            const cols = tr.querySelectorAll('td');
            if(cols.length > 1) { // Skip empty message
                rows.push([
                    cols[0].innerText.split('\n')[0], // Name only
                    cols[1].innerText.trim(), // Dept
                    cols[2].innerText.trim(), // Rating
                    cols[3].innerText.trim(), // Responses
                    cols[4].innerText.trim()  // Status
                ]);
            }
        });

        doc.autoTable({
            startY: 35,
            head: [['Faculty Name', 'Dept', 'Rating', 'Responses', 'Status']],
            body: rows,
            theme: 'grid',
            headStyles: { fillColor: [128, 0, 0] }
        });

        doc.save('Full_Faculty_Report.pdf');
    }

    function downloadIndividualPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        doc.setFontSize(22);
        doc.setTextColor(128, 0, 0);
        doc.text("Individual Performance Report", 105, 20, { align: 'center' });
        
        doc.setDrawColor(200);
        doc.line(20, 30, 190, 30);

        doc.setFontSize(12);
        doc.setTextColor(0);
        
        let y = 50;
        doc.text(`Faculty Name: ${currentData.name}`, 20, y); y+=10;
        doc.text(`Department: ${currentData.dept}`, 20, y); y+=10;
        doc.text(`Faculty ID: ${currentData.id}`, 20, y); y+=15;
        
        doc.setFontSize(16);
        doc.text(`Overall Rating: ${currentData.rating} / 5.00`, 20, y); y+=10;
        doc.setFontSize(12);
        doc.text(`Performance Status: ${currentData.status}`, 20, y); y+=10;
        doc.text(`Total Student Responses: ${currentData.responses}`, 20, y);

        doc.save(`Report_${currentData.id}.pdf`);
    }
</script>
@endsection