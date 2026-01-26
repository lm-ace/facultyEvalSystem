@extends('layouts.app')

@section('title', 'Admin Departments')

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

<main class="pt-36 pb-20 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 md:px-6 max-w-7xl">

        @if($errors->any())
        <div class="mb-4">
            @foreach($errors->all() as $error)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-2 text-xs md:text-sm font-bold flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ $error }}
            </div>
            @endforeach
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-xs md:text-sm font-bold flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
        @endif

        <div id="selectionView" class="transition-all duration-300">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-gray-800 uppercase tracking-tight">Institutional Departments</h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-1">Manage departments and their academic programs.</p>
                </div>
                <button onclick="toggleModal('addDeptModal', true)" class="w-full md:w-auto bg-[#800000] text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-[#660000] transition active:scale-95 flex items-center justify-center">
                    <i class="fa-solid fa-plus mr-2"></i> Add Department
                </button>
            </div>

            <div class="p-1 mb-6">
                <div class="relative w-full md:max-w-xs">
                    <input type="search" id="departmentSearch" placeholder="Search departments..." class="w-full pl-10 pr-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#800000] focus:border-transparent transition-all shadow-sm" oninput="searchDepartments(this.value)">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden min-h-[400px]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px] tracking-wider border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 w-1/6">Code</th>
                                <th class="px-6 py-4 w-2/6">Department Name</th>
                                <th class="px-6 py-4 w-1/6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="departmentTableBody" class="text-sm font-semibold text-gray-700 divide-y divide-gray-100">
                            @forelse($departments as $dept)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-100 group">
                                <td class="px-6 py-5 text-[#800000] font-black">{{ $dept->code }}</td>
                                <td class="px-6 py-5 text-gray-600">{{ $dept->name }}</td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end space-x-1">
                                        <button onclick="openDepartmentManage('{{ e($dept->code) }}', '{{ e($dept->name) }}', {{ $dept->id }})" type="button" class="bg-blue-50 text-blue-600 hover:bg-blue-100 p-2 rounded-lg transition" title="Manage Programs">
                                            <i class="fa-solid fa-list-check"></i>
                                        </button>
                                        <button onclick="openEditDepartmentModal('{{ e($dept->code) }}', '{{ e($dept->name) }}', {{ $dept->id }})" type="button" class="bg-green-50 text-green-600 hover:bg-green-100 p-2 rounded-lg transition" title="Edit Details">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form id="delete-dept-form-{{ $dept->id }}" method="post" action="{{ route('admin.departments.destroy', $dept->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteDept({{ $dept->id }})" class="bg-red-50 text-red-500 hover:bg-red-100 p-2 rounded-lg transition" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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
        </div>

        <div id="departmentDrillDownView" class="hidden transition-all duration-300">
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-2xl md:text-3xl font-black text-[#800000] uppercase tracking-tight leading-none">Manage Department</h3>
                    <div class="flex items-center space-x-3 mt-2 bg-white px-3 py-1.5 rounded-lg border border-gray-200 w-fit shadow-sm">
                        <span id="deptTitleCode" class="text-xs text-gray-500 font-bold uppercase tracking-widest">CODE</span>
                        <span class="text-gray-300">|</span>
                        <span id="deptTitleName" class="text-xs text-gray-800 font-bold uppercase truncate max-w-[200px] md:max-w-none">Name</span>
                    </div>
                </div>
                <button onclick="closeDepartmentManage()" class="w-full md:w-auto bg-white border border-gray-200 text-gray-500 hover:text-[#800000] px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all hover:shadow-md flex items-center justify-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
                </button>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border-t-8 border-[#800000] min-h-[500px] overflow-hidden p-6 md:p-8">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-lg font-bold text-gray-800 uppercase tracking-tight">Academic Programs</h4>
                    <button onclick="toggleModal('addProgramModal', true)" class="bg-[#800000] text-white px-4 py-2.5 rounded-xl font-bold text-xs shadow-md hover:bg-[#660000] flex items-center transition active:scale-95">
                        <i class="fa-solid fa-plus mr-2"></i> Add Program
                    </button>
                </div>

                <div class="border border-gray-100 rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[500px]">
                            <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-[10px] tracking-wider border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4">Program Code</th>
                                    <th class="px-6 py-4">Program Name</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="programsTableBody" class="divide-y divide-gray-100"></tbody>
                        </table>
                    </div>
                    <div id="noProgramsMsg" class="hidden p-12 text-center text-gray-400">
                        <i class="fa-solid fa-layer-group text-3xl mb-2 opacity-30"></i>
                        <p class="text-xs font-bold uppercase">No programs added yet.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="programDrillDownView" class="hidden transition-all duration-300">
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-6 gap-4">
                <div>
                    <h3 id="viewTitle" class="text-2xl md:text-3xl font-black text-[#800000] uppercase tracking-tight leading-none">Manage Program</h3>
                    <p id="viewSub" class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1 ml-1">Department Context</p>
                </div>
                <button onclick="closeProgramManage()" class="w-full md:w-auto bg-white border border-gray-200 text-gray-500 hover:text-[#800000] px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-sm transition-all hover:shadow-md flex items-center justify-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Dept
                </button>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border-t-8 border-[#800000] min-h-[500px] overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-4 md:px-8 py-3 overflow-x-auto">
                    <div class="flex items-center space-x-6 text-xs font-bold uppercase tracking-wide text-gray-400 select-none whitespace-nowrap">
                        <button onclick="switchTab('subjects')" id="tab-subjects" class="hover:text-[#800000] transition-colors focus:outline-none py-2 border-b-2 border-transparent">Subjects</button>
                        <button onclick="switchTab('classes')" id="tab-classes" class="hover:text-[#800000] transition-colors focus:outline-none py-2 border-b-2 border-transparent">Classes</button>
                        <button onclick="switchTab('faculty')" id="tab-faculty" class="hover:text-[#800000] transition-colors focus:outline-none py-2 border-b-2 border-transparent">Faculties</button>
                        <button onclick="switchTab('students')" id="tab-students" class="hover:text-[#800000] transition-colors focus:outline-none py-2 border-b-2 border-transparent">Students</button>
                    </div>
                </div>
                <div id="drillDownContent" class="p-6 md:p-8 overflow-x-auto"></div>
            </div>
        </div>

    </div>

    <div id="addDeptModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col">
            <div class="bg-[#800000] px-5 py-3 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm tracking-wider">Add Department</h3>
                <button onclick="toggleModal('addDeptModal', false)" class="text-white/80 hover:text-white transition p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.departments.store') }}">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Department Code</label>
                        <input name="code" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all placeholder-gray-300" placeholder="e.g., CCIS" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Department Name</label>
                        <input name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all placeholder-gray-300" placeholder="e.g., College of Computer Science" required>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-[#660000] transition-all transform active:scale-[0.98] mt-2">Save Department</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editDeptModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col">
            <div class="bg-[#800000] px-5 py-3 flex justify-between items-center">
                <h3 class="text-white font-bold uppercase text-sm tracking-wider">Edit Department</h3>
                <button onclick="toggleModal('editDeptModal', false)" class="text-white/80 hover:text-white transition p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="editDeptForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Department Code</label>
                        <input id="edit-dept-code" name="code" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Department Name</label>
                        <input id="edit-dept-name" name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                    </div>
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-[#660000] transition-all transform active:scale-[0.98] mt-2">Update Department</button>
                </div>
            </form>
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

    @include('admin.modals.programs')
    @include('admin.modals.subjects')
    @include('admin.modals.sections')
    @include('admin.modals.faculties')
    @include('admin.modals.students')

</main>

<script>
    const selectionView = document.getElementById('selectionView');
    const departmentDrillDownView = document.getElementById('departmentDrillDownView');
    const programDrillDownView = document.getElementById('programDrillDownView');
    const content = document.getElementById('drillDownContent');

    let allDepartments = @json($departments) || [];
    let currentDepartmentId = null;
    let activeDeptCode = null;
    let activeDeptName = null;
    let activeProgram = null;

    function openDepartmentManage(code, name, id) {
        activeDeptCode = code;
        activeDeptName = name;
        currentDepartmentId = id;

        document.getElementById('deptTitleCode').innerText = code;
        document.getElementById('deptTitleName').innerText = name;
        document.getElementById('program-dept-target').innerText = name;

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

        document.getElementById('add-subject-dept-id').value = currentDepartmentId;
        document.getElementById('add-subject-course-id').value = progId;
        document.getElementById('add-class-course-id').value = progId;
        document.getElementById('add-student-dept-id').value = currentDepartmentId;
        document.getElementById('add-student-course-id').value = progId;
        document.getElementById('add-faculty-course-id').value = progId;

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

    function handleAjaxSubmit(event, modalId, refreshCallback) {
        event.preventDefault(); 
        const form = event.target;
        const formData = new FormData(form);
        const url = form.action;
        const method = form.getAttribute('method') || 'POST';

        fetch(url, {
                method: method
                , headers: {
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
                    updateLocalData(data);

                    if (refreshCallback) refreshCallback();

                    toggleModal(modalId, false);
                    form.reset();

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

    function updateLocalData(data) {
        const dept = allDepartments.find(d => d.id === currentDepartmentId);

        if (data.course) {
            if (!dept.courses) dept.courses = [];
            const idx = dept.courses.findIndex(c => c.id === data.course.id);
            if (idx !== -1) dept.courses[idx] = data.course;
            else dept.courses.push(data.course);
        }

        if (data.faculty) {
            if (!dept.faculties) dept.faculties = [];
            const idx = dept.faculties.findIndex(f => f.id === data.faculty.id);
            if (idx !== -1) dept.faculties[idx] = data.faculty;
            else dept.faculties.push(data.faculty);
        }

        if (data.subject) {
            const course = dept.courses.find(c => c.code === activeProgram);
            if (course) {
                if (!course.subjects) course.subjects = [];
                const idx = course.subjects.findIndex(s => s.id === data.subject.id);
                if (idx !== -1) course.subjects[idx] = data.subject;
                else course.subjects.push(data.subject);
            }
        }

        if (data.section) {
            const course = dept.courses.find(c => c.code === activeProgram);
            if (course) {
                if (!course.class_sections) course.class_sections = [];
                const idx = course.class_sections.findIndex(s => s.id === data.section.id);
                if (idx !== -1) course.class_sections[idx] = data.section;
                else course.class_sections.push(data.section);
            }
        }

        if (data.student) {
            const course = dept.courses.find(c => c.code === activeProgram);
            const section = course.class_sections.find(s => s.id == data.student.section_id);
            if (section) {
                if (!section.students) section.students = [];
                const existingIdx = section.students.findIndex(s => s.id == data.student.id);
                course.class_sections.forEach(s => {
                    if (s.students) s.students = s.students.filter(st => st.id != data.student.id);
                });
                data.student.section_name = `Year ${section.year_level} - ${section.block}`;
                section.students.push(data.student);
            }
        }
    }


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

        document.getElementById('drillDownContent').innerHTML = `
        <div class="flex justify-between mb-4">
            <h4 class="font-bold text-gray-800">Classes for ${activeProgram}</h4>
            <button onclick="openAddClass()" class="bg-[#800000] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase shadow hover:bg-[#660000] flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Add Class
            </button>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3">Section Name</th>
                    <th class="px-6 py-3">Year Level</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>${html}</tbody>
        </table>`;
    }

    function renderFaculty() {
        const dept = allDepartments.find(d => d.id === currentDepartmentId);
        const faculties = (dept && dept.faculties) ? dept.faculties : [];

        const html = faculties.map(fac => {
            const safeFac = JSON.stringify(fac).replace(/"/g, '&quot;');

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

        document.getElementById('drillDownContent').innerHTML = `
        <div class="flex justify-between mb-4">
            <h4 class="font-bold">Students in ${activeProgram}</h4>
            <button onclick="toggleModal('addStudentModal', true)" class="bg-[#800000] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase shadow hover:bg-[#660000] flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Add Student
            </button>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3">Student #</th>
                    <th class="px-6 py-3">Name / Email</th>
                    <th class="px-6 py-3">Section</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>${html}</tbody>
        </table>`;
    }

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
        document.getElementById('edit-class-dept-id').value = currentDepartmentId;

        const activeCourseId = document.getElementById('add-class-course-id').value;
        document.getElementById('edit-class-course-id').value = activeCourseId;

        let currentOfferings = sec.class_offerings ? sec.class_offerings : [];

        loadSubjectsForClass(sec.year_level, 'edit-subject-list', currentOfferings);

        toggleModal('editClassModal', true);
    }

    function openEditFaculty(fac) {
        const activeCourseId = document.getElementById('add-class-course-id').value; 

        document.getElementById('edit-faculty-fname').value = fac.first_name;
        document.getElementById('edit-faculty-mname').value = fac.middle_name || ''; 
        document.getElementById('edit-faculty-lname').value = fac.last_name;
        document.getElementById('edit-faculty-suffix').value = fac.suffix || ''; 
        document.getElementById('edit-faculty-email').value = fac.email;
        document.getElementById('edit-faculty-contact').value = fac.contact_no || '';
        
        document.getElementById('edit-faculty-course-id').value = activeCourseId; 

        const previewEl = document.getElementById('edit-faculty-preview');
        
        if (fac.profile_picture && fac.profile_picture !== 'default-avatar.png') {
            previewEl.src = `/storage/${fac.profile_picture}`;
        } else {
            previewEl.src = "{{ asset('images/default-avatar.png') }}";
        }

        document.getElementById('editFacultyForm').action = `/admin/faculty/${fac.id}`;

        document.getElementById('edit-faculty-dept-id').value = currentDepartmentId;

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
        document.getElementById('edit-student-contact').value = stu.contact_no || '';

        document.getElementById('edit-student-dept-id').value = currentDepartmentId;
        const activeCourseId = document.getElementById('add-class-course-id').value;
        document.getElementById('edit-student-course-id').value = activeCourseId;

        document.getElementById('editStudentForm').action = `/admin/students/${stu.id}`;

        document.getElementById('edit-student-year').value = stu.year_level;
        filterStudentSections(stu.year_level, 'edit-student-section');

        setTimeout(() => {
            document.getElementById('edit-student-section').value = stu.section_id;
        }, 50);

        toggleModal('editStudentModal', true);
    }

    function deleteItem(url, unusedCallback, title = "Are you sure?") {
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
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
    }

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

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


    function loadSubjectsForClass(year, containerId, currentOfferings = []) {
        const container = document.getElementById(containerId);
        if (!year) {
            container.innerHTML = '<p class="text-gray-400 italic text-xs text-center mt-4">Enter a Year Level.</p>';
            return;
        }

        const dept = allDepartments.find(d => d.id === currentDepartmentId);
        const course = dept.courses.find(c => c.code === activeProgram);

        const subjects = (course && course.subjects) ? course.subjects.filter(s => s.year_level == year) : [];

        if (subjects.length === 0) {
            container.innerHTML = `<p class="text-red-400 italic text-xs text-center mt-4">No subjects found for Year ${year}</p>`;
            return;
        }

        container.innerHTML = subjects.map(sub => {
            const existingOffering = currentOfferings.find(off => off.subject_id === sub.id);
            const isSubjectChecked = existingOffering ? 'checked' : '';
            const assignedFacultyId = existingOffering ? existingOffering.faculty_id : 'TBA';
            
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

    function toggleFacultyRadios(checkbox, targetId) {
        const target = document.getElementById(targetId);
        if (checkbox.checked) {
            target.classList.remove('hidden', 'opacity-50', 'pointer-events-none');
        } else {
            target.classList.add('hidden', 'opacity-50', 'pointer-events-none');
        }
    }

    function openAddClass() {
        const form = document.querySelector('#addClassModal form');
        if (form) form.reset();

        document.getElementById('add-class-dept-id').value = currentDepartmentId;
        document.getElementById('add-class-course-id').value = activeProgram ? 
            (allDepartments.find(d => d.id === currentDepartmentId).courses.find(c => c.code === activeProgram).id) 
            : '';

        document.getElementById('add-subject-list').innerHTML = 
            '<p class="text-gray-400 italic text-xs text-center mt-4">Enter a Year Level to see subjects.</p>';

        toggleModal('addClassModal', true);
    }

    function openAddFaculty() {
        const form = document.querySelector('#addFacultyModal form');
        if (form) form.reset();

        document.getElementById('add-faculty-dept-id').value = currentDepartmentId;

        loadFacultySubjectCheckboxes('faculty-subject-list');

        toggleModal('addFacultyModal', true);
    }

    function loadFacultySubjectCheckboxes(containerId, preSelectedIds = []) {
        const container = document.getElementById(containerId);

        const dept = allDepartments.find(d => d.id === currentDepartmentId);

        if (!dept || !dept.courses) {
            container.innerHTML = '<p class="text-gray-400 text-xs italic p-2">No department data found.</p>';
            return;
        }

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

        const uniqueSubjects = Array.from(new Set(allSubjects.map(s => s.id)))
            .map(id => allSubjects.find(s => s.id === id));

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

    function confirmLogout() {
        Swal.fire({
            title: 'Log Out?'
            , text: "You will be returned to the login screen."
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#800000'
            , confirmButtonText: 'Yes, log out!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('logout') }}"; 

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            try {
                @if(session('open_dept_id'))
                const deptIdToOpen = parseInt("{{ session('open_dept_id') }}");

                if (deptIdToOpen) {
                    const deptData = allDepartments.find(d => d.id === deptIdToOpen);

                    if (deptData) {
                        console.log("Auto-opening Department:", deptData.name);
                        openDepartmentManage(deptData.code, deptData.name, deptData.id);

                        @if(session('open_program_id'))
                        const progIdToOpen = parseInt("{{ session('open_program_id') }}");
                        if (progIdToOpen) {
                            console.log("Auto-opening Program ID:", progIdToOpen);
                            openProgramManage(progIdToOpen);

                            @if(session('open_tab'))
                            const tabToOpen = "{{ session('open_tab') }}";
                            console.log("Switching to tab:", tabToOpen);
                            switchTab(tabToOpen);
                            @endif
                        }
                        @endif
                    }
                }
                @endif
            } catch (error) {
                console.error("Auto-open logic failed:", error);
            }
        }, 100);
    });

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
