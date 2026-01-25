@extends('layouts.app')

@section('content')

{{-- NAVBAR --}}
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

{{-- SUB-NAVBAR --}}
<div class="fixed top-[48px] left-0 right-0 z-40 bg-white border-b border-gray-200 shadow-sm px-10 py-3">
    <div class="max-w-7xl mx-auto flex items-center space-x-8 text-xs font-bold uppercase tracking-widest">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-chart-pie mr-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.departments') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-sitemap mr-2"></i> Departments
        </a>
        <a href="{{ route('admin.criteria') }}" class="flex items-center text-[#800000] border-b-2 border-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-list-check mr-2"></i> Criteria
        </a>
        <a href="{{ route('admin.reports') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-file-contract mr-2"></i> Reports
        </a>
    </div>
</div>

<main class="pt-36 pb-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 max-w-7xl">

        {{-- ALERTS --}}
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check mr-2"></i>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        {{-- ERROR ALERT (For "Position Taken") --}}
        @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm animate-fade-in">
            <div class="flex items-center mb-1">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                <p class="font-bold text-sm">Action Failed</p>
            </div>
            <ul class="list-disc list-inside text-xs ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3 space-y-10">
                
                {{-- HEADER & BUTTONS --}}
                <div class="flex justify-between items-end">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-[#800000] flex-1">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Evaluation Criteria</h2>
                        <p class="text-gray-600 text-sm">Manage evaluation questions grouped by category.</p>
                    </div>

                    <div class="flex space-x-3 ml-6">
                        {{-- ADD CATEGORY BUTTON --}}
                        <button onclick="showAddCategoryModal()" class="bg-white border-2 border-[#800000] text-[#800000] hover:bg-gray-50 px-5 py-4 rounded-xl text-xs font-bold uppercase shadow-sm transition active:scale-95 flex items-center h-fit">
                            <i class="fa-solid fa-folder-plus mr-2 text-lg"></i> Add Category
                        </button>
                    </div>
                </div>

                {{-- CRITERIA SECTIONS LOOP --}}
                @forelse($sections as $section)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    {{-- SECTION HEADER --}}
                    <div class="bg-[#800000] px-6 py-3 flex items-center justify-between text-white">
                        <div class="flex items-center">
                            <i class="fa-solid fa-layer-group mr-3 opacity-80"></i>
                            <h3 class="font-bold text-sm uppercase tracking-widest">
                                Section {{ $section->section_number }}: {{ $section->section_name }}
                            </h3>
                        </div>
                        
                        {{-- SECTION ACTIONS (Top Right) --}}
                        <div class="flex items-center space-x-1">
                            {{-- Add Question to this specific section --}}
                            <button onclick="showAddQuestionModal({{ $section->id }})" class="bg-white/10 hover:bg-white/30 text-white p-1.5 rounded-lg transition" title="Add Question to this Section">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                            {{-- Edit Section --}}
                            <button onclick='showEditCategoryModal(@json($section))' class="bg-white/10 hover:bg-white/30 text-white p-1.5 rounded-lg transition" title="Edit Section Name/Position">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            {{-- Delete Section --}}
                            <button onclick="showDeleteCategoryModal({{ $section->id }}, '{{ $section->section_name }}')" class="bg-white/10 hover:bg-red-600 text-white p-1.5 rounded-lg transition" title="Delete Section">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>

                    {{-- QUESTIONS TABLE --}}
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b">
                            <tr>
                                <th class="px-6 py-3 w-3/4">Question</th>
                                <th class="px-6 py-3 w-1/4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($section->items as $item)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $item->question_text }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick='showEditQuestionModal(@json($item))' class="text-blue-600 hover:text-blue-800 transition transform hover:scale-110">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-400 italic">No questions added to this section yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @empty
                <div class="bg-white p-10 rounded-2xl shadow-sm text-center border-2 border-dashed border-gray-300">
                    <div class="text-gray-300 text-6xl mb-4"><i class="fa-regular fa-folder-open"></i></div>
                    <p class="text-gray-500 font-bold">No criteria sections found.</p>
                    <p class="text-gray-400 text-xs mt-1">Click "Add Category" to get started.</p>
                </div>
                @endforelse

            </div>

            {{-- SIDEBAR --}}
            <div class="lg:col-span-1 space-y-6 lg:mt-[112px]">
                {{-- Legend & Summary (Kept same) --}}
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                        <i class="fa-solid fa-chart-simple mr-3 text-[#800000]"></i> Summary
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <span class="text-xs text-gray-500">Categories</span>
                            <span class="font-black text-[#800000] text-sm">{{ isset($sections) ? $sections->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Total Questions</span>
                            <span class="font-black text-gray-800 text-sm">{{ $totalQuestions ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                        <i class="fa-solid fa-scale-balanced mr-3 text-[#800000]"></i> Likert Scale Legend
                    </h4>
                    <div class="text-[10px] text-gray-600 space-y-2">
                        <div class="flex justify-between"><span>5 - Strongly Agree</span><span class="font-bold text-emerald-600">Excellent</span></div>
                        <div class="flex justify-between"><span>4 - Agree</span><span class="font-bold text-green-600">Very Good</span></div>
                        <div class="flex justify-between"><span>3 - Neutral</span><span class="font-bold text-yellow-600">Satisfactory</span></div>
                        <div class="flex justify-between"><span>2 - Disagree</span><span class="font-bold text-orange-600">Fair</span></div>
                        <div class="flex justify-between"><span>1 - Strongly Disagree</span><span class="font-bold text-red-600">Poor</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- ================= MODALS ================= --}}

{{-- 1. ADD CATEGORY MODAL --}}
<div id="addCategoryModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-[#800000]">
        <h3 class="text-xl font-black text-gray-800 mb-6 text-center uppercase">Add New Category</h3>
        <form action="{{ route('admin.criteria.section.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Category Name</label>
                    <input name="section_name" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:border-[#800000] outline-none" placeholder="e.g., Instructional Competence" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Section Number</label>
                    <input type="number" name="section_number" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:border-[#800000] outline-none" placeholder="e.g., 1" required>
                    <p class="text-[9px] text-gray-400 mt-1">Must be unique.</p>
                </div>
            </div>
            <div class="flex space-x-3 mt-8">
                <button type="submit" class="flex-1 py-3 bg-[#800000] text-white font-bold rounded-xl text-sm transition hover:scale-[1.02]">Save Category</button>
                <button type="button" onclick="hideModal('addCategoryModal')" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- 2. EDIT CATEGORY MODAL (NEW) --}}
<div id="editCategoryModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-blue-600">
        <h3 class="text-xl font-black text-gray-800 mb-6 text-center uppercase">Edit Category</h3>
        
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Category Name</label>
                    <input name="section_name" id="editCategoryName" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:border-blue-600 outline-none" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Section Number</label>
                    <input type="number" name="section_number" id="editCategoryNumber" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:border-blue-600 outline-none" required>
                </div>
            </div>
            <div class="flex space-x-3 mt-8">
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl text-sm transition hover:scale-[1.02]">Update</button>
                <button type="button" onclick="hideModal('editCategoryModal')" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- 3. DELETE CATEGORY CONFIRMATION MODAL (NEW) --}}
<div id="deleteCategoryModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 text-center border-t-8 border-red-600">
        <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-2">Delete Category?</h3>
        <p class="text-gray-600 text-xs font-bold mb-1" id="deleteCategoryNameDisplay"></p>
        <p class="text-red-500 text-xs mb-8 bg-red-50 p-2 rounded border border-red-100">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> Warning: This will delete ALL questions inside this category.
        </p>
        
        <form id="deleteCategoryForm" method="POST" class="flex space-x-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex-1 py-3 bg-red-600 text-white font-bold rounded-xl text-sm transition hover:bg-red-700">Yes, Delete</button>
            <button type="button" onclick="hideModal('deleteCategoryModal')" class="flex-1 py-3 bg-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-200">Cancel</button>
        </form>
    </div>
</div>

{{-- 4. ADD QUESTION MODAL (Updated) --}}
<div id="addQuestionModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-[#E6A600]">
        <h3 class="text-xl font-black text-gray-800 mb-6 text-center uppercase">Add New Question</h3>
        <form action="{{ route('admin.criteria.item.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Category</label>
                    <select name="section_id" id="addQuestionCategorySelect" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#E6A600]" required>
                        <option value="">Select Category</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Question Text</label>
                    <textarea name="question_text" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium h-24 focus:border-[#E6A600] outline-none" placeholder="Enter question..." required></textarea>
                </div>
            </div>
            <div class="flex space-x-3 mt-8">
                <button type="submit" class="flex-1 py-3 bg-[#E6A600] text-[#800000] font-bold rounded-xl text-sm transition hover:scale-[1.02]">Save Question</button>
                <button type="button" onclick="hideModal('addQuestionModal')" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- 5. EDIT QUESTION MODAL --}}
<div id="editQuestionModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-blue-600">
        <h3 class="text-xl font-black text-gray-800 mb-6 text-center uppercase">Edit Question</h3>
        <form id="editQuestionForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Question Text</label>
                    <textarea name="question_text" id="editQuestionText" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium h-32 focus:border-blue-600 outline-none" required></textarea>
                </div>
            </div>
            <div class="flex space-x-3 mt-8">
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl text-sm transition hover:bg-blue-700">Update</button>
                <button type="button" onclick="confirmDelete()" class="py-3 px-4 bg-red-100 text-red-600 font-bold rounded-xl text-sm transition hover:bg-red-200"><i class="fa-solid fa-trash"></i></button>
                <button type="button" onclick="hideModal('editQuestionModal')" class="py-3 px-4 border-2 border-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-50">Cancel</button>
            </div>
        </form>
        <form id="deleteQuestionForm" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    </div>
</div>

{{-- 6. LOGOUT MODAL --}}
<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-shield-halved text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Admin Logout</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Confirm if you want to end your current administrative session.</p>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="flex flex-col space-y-3">
            @csrf
            <button type="submit" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98]">Confirm Logout</button>
            <button type="button" onclick="hideModal('logoutModal')" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Cancel</button>
        </form>
    </div>
</div>

<script>
    function hideModal(id) {
        document.getElementById(id).classList.replace('flex', 'hidden');
    }

    function showAddCategoryModal() {
        document.getElementById('addCategoryModal').classList.replace('hidden', 'flex');
    }

    function showAddQuestionModal(preselectedSectionId = null) {
        const select = document.getElementById('addQuestionCategorySelect');
        if (preselectedSectionId) {
            select.value = preselectedSectionId;
        } else {
            select.value = "";
        }
        document.getElementById('addQuestionModal').classList.replace('hidden', 'flex');
    }

    // --- NEW: EDIT CATEGORY ---
    function showEditCategoryModal(section) {
        document.getElementById('editCategoryName').value = section.section_name;
        document.getElementById('editCategoryNumber').value = section.section_number;
        document.getElementById('editCategoryForm').action = `/admin/criteria/section/${section.id}`;
        document.getElementById('editCategoryModal').classList.replace('hidden', 'flex');
    }

    // --- NEW: DELETE CATEGORY ---
    function showDeleteCategoryModal(id, name) {
        document.getElementById('deleteCategoryNameDisplay').innerText = `"${name}"`;
        document.getElementById('deleteCategoryForm').action = `/admin/criteria/section/${id}`;
        document.getElementById('deleteCategoryModal').classList.replace('hidden', 'flex');
    }

    function showEditQuestionModal(item) {
        document.getElementById('editQuestionText').value = item.question_text;
        document.getElementById('editQuestionForm').action = `/admin/criteria/item/${item.id}`;
        document.getElementById('deleteQuestionForm').action = `/admin/criteria/item/${item.id}`;
        document.getElementById('editQuestionModal').classList.replace('hidden', 'flex');
    }

    function confirmDelete() {
        if(confirm('Delete this question?')) {
            document.getElementById('deleteQuestionForm').submit();
        }
    }
    
    function showLogoutModal() { document.getElementById('logoutModal').classList.replace('hidden', 'flex'); }
</script>
@endsection