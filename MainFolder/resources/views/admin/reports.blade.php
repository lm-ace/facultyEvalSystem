@extends('layouts.app')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <!-- Filter Section -->
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
                            <option value="CCIS">College of Computer and Information Sciences (CCIS)</option>
                            <option value="CAF">College of Accountancy and Finance (CAF)</option>
                            <option value="CADBE">College of Architecture and Design (CADBE)</option>
                            <option value="CAL">College of Arts and Letters (CAL)</option>
                            <option value="CBA">College of Business Administration (CBA)</option>
                            <option value="COC">College of Communication (COC)</option>
                            <option value="COED">College of Education (COED)</option>
                            <option value="CE">College of Engineering (CE)</option>
                            <option value="CHK">College of Human Kinetics (CHK)</option>
                            <option value="CL">College of Law (CL)</option>
                            <option value="CPSPA">College of Political Science and Public Administration (CPSPA)</option>
                            <option value="CSSD">College of Social Sciences and Development (CSSD)</option>
                            <option value="CS">College of Science (CS)</option>
                            <option value="CTHTM">College of Tourism, Hospitality and Transportation Management (CTHTM)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Semester</label>
                        <select id="semesterFilter" class="mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#800000] outline-none text-xs font-bold text-gray-700">
                            <option value="1st Semester 2025-2026">1st Semester 2025-2026</option>
                            <option value="2nd Semester 2024-2025">2nd Semester 2024-2025</option>
                            <option value="1st Semester 2024-2025">1st Semester 2024-2025</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Status</label>
                        <select id="statusFilter" class="mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#800000] outline-none text-xs font-bold text-gray-700">
                            <option value="all">All Status</option>
                            <option value="completed">Completed</option>
                            <option value="in_progress">In Progress</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Faculty Performance Summary -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-8">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-sm uppercase tracking-wider">Faculty Performance Summary</h3>
                <span class="text-[10px] bg-white/20 px-3 py-1 rounded-full font-bold">45 Faculty Members</span>
            </div>
            
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b">
                        <tr>
                            <th class="px-6 py-4">Faculty Member</th>
                            <th class="px-6 py-4">Department</th>
                            <th class="px-6 py-4 text-center">Overall Rating</th>
                            <th class="px-6 py-4 text-center">Responses</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Faculty 1 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">Prof. Ricardo Dalisay, PhD</div>
                                <div class="text-gray-400 text-[9px] font-normal italic">FAC-2021-001</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[9px] font-bold border border-blue-100">CCIS</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center">
                                    <span class="font-black text-[#800000] text-sm mr-2">4.85</span>
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
                                <span class="font-bold text-gray-800">120/125</span>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-green-600 h-1.5 rounded-full" style="width: 96%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-lg text-[9px] font-bold border border-green-100">EXCELLENT</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="viewFacultyReport(this)" 
                                        data-faculty-id="FAC-2021-001"
                                        data-faculty-name="Prof. Ricardo Dalisay, PhD"
                                        data-department="CCIS"
                                        data-rating="4.85"
                                        data-responses="120/125"
                                        data-status="EXCELLENT"
                                        class="bg-[#800000] hover:bg-[#660000] text-white px-4 py-2 rounded-lg text-[9px] font-bold uppercase shadow-sm transition active:scale-95">
                                    <i class="fa-solid fa-eye mr-1"></i> View
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Faculty 2 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">Prof. Maria Santos, MA</div>
                                <div class="text-gray-400 text-[9px] font-normal italic">FAC-2020-045</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-lg text-[9px] font-bold border border-purple-100">CBA</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center">
                                    <span class="font-black text-[#800000] text-sm mr-2">4.20</span>
                                    <div class="flex">
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-regular fa-star text-gray-300 text-[10px]"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-gray-800">95/100</span>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-green-600 h-1.5 rounded-full" style="width: 95%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-lg text-[9px] font-bold border border-blue-100">VERY GOOD</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="viewFacultyReport(this)"
                                        data-faculty-id="FAC-2020-045"
                                        data-faculty-name="Prof. Maria Santos, MA"
                                        data-department="CBA"
                                        data-rating="4.20"
                                        data-responses="95/100"
                                        data-status="VERY GOOD"
                                        class="bg-[#800000] hover:bg-[#660000] text-white px-4 py-2 rounded-lg text-[9px] font-bold uppercase shadow-sm transition active:scale-95">
                                    <i class="fa-solid fa-eye mr-1"></i> View
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Faculty 3 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">Prof. Juan Dela Cruz</div>
                                <div class="text-gray-400 text-[9px] font-normal italic">FAC-2022-023</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-50 text-green-600 px-3 py-1 rounded-lg text-[9px] font-bold border border-green-100">CE</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center">
                                    <span class="font-black text-gray-600 text-sm mr-2">3.45</span>
                                    <div class="flex">
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>
                                        <i class="fa-solid fa-star-half-stroke text-yellow-500 text-[10px]"></i>
                                        <i class="fa-regular fa-star text-gray-300 text-[10px]"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-gray-800">78/85</span>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-yellow-500 h-1.5 rounded-full" style="width: 92%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-lg text-[9px] font-bold border border-yellow-100">GOOD</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="viewFacultyReport(this)"
                                        data-faculty-id="FAC-2022-023"
                                        data-faculty-name="Prof. Juan Dela Cruz"
                                        data-department="CE"
                                        data-rating="3.45"
                                        data-responses="78/85"
                                        data-status="GOOD"
                                        class="bg-[#800000] hover:bg-[#660000] text-white px-4 py-2 rounded-lg text-[9px] font-bold uppercase shadow-sm transition active:scale-95">
                                    <i class="fa-solid fa-eye mr-1"></i> View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Graph Reports / Visual Presentations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Department Performance Chart -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-bold text-gray-800 text-sm flex items-center uppercase tracking-widest">
                        <i class="fa-solid fa-chart-column mr-3 text-[#800000]"></i> Department Performance
                    </h4>
                    <select id="chartFilter" onchange="updateCharts()" class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700">
                        <option>By Overall Rating</option>
                        <option>By Response Rate</option>
                        <option>By Semester</option>
                    </select>
                </div>
                <div class="h-64">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-bold text-gray-800 text-sm flex items-center uppercase tracking-widest">
                        <i class="fa-solid fa-chart-pie mr-3 text-[#800000]"></i> Rating Distribution
                    </h4>
                    <span class="text-[10px] font-bold text-gray-400">Current Semester</span>
                </div>
                <div class="h-64">
                    <canvas id="ratingChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Response Rate Over Time -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h4 class="font-bold text-gray-800 text-sm flex items-center uppercase tracking-widest">
                    <i class="fa-solid fa-chart-line mr-3 text-[#800000]"></i> Response Rate Over Time
                </h4>
                <div class="flex space-x-2">
                    <button onclick="downloadChart('responseChart')" class="text-[#800000] hover:text-[#660000] transition">
                        <i class="fa-solid fa-download text-sm"></i>
                    </button>
                    <button onclick="printChart('responseChart')" class="text-[#800000] hover:text-[#660000] transition">
                        <i class="fa-solid fa-print text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="h-72">
                <canvas id="responseChart"></canvas>
            </div>
        </div>
    </div>
</main>

<!-- Faculty Report Modal -->
<div id="facultyReportModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000] max-h-[90vh] overflow-y-auto">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Faculty Performance Report</h3>
            <p id="facultyName" class="text-gray-500 text-sm font-bold"></p>
        </div>
        
        <div class="space-y-6">
            <!-- Performance Summary -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Overall Rating</p>
                    <p id="reportOverall" class="text-2xl font-black text-[#800000] mt-2">4.85</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Responses</p>
                    <p id="reportResponses" class="text-2xl font-black text-gray-800 mt-2">120/125</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Rank</p>
                    <p id="reportRank" class="text-2xl font-black text-green-600 mt-2">#2</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Status</p>
                    <p id="reportStatus" class="text-xl font-black text-green-600 mt-2">EXCELLENT</p>
                </div>
            </div>
            
            <!-- Detailed Breakdown -->
            <div>
                <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                    <i class="fa-solid fa-chart-simple mr-2"></i> Criteria Breakdown
                </h4>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span>Teaching Competence</span>
                            <span>4.9 / 5.0</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 98%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span>Communication Skills</span>
                            <span>4.7 / 5.0</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 94%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span>Classroom Management</span>
                            <span>4.8 / 5.0</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: 96%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Comments Summary -->
            <div>
                <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                    <i class="fa-solid fa-comment-dots mr-2"></i> Student Feedback
                </h4>
                <div class="bg-gray-50 p-4 rounded-2xl">
                    <p class="text-xs text-gray-600 italic">"Excellent teaching methodology and very approachable. Always available for consultations."</p>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-3 mt-8">
            <button onclick="downloadFacultyReport()" class="flex-1 py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98] text-sm">
                <i class="fa-solid fa-file-pdf mr-2"></i> Download PDF
            </button>
            <button onclick="hideFacultyReportModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98] text-sm">Close</button>
        </div>
    </div>
</div>

<footer class="bg-[#660000] text-white py-12">
    <div class="container mx-auto px-10 text-center text-xs">
        <p class="opacity-50">Polytechnic University of the Philippines - Main Campus</p>
        <p class="mt-4 opacity-40 tracking-widest uppercase font-bold">Copyright © {{ date('Y') }} | All Evaluation Data is Protected by Privacy Laws</p>
    </div>
</footer>

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
    // Department mapping
    const departmentMapping = {
        'CCIS': 'College of Computer and Information Sciences',
        'CAF': 'College of Accountancy and Finance',
        'CADBE': 'College of Architecture and Design',
        'CAL': 'College of Arts and Letters',
        'CBA': 'College of Business Administration',
        'COC': 'College of Communication',
        'COED': 'College of Education',
        'CE': 'College of Engineering',
        'CHK': 'College of Human Kinetics',
        'CL': 'College of Law',
        'CPSPA': 'College of Political Science and Public Administration',
        'CSSD': 'College of Social Sciences and Development',
        'CS': 'College of Science',
        'CTHTM': 'College of Tourism, Hospitality and Transportation Management'
    };

    // Store current faculty data for PDF generation
    let currentFacultyData = null;

    // Initialize Charts
    let departmentChart, ratingChart, responseChart;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Department Performance Chart
        const deptCtx = document.getElementById('departmentChart').getContext('2d');
        departmentChart = new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: ['CCIS', 'CBA', 'CE', 'CAL', 'CS', 'CTHTM'],
                datasets: [{
                    label: 'Average Rating',
                    data: [4.85, 4.20, 3.45, 4.60, 4.30, 4.75],
                    backgroundColor: [
                        'rgba(128, 0, 0, 0.7)',
                        'rgba(128, 0, 0, 0.6)',
                        'rgba(128, 0, 0, 0.5)',
                        'rgba(128, 0, 0, 0.6)',
                        'rgba(128, 0, 0, 0.5)',
                        'rgba(128, 0, 0, 0.65)'
                    ],
                    borderColor: [
                        'rgba(128, 0, 0, 1)',
                        'rgba(128, 0, 0, 1)',
                        'rgba(128, 0, 0, 1)',
                        'rgba(128, 0, 0, 1)',
                        'rgba(128, 0, 0, 1)',
                        'rgba(128, 0, 0, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        // Rating Distribution Chart
        const ratingCtx = document.getElementById('ratingChart').getContext('2d');
        ratingChart = new Chart(ratingCtx, {
            type: 'doughnut',
            data: {
                labels: ['Excellent (4.0-5.0)', 'Very Good (3.5-3.9)', 'Good (3.0-3.4)', 'Needs Improvement (<3.0)'],
                datasets: [{
                    data: [25, 12, 6, 2],
                    backgroundColor: [
                        'rgba(0, 128, 0, 0.7)',
                        'rgba(0, 0, 255, 0.7)',
                        'rgba(255, 165, 0, 0.7)',
                        'rgba(255, 0, 0, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 9
                            }
                        }
                    }
                }
            }
        });

        // Response Rate Chart
        const responseCtx = document.getElementById('responseChart').getContext('2d');
        responseChart = new Chart(responseCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7'],
                datasets: [{
                    label: 'Response Rate %',
                    data: [15, 35, 50, 65, 78, 85, 92],
                    borderColor: 'rgb(128, 0, 0)',
                    backgroundColor: 'rgba(128, 0, 0, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    });

    // Modal Functions
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
    function executeLogout() { window.location.href = "/"; }

    // Report Functions
    function filterReports() {
        const dept = document.getElementById('departmentFilter').value;
        const semester = document.getElementById('semesterFilter').value;
        const status = document.getElementById('statusFilter').value;
        
        // This would filter table data in real application
        console.log('Filtering by:', { dept, semester, status });
    }

    function viewFacultyReport(button) {
        // Get faculty data from button data attributes
        currentFacultyData = {
            id: button.getAttribute('data-faculty-id'),
            name: button.getAttribute('data-faculty-name'),
            departmentCode: button.getAttribute('data-department'),
            department: departmentMapping[button.getAttribute('data-department')] || button.getAttribute('data-department'),
            rating: button.getAttribute('data-rating'),
            responses: button.getAttribute('data-responses'),
            status: button.getAttribute('data-status')
        };
        
        // Update modal with faculty data
        document.getElementById('facultyName').textContent = currentFacultyData.name + ' | ' + currentFacultyData.id;
        document.getElementById('reportOverall').textContent = currentFacultyData.rating;
        document.getElementById('reportResponses').textContent = currentFacultyData.responses;
        document.getElementById('reportRank').textContent = '#2'; // This would come from server
        document.getElementById('reportStatus').textContent = currentFacultyData.status;
        
        const modal = document.getElementById('facultyReportModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => { modal.querySelector('div').classList.remove('scale-95'); modal.querySelector('div').classList.add('scale-100'); }, 10);
    }

    function hideFacultyReportModal() {
        const modal = document.getElementById('facultyReportModal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    function downloadFacultyReport() {
        if (!currentFacultyData) {
            alert('No faculty data available');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const logoImg = document.getElementById('pdfLogo');
        const today = new Date();
        const formattedDate = today.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Header with logo
        if (logoImg) {
            doc.addImage(logoImg, 'PNG', 20, 15, 15, 15);
        }
        
        // Report title
        doc.setFontSize(20);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(128, 0, 0);
        doc.text("EduRate Faculty Evaluation Report", 105, 25, { align: 'center' });
        
        doc.setFontSize(11);
        doc.setTextColor(100, 100, 100);
        doc.text(`Generated on: ${formattedDate}`, 105, 32, { align: 'center' });
        
        // Separator line
        doc.setDrawColor(128, 0, 0);
        doc.setLineWidth(0.5);
        doc.line(20, 40, 190, 40);
        
        // Faculty Information
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        
        // Faculty Name
        doc.setFont("helvetica", "bold");
        doc.text("Faculty Name:", 20, 55);
        doc.setFont("helvetica", "normal");
        doc.text(currentFacultyData.name, 70, 55);
        
        // Department
        doc.setFont("helvetica", "bold");
        doc.text("Department:", 20, 65);
        doc.setFont("helvetica", "normal");
        doc.text(currentFacultyData.department, 70, 65);
        
        // Review Period (from filter)
        const reviewPeriod = document.getElementById('semesterFilter').value;
        doc.setFont("helvetica", "bold");
        doc.text("Review Period:", 20, 75);
        doc.setFont("helvetica", "normal");
        doc.text(reviewPeriod, 70, 75);
        
        // Space
        doc.line(20, 85, 190, 85);
        
        // Overall Rating
        doc.setFont("helvetica", "bold");
        doc.setFontSize(14);
        doc.text("Overall Rating:", 20, 100);
        doc.setFontSize(18);
        doc.setTextColor(128, 0, 0);
        doc.text(`${currentFacultyData.rating} / 5.0`, 70, 100);
        
        // Rating description
        doc.setFontSize(10);
        doc.setTextColor(0, 128, 0);
        doc.text(currentFacultyData.status + " Performance", 70, 107);
        
        // Separator
        doc.setDrawColor(200, 200, 200);
        doc.setLineWidth(0.3);
        doc.line(20, 115, 190, 115);
        
        // Student Feedback Section
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.setFont("helvetica", "bold");
        doc.text("Student Feedback:", 20, 130);
        
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        
        // Sample feedback (in real app, this would come from database)
        const feedbackText = [
            "Demonstrates exceptional teaching competence and subject matter expertise.",
            "Lectures are well-structured, engaging, and effectively communicated.",
            "Maintains excellent classroom management and learning environment.",
            "Approachable and provides extra assistance to students when needed.",
            "Assessment methods are fair and aligned with learning outcomes."
        ];
        
        let currentY = 140;
        feedbackText.forEach(line => {
            doc.text(line, 20, currentY, { maxWidth: 170 });
            currentY += 7;
        });
        
        // Footer
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(128, 128, 128);
            doc.text(`Page ${i} of ${pageCount}`, 105, 287, { align: 'center' });
            doc.text("Polytechnic University of the Philippines - Confidential", 105, 292, { align: 'center' });
        }
        
        doc.save(`Faculty_Report_${currentFacultyData.id}_${formattedDate.replace(/[/]/g, '-')}.pdf`);
    }

    function generateFullReport() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const logoImg = document.getElementById('pdfLogo');
        const today = new Date();
        const formattedDate = today.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Header with logo
        if (logoImg) {
            doc.addImage(logoImg, 'PNG', 20, 15, 15, 15);
        }
        
        // Report title
        doc.setFontSize(20);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(128, 0, 0);
        doc.text("EduRate Faculty Evaluation Report", 105, 25, { align: 'center' });
        
        doc.setFontSize(11);
        doc.setTextColor(100, 100, 100);
        doc.text(`Generated on: ${formattedDate}`, 105, 32, { align: 'center' });
        
        // Separator line
        doc.setDrawColor(128, 0, 0);
        doc.setLineWidth(0.5);
        doc.line(20, 40, 190, 40);
        
        // Report summary
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.text("Comprehensive Faculty Evaluation Summary", 20, 55);
        
        doc.setFontSize(10);
        doc.text(`Academic Year: 2025-2026 | 1st Semester`, 20, 65);
        doc.text(`Total Faculty Evaluated: 45`, 20, 72);
        doc.text(`Overall Average Rating: 4.35 / 5.0`, 20, 79);
        doc.text(`Total Student Responses: 1,250`, 20, 86);
        
        // Department Summary Table
        doc.autoTable({
            startY: 95,
            head: [['Department', 'Faculty Count', 'Avg Rating', 'Response Rate']],
            body: [
                [departmentMapping['CCIS'], '12', '4.62', '96%'],
                [departmentMapping['CBA'], '8', '4.35', '94%'],
                [departmentMapping['CE'], '10', '4.18', '92%'],
                [departmentMapping['CAL'], '5', '4.45', '95%'],
                [departmentMapping['CS'], '6', '4.28', '93%'],
                [departmentMapping['CTHTM'], '4', '4.52', '97%']
            ],
            margin: { left: 20, right: 20 },
            theme: 'grid',
            headStyles: { fillColor: [128, 0, 0], textColor: 255 },
            columnStyles: {
                0: { cellWidth: 80 },
                1: { cellWidth: 30, halign: 'center' },
                2: { cellWidth: 30, halign: 'center' },
                3: { cellWidth: 30, halign: 'center' }
            }
        });
        
        // Top Performing Faculty
        const finalY = doc.lastAutoTable.finalY + 15;
        doc.setFontSize(12);
        doc.text("Top Performing Faculty", 20, finalY);
        
        doc.autoTable({
            startY: finalY + 5,
            head: [['Faculty Name', 'Department', 'Rating', 'Status']],
            body: [
                ['Prof. Ricardo Dalisay, PhD', 'CCIS', '4.85', 'EXCELLENT'],
                ['Prof. Maria Santos, MA', 'CBA', '4.20', 'VERY GOOD'],
                ['Prof. Juan Dela Cruz', 'CE', '3.45', 'GOOD'],
                ['Dr. Ana Reyes, PhD', 'CAL', '4.75', 'EXCELLENT'],
                ['Prof. Carlos Lim', 'CS', '4.65', 'EXCELLENT']
            ],
            margin: { left: 20, right: 20 },
            theme: 'grid',
            headStyles: { fillColor: [128, 0, 0], textColor: 255 }
        });
        
        // Footer
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(128, 128, 128);
            doc.text(`Page ${i} of ${pageCount}`, 105, 287, { align: 'center' });
            doc.text("Polytechnic University of the Philippines - Confidential", 105, 292, { align: 'center' });
        }
        
        doc.save(`Comprehensive_Faculty_Report_${formattedDate.replace(/[/]/g, '-')}.pdf`);
    }

    function updateCharts() {
        const filter = document.getElementById('chartFilter').value;
        console.log('Updating charts with filter:', filter);
    }

    function downloadChart(chartId) {
        const canvas = document.getElementById(chartId);
        const image = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = `${chartId}_chart.png`;
        link.href = image;
        link.click();
    }

    function printChart(chartId) {
        const canvas = document.getElementById(chartId);
        const image = canvas.toDataURL('image/png');
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Chart</title>
                    <style>
                        body { display: flex; justify-content: center; align-items: center; height: 100vh; }
                        img { max-width: 100%; max-height: 100%; }
                    </style>
                </head>
                <body>
                    <img src="${image}" alt="${chartId} Chart">
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    }
</script>
@endsection