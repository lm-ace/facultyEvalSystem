@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        <a href="{{ route('admin.dashboard') }}" class="flex items-center {{ Request::is('admin/dashboard') ? 'text-[#800000] border-b-2 border-[#800000]' : 'text-gray-400 hover:text-[#800000]' }} pb-1 transition-all">
            <i class="fa-solid fa-chart-pie mr-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.departments') }}" class="flex items-center {{ Request::is('admin/departments*') ? 'text-[#800000] border-b-2 border-[#800000]' : 'text-gray-400 hover:text-[#800000]' }} pb-1 transition-all">
            <i class="fa-solid fa-sitemap mr-2"></i> Departments
        </a>
        <a href="{{ route('admin.criteria') }}" class="flex items-center {{ Request::is('admin/criteria*') ? 'text-[#800000] border-b-2 border-[#800000]' : 'text-gray-400 hover:text-[#800000]' }} pb-1 transition-all">
            <i class="fa-solid fa-list-check mr-2"></i> Criteria
        </a>
        <a href="{{ route('admin.reports') }}" class="flex items-center {{ Request::is('admin/reports*') ? 'text-[#800000] border-b-2 border-[#800000]' : 'text-gray-400 hover:text-[#800000]' }} pb-1 transition-all">
            <i class="fa-solid fa-file-contract mr-2"></i> Reports
        </a>
    </div>
</div>

<main class="pt-36 pb-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 max-w-7xl">
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border-l-8 border-[#800000] mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 text-center md:text-left">Welcome, {{ Auth::user()->name ?? 'Administrator' }}!</h2>
            <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base">
                <p>This administrative dashboard provides high-level oversight of the faculty evaluation process. Manage institutional departments, update evaluation criteria, and generate comprehensive system-wide reports.</p>
                <div class="p-3 bg-red-50 rounded-xl border border-red-100 flex items-center text-[#800000] font-bold text-xs">
                    <i class="fa-solid fa-circle-exclamation mr-3 text-lg"></i>
                    <span>Authorized Personnel Only: All actions performed within this module are logged for security auditing.</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-xl transition-all duration-300">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Faculty</p>
                    <h3 class="text-4xl font-black text-[#800000] mt-1">{{ number_format($totalFaculty) }}</h3>
                    <p class="text-gray-400 text-[10px] mt-1">Across {{ $departmentCount }} Departments</p>
                </div>
                <div class="bg-red-50 p-5 rounded-2xl text-[#800000] group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-user-tie text-4xl opacity-40"></i>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-xl transition-all duration-300">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Students</p>
                    <h3 class="text-4xl font-black text-gray-800 mt-1">{{ number_format($totalStudents) }}</h3>
                    <p class="text-gray-400 text-[10px] mt-1">Registered Users</p>
                </div>
                <div class="bg-blue-50 p-5 rounded-2xl text-blue-600 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-graduation-cap text-4xl opacity-40"></i>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-xl transition-all duration-300">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Evaluations</p>
                    <h3 class="text-4xl font-black text-green-600 mt-1">{{ number_format($totalEvaluations) }}</h3>
                    <p class="text-gray-400 text-[10px] mt-1">Current Period</p>
                </div>
                <div class="bg-green-50 p-5 rounded-2xl text-green-600 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-clipboard-check text-4xl opacity-40"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-md border border-gray-100 h-fit">
                <h4 class="font-bold text-gray-800 text-sm mb-6 flex items-center uppercase tracking-widest">
                    <i class="fa-solid fa-calendar-day mr-3 text-[#800000]"></i> Review Period
                </h4>
                <div class="space-y-5">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Active Semester</label>
                        <select class="w-full mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#800000] outline-none text-xs font-bold text-gray-700">
                            @foreach($reviewPeriods as $period)
                                <option value="{{ $period->id }}" {{ $period->is_active ? 'selected' : '' }}>
                                    {{ $period->term ?? 'Term' }} | {{ $period->year ?? 'Year' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="statusIndicator" class="p-4 {{ $isEvalOpen ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100' }} rounded-2xl border text-center transition-all duration-300">
                        <p class="text-[9px] font-bold {{ $isEvalOpen ? 'text-green-800' : 'text-red-800' }} uppercase mb-1 tracking-widest" id="statusLabel">System Status</p>
                        <span id="statusText" class="{{ $isEvalOpen ? 'text-green-600' : 'text-red-600' }} font-bold text-[10px] uppercase">
                            {{ $isEvalOpen ? 'Evaluation is OPEN' : 'Evaluation is CLOSED' }}
                        </span>
                    </div>

                    <button type="button" onclick="showSecurityModal()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl hover:bg-[#660000] transition shadow-lg active:scale-95 text-xs uppercase tracking-widest">
                         EVALUATION STATUS
                    </button>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="bg-[#800000] px-6 py-4 flex justify-between items-center text-white">
                    <h4 class="font-bold text-sm uppercase tracking-wider">Live Evaluation Feed</h4>
                    <span class="bg-green-500 text-white text-[9px] px-3 py-1 rounded-full font-bold animate-pulse">MONITORING</span>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-[10px] text-gray-400 uppercase font-black border-b bg-gray-50">
                            <tr>
                                <th class="px-6 py-4">Student Name</th>
                                <th class="px-6 py-4">Submission Details</th>
                                <th class="px-6 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($recentEvaluations as $evaluation)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-gray-800 font-bold">
                                        {{ $evaluation->student->last_name ?? 'Unknown' }}, {{ $evaluation->student->first_name ?? 'Student' }}
                                    </div>
                                    <div class="text-gray-400 text-[8px] font-normal italic">
                                        {{ $evaluation->student->student_number ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-clock text-gray-400 mr-2"></i>
                                        <span>{{ $evaluation->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[9px] font-bold border border-blue-100">SUBMITTED</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                                    <i class="fa-solid fa-inbox text-2xl mb-2 opacity-50"></i>
                                    <p>No evaluations submitted for this period yet.</p>
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

<div id="securityModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8 border-t-8 border-[#800000] transform transition-all scale-95 duration-300">
        <div class="text-center mb-6">
            <div class="bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-[#800000]">
                <i class="fa-solid fa-lock text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Security Check</h3>
            <p class="text-gray-500 text-xs mt-2">Please enter your admin password to toggle the evaluation status.</p>
        </div>

        <form action="{{ route('admin.toggle.status') }}" method="POST" class="space-y-4">
            @csrf
            <div class="relative">
                <input id="adminPassword" name="password" type="password" required
                    class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none text-sm pr-12" 
                    placeholder="Admin Password">
                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#800000]">
                    <i id="eyeIcon" class="fa-solid fa-eye"></i>
                </button>
            </div>
            <button type="submit" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition">Confirm Action</button>
            <button type="button" onclick="hideSecurityModal()" class="w-full py-3 text-gray-400 font-bold hover:bg-gray-50 rounded-xl transition">Cancel</button>
        </form>
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 border-t-8 border-[#800000] transform transition-all scale-95 duration-300">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-shield-halved text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Admin Logout</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Confirm if you want to end your current administrative session.</p>
        </div>
        <div class="flex flex-col space-y-3">
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition">Confirm Logout</button>
            </form>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition">Cancel</button>
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
    // AUTO-RESET LOGIC (Clears password field on back button)
    window.addEventListener('pageshow', function (event) {
        const passwordField = document.getElementById('adminPassword');
        if (passwordField) {
            passwordField.value = ''; // Reset input
            passwordField.type = 'password'; // Reset to hidden
            document.getElementById('eyeIcon').classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    // PASSWORD TOGGLE (EYE OPENER)
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('adminPassword');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // MODAL FUNCTIONS
    function showSecurityModal() {
        const modal = document.getElementById('securityModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }

    function hideSecurityModal() {
        const modal = document.getElementById('securityModal');
        document.getElementById('adminPassword').value = ''; 
        modal.classList.add('hidden'); modal.classList.remove('flex');
    }

    function showLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }

    function hideLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.add('hidden'); modal.classList.remove('flex');
    }
</script>
@endsection