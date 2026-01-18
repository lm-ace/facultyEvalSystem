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
                                <option value="CCIS">College of Computer and Information Sciences (CCIS)</option>
                                <option value="COA">College of Accountancy (Locked)</option>
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    
                     <div id="noticeArea" class="hidden animate-fade-in">
                        <div class="p-4 bg-red-50 rounded-2xl border border-red-100 flex items-center text-[#800000]">
                            <i class="fa-solid fa-lock mr-3"></i>
                            <span class="text-xs font-bold uppercase">Locked or unavailable.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="drillDownView" class="hidden transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 id="viewTitle" class="text-3xl font-black text-[#800000] uppercase tracking-tight leading-none">Manage Program</h3>
                    <p id="viewSub" class="text-sm text-gray-400 font-bold uppercase tracking-widest mt-1">CCIS Department</p>
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

                <div id="drillDownContent" class="p-8">
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
    const notice = document.getElementById('noticeArea');
    const deptSelector = document.getElementById('deptSelector');

    // --- MOCK DATABASE ---
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

    // --- HELPER: GENERATE PASSWORD ---
    function generatePassword(fieldId) {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$";
        let pass = "";
        for (let i = 0; i < 10; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById(fieldId).value = pass;
    }

    // --- NAVIGATION LOGIC ---
    function handleDeptSelection(select) {
        if (select.value === 'CCIS') {
            notice.classList.add('hidden');
            selectionView.classList.add('hidden');
            drillDownView.classList.remove('hidden');
            switchTab('subjects'); 
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

    // --- 1. SUBJECTS ---
    function renderSubjects() {
        const rows = subjects.map((sub, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-[#800000]">${sub.code}</td>
                <td class="px-6 py-4 text-gray-700 font-bold text-xs uppercase">${sub.name}</td>
                <td class="px-6 py-4">
                     <select onchange="updateSubjectProf(${i}, this.value)" class="bg-gray-50 border border-gray-200 text-xs font-bold rounded-lg p-2 w-full outline-none focus:border-[#800000]">
                        <option value="" disabled ${!sub.assignedProf ? 'selected' : ''}>-- Assign Prof --</option>
                        ${faculty.map(f => `<option value="${f.name}" ${sub.assignedProf === f.name ? 'selected' : ''}>${f.name}</option>`).join('')}
                    </select>
                </td>
                <td class="px-6 py-4 text-right"><button onclick="deleteSubject(${i})" class="text-gray-300 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
        `).join('');
        content.innerHTML = `
            <div class="animate-fade-in-up">
                <h4 class="text-lg font-bold text-gray-800 mb-6">1. Manage Subjects <span class="text-xs font-normal text-gray-400 ml-2">(Create subjects & assign professors)</span></h4>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6 flex gap-4 items-end">
                    <div class="flex-1"><label class="text-[10px] font-bold text-gray-400 uppercase">Subject Code</label><input id="new-sub-code" class="w-full p-2 border rounded-lg" placeholder="COMP 101"></div>
                    <div class="flex-1"><label class="text-[10px] font-bold text-gray-400 uppercase">Description</label><input id="new-sub-name" class="w-full p-2 border rounded-lg" placeholder="Programming 1"></div>
                    <button onclick="addSubject()" class="bg-[#800000] text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-[#660000]">Add Subject</button>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Code</th><th class="px-6 py-3">Description</th><th class="px-6 py-3">Assigned Prof</th><th class="px-6 py-3 text-right">Action</th></tr></thead><tbody>${rows}</tbody></table></div>
            </div>`;
    }
    function addSubject() { subjects.push({ code: document.getElementById('new-sub-code').value, name: document.getElementById('new-sub-name').value, assignedProf: '' }); renderSubjects(); }
    function deleteSubject(i) { subjects.splice(i, 1); renderSubjects(); }
    function updateSubjectProf(i, val) { subjects[i].assignedProf = val; }

    // --- 2. CLASSES ---
    function renderClasses() {
        const rows = sections.map((sec, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-black text-gray-800">${sec.name}</td>
                <td class="px-6 py-4"><div class="flex flex-wrap gap-2">${sec.subjects.map(s => `<span class="bg-blue-50 text-blue-700 border border-blue-100 px-2 py-1 rounded text-[10px] font-bold">${s}</span>`).join('')}</div></td>
                <td class="px-6 py-4 text-right">
                    <button onclick="manageSectionSubjects(${i})" class="text-[#800000] font-bold text-xs underline mr-2">Manage Subjects</button>
                    <button onclick="deleteSection(${i})" class="text-gray-300 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `).join('');
        content.innerHTML = `
            <div class="animate-fade-in-up">
                <h4 class="text-lg font-bold text-gray-800 mb-6">2. Manage Classes <span class="text-xs font-normal text-gray-400 ml-2">(Create sections & assign subjects)</span></h4>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6 flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Section Name</label>
                        <select id="new-sec-name" class="w-full p-2 border rounded-lg bg-white outline-none focus:border-[#800000]">
                            <option value="" disabled selected>-- Select Section --</option>
                            <optgroup label="1st Year">
                                <option value="BSIT 1-1">BSIT 1-1</option>
                                <option value="BSIT 1-2">BSIT 1-2</option>
                                <option value="BSIT 1-1N">BSIT 1-1N (Night)</option>
                            </optgroup>
                            <optgroup label="2nd Year">
                                <option value="BSIT 2-1">BSIT 2-1</option>
                                <option value="BSIT 2-2">BSIT 2-2</option>
                                <option value="BSIT 2-1N">BSIT 2-1N (Night)</option>
                            </optgroup>
                            <optgroup label="3rd Year">
                                <option value="BSIT 3-1">BSIT 3-1</option>
                                <option value="BSIT 3-2">BSIT 3-2</option>
                                <option value="BSIT 3-1N">BSIT 3-1N (Night)</option>
                            </optgroup>
                            <optgroup label="4th Year">
                                <option value="BSIT 4-1">BSIT 4-1</option>
                                <option value="BSIT 4-2">BSIT 4-2</option>
                                <option value="BSIT 4-1N">BSIT 4-1N (Night)</option>
                            </optgroup>
                        </select>
                    </div>
                    <button onclick="addSection()" class="bg-[#800000] text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-[#660000]">Create Section</button>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Section</th><th class="px-6 py-3">Subjects Assigned</th><th class="px-6 py-3 text-right">Action</th></tr></thead><tbody>${rows}</tbody></table></div>
            </div>`;
    }
    function addSection() { 
        const name = document.getElementById('new-sec-name').value;
        if(!name) return Swal.fire('Error', 'Please select a section.', 'error');
        sections.push({ name: name, subjects: [] }); 
        renderClasses(); 
    }
    function deleteSection(i) { sections.splice(i, 1); renderClasses(); }
    function manageSectionSubjects(index) {
        const section = sections[index];
        const subjectOptions = subjects.map(sub => `<div class="flex items-center mb-2"><input type="checkbox" value="${sub.code}" ${section.subjects.includes(sub.code) ? 'checked' : ''} class="sub-checkbox mr-2"> <span>${sub.code} - ${sub.name}</span></div>`).join('');
        Swal.fire({ title: `Manage ${section.name}`, html: `<div class="text-left max-h-60 overflow-y-auto">${subjectOptions}</div>`, confirmButtonText: 'Save', confirmButtonColor: '#800000', preConfirm: () => [...document.querySelectorAll('.sub-checkbox:checked')].map(c => c.value) }).then((res) => { if(res.isConfirmed) { sections[index].subjects = res.value; renderClasses(); } });
    }

    // --- 3. FACULTY (Updated with Email Simulation) ---
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
                    <button onclick="assignSectionsToProf(${i})" class="text-[#800000] font-bold text-xs underline mr-2">Assign Sections</button>
                    <button onclick="deleteFaculty(${i})" class="text-gray-300 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `).join('');

        content.innerHTML = `
            <div class="animate-fade-in-up">
                 <h4 class="text-lg font-bold text-gray-800 mb-6">3. Manage Professors</h4>
                 
                 <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="flex-shrink-0 text-center">
                            <label class="block w-24 h-24 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 hover:border-[#800000] hover:text-[#800000] transition cursor-pointer relative overflow-hidden group">
                                <i class="fa-solid fa-camera text-2xl group-hover:scale-110 transition"></i>
                                <input type="file" class="hidden" onchange="alert('Image selected (Simulation)')">
                                <div class="absolute inset-0 bg-black/50 text-white text-[9px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition">Upload</div>
                            </label>
                            <p class="text-[10px] text-gray-400 mt-2 font-bold uppercase">Profile Image</p>
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="text-[10px] font-bold text-gray-400 uppercase">Faculty ID</label><input id="fac-id" class="w-full p-2 border rounded-lg" placeholder="FAC-2026-001"></div>
                            <div><label class="text-[10px] font-bold text-gray-400 uppercase">Name</label><input id="fac-name" class="w-full p-2 border rounded-lg" placeholder="Dr. Rogelio Reyes"></div>
                            
                            <div><label class="text-[10px] font-bold text-gray-400 uppercase">Email</label><input id="fac-email" class="w-full p-2 border rounded-lg" placeholder="email@pup.edu.ph"></div>
                            
                            <div class="relative">
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Password</label>
                                <div class="flex">
                                    <input id="fac-pass" type="text" class="w-full p-2 border rounded-l-lg" placeholder="••••••••">
                                    <button onclick="generatePassword('fac-pass')" class="bg-gray-100 border border-l-0 rounded-r-lg px-3 hover:bg-gray-200 text-gray-500" title="Generate Password"><i class="fa-solid fa-key"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <button onclick="addFaculty()" class="bg-[#800000] text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-[#660000]">Add Faculty</button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Image</th><th class="px-6 py-3">ID</th><th class="px-6 py-3">Name</th><th class="px-6 py-3">Handled Sections</th><th class="px-6 py-3 text-right">Action</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
    }

    function addFaculty() {
        const id = document.getElementById('fac-id').value;
        const name = document.getElementById('fac-name').value;
        const email = document.getElementById('fac-email').value;
        const pass = document.getElementById('fac-pass').value;
        if(!id || !name || !email || !pass) return Swal.fire('Error', 'Fill all fields', 'error');
        
        // Show Loading Email
        Swal.fire({
            title: 'Sending Credentials...',
            text: `Emailing login details to ${email}`,
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulate Delay
        setTimeout(() => {
            faculty.push({ id, name, email, assignedSections: [] });
            Swal.fire({
                icon: 'success',
                title: 'Account Created & Emailed!',
                html: `Credentials sent to <b>${email}</b>.<br>Faculty ID: <b>${id}</b><br>Password: <b>${pass}</b>`,
                confirmButtonColor: '#800000'
            });
            renderFaculty();
        }, 2000);
    }
    
    function assignSectionsToProf(index) {
        const prof = faculty[index];
        const secOptions = sections.map(sec => `<div class="flex items-center mb-2"><input type="checkbox" value="${sec.name}" ${prof.assignedSections.includes(sec.name) ? 'checked' : ''} class="sec-checkbox mr-2"> <span>${sec.name}</span></div>`).join('');
        Swal.fire({ title: `Assign Sections to ${prof.name}`, html: `<div class="text-left max-h-60 overflow-y-auto">${secOptions}</div>`, confirmButtonText: 'Save', confirmButtonColor: '#800000', preConfirm: () => [...document.querySelectorAll('.sec-checkbox:checked')].map(c => c.value) }).then((res) => { if(res.isConfirmed) { faculty[index].assignedSections = res.value; renderFaculty(); } });
    }
    function deleteFaculty(i) { faculty.splice(i, 1); renderFaculty(); }


    // --- 4. STUDENTS (Updated with Email Simulation) ---
    function renderStudents() {
        const rows = students.map((std, i) => {
            const sectionData = sections.find(s => s.name === std.section);
            const subCount = sectionData ? sectionData.subjects.length : 0;
            const profs = faculty.filter(f => f.assignedSections.includes(std.section)).map(f => f.name).join(', ');

            return `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">${std.id}</td>
                <td class="px-6 py-4 font-bold text-gray-800">${std.name}</td>
                <td class="px-6 py-4"><span class="bg-green-50 text-green-700 px-2 py-1 rounded font-bold text-xs">${std.section}</span></td>
                <td class="px-6 py-4 text-xs text-gray-600">
                    <div><b>${subCount} Subjects</b></div>
                    <div class="text-[10px] text-gray-400 mt-1">Profs: ${profs || 'None'}</div>
                </td>
                <td class="px-6 py-4 text-right"><button onclick="deleteStudent(${i})" class="text-gray-300 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></td>
            </tr>`;
        }).join('');

        const sectionOptions = sections.map(s => `<option value="${s.name}">${s.name}</option>`).join('');

        content.innerHTML = `
            <div class="animate-fade-in-up">
                 <h4 class="text-lg font-bold text-gray-800 mb-6">4. Student Registry</h4>
                 <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Student Number</label><input id="new-std-id" class="w-full p-2 border rounded-lg" placeholder="2026-00123-MN-0"></div>
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Student Name</label><input id="new-std-name" class="w-full p-2 border rounded-lg" placeholder="Juan Dela Cruz"></div>
                    
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Email</label><input id="new-std-email" class="w-full p-2 border rounded-lg" placeholder="juan@isko.pup.edu.ph"></div>
                    
                    <div class="relative">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Password</label>
                        <div class="flex">
                            <input id="new-std-pass" type="text" class="w-full p-2 border rounded-l-lg" placeholder="••••••••">
                            <button onclick="generatePassword('new-std-pass')" class="bg-gray-100 border border-l-0 rounded-r-lg px-3 hover:bg-gray-200 text-gray-500" title="Generate Password"><i class="fa-solid fa-key"></i></button>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Assign Section</label>
                        <select id="new-std-sec" class="w-full p-2 border rounded-lg bg-white">
                            <option value="" disabled selected>-- Select Section --</option>
                            ${sectionOptions}
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1 italic">* Selecting a section automatically enrolls student in subjects & professors.</p>
                    </div>

                    <div class="md:col-span-2 text-right">
                        <button onclick="addStudent()" class="bg-[#800000] text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-[#660000]">Register Student</button>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Student #</th><th class="px-6 py-3">Name</th><th class="px-6 py-3">Section</th><th class="px-6 py-3">Load Info</th><th class="px-6 py-3 text-right">Action</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
    }

    function addStudent() {
        const id = document.getElementById('new-std-id').value;
        const name = document.getElementById('new-std-name').value;
        const email = document.getElementById('new-std-email').value;
        const pass = document.getElementById('new-std-pass').value;
        const section = document.getElementById('new-std-sec').value;
        
        if(!id || !name || !email || !section || !pass) return Swal.fire('Error', 'Fill all fields', 'error');

        // Show Loading
        Swal.fire({
            title: 'Sending Credentials...',
            text: `Emailing login details to ${email}`,
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulate Delay
        setTimeout(() => {
            students.push({ id, name, email, section });
            
            const secData = sections.find(s => s.name === section);
            const subCount = secData ? secData.subjects.length : 0;

            Swal.fire({
                icon: 'success',
                title: 'Enrolled & Emailed!',
                html: `<b>${name}</b> has been registered.<br>Credentials sent to <b>${email}</b>.<br>Student #: <b>${id}</b>`,
                confirmButtonColor: '#800000'
            });
            renderStudents();
        }, 2000);
    }
    function deleteStudent(i) { students.splice(i, 1); renderStudents(); }

    // --- UTILS ---
    function showLogoutModal() { document.getElementById('logoutModal').classList.remove('hidden'); document.getElementById('logoutModal').classList.add('flex'); }
    function hideLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('flex'); }
    function executeLogout() { window.location.href = "{{ route('home') }}"; }
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
</style>
@endsection