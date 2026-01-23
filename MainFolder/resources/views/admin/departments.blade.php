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

        {{-- Alerts --}}

        {{-- 1. Validation Errors --}}
        @if($errors->any())
        <div class="mb-4">
            @foreach($errors->all() as $error)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-2 text-sm font-bold">{{ $error }}</div>
            @endforeach
        </div>
        @endif

        {{-- 2. Success Message --}}
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-bold flex items-center">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
        @endif

        {{-- 3. Logical Errors (e.g. Cannot delete because it has faculty) --}}
        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-bold flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
        </div>
        @endif

        {{-- VIEW 1: DEPARTMENT SELECTION (Level 1) --}}
        <div id="selectionView" class="transition-all duration-300">
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 uppercase tracking-tight">Institutional Departments</h2>
                    <p class="text-gray-400 text-sm italic mt-1">Manage departments and their academic programs.</p>
                </div>
                <button onclick="toggleModal('addDeptModal', true)" class="bg-[#800000] text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-[#660000] transition active:scale-95 flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i> Add Department
                </button>
            </div>

            {{-- Search Bar --}}
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <div class="relative w-full max-w-xs">
                    <input type="search" id="departmentSearch" placeholder="Search departments..." class="w-full pl-10 pr-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#800000] focus:border-transparent transition-all" oninput="searchDepartments(this.value)">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>

            {{-- Departments Table --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden min-h-[400px]">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-500 font-bold uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="px-8 py-4 w-1/6">Dept Code</th>
                            <th class="px-8 py-4 w-2/6">Department Name</th>
                            <th class="px-8 py-4 w-1/6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="departmentTableBody" class="text-sm font-semibold text-gray-700 divide-y divide-gray-100">
                        @forelse($departments as $dept)
                        <tr class="hover:bg-gray-50 transition border-b border-gray-100 group">
                            <td class="px-8 py-5 text-[#800000] font-black">{{ $dept->code }}</td>
                            <td class="px-8 py-5 text-gray-600">{{ $dept->name }}</td>
                            <td class="px-8 py-5 text-right flex items-center justify-end space-x-2">
                                {{-- 1. MANAGE BUTTON (Drill Down) --}}
                                <button onclick="openDepartmentManage('{{ e($dept->code) }}', '{{ e($dept->name) }}', {{ $dept->id }})" type="button" class="text-blue-600 hover:text-blue-800 px-2" title="Manage Programs">
                                    <i class="fa-solid fa-list-check"></i>
                                </button>

                                {{-- 2. EDIT DEPARTMENT BUTTON (New) --}}
                                <button onclick="openEditDepartmentModal('{{ e($dept->code) }}', '{{ e($dept->name) }}', {{ $dept->id }})" type="button" class="text-green-600 hover:text-green-800 px-2" title="Edit Details">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                {{-- 3. DELETE BUTTON --}}
                                <form id="delete-dept-form-{{ $dept->id }}" method="post" action="{{ route('admin.departments.destroy', $dept->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteDept({{ $dept->id }})" class="text-red-500 hover:text-red-700 px-2 transition-colors" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-12 text-center text-gray-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-50"></i>
                                <p class="text-xs uppercase font-bold">No departments found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- VIEW 2: DEPARTMENT MANAGE (Programs List) (Level 2) --}}
        <div id="departmentDrillDownView" class="hidden transition-all duration-300">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <h3 class="text-3xl font-black text-[#800000] uppercase tracking-tight leading-none">Manage Department</h3>
                    <div class="flex items-center space-x-4 mt-2">
                        <span id="deptTitleCode" class="text-sm text-gray-400 font-bold uppercase tracking-widest">CCIS</span>
                        <span class="text-gray-300">|</span>
                        <span id="deptTitleName" class="text-sm text-gray-600 font-bold uppercase">College of Computer Science</span>
                    </div>
                </div>
                <button onclick="closeDepartmentManage()" class="bg-white border border-gray-200 text-gray-500 hover:text-[#800000] px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all hover:shadow-md">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
                </button>
            </div>

            {{-- Programs Table --}}
            <div class="bg-white rounded-3xl shadow-xl border-t-8 border-[#800000] min-h-[500px] overflow-hidden p-8">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Academic Programs</h4>
                    <button onclick="toggleModal('addProgramModal', true)" class="bg-[#800000] text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md hover:bg-[#660000] flex items-center">
                        <i class="fa-solid fa-plus mr-2"></i> Add Program
                    </button>
                </div>

                <div class="border rounded-xl overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Program Code</th>
                                <th class="px-6 py-4">Program Name</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="programsTableBody">
                            {{-- JS will populate this --}}
                        </tbody>
                    </table>
                    <div id="noProgramsMsg" class="hidden p-8 text-center text-gray-400">
                        <p class="text-sm font-bold">No programs added to this department yet.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- VIEW 3: PROGRAM DETAILS (Subjects/Classes) (Level 3) --}}
        <div id="programDrillDownView" class="hidden transition-all duration-300">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <h3 id="viewTitle" class="text-3xl font-black text-[#800000] uppercase tracking-tight leading-none">Manage Program</h3>
                    <div class="flex items-center space-x-4 mt-2">
                        <span id="viewSub" class="text-sm text-gray-400 font-bold uppercase tracking-widest"></span>
                    </div>
                </div>
                <button onclick="closeProgramManage()" class="bg-white border border-gray-200 text-gray-500 hover:text-[#800000] px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all hover:shadow-md">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Department
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

    {{-- ======================================================================= --}}
    {{-- 1. DEPARTMENT MODALS (Standard Full Page Reload Submission) --}}
    {{-- ======================================================================= --}}

    <div id="addDeptModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Add New Department</h3>
                <button onclick="toggleModal('addDeptModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.departments.store') }}">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Department Code</label>
                        <input name="code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g., CCIS" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Department Name</label>
                        <input name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g., College of Computer Science" required>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Save Department</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editDeptModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Edit Department</h3>
                <button onclick="toggleModal('editDeptModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editDeptForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Department Code</label>
                        <input id="edit-dept-code" name="code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Department Name</label>
                        <input id="edit-dept-name" name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Update Department</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================================= --}}
    {{-- 2. PROGRAM (COURSE) MODALS (AJAX) --}}
    {{-- ======================================================================= --}}

    <div id="addProgramModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Add Program</h3>
                <button onclick="toggleModal('addProgramModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="handleAjaxSubmit(event, 'addProgramModal', renderProgramsTable)" action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                <input type="hidden" name="department_id" id="add-program-dept-id">
                <div class="p-6 space-y-4">
                    <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                        <p class="text-[10px] font-bold text-[#800000] uppercase tracking-wide">Target Dept: <span id="program-dept-target" class="text-gray-700"></span></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Program Code</label>
                        <input name="code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g., BSIT" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Program Name</label>
                        <input name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g., Bachelor of Science in IT" required>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Save Program</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editProgramModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Edit Program</h3>
                <button onclick="toggleModal('editProgramModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editProgramForm" onsubmit="handleAjaxSubmit(event, 'editProgramModal', renderProgramsTable)" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Program Code</label>
                        <input id="edit-program-code" name="code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Program Name</label>
                        <input id="edit-program-name" name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Update Program</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================================= --}}
    {{-- 3. SUBJECT MODALS (AJAX) --}}
    {{-- ======================================================================= --}}

    <div id="addSubjectModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Add Subject</h3>
                <button onclick="toggleModal('addSubjectModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="handleAjaxSubmit(event, 'addSubjectModal', renderSubjects)" action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <input type="hidden" name="department_id" id="add-subject-dept-id">
                <input type="hidden" name="course_id" id="add-subject-course-id">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Subject Code</label>
                        <input name="subject_code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g. COMP 101" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Description</label>
                        <input name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g. Intro to Computing" required>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label>
                            <input type="number" name="year_level" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="4" required>
                        </div>
                        <div class="w-1/2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Credits</label>
                            <input type="number" name="credits" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="10" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Save Subject</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editSubjectModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Edit Subject</h3>
                <button onclick="toggleModal('editSubjectModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editSubjectForm" onsubmit="handleAjaxSubmit(event, 'editSubjectModal', renderSubjects)" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Subject Code</label>
                        <input id="edit-subject-code" name="subject_code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Description</label>
                        <input id="edit-subject-name" name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label>
                            <input id="edit-subject-year" type="number" name="year_level" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="5" required>
                        </div>
                        <div class="w-1/2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Credits</label>
                            <input id="edit-subject-credits" type="number" name="credits" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="10" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Update Subject</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================================= --}}
    {{-- 4. CLASS SECTION MODALS (AJAX) --}}
    {{-- ======================================================================= --}}

    <div id="addClassModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Add Class Section</h3>
                <button onclick="toggleModal('addClassModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="handleAjaxSubmit(event, 'addClassModal', renderClasses)" action="{{ route('admin.sections.store') }}" method="POST">
                @csrf
                <input type="hidden" name="course_id" id="add-class-course-id">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label>
                        <input type="number" name="year_level" id="new-section-year" oninput="loadSubjectsForClass(this.value, 'add-subject-list')" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g. 1" min="1" max="4" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Block</label>
                        <input name="block" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g. A" required>
                    </div>
                    <div class="border-t pt-4">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Assign Subjects</label>
                        <div id="add-subject-list" class="h-32 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50 text-sm grid grid-cols-1 gap-1">
                            <p class="text-gray-400 italic text-xs text-center mt-4">Enter a Year Level to see subjects.</p>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Save Class</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editClassModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm">Edit Class Section</h3>
                <button onclick="toggleModal('editClassModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editSectionForm" onsubmit="handleAjaxSubmit(event, 'editClassModal', renderClasses)" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label>
                        <input type="number" name="year_level" id="edit-section-year" oninput="loadSubjectsForClass(this.value, 'edit-subject-list')" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="4" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Block</label>
                        <input name="block" id="edit-section-block" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                    </div>
                    <div class="border-t pt-4">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Assigned Subjects</label>
                        <div id="edit-subject-list" class="h-32 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50 text-sm grid grid-cols-1 gap-1"></div>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Update Class</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================================= --}}
    {{-- 5. FACULTY MODALS (AJAX with File Upload) --}}
    {{-- ======================================================================= --}}

    <div id="addFacultyModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center flex-shrink-0">
                <h3 class="text-white font-bold uppercase text-sm">Add Faculty</h3>
               <button onclick="openAddFaculty()" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="handleAjaxSubmit(event, 'addFacultyModal', renderFaculty)" action="{{ route('admin.faculty.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="department_id" id="add-faculty-dept-id">
                <div class="p-6 space-y-4 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Faculty Code</label><input name="faculty_code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Email</label><input type="email" name="email" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">First Name</label><input name="first_name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Last Name</label><input name="last_name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Contact No.</label><input name="contact_no" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="0912..."></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Profile Picture</label><input type="file" name="profile_picture" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#800000] file:text-white hover:file:bg-[#660000]"></div>
                    </div>
                    <div class="border-t pt-4">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Qualified Subjects</label>
                        <div id="faculty-subject-list" class="h-32 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50 text-sm grid grid-cols-1 gap-1"></div>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Save Faculty</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editFacultyModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center flex-shrink-0">
                <h3 class="text-white font-bold uppercase text-sm">Edit Faculty</h3>
                <button onclick="toggleModal('editFacultyModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editFacultyForm" onsubmit="handleAjaxSubmit(event, 'editFacultyModal', renderFaculty)" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">First Name</label><input id="edit-faculty-fname" name="first_name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Last Name</label><input id="edit-faculty-lname" name="last_name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                    </div>
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Email</label><input type="email" id="edit-faculty-email" name="email" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Contact No.</label><input id="edit-faculty-contact" name="contact_no" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none"></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Change Picture</label><input type="file" name="profile_picture" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#800000] file:text-white hover:file:bg-[#660000]"></div>
                    </div>
                    <div class="border-t pt-4">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Qualified Subjects</label>
                        <div id="edit-faculty-subject-list" class="h-32 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50 text-sm grid grid-cols-1 gap-1"></div>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Update Faculty</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================================= --}}
    {{-- 6. STUDENT MODALS (AJAX) --}}
    {{-- ======================================================================= --}}

    <div id="addStudentModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center flex-shrink-0">
                <h3 class="text-white font-bold uppercase text-sm">Add Student</h3>
                <button onclick="toggleModal('addStudentModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="handleAjaxSubmit(event, 'addStudentModal', renderStudents)" action="{{ route('admin.students.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Student Number</label><input name="student_number" id="add_student_number" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Email</label><input type="email" name="email" id="add_student_email" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">First Name</label><input name="first_name" id="add_student_fname" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Middle Name</label><input name="middle_name" id="add_student_mname" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Last Name</label><input name="last_name" id="add_student_lname" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Suffix</label><input name="suffix" id="add_student_suffix" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none"></div>
                    </div>
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Contact Number</label><input name="contact_no" id="add_student_contact" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label><select id="add_student_year" onchange="filterStudentSections(this.value, 'add_student_section')" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none cursor-pointer">
                                <option value="">Select Year</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Section / Block</label><select name="section_id" id="add_student_section" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none cursor-pointer disabled:bg-gray-100" disabled>
                                <option value="">Select Year First</option>
                            </select></div>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Add Student</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editStudentModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100 max-h-[90vh] flex flex-col">
            <div class="bg-[#800000] px-6 py-4 flex justify-between items-center flex-shrink-0">
                <h3 class="text-white font-bold uppercase text-sm">Edit Student</h3>
                <button onclick="toggleModal('editStudentModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editStudentForm" onsubmit="handleAjaxSubmit(event, 'editStudentModal', renderStudents)" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 overflow-y-auto">
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Student Number</label><input id="edit-student-number" class="w-full p-2 border border-gray-200 rounded-lg text-sm bg-gray-100 text-gray-500 cursor-not-allowed" readonly></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">First Name</label><input name="first_name" id="edit-student-fname" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Middle Name</label><input name="middle_name" id="edit-student-mname" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Last Name</label><input name="last_name" id="edit-student-lname" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Suffix</label><input name="suffix" id="edit-student-suffix" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none"></div>
                    </div>
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Email</label><input type="email" name="email" id="edit-student-email" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required></div>
                    <div><label class="text-[10px] font-bold text-gray-400 uppercase">Contact Number</label><input name="contact_no" id="edit-student-contact" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label><select id="edit-student-year" onchange="filterStudentSections(this.value, 'edit-student-section')" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none cursor-pointer">
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select></div>
                        <div><label class="text-[10px] font-bold text-gray-400 uppercase">Section / Block</label><select name="section_id" id="edit-student-section" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none cursor-pointer"></select></div>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">Update Student</button>
                </div>
            </form>
        </div>
    </div>

</main>

<script>
    // ==========================================
    // 1. STATE & INITIALIZATION
    // ==========================================
    const selectionView = document.getElementById('selectionView');
    const departmentDrillDownView = document.getElementById('departmentDrillDownView');
    const programDrillDownView = document.getElementById('programDrillDownView');
    const content = document.getElementById('drillDownContent');

    // Load Data from Laravel
    let allDepartments = @json($departments) || [];

    // Active State Trackers
    let currentDepartmentId = null;
    let activeDeptCode = null;
    let activeDeptName = null;
    let activeProgram = null;

    // ==========================================
    // 2. NAVIGATION & VIEW SWITCHING
    // ==========================================

    function openDepartmentManage(code, name, id) {
        activeDeptCode = code;
        activeDeptName = name;
        currentDepartmentId = id;

        document.getElementById('deptTitleCode').innerText = code;
        document.getElementById('deptTitleName').innerText = name;
        document.getElementById('program-dept-target').innerText = name;

        // Context IDs for hidden inputs
        document.getElementById('add-program-dept-id').value = id;
        document.getElementById('add-faculty-dept-id').value = id;

        renderProgramsTable();

        selectionView.classList.add('hidden');
        departmentDrillDownView.classList.remove('hidden');
        programDrillDownView.classList.add('hidden');
    }

    function closeDepartmentManage() {
        selectionView.classList.remove('hidden');
        departmentDrillDownView.classList.add('hidden');
        activeDeptCode = null;
        currentDepartmentId = null;
    }

    function openProgramManage(progId) {
        const dept = allDepartments.find(d => d.id === currentDepartmentId);
        const program = dept.courses.find(c => c.id === progId);

        if (!program) return console.error("Program not found!");

        activeProgram = program.code;

        // Context IDs for hidden inputs
        document.getElementById('add-subject-dept-id').value = currentDepartmentId;
        document.getElementById('add-subject-course-id').value = progId;
        document.getElementById('add-class-course-id').value = progId;

        departmentDrillDownView.classList.add('hidden');
        programDrillDownView.classList.remove('hidden');
        document.getElementById('viewSub').innerText = activeDeptCode + ' > ' + program.name;

        switchTab('subjects');
    }

    function closeProgramManage() {
        programDrillDownView.classList.add('hidden');
        departmentDrillDownView.classList.remove('hidden');
        activeProgram = null;
    }

    function switchTab(tabName) {
        ['subjects', 'faculty', 'classes', 'students'].forEach(t => {
            const el = document.getElementById(`tab-${t}`);
            if (el) {
                el.classList.remove('text-[#800000]', 'scale-105');
                el.classList.add('text-gray-400');
            }
        });
        const activeTab = document.getElementById(`tab-${tabName}`);
        if (activeTab) {
            activeTab.classList.remove('text-gray-400');
            activeTab.classList.add('text-[#800000]', 'scale-105');
        }

        if (tabName === 'subjects') renderSubjects();
        else if (tabName === 'classes') renderClasses();
        else if (tabName === 'faculty') renderFaculty();
        else if (tabName === 'students') renderStudents();
    }

    // ==========================================
    // 3. GENERIC FORM HANDLER (THE KEY TO YOUR REQUEST)
    // ==========================================

    function handleAjaxSubmit(event, modalId, refreshCallback) {
        event.preventDefault(); // Stop page reload
        const form = event.target;
        const formData = new FormData(form);
        const url = form.action;
        const method = form.getAttribute('method') || 'POST';

        fetch(url, {
                method: method
                , headers: {
                    // 'Content-Type': 'multipart/form-data', // Do NOT set this manually with FormData
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    , 'Accept': 'application/json'
                }
                , body: formData
            })
            .then(async res => {
                if (!res.ok) {
                    const text = await res.text();
                    try {
                        const json = JSON.parse(text);
                        throw new Error(json.message || res.statusText);
                    } catch (e) {
                        throw new Error(text || res.statusText);
                    }
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // 1. Update Local State (Manual Update for immediate UI feedback)
                    updateLocalData(data);

                    // 2. Refresh UI
                    if (refreshCallback) refreshCallback();

                    // 3. Close Modal
                    toggleModal(modalId, false);
                    form.reset();

                    // 4. Success Message (with Password if available)
                    if (data.generated_password) {
                        Swal.fire({
                            title: 'Success!'
                            , html: `<p>Account created.</p><div class="bg-gray-100 p-4 rounded text-center mt-2"><span class="text-xs text-gray-500 uppercase font-bold">Password</span><div class="text-2xl font-mono font-bold text-[#800000] select-all">${data.generated_password}</div></div>`
                            , icon: 'success'
                            , confirmButtonColor: '#800000'
                        });
                    } else {
                        Swal.fire('Success', data.message || 'Operation successful.', 'success');
                    }
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', err.message, 'error');
            });
    }

    // Helper to update the 'allDepartments' array so lists refresh without page reload
    function updateLocalData(data) {
        const dept = allDepartments.find(d => d.id === currentDepartmentId);

        // Course Update
        if (data.course) {
            if (!dept.courses) dept.courses = [];
            const idx = dept.courses.findIndex(c => c.id === data.course.id);
            if (idx !== -1) dept.courses[idx] = data.course;
            else dept.courses.push(data.course);
        }

        // Faculty Update
        if (data.faculty) {
            if (!dept.faculties) dept.faculties = [];
            const idx = dept.faculties.findIndex(f => f.id === data.faculty.id);
            if (idx !== -1) dept.faculties[idx] = data.faculty;
            else dept.faculties.push(data.faculty);
        }

        // Subject Update
        if (data.subject) {
            const course = dept.courses.find(c => c.code === activeProgram);
            if (course) {
                if (!course.subjects) course.subjects = [];
                const idx = course.subjects.findIndex(s => s.id === data.subject.id);
                if (idx !== -1) course.subjects[idx] = data.subject;
                else course.subjects.push(data.subject);
            }
        }

        // Section Update
        if (data.section) {
            const course = dept.courses.find(c => c.code === activeProgram);
            if (course) {
                if (!course.class_sections) course.class_sections = [];
                const idx = course.class_sections.findIndex(s => s.id === data.section.id);
                if (idx !== -1) course.class_sections[idx] = data.section;
                else course.class_sections.push(data.section);
            }
        }

        // Student Update
        if (data.student) {
            const course = dept.courses.find(c => c.code === activeProgram);
            const section = course.class_sections.find(s => s.id == data.student.section_id);
            if (section) {
                if (!section.students) section.students = [];
                // Check if updating
                const existingIdx = section.students.findIndex(s => s.id == data.student.id);
                // Also check other sections if student moved
                course.class_sections.forEach(s => {
                    if (s.students) s.students = s.students.filter(st => st.id != data.student.id);
                });

                // Add helper
                data.student.section_name = `Year ${section.year_level} - ${section.block}`;
                section.students.push(data.student);
            }
        }
    }

    // ==========================================
    // 4. RENDERERS & HELPERS
    // ==========================================

    function renderProgramsTable() {
        const tbody = document.getElementById('programsTableBody');
        const emptyMsg = document.getElementById('noProgramsMsg');
        tbody.innerHTML = '';
        const deptData = allDepartments.find(d => d.id === currentDepartmentId);
        const programs = deptData ? deptData.courses : [];

        if (programs.length === 0) emptyMsg.classList.remove('hidden');
        else {
            emptyMsg.classList.add('hidden');
            programs.forEach(prog => {
                const tr = document.createElement('tr');
                tr.className = "bg-white border-b border-gray-100 hover:bg-gray-50 transition";
                const safeProg = JSON.stringify(prog).replace(/"/g, '&quot;');
                tr.innerHTML = `
                    <td class="px-6 py-4 font-black text-[#800000] text-sm">${prog.code}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-700">${prog.name}</td>
                    <td class="px-6 py-4 text-right flex justify-end space-x-2">
                        <button onclick="openProgramManage(${prog.id})" class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase flex items-center px-2"><i class="fa-solid fa-list-check mr-1"></i> Manage</button>
                        <button onclick='openEditProgram(${safeProg})' class="text-green-500 hover:text-green-700 px-2"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button onclick="deleteProgram(${prog.id})" class="text-gray-300 hover:text-red-500 px-2"><i class="fa-solid fa-trash"></i></button>
                    </td>`;
                tbody.appendChild(tr);
            });
        }
    }

    function renderSubjects() {
        const dept = allDepartments.find(d => d.id === currentDepartmentId);
        const course = dept ? dept.courses.find(c => c.code === activeProgram) : null;
        const subjects = (course && course.subjects) ? course.subjects : [];
        const html = subjects.map(sub => {
            const safeSub = JSON.stringify(sub).replace(/"/g, '&quot;');
            return `<tr class="hover:bg-gray-50 transition border-b border-gray-100"><td class="px-6 py-4 font-bold text-[#800000]">${sub.subject_code}</td><td class="px-6 py-4">${sub.name}<div class="text-[10px] text-gray-400 font-bold mt-1">${sub.credits} Units</div></td><td class="px-6 py-4">${sub.year_level}</td><td class="px-6 py-4 text-right flex justify-end space-x-2"><button onclick='openEditSubject(${safeSub})' class="text-green-500 hover:text-green-700 px-2"><i class="fa-solid fa-pen-to-square"></i></button><button onclick="deleteSubject(${sub.id})" class="text-gray-300 hover:text-red-500 px-2"><i class="fa-solid fa-trash"></i></button></td></tr>`;
        }).join('');
        document.getElementById('drillDownContent').innerHTML = `<div class="flex justify-between mb-4"><h4 class="font-bold text-gray-800">Subjects for ${activeProgram}</h4><button onclick="toggleModal('addSubjectModal', true)" class="bg-[#800000] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase shadow hover:bg-[#660000] flex items-center"><i class="fa-solid fa-plus mr-2"></i> Add Subject</button></div><div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm"><table class="w-full text-sm text-left"><thead class="bg-gray-100 text-xs uppercase font-bold text-gray-500"><tr><th class="px-6 py-3">Code</th><th class="px-6 py-3">Description</th><th class="px-6 py-3">Year</th><th class="px-6 py-3 text-right">Action</th></tr></thead><tbody class="divide-y divide-gray-100">${html}</tbody></table></div>`;
    }

    function renderClasses() {
        const dept = allDepartments.find(d => d.id === currentDepartmentId);
        const course = dept ? dept.courses.find(c => c.code === activeProgram) : null;
        const sections = (course && course.class_sections) ? course.class_sections : [];
        const html = sections.map(sec => {
            const safeSec = JSON.stringify(sec).replace(/"/g, '&quot;');
            const count = sec.class_offerings ? sec.class_offerings.length : 0;
            return `<tr><td class="px-6 py-4 font-bold text-[#800000]">${sec.full_name || (sec.year_level + ' - ' + sec.block)}</td><td class="px-6 py-4">${sec.year_level}</td><td class="px-6 py-4 text-xs text-gray-500">${count} Subjects Assigned</td><td class="px-6 py-4 text-right flex justify-end space-x-2"><button onclick='openEditSection(${safeSec})' class="text-green-500 hover:text-green-700 px-2"><i class="fa-solid fa-pen-to-square"></i></button><button onclick="deleteSection(${sec.id})" class="text-red-500"><i class="fa-solid fa-trash"></i></button></td></tr>`;
        }).join('');
        document.getElementById('drillDownContent').innerHTML = `<div class="flex justify-between mb-4"><h4 class="font-bold text-gray-800">Classes for ${activeProgram}</h4><button onclick="toggleModal('addClassModal', true)" class="bg-[#800000] text-white px-3 py-1 rounded text-xs">Add Class</button></div><table class="w-full text-sm text-left"><thead class="bg-gray-100 text-xs uppercase"><tr><th class="px-6 py-3">Section Name</th><th class="px-6 py-3">Year Level</th><th class="px-6 py-3">Status</th><th class="px-6 py-3 text-right">Action</th></tr></thead><tbody>${html}</tbody></table>`;
    }

    function renderFaculty() {
        const dept = allDepartments.find(d => d.id === currentDepartmentId);
        const faculties = (dept && dept.faculties) ? dept.faculties : [];

        const html = faculties.map(fac => {
            const safeFac = JSON.stringify(fac).replace(/"/g, '&quot;');

            // Generate pills for subjects
            let subjectsHtml = (fac.subjects && fac.subjects.length > 0) ?
                fac.subjects.map(sub => `<span class="inline-block bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded border border-gray-200 font-bold mr-1 mb-1">${sub.subject_code}</span>`).join('') :
                '<span class="text-gray-300 italic text-[10px]">No subjects assigned</span>';

            return `
        <tr class="hover:bg-gray-50 transition border-b border-gray-100">
            <td class="px-6 py-4 font-bold text-[#800000] align-top">${fac.faculty_code}</td>
            <td class="px-6 py-4 align-top">
                <div class="font-bold text-gray-700">${fac.first_name} ${fac.last_name}</div>
                <div class="text-[10px] text-gray-400 font-normal">${fac.email}</div>
                ${fac.contact_no ? `<div class="text-[10px] text-gray-400 font-normal"><i class="fa-solid fa-phone mr-1"></i>${fac.contact_no}</div>` : ''}
            </td>
            <td class="px-6 py-4 align-top">
                <div class="flex flex-wrap max-w-sm">${subjectsHtml}</div>
            </td>
            <td class="px-6 py-4 text-right flex justify-end space-x-2 align-top">
                <button onclick='openEditFaculty(${safeFac})' class="text-green-500 hover:text-green-700 px-2"><i class="fa-solid fa-pen-to-square"></i></button>
                <button onclick="deleteFaculty(${fac.id})" class="text-gray-300 hover:text-red-500 px-2"><i class="fa-solid fa-trash"></i></button>
            </td>
        </tr>`;
        }).join('');

        document.getElementById('drillDownContent').innerHTML = `
        <div class="flex justify-between mb-4">
            <h4 class="font-bold text-gray-800">Faculty in ${activeDeptCode}</h4>
            <button onclick="openAddFaculty()" class="bg-[#800000] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase shadow hover:bg-[#660000] flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Add Faculty
            </button>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-xs uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Code</th>
                        <th class="px-6 py-3">Info</th>
                        <th class="px-6 py-3">Qualified Subjects</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">${html}</tbody>
            </table>
        </div>
    `;
    }

    function renderStudents() {
        const dept = allDepartments.find(d => d.id === currentDepartmentId);
        const course = dept ? dept.courses.find(c => c.code === activeProgram) : null;
        let students = [];
        if (course && course.class_sections) {
            course.class_sections.forEach(sec => {
                if (sec.students) {
                    sec.students.forEach(stu => {
                        stu.section_name = sec.full_name || (sec.year_level + '-' + sec.block);
                        stu.year_level = sec.year_level;
                        stu.section_id = sec.id;
                        students.push(stu);
                    });
                }
            });
        }
        const html = students.map(stu => {
            const safeStu = JSON.stringify(stu).replace(/"/g, '&quot;');
            const fullName = `${stu.first_name} ${stu.middle_name ? stu.middle_name + ' ' : ''}${stu.last_name}${stu.suffix ? ' ' + stu.suffix : ''}`;
            return `<tr class="hover:bg-gray-50 transition border-b border-gray-100"><td class="px-6 py-4 font-bold text-[#800000]">${stu.student_number}</td><td class="px-6 py-4"><div class="font-bold text-gray-700">${fullName}</div><div class="text-[10px] text-gray-400">${stu.email}</div></td><td class="px-6 py-4">${stu.section_name}</td><td class="px-6 py-4 text-right flex justify-end space-x-2"><button onclick='openEditStudent(${safeStu})' class="text-green-500 hover:text-green-700 px-2"><i class="fa-solid fa-pen-to-square"></i></button><button onclick="deleteStudent(${stu.id})" class="text-gray-300 hover:text-red-500 px-2"><i class="fa-solid fa-trash"></i></button></td></tr>`;
        }).join('');
        document.getElementById('drillDownContent').innerHTML = `<div class="flex justify-between mb-4"><h4 class="font-bold">Students in ${activeProgram}</h4><button onclick="toggleModal('addStudentModal', true)" class="bg-[#800000] text-white px-3 py-1 rounded text-xs">Add Student</button></div><table class="w-full text-sm text-left"><thead class="bg-gray-100 text-xs uppercase"><tr><th class="px-6 py-3">Student #</th><th class="px-6 py-3">Name / Email</th><th class="px-6 py-3">Section</th><th class="px-6 py-3 text-right">Action</th></tr></thead><tbody>${html}</tbody></table>`;
    }

    // ==========================================
    // 5. EDIT HANDLERS (Pre-fill Data)
    // ==========================================

    function openEditDepartmentModal(code, name, id) {
        document.getElementById('edit-dept-code').value = code;
        document.getElementById('edit-dept-name').value = name;
        document.getElementById('editDeptForm').action = `/admin/departments/${id}`;
        toggleModal('editDeptModal', true);
    }

    function openEditProgram(prog) {
        document.getElementById('edit-program-code').value = prog.code;
        document.getElementById('edit-program-name').value = prog.name;
        document.getElementById('editProgramForm').action = `/admin/courses/${prog.id}`;
        toggleModal('editProgramModal', true);
    }

    function openEditSubject(sub) {
        document.getElementById('edit-subject-code').value = sub.subject_code;
        document.getElementById('edit-subject-name').value = sub.name;
        document.getElementById('edit-subject-year').value = sub.year_level;
        document.getElementById('edit-subject-credits').value = sub.credits;
        document.getElementById('editSubjectForm').action = `/admin/subjects/${sub.id}`;
        toggleModal('editSubjectModal', true);
    }

    function openEditSection(sec) {
        document.getElementById('edit-section-year').value = sec.year_level;
        document.getElementById('edit-section-block').value = sec.block;
        document.getElementById('editSectionForm').action = `/admin/sections/${sec.id}`;
        let currentSubjectIds = sec.class_offerings ? sec.class_offerings.map(o => o.subject_id) : [];
        loadSubjectsForClass(sec.year_level, 'edit-subject-list', currentSubjectIds);
        toggleModal('editClassModal', true);
    }

    function openEditFaculty(fac) {
        document.getElementById('edit-faculty-fname').value = fac.first_name;
        document.getElementById('edit-faculty-lname').value = fac.last_name;
        document.getElementById('edit-faculty-email').value = fac.email;
        document.getElementById('edit-faculty-contact').value = fac.contact_no || '';
        document.getElementById('editFacultyForm').action = `/admin/faculty/${fac.id}`; // Note: POST with _method=PUT is handled by FormData
        let preSelectedIds = fac.subjects ? fac.subjects.map(s => s.id) : [];
        loadFacultySubjectCheckboxes('edit-faculty-subject-list', preSelectedIds);
        toggleModal('editFacultyModal', true);
    }

    function openEditStudent(stu) {
        document.getElementById('edit-student-number').value = stu.student_number;
        document.getElementById('edit-student-fname').value = stu.first_name;
        document.getElementById('edit-student-mname').value = stu.middle_name || '';
        document.getElementById('edit-student-lname').value = stu.last_name;
        document.getElementById('edit-student-suffix').value = stu.suffix || '';
        document.getElementById('edit-student-email').value = stu.email;
        document.getElementById('edit-student-contact').value = stu.contact_no || '';

        document.getElementById('edit-student-year').value = stu.year_level;
        filterStudentSections(stu.year_level, 'edit-student-section');
        setTimeout(() => {
            document.getElementById('edit-student-section').value = stu.section_id;
        }, 50);

        document.getElementById('editStudentForm').action = `/admin/students/${stu.id}`;
        toggleModal('editStudentModal', true);
    }

    // ==========================================
    // 6. DELETE HANDLER (Standardized)
    // ==========================================

    function deleteItem(url, successCallback, title = "Are you sure?") {
        Swal.fire({
                title: title
                , text: "Cannot be undone."
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#800000'
                , confirmButtonText: 'Yes, delete!'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                            method: "DELETE"
                            , headers: {
                                "Content-Type": "application/json"
                                , "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Reload all data to ensure consistency (simpler than splicing nested arrays)
                                location.reload();
                                // OR if you want to keep the "No Reload" spirit, you must manually splice based on the ID passed.
                                // For simplicity in this unified script, I'm using reload for deletions to guarantee state accuracy.
                            } else Swal.fire('Error', data.message, 'error');
                        });
                }
            });
    }
    // Specific Delete Wrappers
    function deleteProgram(id) {
        deleteItem(`/admin/courses/${id}`, null, 'Delete Program?');
    }

    function deleteSubject(id) {
        deleteItem(`/admin/subjects/${id}`, null, 'Delete Subject?');
    }

    function deleteSection(id) {
        deleteItem(`/admin/sections/${id}`, null, 'Delete Class?');
    }

    function deleteFaculty(id) {
        deleteItem(`/admin/faculty/${id}`, null, 'Delete Faculty?');
    }

    function deleteStudent(id) {
        deleteItem(`/admin/students/${id}`, null, 'Delete Student?');
    }

    function confirmDeleteDept(id) {
        Swal.fire({
            title: 'Delete Dept?'
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#800000'
        }).then((r) => {
            if (r.isConfirmed) document.getElementById(`delete-dept-form-${id}`).submit();
        });
    }

    // ==========================================
    // 7. HELPER LOGIC (Checkbox Loaders)
    // ==========================================

function loadSubjectsForClass(year, containerId, currentOfferings = []) {
    const container = document.getElementById(containerId);
    if (!year) { container.innerHTML = '<p class="text-gray-400 italic text-xs text-center mt-4">Enter a Year Level.</p>'; return; }

    const dept = allDepartments.find(d => d.id === currentDepartmentId);
    const course = dept.courses.find(c => c.code === activeProgram);
    
    // Filter subjects by year
    const subjects = (course && course.subjects) ? course.subjects.filter(s => s.year_level == year) : [];

    if (subjects.length === 0) {
        container.innerHTML = `<p class="text-red-400 italic text-xs text-center mt-4">No subjects found for Year ${year}</p>`;
        return;
    }

    container.innerHTML = subjects.map(sub => {
        // Check if this subject is currently assigned (for Edit Mode)
        const existingOffering = currentOfferings.find(off => off.subject_id === sub.id);
        const isSubjectChecked = existingOffering ? 'checked' : '';
        const assignedFacultyId = existingOffering ? existingOffering.faculty_id : 'TBA';

        // Generate Radio Buttons for Faculty
        // Default option is "TBA" (null)
        let facultyRadios = `
            <label class="flex items-center space-x-2 text-xs text-gray-500 cursor-pointer">
                <input type="radio" name="faculty_for[${sub.id}]" value="TBA" class="accent-gray-500" ${assignedFacultyId === 'TBA' || !assignedFacultyId ? 'checked' : ''}>
                <span>TBA (No Instructor)</span>
            </label>
        `;

        if (sub.faculties && sub.faculties.length > 0) {
            facultyRadios += sub.faculties.map(fac => `
                <label class="flex items-center space-x-2 text-xs text-gray-700 cursor-pointer">
                    <input type="radio" name="faculty_for[${sub.id}]" value="${fac.id}" class="accent-[#800000]" ${assignedFacultyId == fac.id ? 'checked' : ''}>
                    <span>${fac.first_name} ${fac.last_name}</span>
                </label>
            `).join('');
        } else {
            facultyRadios += `<div class="text-[10px] text-red-400 italic ml-5">No qualified faculty found.</div>`;
        }

        // Return the Card HTML
        return `
        <div class="border border-gray-100 rounded-lg p-2 mb-2 bg-white">
            <label class="flex items-center space-x-2 cursor-pointer mb-2 border-b border-gray-50 pb-1">
                <input type="checkbox" name="subject_ids[]" value="${sub.id}" class="accent-[#800000] font-bold" ${isSubjectChecked} 
                    onchange="toggleFacultyRadios(this, 'radios-${sub.id}')">
                <div>
                    <div class="font-bold text-xs text-[#800000]">${sub.subject_code}</div>
                    <div class="text-[10px] text-gray-500">${sub.name}</div>
                </div>
            </label>

            <div id="radios-${sub.id}" class="${isSubjectChecked ? '' : 'hidden opacity-50 pointer-events-none'} pl-6 space-y-1 transition-all">
                <p class="text-[10px] font-bold uppercase text-gray-300">Select Instructor:</p>
                ${facultyRadios}
            </div>
        </div>`;
    }).join('');
}

// Helper to disable radios if subject is not selected
function toggleFacultyRadios(checkbox, targetId) {
    const target = document.getElementById(targetId);
    if (checkbox.checked) {
        target.classList.remove('hidden', 'opacity-50', 'pointer-events-none');
    } else {
        target.classList.add('hidden', 'opacity-50', 'pointer-events-none');
    }
}
    function openAddFaculty() {
        // 1. Clear the form first (optional but good for UX)
        const form = document.querySelector('#addFacultyModal form');
        if (form) form.reset();

        // 2. Ensure the hidden Department ID is set
        document.getElementById('add-faculty-dept-id').value = currentDepartmentId;

        // 3. LOAD THE CHECKBOXES (This is the missing piece!)
        loadFacultySubjectCheckboxes('faculty-subject-list');

        // 4. Show the modal
        toggleModal('addFacultyModal', true);
    }

    function loadFacultySubjectCheckboxes(containerId, preSelectedIds = []) {
        const container = document.getElementById(containerId);

        // 1. Get the current Department
        const dept = allDepartments.find(d => d.id === currentDepartmentId);

        if (!dept || !dept.courses) {
            container.innerHTML = '<p class="text-gray-400 text-xs italic p-2">No department data found.</p>';
            return;
        }

        // 2. Collect ALL subjects from ALL courses in this department
        let allSubjects = [];
        dept.courses.forEach(c => {
            if (c.subjects) {
                allSubjects = [...allSubjects, ...c.subjects];
            }
        });

        if (allSubjects.length === 0) {
            container.innerHTML = '<p class="text-gray-400 text-xs italic p-2">No subjects created for this department yet.</p>';
            return;
        }

        // 3. Remove Duplicates (Unique by ID)
        // This prevents seeing "Math 101" five times if it's in five different courses
        const uniqueSubjects = Array.from(new Set(allSubjects.map(s => s.id)))
            .map(id => allSubjects.find(s => s.id === id));

        // 4. Render Checkboxes
        container.innerHTML = uniqueSubjects.map(sub => {
            const isChecked = preSelectedIds.includes(sub.id) ? 'checked' : '';
            return `
            <label class="flex items-center space-x-2 p-2 hover:bg-gray-100 border-b border-gray-50 last:border-0 rounded cursor-pointer">
                <input type="checkbox" name="subject_ids[]" value="${sub.id}" class="accent-[#800000]" ${isChecked}>
                <div>
                    <div class="font-bold text-xs text-gray-700">${sub.subject_code}</div>
                    <div class="text-[10px] text-gray-500">${sub.name}</div>
                </div>
            </label>`;
        }).join('');
    }

    function filterStudentSections(year, targetId) {
        const sectionSelect = document.getElementById(targetId);
        if (!year) {
            sectionSelect.innerHTML = '<option value="">Select Year First</option>';
            sectionSelect.disabled = true;
            return;
        }
        const dept = allDepartments.find(d => d.id === currentDepartmentId);
        const course = dept ? dept.courses.find(c => c.code === activeProgram) : null;
        const sections = (course && course.class_sections) ? course.class_sections.filter(s => s.year_level == year) : [];
        if (sections.length === 0) {
            sectionSelect.innerHTML = '<option value="">No blocks found</option>';
            sectionSelect.disabled = true;
        } else {
            sectionSelect.disabled = false;
            sectionSelect.innerHTML = '<option value="">Select Block</option>' + sections.map(sec => `<option value="${sec.id}">Block ${sec.block}</option>`).join('');
        }
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

</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

</style>
@endsection
