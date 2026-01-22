@extends('layouts.app')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-10 py-2 text-white bg-[#800000]/90 shadow-lg transition-all duration-300">
    <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8">
        <div>
            <h1 class="font-bold leading-none text-base">EduRate</h1>
            <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
        </div>
    </div>
    
    <div class="flex items-center space-x-4">
        <span class="text-xs font-medium opacity-70 hidden sm:inline tracking-wider uppercase">System Administrator</span>
        <button type="button" onclick="confirmLogout()" class="bg-white/10 px-4 py-1.5 rounded-lg text-sm font-bold hover:bg-white/20 transition flex items-center border border-white/20">
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
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 uppercase tracking-tight">Institutional Departments</h2>
                    <p class="text-gray-400 text-sm italic mt-1">Manage departments and assign programs.</p>
                </div>
                <button onclick="toggleModal('addDeptModal', true)" class="bg-[#800000] text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-[#660000] transition active:scale-95 flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i> Add Department
                </button>
            </div>

<<<<<<< HEAD
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
=======
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden min-h-[400px]">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="px-8 py-4 w-1/6">Dept Code</th>
                            <th class="px-8 py-4 w-2/6">Department Name</th>
                            <th class="px-8 py-4 w-2/6">Programs (Courses)</th>
                            <th class="px-8 py-4 w-1/6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="departmentTableBody" class="text-sm font-semibold text-gray-700 divide-y divide-gray-100">
                        </tbody>
                </table>
                <div id="emptyState" class="hidden p-12 text-center text-gray-400">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-50"></i>
                    <p class="text-xs uppercase font-bold">No departments found.</p>
>>>>>>> f85b3c65440bfe9469d7de2ea101274d6fa532f9
                </div>
            </div>
        </div>

        <div id="drillDownView" class="hidden transition-all duration-300">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <h3 id="viewTitle" class="text-3xl font-black text-[#800000] uppercase tracking-tight leading-none">Manage Program</h3>
<<<<<<< HEAD
                    <p id="viewSub" class="text-sm text-gray-400 font-bold uppercase tracking-widest mt-1">Department</p>
=======
                    <div class="flex items-center space-x-4 mt-2">
                        <span id="viewSub" class="text-sm text-gray-400 font-bold uppercase tracking-widest">CCIS Department</span>
                        
                        <div class="relative inline-block ml-4">
                            <select id="headerProgramSelect" onchange="selectCourse(this.value)" class="bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg focus:ring-[#800000] focus:border-[#800000] block p-2">
                                </select>
                        </div>
                    </div>
>>>>>>> f85b3c65440bfe9469d7de2ea101274d6fa532f9
                </div>
                <button onclick="closeDrillDown()" class="bg-white border border-gray-200 text-gray-500 hover:text-[#800000] px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all hover:shadow-md">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Departments
                </button>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border-t-8 border-[#800000] min-h-[500px] overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-8 py-4">
                    <div class="flex flex-wrap items-center space-x-2 sm:space-x-4 text-xs sm:text-sm font-bold uppercase tracking-wide text-gray-400 select-none">
                        <button onclick="switchTab('subjects')" id="tab-subjects" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Subjects</button>
                        <span class="text-gray-300 font-light text-lg mx-2">|</span>
                        <button onclick="switchTab('classes')" id="tab-classes" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Classes</button>
                        <span class="text-gray-300 font-light text-lg mx-2">|</span>
                        <button onclick="switchTab('faculty')" id="tab-faculty" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Faculties</button>
                        <span class="text-gray-300 font-light text-lg mx-2">|</span>
                        <button onclick="switchTab('students')" id="tab-students" class="hover:text-[#800000] transition-colors focus:outline-none py-1">Students</button>
                    </div>
                </div>

                <div id="drillDownContent" class="p-8"></div>
            </div>
        </div>
    </div>

    <div id="addDeptModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.25)] w-full max-w-md overflow-hidden transform transition-all scale-100 pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Add New Department</h3>
                <button onclick="toggleModal('addDeptModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Department Code</label>
                    <input id="new-dept-code" class="w-full p-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:border-[#800000] outline-none" placeholder="e.g., CCIS">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Department Name</label>
                    <input id="new-dept-name" class="w-full p-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:border-[#800000] outline-none" placeholder="e.g., College of Computer Science">
                </div>
                <button onclick="confirmAddDept()" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Save Department</button>
            </div>
        </div>
    </div>

    <div id="addProgramModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.25)] w-full max-w-md overflow-hidden transform transition-all scale-100 pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Add Program</h3>
                <button onclick="toggleModal('addProgramModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                    <p class="text-[10px] font-bold text-[#800000] uppercase tracking-wide">Adding to: <span id="program-dept-target" class="text-gray-700"></span></p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Program Code/Course</label>
                    <input id="new-program-code" class="w-full p-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:border-[#800000] outline-none" placeholder="e.g., BSIT">
                </div>
                <button onclick="confirmAddProgram()" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Add Program</button>
            </div>
        </div>
    </div>

    <div id="assignSectionModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.25)] w-full max-w-md overflow-hidden pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center shrink-0">
                <h3 class="text-white font-bold uppercase text-sm">Assign Sections to Faculty</h3>
                <button onclick="toggleModal('assignSectionModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="p-6 overflow-y-auto">
                <p class="text-xs text-gray-500 mb-4 font-bold">Select sections to assign:</p>
                <div id="sectionCheckboxes" class="space-y-2 border border-gray-100 p-3 rounded-lg bg-gray-50"></div>
            </div>
            <div class="p-6 pt-0 shrink-0">
                 <button onclick="confirmAssignSections()" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000]">Save Assignments</button>
            </div>
        </div>
    </div>

    <div id="manageClassSubjectModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.25)] w-full max-w-md overflow-hidden pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center shrink-0">
                <h3 class="text-white font-bold uppercase text-sm">Manage Class Subjects</h3>
                <button onclick="toggleModal('manageClassSubjectModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="p-6 overflow-y-auto">
                <p class="text-xs text-gray-500 mb-4 font-bold">Select subjects for this section:</p>
                <div id="subjectCheckboxes" class="space-y-2 border border-gray-100 p-3 rounded-lg bg-gray-50"></div>
            </div>
            <div class="p-6 pt-0 shrink-0">
                <button onclick="confirmClassSubjects()" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000]">Update Class Subjects</button>
            </div>
        </div>
    </div>

</main>

<script>
    // --- STATE & DOM ELEMENTS ---
    const selectionView = document.getElementById('selectionView');
    const drillDownView = document.getElementById('drillDownView');
    const content = document.getElementById('drillDownContent');
<<<<<<< HEAD
    const deptSelector = document.getElementById('deptSelector');
=======
    const headerProgramSelect = document.getElementById('headerProgramSelect');
    const deptTableBody = document.getElementById('departmentTableBody');
    const emptyState = document.getElementById('emptyState');

    // --- DATA STRUCTURE ---
    let departmentList = [
        { code: 'CCIS', name: 'College of Computer and Information Sciences', programs: ['BSIT', 'BSCS'] },
        { code: 'COA', name: 'College of Accountancy', programs: ['BSA'] },
        { code: 'COC', name: 'College of Communication', programs: [] }
    ];
>>>>>>> f85b3c65440bfe9469d7de2ea101274d6fa532f9

    let subjects = [
        { code: 'COMP 20133', name: 'Applications Dev', assignedProf: 'Dr. Rogelio Reyes' },
        { code: 'INTE 30023', name: 'Integrative Prog', assignedProf: 'Dr. Sarah Santos' },
        { code: 'GEED 10013', name: 'Life and Works of Rizal', assignedProf: null }
    ];
    let sections = [
        { name: 'BSIT 3-1', course: 'BSIT', subjects: ['COMP 20133', 'INTE 30023'] }, 
        { name: 'BSIT 3-2', course: 'BSIT', subjects: ['COMP 20133'] }
    ];
    let faculty = [
        { id: 'FAC-001', name: 'Dr. Rogelio Reyes', email: 'r.reyes@pup.edu.ph', assignedSections: ['BSIT 3-1', 'BSIT 3-2'] },
        { id: 'FAC-002', name: 'Dr. Sarah Santos', email: 's.santos@pup.edu.ph', assignedSections: ['BSIT 3-1'] }
    ];
    let students = [
        { id: '2023-00123-MN-0', name: 'Dela Cruz, Juan', section: 'BSIT 3-1', email: 'juan@isko.pup.edu.ph' }
    ];

    let currentDeptIndex = null;
    let currentEditIndex = null; 
    let activeProgram = null;

    // --- INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', () => {
        renderDepartments();
    });

    // --- 1. DEPARTMENT & PROGRAM LOGIC ---
    function renderDepartments() {
        deptTableBody.innerHTML = '';
        if (departmentList.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }
        emptyState.classList.add('hidden');

        departmentList.forEach((dept, index) => {
            const row = document.createElement('tr');
            row.className = "hover:bg-gray-50 transition border-b border-gray-100 group";
            
            let programsHtml = '';
            if(dept.programs.length > 0) {
                programsHtml = `<div class="flex flex-wrap gap-2">` + 
                    dept.programs.map(prog => `
                        <button onclick="selectCourse('${prog}', '${dept.code}')" class="group/btn relative bg-gray-100 hover:bg-[#800000] hover:text-white text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center pr-8">
                            ${prog}
                            <span class="absolute right-2 opacity-0 group-hover/btn:opacity-100 transition-opacity">
                                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            </span>
                        </button>
                    `).join('') + 
                `</div>`;
            } else {
                programsHtml = `<span class="text-xs text-gray-300 italic">No programs added</span>`;
            }

            // --- FIX APPLIED HERE ---
            // Added 'flex items-center justify-end' to the last <td>
            row.innerHTML = `
                <td class="px-8 py-5 text-[#800000] font-black">${dept.code}</td>
                <td class="px-8 py-5 text-gray-600">${dept.name}</td>
                <td class="px-8 py-5">${programsHtml}</td>
                <td class="px-8 py-5 text-right flex items-center justify-end space-x-2">
                    <button onclick="openAddProgramModal(${index})" class="bg-white border border-gray-200 text-gray-500 hover:text-[#800000] hover:border-[#800000] px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm">
                        <i class="fa-solid fa-plus mr-1"></i> Program
                    </button>
                    <button onclick="deleteDepartment(${index})" class="text-gray-300 hover:text-red-500 px-2 py-1.5 rounded-lg text-xs transition-all">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </td>
            `;
            deptTableBody.appendChild(row);
        });
    }

    // Modal: Add Department
    function confirmAddDept() {
        const code = document.getElementById('new-dept-code').value;
        const name = document.getElementById('new-dept-name').value;
        
        if(!code || !name) return Swal.fire('Error', 'Please fill all fields', 'error');

        confirmAction('Add Department?', `Create ${name}?`, function() {
            departmentList.push({ code, name, programs: [] });
            toggleModal('addDeptModal', false);
            renderDepartments();
        });
    }

    // Modal: Add Program
    function openAddProgramModal(index) {
        currentDeptIndex = index;
        document.getElementById('program-dept-target').innerText = departmentList[index].code;
        document.getElementById('new-program-code').value = '';
        toggleModal('addProgramModal', true);
    }

    function confirmAddProgram() {
        const progCode = document.getElementById('new-program-code').value;
        if(!progCode) return Swal.fire('Error', 'Program code required', 'error');

        if(departmentList[currentDeptIndex].programs.includes(progCode)) {
             return Swal.fire('Error', 'Program already exists in this department', 'warning');
        }

        departmentList[currentDeptIndex].programs.push(progCode);
        toggleModal('addProgramModal', false);
        renderDepartments();
        
        Swal.fire({
            title: 'Success!',
            text: 'Program added successfully.',
            icon: 'success',
            confirmButtonColor: '#800000',
            timer: 1000,
            showConfirmButton: false
        });
    }

    function deleteDepartment(index) {
        confirmAction('Delete Department?', 'This will remove the department and all its programs.', function() {
            departmentList.splice(index, 1);
            renderDepartments();
        });
    }

    // --- DRILL DOWN / MANAGE PROGRAM LOGIC ---
    function selectCourse(course, deptCode = null) {
        activeProgram = course;
        selectionView.classList.add('hidden');
        drillDownView.classList.remove('hidden');
        
        headerProgramSelect.innerHTML = '';
        let opt = new Option(course, course);
        headerProgramSelect.add(opt);
        headerProgramSelect.value = course;
        
        document.getElementById('viewSub').innerText = `${deptCode ? deptCode + ' - ' : ''} ${course} Program`;
        switchTab('classes');
    }

    function closeDrillDown() { 
        drillDownView.classList.add('hidden');
        selectionView.classList.remove('hidden');
        activeProgram = null;
    }

    // --- GENERIC HELPERS ---
    function generatePassword(fieldId) {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$";
        let pass = "";
        for (let i = 0; i < 10; i++) { pass += chars.charAt(Math.floor(Math.random() * chars.length)); }
        document.getElementById(fieldId).value = pass;
    }

    function toggleModal(modalId, show) {
        const modal = document.getElementById(modalId);
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function confirmAction(title, text, callback) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#800000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            background: '#fff',
            color: '#333'
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }

    // --- TAB SWITCHING LOGIC ---
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

    // --- SUB-FUNCTIONS (Subjects, Classes, etc.) ---
    function renderSubjects() {
        const rows = subjects.map((sub, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-[#800000]">${sub.code}</td>
                <td class="px-6 py-4 text-gray-700 font-bold text-xs uppercase">${sub.name}</td>
<<<<<<< HEAD
                <td class="px-6 py-4 text-center text-xs text-gray-400 italic">Managed in Classes</td>
                <td class="px-6 py-4 text-right"><button class="text-gray-300 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button></td>
=======
                <td class="px-6 py-4">
                     <select class="bg-gray-50 border border-gray-200 text-xs font-bold rounded-lg p-2 w-full outline-none focus:border-[#800000]">
                        <option value="" disabled ${!sub.assignedProf ? 'selected' : ''}>-- Assign Prof --</option>
                        ${faculty.map(f => `<option value="${f.name}" ${sub.assignedProf === f.name ? 'selected' : ''}>${f.name}</option>`).join('')}
                    </select>
                </td>
                <td class="px-6 py-4 text-right"><button onclick="deleteSubject(${i})" class="text-gray-300 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button></td>
>>>>>>> f85b3c65440bfe9469d7de2ea101274d6fa532f9
            </tr>
        `).join('');

        const modalHtml = `
        <div id="addSubjectModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
            <div class="bg-white rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.25)] w-full max-w-md overflow-hidden pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
                <div class="bg-[#800000] px-6 py-4 flex justify-between items-center shrink-0">
                    <h3 class="text-white font-bold uppercase text-sm">Add New Subject to ${activeProgram}</h3>
                    <button onclick="toggleModal('addSubjectModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="p-6 space-y-4 overflow-y-auto">
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Subject Code</label><input id="sub-code-val" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="COMP 101"></div>
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Description</label><input id="sub-name-val" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="Programming 1"></div>
                </div>
                <div class="p-6 pt-0 shrink-0"><button onclick="confirmAddSubject()" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000]">Save Subject</button></div>
            </div>
        </div>`;

        content.innerHTML = `
            <div class="animate-fade-in-up">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-lg font-bold text-gray-800 font-black uppercase tracking-tight">1. Manage Subjects (${activeProgram})</h4>
                    <button onclick="toggleModal('addSubjectModal', true)" class="bg-[#800000] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase shadow hover:bg-[#660000] flex items-center">
                        <i class="fa-solid fa-plus mr-2"></i> Add Subject
                    </button>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Code</th><th class="px-6 py-3">Description</th><th class="px-6 py-3">Assigned Prof</th><th class="px-6 py-3 text-right">Action</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
                ${modalHtml}
            </div>`;
    }

<<<<<<< HEAD
    // Modal and Logout Logic
    function showLogoutModal() { document.getElementById('logoutModal').classList.remove('hidden'); document.getElementById('logoutModal').classList.add('flex'); }
    function hideLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('flex'); }
    function executeLogout() { window.location.href = "/"; }
=======
    function confirmAddSubject() {
        const code = document.getElementById('sub-code-val').value;
        const name = document.getElementById('sub-name-val').value;
        if(!code || !name) return Swal.fire('Error', 'Fill all fields', 'error');

        confirmAction('Add Subject?', 'Are you sure?', function() {
            subjects.push({ code: code, name: name, assignedProf: null });
            toggleModal('addSubjectModal', false);
            renderSubjects();
        });
    }
    function deleteSubject(index) { confirmAction('Delete Subject?', 'This cannot be undone.', function() { subjects.splice(index, 1); renderSubjects(); }); }

    function renderClasses() {
        const filteredSections = sections.filter(s => s.course === activeProgram);
        const rows = filteredSections.map((sec, i) => `<tr class="bg-white border-b hover:bg-gray-50 transition"><td class="px-6 py-4 font-black text-gray-800">${sec.name}</td><td class="px-6 py-4"><div class="flex flex-wrap gap-2">${sec.subjects.map(s => `<span class="bg-blue-50 text-blue-700 border border-blue-100 px-2 py-1 rounded text-[10px] font-bold">${s}</span>`).join('')}</div></td><td class="px-6 py-4 text-right"><button onclick="manageSectionSubjects(${i})" class="text-[#800000] font-bold text-xs underline mr-2 hover:text-[#660000]">Manage Subjects</button><button onclick="deleteSection(${i})" class="text-gray-300 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></td></tr>`).join('');
        
        const modalHtml = `
        <div id="addClassModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
            <div class="bg-white rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.25)] w-full max-w-md overflow-hidden pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
                <div class="bg-[#800000] px-6 py-4 flex justify-between items-center shrink-0">
                    <h3 class="text-white font-bold uppercase text-sm">Create New Class for ${activeProgram}</h3>
                    <button onclick="toggleModal('addClassModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="p-6 space-y-4 overflow-y-auto">
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Section Name</label><input id="class-name-val" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g. ${activeProgram} 3-1"></div>
                </div>
                <div class="p-6 pt-0 shrink-0"><button onclick="confirmAddClass()" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000]">Create Class</button></div>
            </div>
        </div>`;

        content.innerHTML = `
        <div class="animate-fade-in-up">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-lg font-bold text-gray-800 font-black uppercase tracking-tight">2. Manage Classes (${activeProgram})</h4>
                 <button onclick="toggleModal('addClassModal', true)" class="bg-[#800000] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase shadow hover:bg-[#660000] flex items-center"><i class="fa-solid fa-plus mr-2"></i> Add Class</button>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px]"><tr><th class="px-6 py-3">Section</th><th class="px-6 py-3">Subjects Assigned</th><th class="px-6 py-3 text-right">Action</th></tr></thead><tbody>${rows}</tbody></table>
            </div>
            ${modalHtml}
        </div>`;
    }

    function confirmAddClass() {
        const name = document.getElementById('class-name-val').value;
        if(!name) return Swal.fire('Error', 'Section name required', 'error');
        confirmAction('Create Class?', 'Are you sure?', function() { 
            sections.push({ name: name, course: activeProgram, subjects: [] }); 
            toggleModal('addClassModal', false); 
            renderClasses(); 
        });
    }
    function deleteSection(index) { confirmAction('Delete Section?', 'All students in this section will be affected.', function() { sections.splice(index, 1); renderClasses(); }); }
    
    function manageSectionSubjects(index) {
        currentEditIndex = index;
        const currentSubjects = sections[index].subjects;
        const container = document.getElementById('subjectCheckboxes');
        container.innerHTML = subjects.map(sub => `
            <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                <input type="checkbox" class="form-checkbox text-[#800000] h-4 w-4" value="${sub.code}" ${currentSubjects.includes(sub.code) ? 'checked' : ''}>
                <span class="ml-2 text-xs font-bold text-gray-700">${sub.code} - ${sub.name}</span>
            </label>
        `).join('');
        toggleModal('manageClassSubjectModal', true);
    }
    function confirmClassSubjects() {
        const checkboxes = document.querySelectorAll('#subjectCheckboxes input[type="checkbox"]:checked');
        const selectedSubjects = Array.from(checkboxes).map(cb => cb.value);
        confirmAction('Update Subjects?', 'Update assigned subjects for this section?', function() { sections[currentEditIndex].subjects = selectedSubjects; toggleModal('manageClassSubjectModal', false); renderClasses(); });
    }

    function renderFaculty() {
        const rows = faculty.map((fac, i) => `
            <tr class="bg-white border-b hover:bg-gray-50 transition">
                <td class="px-6 py-4"><div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 font-bold text-xs border border-gray-200">${fac.name.charAt(0)}</div></td>
                <td class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">${fac.id}</td>
                <td class="px-6 py-4 font-bold text-gray-800 text-sm">${fac.name}</td>
                <td class="px-6 py-4"><div class="flex flex-wrap gap-2">${fac.assignedSections.map(sec => `<span class="bg-red-50 text-[#800000] border border-red-100 px-2 py-1 rounded text-[10px] font-bold">${sec}</span>`).join('')}</div></td>
                <td class="px-6 py-4 text-right"><button onclick="assignSectionsToProf(${i})" class="text-[#800000] font-bold text-xs underline mr-4 hover:text-[#660000]">Assign Sections</button><button onclick="deleteFaculty(${i})" class="text-gray-300 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
        `).join('');

        const modalHtml = `
        <div id="addFacultyModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
            <div class="bg-white rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.25)] w-full max-w-2xl overflow-hidden pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
                <div class="bg-[#800000] px-6 py-4 flex justify-between items-center shrink-0">
                    <h3 class="text-white font-bold uppercase text-sm">Add New Faculty</h3>
                    <button onclick="toggleModal('addFacultyModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="p-8 overflow-y-auto">
                      <div class="flex flex-col md:flex-row gap-8">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Faculty ID</label><input id="fac-id-val" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="FAC-2026-001"></div>
                            <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Name</label><input id="fac-name-val" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="Dr. Rogelio Reyes"></div>
                            <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label><input id="fac-email-val" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="email@pup.edu.ph"></div>
                            <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password</label><div class="relative"><input id="fac-pass-val" type="text" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="••••••••"><button onclick="generatePassword('fac-pass-val')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000] transition"><i class="fa-solid fa-key"></i></button></div></div>
                        </div>
                    </div>
                </div>
                <div class="p-8 pt-0 shrink-0 flex justify-end"><button onclick="confirmAddFaculty()" class="bg-[#800000] text-white px-8 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:bg-[#660000] transition active:scale-95">Save Faculty</button></div>
            </div>
        </div>`;

        content.innerHTML = `
            <div class="animate-fade-in-up">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-lg font-bold text-gray-800 font-black uppercase tracking-tight">3. Manage Professors</h4>
                    <button onclick="toggleModal('addFacultyModal', true)" class="bg-[#800000] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase shadow hover:bg-[#660000] flex items-center"><i class="fa-solid fa-plus mr-2"></i> Add Faculty</button>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px] tracking-widest"><tr><th class="px-6 py-4">Image</th><th class="px-6 py-4">ID</th><th class="px-6 py-4">Name</th><th class="px-6 py-4">Handled Sections</th><th class="px-6 py-4 text-right">Action</th></tr></thead><tbody>${rows}</tbody></table>
                </div>
                ${modalHtml}
            </div>`;
    }

    function assignSectionsToProf(index) {
        currentEditIndex = index;
        const currentAssigned = faculty[index].assignedSections;
        const container = document.getElementById('sectionCheckboxes');
        container.innerHTML = sections.map(sec => `
            <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                <input type="checkbox" class="form-checkbox text-[#800000] h-4 w-4" value="${sec.name}" ${currentAssigned.includes(sec.name) ? 'checked' : ''}>
                <span class="ml-2 text-xs font-bold text-gray-700">${sec.name}</span>
            </label>
        `).join('');
        toggleModal('assignSectionModal', true);
    }
    function confirmAssignSections() {
        const checkboxes = document.querySelectorAll('#sectionCheckboxes input[type="checkbox"]:checked');
        const selectedSections = Array.from(checkboxes).map(cb => cb.value);
        confirmAction('Update Assignment?', 'Assign these sections to the professor?', function() { faculty[currentEditIndex].assignedSections = selectedSections; toggleModal('assignSectionModal', false); renderFaculty(); });
    }
    function confirmAddFaculty() {
         const id = document.getElementById('fac-id-val').value;
         const name = document.getElementById('fac-name-val').value;
         const email = document.getElementById('fac-email-val').value;
         if(!id || !name) return Swal.fire('Error', 'ID and Name are required', 'error');
         confirmAction('Add Faculty?', 'Confirm details.', function() { faculty.push({ id: id, name: name, email: email, assignedSections: [] }); toggleModal('addFacultyModal', false); renderFaculty(); });
    }
    function deleteFaculty(index) { confirmAction('Remove Faculty?', 'This action is irreversible.', function() { faculty.splice(index, 1); renderFaculty(); }); }

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
                <td class="px-6 py-4 text-xs text-gray-600"><div class="font-bold">${subCount} Subjects</div><div class="text-[10px] text-gray-400 mt-1">Profs: ${profs || 'None'}</div></td>
                <td class="px-6 py-4 text-right"><button onclick="deleteStudent(${i})" class="text-gray-300 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button></td>
            </tr>`;
        }).join('');

        const modalHtml = `
        <div id="addStudentModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
            <div class="bg-white rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.25)] w-full max-w-2xl overflow-hidden pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
                <div class="bg-[#800000] px-6 py-4 flex justify-between items-center shrink-0">
                    <h3 class="text-white font-bold uppercase text-sm">Register New Student</h3>
                    <button onclick="toggleModal('addStudentModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="p-8 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Student Number</label><input id="std-id-val" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="2026-00123-MN-0"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Student Name</label><input id="std-name-val" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="Juan Dela Cruz"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label><input id="std-email-val" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="juan@isko.pup.edu.ph"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password</label><div class="relative"><input id="std-pass-val" type="text" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all" placeholder="••••••••"><button onclick="generatePassword('std-pass-val')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000] transition"><i class="fa-solid fa-key"></i></button></div></div>
                         <div class="space-y-1"><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Assign Section</label><div class="relative"><select id="std-sec-val" class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all appearance-none cursor-pointer"><option value="" disabled selected>-- Select Section --</option>${sections.map(s => `<option value="${s.name}">${s.name}</option>`).join('')}</select><div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400"><i class="fa-solid fa-chevron-down"></i></div></div></div>
                    </div>
                </div>
                <div class="p-8 pt-0 shrink-0 flex justify-end"><button onclick="confirmAddStudent()" class="bg-[#800000] text-white px-8 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:bg-[#660000] transition active:scale-95">Register Student</button></div>
            </div>
        </div>`;

        content.innerHTML = `
            <div class="animate-fade-in-up">
                <div class="flex justify-between items-center mb-6">
                     <h4 class="text-lg font-bold text-gray-800 font-black uppercase tracking-tight">4. Student Registry</h4>
                     <button onclick="toggleModal('addStudentModal', true)" class="bg-[#800000] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase shadow hover:bg-[#660000] flex items-center"><i class="fa-solid fa-plus mr-2"></i> Add Student</button>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px] tracking-widest"><tr><th class="px-6 py-4">Student #</th><th class="px-6 py-4">Name</th><th class="px-6 py-4">Section</th><th class="px-6 py-4">Load Info</th><th class="px-6 py-4 text-right">Action</th></tr></thead><tbody>${rows}</tbody></table>
                </div>
                ${modalHtml}
            </div>`;
    }

    function confirmAddStudent() {
        const id = document.getElementById('std-id-val').value;
        const name = document.getElementById('std-name-val').value;
        const sec = document.getElementById('std-sec-val').value;
        if(!id || !name || !sec) return Swal.fire('Error', 'Complete all details', 'error');
        confirmAction('Register Student?', 'Confirm registration details.', function() { students.push({ id: id, name: name, section: sec, email: 'temp@mail.com' }); toggleModal('addStudentModal', false); renderStudents(); });
    }
    function deleteStudent(index) { confirmAction('Delete Student?', 'Are you sure?', function() { students.splice(index, 1); renderStudents(); }); }
    function confirmLogout() { confirmAction('Log Out?', 'You will be returned to the login screen.', function() { window.location.href = "{{ route('home') }}"; }); }

>>>>>>> f85b3c65440bfe9469d7de2ea101274d6fa532f9
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection