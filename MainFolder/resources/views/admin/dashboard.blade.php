@extends('layouts.app')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4 md:px-8 py-2 text-white bg-[#800000]/95 backdrop-blur-md shadow-md transition-all">
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-7 md:h-8">
        <div>
            <h1 class="font-bold leading-none text-sm md:text-lg">EduRate</h1>
            <p class="text-[8px] md:text-[10px] tracking-tight uppercase opacity-80">Faculty Evaluation System</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-[10px] md:text-xs font-medium opacity-70 hidden sm:inline tracking-wider uppercase">System Administrator</span>
        <button onclick="showLogoutModal()" class="bg-white/10 px-3 py-1.5 rounded-lg text-xs md:text-sm font-bold hover:bg-white/20 transition flex items-center border border-white/20">
            <i class="fa-solid fa-right-from-bracket md:mr-2"></i> <span class="hidden md:inline">Log Out</span>
        </button>
    </div>
</nav>

<div class="fixed top-[48px] md:top-[52px] left-0 right-0 z-40 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-2 md:px-6">
        
        <div class="grid grid-cols-4 md:flex md:gap-8 py-2">
            
            <a href="{{ route('admin.dashboard') }}" class="group flex flex-col md:flex-row items-center justify-center md:justify-start text-center md:text-left rounded-lg p-2 transition-all {{ Request::is('admin/dashboard') ? 'text-[#800000] bg-red-50' : 'text-gray-400 hover:text-[#800000] hover:bg-gray-50' }}">
                <i class="fa-solid fa-chart-pie text-sm md:text-xs md:mr-2 mb-1 md:mb-0 group-hover:scale-110 transition-transform"></i>
                <span class="text-[9px] md:text-xs font-bold uppercase tracking-tight md:tracking-widest">Dashboard</span>
            </a>
            
            <a href="{{ route('admin.departments') }}" class="group flex flex-col md:flex-row items-center justify-center md:justify-start text-center md:text-left rounded-lg p-2 transition-all {{ Request::is('admin/departments*') ? 'text-[#800000] bg-red-50' : 'text-gray-400 hover:text-[#800000] hover:bg-gray-50' }}">
                <i class="fa-solid fa-sitemap text-sm md:text-xs md:mr-2 mb-1 md:mb-0 group-hover:scale-110 transition-transform"></i>
                <span class="text-[9px] md:text-xs font-bold uppercase tracking-tight md:tracking-widest">Departments</span>
            </a>
            
            <a href="{{ route('admin.criteria') }}" class="group flex flex-col md:flex-row items-center justify-center md:justify-start text-center md:text-left rounded-lg p-2 transition-all {{ Request::is('admin/criteria*') ? 'text-[#800000] bg-red-50' : 'text-gray-400 hover:text-[#800000] hover:bg-gray-50' }}">
                <i class="fa-solid fa-list-check text-sm md:text-xs md:mr-2 mb-1 md:mb-0 group-hover:scale-110 transition-transform"></i>
                <span class="text-[9px] md:text-xs font-bold uppercase tracking-tight md:tracking-widest">Criteria</span>
            </a>
            
            <a href="{{ route('admin.reports') }}" class="group flex flex-col md:flex-row items-center justify-center md:justify-start text-center md:text-left rounded-lg p-2 transition-all {{ Request::is('admin/reports*') ? 'text-[#800000] bg-red-50' : 'text-gray-400 hover:text-[#800000] hover:bg-gray-50' }}">
                <i class="fa-solid fa-file-contract text-sm md:text-xs md:mr-2 mb-1 md:mb-0 group-hover:scale-110 transition-transform"></i>
                <span class="text-[9px] md:text-xs font-bold uppercase tracking-tight md:tracking-widest">Reports</span>
            </a>

        </div>
    </div>
</div>

<main class="pt-36 pb-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 md:px-6 max-w-7xl">

        <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-[#800000] mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2 text-center md:text-left">Welcome, {{ Auth::user()->name ?? 'Administrator' }}!</h2>
            <div class="space-y-3 text-gray-600 leading-relaxed text-xs md:text-sm">
                <p>This administrative dashboard provides high-level oversight of the faculty evaluation process. Manage institutional departments, update evaluation criteria, and generate comprehensive system-wide reports.</p>
                <div class="p-2.5 bg-red-50 rounded-lg border border-red-100 flex items-center text-[#800000] font-bold text-[10px] md:text-xs">
                    <i class="fa-solid fa-circle-exclamation mr-2 text-base"></i>
                    <span>Authorized Personnel Only: All actions performed within this module are logged for security auditing.</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-5 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-lg transition-all duration-300 border border-gray-50">
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Total Faculty</p>
                    <h3 class="text-3xl font-black text-[#800000] mt-0.5">{{ number_format($totalFaculty) }}</h3>
                    <p class="text-gray-400 text-[9px] mt-0.5">In {{ $departmentCount }} Depts</p>
                </div>
                <div class="bg-red-50 p-3 rounded-xl text-[#800000] group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-user-tie text-2xl opacity-50"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-lg transition-all duration-300 border border-gray-50">
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Total Students</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-0.5">{{ number_format($totalStudents) }}</h3>
                    <p class="text-gray-400 text-[9px] mt-0.5">Registered</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl text-blue-600 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-graduation-cap text-2xl opacity-50"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-lg transition-all duration-300 border border-gray-50">
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Evaluations</p>
                    <h3 class="text-3xl font-black text-green-600 mt-0.5">{{ number_format($totalEvaluations) }}</h3>
                    <p class="text-gray-400 text-[9px] mt-0.5">Submitted</p>
                </div>
                <div class="bg-green-50 p-3 rounded-xl text-green-600 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-clipboard-check text-2xl opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-1 bg-white p-5 rounded-2xl shadow-md border border-gray-100 h-fit flex flex-col">
                <h4 class="font-bold text-gray-800 text-xs mb-4 flex items-center uppercase tracking-widest">
                    <i class="fa-solid fa-calendar-day mr-2 text-[#800000]"></i> Review Period
                </h4>

                <div class="space-y-4 flex-1">
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Active Semester</label>
                        <div class="w-full mt-1 px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-black text-gray-800 flex items-center justify-between">
                            @if($isEvalOpen && isset($activePeriod))
                                <span>{{ $activePeriod->semester }} | {{ $activePeriod->academic_year }}</span>
                                <i class="fa-solid fa-circle-check text-green-500"></i>
                            @else
                                <span class="text-gray-400 italic">No Active Period</span>
                                <i class="fa-solid fa-circle-xmark text-red-400"></i>
                            @endif
                        </div>
                    </div>

                    <div class="p-3 {{ $isEvalOpen ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100' }} rounded-xl border text-center transition-all duration-300">
                        <p class="text-[8px] font-bold {{ $isEvalOpen ? 'text-green-800' : 'text-red-800' }} uppercase mb-0.5 tracking-widest">System Status</p>
                        <span class="font-black text-[11px] uppercase {{ $isEvalOpen ? 'text-green-600' : 'text-red-600' }}">
                            {{ $isEvalOpen ? 'EVALUATION IS OPEN' : 'EVALUATION IS CLOSED' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-auto">
                        @if($isEvalOpen)
                            <form action="{{ route('admin.review_periods.close') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full py-2.5 bg-[#800000] text-white font-bold rounded-lg hover:bg-[#660000] transition shadow-sm active:scale-95 text-[9px] uppercase tracking-widest">
                                    Close Period
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full py-2.5 bg-gray-100 text-gray-400 font-bold rounded-lg cursor-not-allowed border border-gray-200 text-[9px] uppercase tracking-widest">
                                System Closed
                            </button>
                        @endif

                        <button type="button" onclick="showManagePeriodsModal()" class="w-full py-2.5 bg-white border border-[#800000] text-[#800000] font-bold rounded-lg hover:bg-gray-50 transition shadow-sm active:scale-95 text-[9px] uppercase tracking-widest">
                            Manage Periods
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="bg-[#800000] px-5 py-3 flex justify-between items-center text-white">
                    <h4 class="font-bold text-xs uppercase tracking-wider">Live Evaluation Feed</h4>
                    <span class="bg-green-500 text-white text-[8px] px-2 py-0.5 rounded-full font-bold animate-pulse">MONITORING</span>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-xs min-w-[300px]">
                        <thead class="text-[9px] text-gray-400 uppercase font-black border-b bg-gray-50">
                            <tr>
                                <th class="px-5 py-3">Student Name</th>
                                <th class="px-5 py-3">Submission Details</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($recentEvaluations as $evaluation)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3">
                                    <div class="text-gray-800 font-bold text-xs">
                                        {{ $evaluation->student->last_name ?? 'Unknown' }}, {{ $evaluation->student->first_name ?? 'Student' }}
                                    </div>
                                    <div class="text-gray-400 text-[8px] font-normal italic">
                                        {{ $evaluation->student->student_number ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-gray-600 text-[10px]">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-clock text-gray-300 mr-1.5"></i>
                                        <span>{{ $evaluation->created_at->format('M d, h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-[8px] font-bold border border-blue-100">SUBMITTED</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-5 py-6 text-center text-gray-400">
                                    <i class="fa-solid fa-inbox text-xl mb-1 opacity-50"></i>
                                    <p class="text-[10px]">No recent evaluations.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="managePeriodsModal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl p-6 transform transition-all scale-100 flex flex-col max-h-[90vh]">

        <div class="flex justify-between items-center mb-4 flex-shrink-0">
            <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight">Manage Periods</h3>
            <button onclick="hideManagePeriodsModal()" class="text-gray-400 hover:text-red-500 transition p-1">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4 shrink-0">
            <h4 class="text-[10px] font-bold text-[#800000] uppercase tracking-widest mb-2">Create New Period</h4>
            <form action="{{ route('admin.review_periods.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-2 items-end">
                @csrf
                <div class="md:col-span-1">
                    <label class="text-[9px] font-bold text-gray-400 uppercase">A.Y.</label>
                    <input type="text" name="academic_year" placeholder="e.g. 2025-2026" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold focus:border-[#800000] outline-none" required>
                </div>
                <div class="md:col-span-1">
                    <label class="text-[9px] font-bold text-gray-400 uppercase">Sem</label>
                    <select name="semester" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold focus:border-[#800000] outline-none">
                        <option>1st Semester</option>
                        <option>2nd Semester</option>
                        <option>Summer Term</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="text-[9px] font-bold text-gray-400 uppercase">Start</label>
                    <input type="date" name="start_date" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold focus:border-[#800000] outline-none" required>
                </div>
                <div class="md:col-span-1">
                    <label class="text-[9px] font-bold text-gray-400 uppercase">End</label>
                    <input type="date" name="end_date" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold focus:border-[#800000] outline-none" required>
                </div>
                <div class="md:col-span-1">
                    <button type="submit" class="w-full py-2 bg-[#800000] text-white rounded-lg text-xs font-bold hover:bg-[#660000] transition shadow-sm">
                        <i class="fa-solid fa-plus mr-1"></i> Add
                    </button>
                </div>
            </form>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar">
            <table class="w-full text-left text-xs">
                <thead class="text-[9px] text-gray-400 uppercase font-black border-b sticky top-0 bg-white z-10">
                    <tr>
                        <th class="py-2">Period Name</th>
                        <th class="py-2">Duration</th>
                        <th class="py-2 text-right">Status</th>
                        <th class="py-2 text-right pr-2">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($reviewPeriods as $period)
                    <tr class="hover:bg-gray-50 group">
                        <td class="py-3 font-bold text-gray-700">
                            {{ $period->semester }} <span class="text-[9px] text-gray-400 font-normal ml-1">{{ $period->academic_year }}</span>
                        </td>
                        <td class="py-3 text-[10px] text-gray-500">
                            {{ \Carbon\Carbon::parse($period->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($period->end_date)->format('M d, Y') }}
                        </td>
                        <td class="py-3 text-right">
                            @if($period->is_open)
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-[9px] font-bold uppercase border border-green-200">Active</span>
                            @else
                            <form action="{{ route('admin.review_periods.activate', $period->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-white border border-gray-200 text-gray-500 hover:text-[#800000] hover:border-[#800000] px-2 py-1 rounded text-[9px] font-bold uppercase transition">
                                    Set Active
                                </button>
                            </form>
                            @endif
                        </td>
                        <td class="py-3 text-right pr-2">
                            @if(!$period->is_open)
                            <form action="{{ route('admin.review_periods.destroy', $period->id) }}" method="POST" onsubmit="return confirm('Delete this period?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-300 hover:text-red-500 transition p-1">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="logoutModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xs p-6 text-center transform transition-all">
        <div class="bg-[#800000]/10 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-right-from-bracket text-[#800000] text-xl"></i>
        </div>
        <h3 class="text-lg font-black text-gray-800 mb-1">Log Out?</h3>
        <p class="text-gray-500 text-xs mb-5">Are you sure you want to end your session?</p>
        <form action="{{ route('logout') }}" method="POST" class="flex flex-col space-y-2">
            @csrf
            <button type="submit" class="w-full py-2.5 bg-[#800000] text-white font-bold rounded-lg shadow-sm hover:bg-[#660000] transition text-xs uppercase tracking-wide">Yes, Log Out</button>
            <button type="button" onclick="hideLogoutModal()" class="w-full py-2.5 border border-gray-200 text-gray-500 font-bold rounded-lg hover:bg-gray-50 transition text-xs uppercase tracking-wide">Cancel</button>
        </form>
    </div>
</div>

<footer class="bg-[#660000] text-white py-8">
    <div class="container mx-auto px-6 text-center text-[10px]">
        <p class="opacity-50">Polytechnic University of the Philippines - Main Campus</p>
        <p class="mt-2 opacity-40 tracking-widest uppercase font-bold">Copyright © {{ date('Y') }} | EduRate</p>
    </div>
</footer>

<script>
    function showLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function hideLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function showManagePeriodsModal() {
        const modal = document.getElementById('managePeriodsModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function hideManagePeriodsModal() {
        const modal = document.getElementById('managePeriodsModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection