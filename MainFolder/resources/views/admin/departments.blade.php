@extends('layouts.app')

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
        
        <div id="selectionView" class="transition-all duration-300">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-800 uppercase tracking-tight">Institutional Departments</h2>
                <p class="text-gray-400 text-sm italic mt-2">Select a department from the dropdown below to manage academic sections.</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-xl border border-gray-100 max-w-2xl mx-auto">
                <div class="space-y-6">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('images/logo.png') }}" class="h-24 w-auto opacity-90">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Choose College / Department</label>
                        <div class="relative">
                            <select id="deptSelector" onchange="handleDeptSelection(this)" class="w-full p-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Select an Institution --</option>
                                @forelse($institutions ?? [] as $inst)
                                    <option value="{{ $inst->code }}">{{ $inst->name }}</option>
                                @empty
                                    <option value="" disabled>No departments loaded</option>
                                @endforelse
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="drillDownView" class="hidden transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 id="viewTitle" class="text-3xl font-black text-[#800000] uppercase tracking-tight leading-none">Manage Program</h3>
                    <p id="viewSub" class="text-sm text-gray-400 font-bold uppercase tracking-widest mt-1">Department</p>
                </div>
                <button onclick="closeDrillDown()" class="bg-white border border-gray-200 text-gray-500 hover:text-[#800000] px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all hover:shadow-md">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Switch Dept
                </button>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border-t-8 border-[#800000] min-h-[500px] overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-8 py-4">
                    <div class="flex flex-wrap items-center space-x-2 sm:space-x-4 text-xs sm:text-sm font-bold uppercase tracking-wide text-gray-400 select-none">
                        <button onclick="switchTab('subjects')" id="tab-subjects" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Subjects</button>
                        <span class="text-gray-300 font-light text-lg mx-2">|</span>
                        <button onclick="switchTab('classes')" id="tab-classes" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Classes</button>
                        <span class="text-gray-300 font-light text-lg mx-2">|</span>
                        <button onclick="switchTab('faculty')" id="tab-faculty" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Faculty</button>
                        <span class="text-gray-300 font-light text-lg mx-2">|</span>
                        <button onclick="switchTab('students')" id="tab-students" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Students</button>
                    </div>
                </div>

                <div id="drillDownContent" class="p-8"></div>
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

<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-right-from-bracket text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Ready to Leave?</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Are you sure you want to log out of the <strong>Admin Portal</strong>?</p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="executeLogout()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98]">Yes, Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Cancel</button>
        </div>
    </div>
</div>

<script>
    // --- DOM Elements ---
    const selectionView = document.getElementById('selectionView');
    const drillDownView = document.getElementById('drillDownView');
    const content = document.getElementById('drillDownContent');
    const deptSelector = document.getElementById('deptSelector');

    // --- DYNAMIC DATA INJECTION ---
    let subjects = @json($subjects ?? []);
    let sections = @json($sections ?? []);
    let faculty = @json($faculty ?? []);
    let students = @json($students ?? []);

    // --- NAVIGATION LOGIC ---
    function handleDeptSelection(select) {
        if (select.value) {
            selectionView.classList.add('hidden');
            drillDownView.classList.remove('hidden');
            document.getElementById('viewSub').innerText = select.options[select.selectedIndex].text;
            switchTab('subjects'); 
        }
    }
    
    function closeDrillDown() { 
        drillDownView.classList.add('hidden');
        selectionView.classList.remove('hidden');
        deptSelector.value = ""; 
    }

    function switchTab(tabName) {
        ['subjects', 'faculty', 'classes', 'students'].forEach(t => {
            const el = document.getElementById(`tab-${t}`);
            if(el) {
                el.classList.remove('text-[#800000]', 'scale-105');
                el.classList.add('text-gray-400');
            }
        });
        const activeTab = document.getElementById(`tab-${tabName}`);
        if(activeTab) {
            activeTab.classList.remove('text-gray-400');
            activeTab.classList.add('text-[#800000]', 'scale-105');
        }

        if(tabName === 'subjects') renderSubjects();
        else if(tabName === 'faculty') renderFaculty();
        else if(tabName === 'classes') renderClasses(); 
        else if(tabName === 'students') renderStudents();
    }

    // --- 1. SUBJECTS ---
    function renderSubjects() {
        const rows = subjects.map((sub, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-[#800000]">${sub.code}</td>
                <td class="px-6 py-4 text-gray-700 font-bold text-xs uppercase">${sub.name}</td>
                <td class="px-6 py-4 text-center text-xs text-gray-400 italic">Managed in Classes</td>
                <td class="px-6 py-4 text-right"><button class="text-gray-300 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
        `).join('');
        
        content.innerHTML = `
            <div class="animate-fade-in-up">
                <h4 class="text-lg font-bold text-gray-800 mb-6">Subject Repository</h4>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]">
                            <tr><th class="px-6 py-3">Code</th><th class="px-6 py-3">Description</th><th class="px-6 py-3 text-center">Info</th><th class="px-6 py-3 text-right">Action</th></tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
    }

    // --- 2. CLASSES ---
    function renderClasses() {
        const rows = sections.map((sec, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-black text-gray-800">${sec.name}</td>
                <td class="px-6 py-4"><div class="flex flex-wrap gap-2">${sec.subjects.map(s => `<span class="bg-blue-50 text-blue-700 border border-blue-100 px-2 py-1 rounded text-[10px] font-bold">${s}</span>`).join('')}</div></td>
                <td class="px-6 py-4 text-right">
                    <button class="text-[#800000] font-bold text-xs underline mr-2">Manage Subjects</button>
                </td>
            </tr>
        `).join('');
        
        content.innerHTML = `
            <div class="animate-fade-in-up">
                <h4 class="text-lg font-bold text-gray-800 mb-6">Manage Classes</h4>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]">
                            <tr><th class="px-6 py-3">Section</th><th class="px-6 py-3">Subjects Assigned</th><th class="px-6 py-3 text-right">Action</th></tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
    }

    // --- 3. FACULTY ---
    function renderFaculty() {
        const rows = faculty.map((fac, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="w-10 h-10 rounded-full bg-gray-200 border-2 border-white shadow-sm flex items-center justify-center text-gray-500 font-bold text-xs overflow-hidden">
                        ${fac.name.charAt(0)}
                    </div>
                </td>
                <td class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">${fac.id}</td>
                <td class="px-6 py-4 font-bold text-gray-800 text-sm">${fac.name}</td>
                <td class="px-6 py-4">
                     <div class="flex flex-wrap gap-2">${fac.assignedSections.map(sec => `<span class="bg-red-50 text-[#800000] border border-red-100 px-2 py-1 rounded text-[10px] font-bold">${sec}</span>`).join('')}</div>
                </td>
                <td class="px-6 py-4 text-right">
                    <button class="text-[#800000] font-bold text-xs underline mr-2">Manage</button>
                </td>
            </tr>
        `).join('');

        content.innerHTML = `
            <div class="animate-fade-in-up">
                 <h4 class="text-lg font-bold text-gray-800 mb-6">Faculty Roster</h4>
                 <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Image</th><th class="px-6 py-3">ID</th><th class="px-6 py-3">Name</th><th class="px-6 py-3">Handled Sections</th><th class="px-6 py-3 text-right">Action</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
    }

    // --- 4. STUDENTS ---
    function renderStudents() {
        const rows = students.map((std, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">${std.id}</td>
                <td class="px-6 py-4 font-bold text-gray-800">${std.name}</td>
                <td class="px-6 py-4"><span class="bg-green-50 text-green-700 px-2 py-1 rounded font-bold text-xs">${std.section}</span></td>
                <td class="px-6 py-4 text-right"><button class="text-gray-300 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
        `).join('');

        content.innerHTML = `
            <div class="animate-fade-in-up">
                 <h4 class="text-lg font-bold text-gray-800 mb-6">Student Registry</h4>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Student #</th><th class="px-6 py-3">Name</th><th class="px-6 py-3">Section</th><th class="px-6 py-3 text-right">Action</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
    }

    // --- UTILS ---
    function showLogoutModal() { document.getElementById('logoutModal').classList.remove('hidden'); document.getElementById('logoutModal').classList.add('flex'); }
    function hideLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('flex'); }
    function executeLogout() { window.location.href = "/"; }
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
</style>
@endsection