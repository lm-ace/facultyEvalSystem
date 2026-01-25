@extends('layouts.app')

@section('content')

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
<main class="pt-32 pb-20 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 md:px-6 max-w-7xl">

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate-fade-in text-xs md:text-sm">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check mr-2"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm animate-fade-in text-xs md:text-sm">
            <div class="flex items-center mb-1">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                <p class="font-bold">Action Failed</p>
            </div>
            <ul class="list-disc list-inside ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex flex-col lg:grid lg:grid-cols-4 gap-6 md:gap-8">
            
            <div class="lg:col-span-3 space-y-6">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-3 md:gap-4">
                    <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border-l-4 md:border-l-8 border-[#800000] w-full md:w-auto flex-1">
                        <h2 class="text-xl md:text-3xl font-bold text-gray-800 mb-1">Evaluation Criteria</h2>
                        <p class="text-gray-500 text-[10px] md:text-sm">Manage evaluation questions grouped by category.</p>
                    </div>

                    <button onclick="showAddCategoryModal()" class="w-full md:w-auto bg-white border border-[#800000] text-[#800000] hover:bg-red-50 px-4 py-3 md:px-6 md:py-4 rounded-xl text-xs font-bold uppercase shadow-sm transition active:scale-95 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-folder-plus text-base md:text-lg"></i> Add Category
                    </button>
                </div>

                @forelse($sections as $section)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-4 py-3 md:px-5 md:py-4 flex items-center justify-between text-white gap-2">
                        <div class="flex items-center gap-2 md:gap-3 overflow-hidden">
                            <div class="bg-white/20 p-1.5 md:p-2 rounded-lg flex-shrink-0">
                                <i class="fa-solid fa-layer-group text-xs md:text-sm"></i>
                            </div>
                            <h3 class="font-bold text-xs md:text-sm uppercase tracking-widest leading-tight truncate">
                                <span class="opacity-70 text-[9px] md:text-[10px] block">Section {{ $section->section_number }}</span>
                                {{ $section->section_name }}
                            </h3>
                        </div>
                        
                        <div class="flex items-center bg-black/20 rounded-lg p-1 flex-shrink-0">
                            <button onclick="showAddQuestionModal({{ $section->id }})" class="hover:bg-white/20 text-white p-1.5 md:p-2 rounded-md transition" title="Add Question">
                                <i class="fa-solid fa-plus text-[10px] md:text-xs"></i>
                            </button>
                            <div class="w-px h-3 md:h-4 bg-white/20 mx-0.5 md:mx-1"></div>
                            <button onclick='showEditCategoryModal(@json($section))' class="hover:bg-white/20 text-white p-1.5 md:p-2 rounded-md transition" title="Edit Section">
                                <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                            </button>
                            <button onclick="showDeleteCategoryModal({{ $section->id }}, '{{ $section->section_name }}')" class="hover:bg-red-600 text-white p-1.5 md:p-2 rounded-md transition" title="Delete Section">
                                <i class="fa-solid fa-trash text-[10px] md:text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs md:text-sm min-w-[300px]">
                            <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b text-[10px] md:text-xs">
                                <tr>
                                    <th class="px-4 py-3 md:px-6 md:py-4 w-3/4">Question</th>
                                    <th class="px-2 py-3 md:px-6 md:py-4 w-1/4 text-center">Edit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($section->items as $item)
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-4 py-3 md:px-6 md:py-4 font-bold text-gray-700 leading-relaxed">
                                        {{ $item->question_text }}
                                    </td>
                                    <td class="px-2 py-3 md:px-6 md:py-4 text-center">
                                        <button onclick='showEditQuestionModal(@json($item))' class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-1.5 md:p-2 rounded-lg transition">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-6 text-center text-gray-400 italic bg-gray-50/50 text-xs">
                                        No questions added yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @empty
                <div class="bg-white p-8 rounded-2xl shadow-sm text-center border-2 border-dashed border-gray-300">
                    <div class="text-gray-200 text-5xl md:text-7xl mb-3"><i class="fa-regular fa-folder-open"></i></div>
                    <p class="text-gray-500 font-bold text-sm md:text-lg">No criteria found.</p>
                    <p class="text-gray-400 text-xs mt-1">Tap "Add Category" to start.</p>
                </div>
                @endforelse

            </div>

            <div class="lg:col-span-1 space-y-6 lg:mt-[100px]">
                <div class="bg-white p-5 md:p-6 rounded-2xl shadow-md border border-gray-100 sticky top-24">
                    <h4 class="font-bold text-gray-800 text-[10px] md:text-xs mb-4 flex items-center uppercase tracking-widest border-b pb-2">
                        <i class="fa-solid fa-chart-simple mr-2 text-[#800000]"></i> Summary
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-gray-50 p-2.5 rounded-xl">
                            <span class="text-xs text-gray-500 font-bold">Categories</span>
                            <span class="font-black text-[#800000] text-base">{{ isset($sections) ? $sections->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-gray-50 p-2.5 rounded-xl">
                            <span class="text-xs text-gray-500 font-bold">Total Questions</span>
                            <span class="font-black text-gray-800 text-base">{{ $totalQuestions ?? 0 }}</span>
                        </div>
                    </div>

                    <h4 class="font-bold text-gray-800 text-[10px] md:text-xs mt-6 mb-3 flex items-center uppercase tracking-widest border-b pb-2">
                        <i class="fa-solid fa-scale-balanced mr-2 text-[#800000]"></i> Legend
                    </h4>
                    <div class="text-[10px] text-gray-500 space-y-1.5 font-medium">
                        <div class="flex justify-between"><span>5 - Strongly Agree</span><span class="text-emerald-600 font-bold">Excellent</span></div>
                        <div class="flex justify-between"><span>4 - Agree</span><span class="text-green-600 font-bold">Very Good</span></div>
                        <div class="flex justify-between"><span>3 - Neutral</span><span class="text-yellow-600 font-bold">Satisfactory</span></div>
                        <div class="flex justify-between"><span>2 - Disagree</span><span class="text-orange-600 font-bold">Fair</span></div>
                        <div class="flex justify-between"><span>1 - Strongly Disagree</span><span class="text-red-600 font-bold">Poor</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="addCategoryModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">
        <div class="bg-[#800000] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Add Category</h3>
            <button onclick="hideModal('addCategoryModal')" class="text-white/80 hover:text-white transition p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <form action="{{ route('admin.criteria.section.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Category Name</label>
                        <input name="section_name" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" placeholder="e.g., Instructional Competence" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Section Number</label>
                        <input type="number" name="section_number" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none" placeholder="e.g., 1" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-[#660000] transition-all transform active:scale-[0.98] mt-6">Save Category</button>
            </form>
        </div>
    </div>
</div>

<div id="editCategoryModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">
        <div class="bg-blue-600 px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Edit Category</h3>
            <button onclick="hideModal('editCategoryModal')" class="text-white/80 hover:text-white transition p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Category Name</label>
                        <input name="section_name" id="editCategoryName" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-blue-600 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Section Number</label>
                        <input type="number" name="section_number" id="editCategoryNumber" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-blue-600 outline-none transition-all" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-blue-700 transition-all transform active:scale-[0.98] mt-6">Update Category</button>
            </form>
        </div>
    </div>
</div>

<div id="addQuestionModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">
        <div class="bg-[#E6A600] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-[#800000] font-black uppercase text-sm tracking-wider">Add Question</h3>
            <button onclick="hideModal('addQuestionModal')" class="text-[#800000]/70 hover:text-[#800000] transition p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <form action="{{ route('admin.criteria.item.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Category</label>
                        <select name="section_id" id="addQuestionCategorySelect" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#E6A600] cursor-pointer" required>
                            <option value="">Select Category</option>
                            @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Question Text</label>
                        <textarea name="question_text" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-medium h-32 focus:border-[#E6A600] outline-none transition-all resize-none" placeholder="Enter the evaluation question here..." required></textarea>
                    </div>
                </div>
                <button type="submit" class="w-full bg-[#E6A600] text-[#800000] py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-yellow-500 transition-all transform active:scale-[0.98] mt-6">Save Question</button>
            </form>
        </div>
    </div>
</div>

<div id="editQuestionModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">
        <div class="bg-blue-600 px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Edit Question</h3>
            <button onclick="hideModal('editQuestionModal')" class="text-white/80 hover:text-white transition p-1"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <form id="editQuestionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Question Text</label>
                        <textarea name="question_text" id="editQuestionText" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-medium h-32 focus:border-blue-600 outline-none transition-all resize-none" required></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="confirmDelete()" class="w-1/3 bg-red-100 text-red-600 py-3 rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-red-200 transition-all"><i class="fa-solid fa-trash mr-2"></i> Delete</button>
                    <button type="submit" class="w-2/3 bg-blue-600 text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-blue-700 transition-all transform active:scale-[0.98]">Update</button>
                </div>
            </form>
            <form id="deleteQuestionForm" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>
</div>

<div id="deleteCategoryModal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all relative flex flex-col p-6 text-center">
        <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-2">Delete Category?</h3>
        <p class="text-gray-600 text-sm font-bold mb-1" id="deleteCategoryNameDisplay"></p>
        <div class="text-red-600 text-xs mb-6 bg-red-50 p-3 rounded-xl border border-red-100 font-medium">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> Warning: This will delete ALL questions inside this category.
        </div>
        
        <form id="deleteCategoryForm" method="POST" class="flex space-x-3">
            @csrf
            @method('DELETE')
            <button type="button" onclick="hideModal('deleteCategoryModal')" class="flex-1 py-3 bg-gray-100 text-gray-500 font-bold rounded-xl text-sm transition hover:bg-gray-200">Cancel</button>
            <button type="submit" class="flex-1 py-3 bg-red-600 text-white font-bold rounded-xl text-sm transition hover:bg-red-700 shadow-md">Yes, Delete</button>
        </form>
    </div>
</div>

<div id="logoutModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center">
        <div class="bg-[#800000]/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-right-from-bracket text-[#800000] text-2xl"></i>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-2">Admin Logout</h3>
        <p class="text-gray-500 text-xs mb-6">Confirm if you want to end your current session.</p>
        <form action="{{ route('logout') }}" method="POST" class="flex flex-col space-y-3">
            @csrf
            <button type="submit" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-md hover:bg-[#660000] transition">Confirm Logout</button>
            <button type="button" onclick="hideModal('logoutModal')" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition">Cancel</button>
        </form>
    </div>
</div>

<script>
    function hideModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function showAddCategoryModal() {
        document.getElementById('addCategoryModal').classList.remove('hidden');
        document.getElementById('addCategoryModal').classList.add('flex');
    }

    function showAddQuestionModal(preselectedSectionId = null) {
        const select = document.getElementById('addQuestionCategorySelect');
        if (preselectedSectionId) {
            select.value = preselectedSectionId;
        } else {
            select.value = "";
        }
        document.getElementById('addQuestionModal').classList.remove('hidden');
        document.getElementById('addQuestionModal').classList.add('flex');
    }

    function showEditCategoryModal(section) {
        document.getElementById('editCategoryName').value = section.section_name;
        document.getElementById('editCategoryNumber').value = section.section_number;
        document.getElementById('editCategoryForm').action = `/admin/criteria/section/${section.id}`;
        document.getElementById('editCategoryModal').classList.remove('hidden');
        document.getElementById('editCategoryModal').classList.add('flex');
    }

    function showDeleteCategoryModal(id, name) {
        document.getElementById('deleteCategoryNameDisplay').innerText = `"${name}"`;
        document.getElementById('deleteCategoryForm').action = `/admin/criteria/section/${id}`;
        document.getElementById('deleteCategoryModal').classList.remove('hidden');
        document.getElementById('deleteCategoryModal').classList.add('flex');
    }

    function showEditQuestionModal(item) {
        document.getElementById('editQuestionText').value = item.question_text;
        document.getElementById('editQuestionForm').action = `/admin/criteria/item/${item.id}`;
        document.getElementById('deleteQuestionForm').action = `/admin/criteria/item/${item.id}`;
        document.getElementById('editQuestionModal').classList.remove('hidden');
        document.getElementById('editQuestionModal').classList.add('flex');
    }

    function confirmDelete() {
        if(confirm('Delete this question?')) {
            document.getElementById('deleteQuestionForm').submit();
        }
    }
    
    function showLogoutModal() { 
        document.getElementById('logoutModal').classList.remove('hidden'); 
        document.getElementById('logoutModal').classList.add('flex');
    }
</script>

<style>
    /* Hide scrollbar for sub-nav but allow scrolling */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>
@endsection