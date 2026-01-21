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
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                @endforeach
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
                        <button onclick="switchTab('subjects')" id="tab-subjects" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Add Subjects</button>
                        <span class="text-gray-300 font-light text-lg mx-2">|</span>
                        <button onclick="switchTab('classes')" id="tab-classes" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Add Classes</button>
                        <span class="text-gray-300 font-light text-lg mx-2">|</span>
                        <button onclick="switchTab('faculty')" id="tab-faculty" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Add Faculties</button>
                        <span class="text-gray-300 font-light text-lg mx-2">|</span>
                        <button onclick="switchTab('students')" id="tab-students" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Add Students</button>
                    </div>
                </div>

                <div id="drillDownContent" class="p-8"></div>
            </div>
        </div>
    </div>
</main>

<script>
    const selectionView = document.getElementById('selectionView');
    const drillDownView = document.getElementById('drillDownView');
    const content = document.getElementById('drillDownContent');
    const deptSelector = document.getElementById('deptSelector');

    // MOCK DATA
    let subjects = [
        { code: 'COMP 20133', name: 'Applications Dev', assignedProf: 'Dr. Rogelio Reyes' },
        { code: 'INTE 30023', name: 'Integrative Prog', assignedProf: 'Dr. Sarah Santos' }
    ];
    let sections = [
        { name: 'BSIT 3-1', subjects: ['COMP 20133', 'INTE 30023'] }, 
        { name: 'BSIT 3-2', subjects: ['COMP 20133'] }
    ];
    let faculty = [
        { id: 'FAC-001', name: 'Dr. Rogelio Reyes', email: 'r.reyes@pup.edu.ph', assignedSections: ['BSIT 3-1', 'BSIT 3-2'] },
        { id: 'FAC-002', name: 'Dr. Sarah Santos', email: 's.santos@pup.edu.ph', assignedSections: ['BSIT 3-1'] }
    ];
    let students = [
        { id: '2023-00123-MN-0', name: 'Dela Cruz, Juan', section: 'BSIT 3-1', email: 'juan@isko.pup.edu.ph' }
    ];

    function generatePassword(fieldId) {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$";
        let pass = "";
        for (let i = 0; i < 10; i++) { pass += chars.charAt(Math.floor(Math.random() * chars.length)); }
        document.getElementById(fieldId).value = pass;
    }

    function handleDeptSelection(select) {
        if (select.value === '1') { 
            notice.classList.add('hidden');
            selectionView.classList.add('hidden');
            drillDownView.classList.remove('hidden');
            switchTab('classes'); 
        } else {
            notice.classList.remove('hidden');
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


    function renderFaculty() {
        const rows = faculty.map((fac, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 font-bold text-xs border border-gray-200">
                        ${fac.name.charAt(0)}
                    </div>
                </td>
                <td class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">${fac.id}</td>
                <td class="px-6 py-4 font-bold text-gray-800 text-sm">${fac.name}</td>
                <td class="px-6 py-4">
                     <div class="flex flex-wrap gap-2">${fac.assignedSections.map(sec => `<span class="bg-red-50 text-[#800000] border border-red-100 px-2 py-1 rounded text-[10px] font-bold">${sec}</span>`).join('')}</div>
                </td>
                <td class="px-6 py-4 text-right">
                    <button onclick="assignSectionsToProf(${i})" class="text-[#800000] font-bold text-xs underline mr-4">Assign Sections</button>
                    <button onclick="deleteFaculty(${i})" class="text-gray-300 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `).join('');

        content.innerHTML = `
            <div class="animate-fade-in-up">
                <h4 class="text-lg font-bold text-gray-800 mb-6 font-black uppercase tracking-tight">3. Manage Professors</h4>
                
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8">
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <label class="w-24 h-24 rounded-full border-2 border-dashed border-gray-300 flex flex-col items-center justify-center cursor-pointer hover:border-[#800000] transition group">
                                <i class="fa-solid fa-camera text-gray-300 group-hover:text-[#800000] text-2xl"></i>
                                <span class="text-[9px] font-bold text-gray-400 uppercase group-hover:text-[#800000]">Upload</span>
                                <input type="file" class="hidden">
                            </label>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Profile Image</span>
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Faculty ID</label>
                                <input id="fac-id" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="FAC-2026-001">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Name</label>
                                <input id="fac-name" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="Dr. Rogelio Reyes">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label>
                                <input id="fac-email" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="email@pup.edu.ph">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password</label>
                                <div class="relative">
                                    <input id="fac-pass" type="text" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="••••••••">
                                    <button onclick="generatePassword('fac-pass')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000] transition">
                                        <i class="fa-solid fa-key"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <button onclick="addFaculty()" class="bg-[#800000] text-white px-8 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:bg-[#660000] transition active:scale-95">Add Faculty</button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px] tracking-widest">
                            <tr><th class="px-6 py-4">Image</th><th class="px-6 py-4">ID</th><th class="px-6 py-4">Name</th><th class="px-6 py-4">Handled Sections</th><th class="px-6 py-4 text-right">Action</th></tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
    }

    function renderStudents() {
        const rows = students.map((std, i) => {
            const sectionData = sections.find(s => s.name === std.section);
            const subCount = sectionData ? sectionData.subjects.length : 0;
            const profs = faculty.filter(f => f.assignedSections.includes(std.section)).map(f => f.name).join(', ');

            return `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">${std.id}</td>
                <td class="px-6 py-4 font-bold text-gray-800 text-sm">${std.name}</td>
                <td class="px-6 py-4"><span class="bg-green-50 text-green-700 px-2 py-1 rounded font-bold text-[10px] uppercase">${std.section}</span></td>
                <td class="px-6 py-4 text-xs text-gray-600">
                    <div class="font-bold">${subCount} Subjects</div>
                    <div class="text-[10px] text-gray-400 mt-1">Profs: ${profs || 'None'}</div>
                </td>
                <td class="px-6 py-4 text-right"><button onclick="deleteStudent(${i})" class="text-gray-300 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button></td>
            </tr>`;
        }).join('');

        content.innerHTML = `
            <div class="animate-fade-in-up">
                <h4 class="text-lg font-bold text-gray-800 mb-6 font-black uppercase tracking-tight">4. Student Registry</h4>
                
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Student Number</label>
                            <input id="new-std-id" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="2026-00123-MN-0">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Student Name</label>
                            <input id="new-std-name" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="Juan Dela Cruz">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label>
                            <input id="new-std-email" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="juan@isko.pup.edu.ph">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password</label>
                            <div class="relative">
                                <input id="new-std-pass" type="text" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="••••••••">
                                <button onclick="generatePassword('new-std-pass')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000] transition">
                                    <i class="fa-solid fa-key"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1 mb-6">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Assign Section</label>
                        <div class="relative">
                            <select id="new-std-sec" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Select Section --</option>
                                ${sections.map(s => `<option value="${s.name}">${s.name}</option>`).join('')}
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 italic font-medium">* Selecting a section automatically enrolls student in subjects & professors.</p>
                    </div>
                    
                    <div class="flex justify-end">
                        <button onclick="addStudent()" class="bg-[#800000] text-white px-8 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:bg-[#660000] transition active:scale-95">Register Student</button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px] tracking-widest">
                            <tr><th class="px-6 py-4">Student #</th><th class="px-6 py-4">Name</th><th class="px-6 py-4">Section</th><th class="px-6 py-4">Load Info</th><th class="px-6 py-4 text-right">Action</th></tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
    }

    // --- REMAINDER OF FUNCTIONS (renderSubjects, renderClasses, delete, add, etc.) ---
    function renderSubjects() {
        const rows = subjects.map((sub, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-[#800000]">${sub.code}</td>
                <td class="px-6 py-4 text-gray-700 font-bold text-xs uppercase">${sub.name}</td>
                <td class="px-6 py-4 text-center text-xs text-gray-400 italic">Managed in Classes</td>
                <td class="px-6 py-4 text-right"><button class="text-gray-300 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
        `).join('');
        content.innerHTML = `<div class="animate-fade-in-up"><h4 class="text-lg font-bold text-gray-800 mb-6">1. Manage Subjects</h4><div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6 flex gap-4 items-end"><div class="flex-1"><label class="text-[10px] font-bold text-gray-400 uppercase">Subject Code</label><input id="new-sub-code" class="w-full p-2 border rounded-lg" placeholder="COMP 101"></div><div class="flex-1"><label class="text-[10px] font-bold text-gray-400 uppercase">Description</label><input id="new-sub-name" class="w-full p-2 border rounded-lg" placeholder="Programming 1"></div><button onclick="addSubject()" class="bg-[#800000] text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-[#660000]">Add Subject</button></div><div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Code</th><th class="px-6 py-3">Description</th><th class="px-6 py-3">Assigned Prof</th><th class="px-6 py-3 text-right">Action</th></tr></thead><tbody>${rows}</tbody></table></div></div>`;
    }
    
    function renderClasses() {
        const rows = sections.map((sec, i) => `<tr class="bg-white border-b hover:bg-gray-50 transition"><td class="px-6 py-4 font-black text-gray-800">${sec.name}</td><td class="px-6 py-4"><div class="flex flex-wrap gap-2">${sec.subjects.map(s => `<span class="bg-blue-50 text-blue-700 border border-blue-100 px-2 py-1 rounded text-[10px] font-bold">${s}</span>`).join('')}</div></td><td class="px-6 py-4 text-right"><button onclick="manageSectionSubjects(${i})" class="text-[#800000] font-bold text-xs underline mr-2">Manage Subjects</button><button onclick="deleteSection(${i})" class="text-gray-300 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></td></tr>`).join('');
        content.innerHTML = `<div class="animate-fade-in-up"><h4 class="text-lg font-bold text-gray-800 mb-6">2. Manage Classes</h4><div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6 flex gap-4 items-end"><div class="flex-1"><label class="text-[10px] font-bold text-gray-400 uppercase">Section Name</label><select id="new-sec-name" class="w-full p-2 border rounded-lg bg-white"><option value="" disabled selected>-- Select Section --</option><option value="BSIT 3-1">BSIT 3-1</option><option value="BSIT 3-2">BSIT 3-2</option></select></div><button onclick="addSection()" class="bg-[#800000] text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-[#660000]">Create Section</button></div><div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Section</th><th class="px-6 py-3">Subjects Assigned</th><th class="px-6 py-3 text-right">Action</th></tr></thead><tbody>${rows}</tbody></table></div></div>`;
    }

    // Modal and Logout Logic
    function showLogoutModal() { document.getElementById('logoutModal').classList.remove('hidden'); document.getElementById('logoutModal').classList.add('flex'); }
    function hideLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('flex'); }
    function executeLogout() { window.location.href = "/"; }
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection