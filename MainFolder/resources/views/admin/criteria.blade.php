@extends('layouts.app')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

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

{{-- LOGOUT MODAL --}}
<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-shield-halved text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Admin Logout</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Confirm if you want to end your current administrative session.</p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="executeLogout()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98]">Confirm Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Cancel</button>
        </div>
    </div>
</div>

<div class="fixed top-[48px] left-0 right-0 z-40 bg-white border-b border-gray-200 shadow-sm px-10 py-3">
    <div class="max-w-7xl mx-auto flex items-center space-x-8 text-xs font-bold uppercase tracking-widest">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-chart-pie mr-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.departments') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-sitemap mr-2"></i> Departments
        </a>
        <a href="{{ route('admin.criteria') }}" class="flex items-center {{ Request::is('admin/criteria*') ? 'text-[#800000] border-b-2 border-[#800000]' : 'text-gray-400 hover:text-[#800000]' }} pb-1 transition-all">
            <i class="fa-solid fa-list-check mr-2"></i> Criteria
        </a>
        <a href="{{ route('admin.reports') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-file-contract mr-2"></i> Reports
        </a>
    </div>
</div>

<main class="pt-36 pb-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 max-w-7xl">
        
        {{-- ========================================= --}}
        {{-- SUCCESS MESSAGE (AUTO-HIDES AFTER 3 SEC) --}}
        {{-- ========================================= --}}
        @if(session('success'))
        <div id="flash-message" class="fixed bottom-5 right-5 z-[150] bg-green-500 text-white px-6 py-4 rounded-2xl shadow-xl font-bold text-sm animate-bounce flex items-center transition-opacity duration-1000">
            <i class="fa-solid fa-circle-check mr-3 text-xl"></i> {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                const flash = document.getElementById('flash-message');
                if (flash) {
                    flash.style.opacity = '0'; // Fade out
                    setTimeout(() => flash.remove(), 1000); // Remove from HTML
                }
            }, 3000); // Wait 3 seconds
        </script>
        @endif
        {{-- ========================================= --}}

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-3 space-y-10">
                <div class="flex justify-between items-end">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-[#800000] flex-1">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Evaluation Criteria Management</h2>
                        <p class="text-gray-600 text-sm">Manage evaluation questions grouped by category. All questions use the standard 1-5 Likert scale.</p>
                    </div>
                    
                    <div class="flex space-x-3 ml-6">
                        {{-- ADD CATEGORY BUTTON --}}
                        <button onclick="showAddCategoryModal()" class="bg-white border-2 border-[#800000] text-[#800000] px-5 py-4 rounded-xl text-xs font-bold uppercase shadow-sm transition hover:bg-[#800000] hover:text-white flex items-center h-fit">
                            <i class="fa-solid fa-folder-plus mr-2 text-lg"></i> Add Category
                        </button>

                        {{-- ADD QUESTION BUTTON --}}
                        <button onclick="showAddQuestionModal()" class="bg-[#FFB800] hover:bg-[#E6A600] text-[#800000] px-6 py-4 rounded-xl text-xs font-bold uppercase shadow-lg transition active:scale-95 flex items-center h-fit">
                            <i class="fa-solid fa-plus mr-2 text-lg"></i> Add Question
                        </button>
                    </div>
                </div>

                {{-- DYNAMIC DATABASE LOOP --}}
                @forelse($sections as $section)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    {{-- =================================== --}}
                    {{-- HEADER WITH DELETE CATEGORY BUTTON --}}
                    {{-- =================================== --}}
                    <div class="bg-[#800000] px-6 py-3 flex justify-between items-center text-white">
                        <div class="flex items-center">
                            <i class="fa-solid fa-layer-group mr-3"></i>
                            <h3 class="font-bold text-sm uppercase tracking-widest">
                                Section {{ $section->section_number }}: {{ $section->section_name }}
                            </h3>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <span class="text-[10px] bg-white/20 px-2 py-1 rounded font-bold">{{ $section->items->count() }} Items</span>
                            
                            {{-- DELETE CATEGORY FORM --}}
                            <form action="{{ route('admin.criteria.destroySection', $section->id) }}" method="POST" onsubmit="return confirm('WARNING: Deleting this category will PERMANENTLY DELETE all questions inside it. Continue?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white/60 hover:text-white transition p-1" title="Delete Category">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    {{-- =================================== --}}
                    
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b">
                            <tr>
                                <th class="px-6 py-3 w-3/4">Question</th>
                                <th class="px-6 py-3 w-1/4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($section->items as $item)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $item->question_text }}</td>
                                <td class="px-6 py-4 text-center flex justify-center space-x-4">
                                    {{-- DELETE QUESTION FORM --}}
                                    <form action="{{ route('admin.criteria.destroyItem', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition p-2">
                                            <i class="fa-solid fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                            @if($section->items->isEmpty())
                            <tr>
                                <td colspan="2" class="px-6 py-8 text-center text-gray-400 italic">
                                    No questions added to this category yet.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @empty
                {{-- EMPTY STATE IF NO CATEGORIES --}}
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <div class="text-gray-300 mb-4 text-6xl"><i class="fa-solid fa-folder-open"></i></div>
                    <h3 class="text-xl font-bold text-gray-500">No Criteria Found</h3>
                    <p class="text-sm text-gray-400 mt-2">Get started by adding a category.</p>
                </div>
                @endforelse

            </div>

            <div class="lg:col-span-1 space-y-6 lg:mt-[136px]">
                {{-- SCALE LEGEND --}}
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                        <i class="fa-solid fa-scale-balanced mr-3 text-[#800000]"></i> Likert Scale Legend
                    </h4>
                    <div class="p-3 bg-[#800000]/5 rounded-xl border border-[#800000]/10">
                        <div class="flex items-center text-[#800000] mb-2">
                            <i class="fa-solid fa-circle-info mr-2 text-xs"></i>
                            <span class="text-xs font-bold uppercase">5-Point Scale</span>
                        </div>
                        <div class="text-[10px] text-gray-600 space-y-1">
                            <div class="flex justify-between"><span>1 - Strongly Disagree</span><span class="font-bold text-red-600">Poor</span></div>
                            <div class="flex justify-between"><span>2 - Disagree</span><span class="font-bold text-orange-600">Fair</span></div>
                            <div class="flex justify-between"><span>3 - Neutral</span><span class="font-bold text-yellow-600">Satisfactory</span></div>
                            <div class="flex justify-between"><span>4 - Agree</span><span class="font-bold text-green-600">Very Good</span></div>
                            <div class="flex justify-between"><span>5 - Strongly Agree</span><span class="font-bold text-emerald-600">Excellent</span></div>
                        </div>
                    </div>
                </div>

                {{-- DYNAMIC SUMMARY --}}
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                        <i class="fa-solid fa-chart-simple mr-3 text-[#800000]"></i> Database Status
                    </h4>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Active Categories</span>
                            <span class="font-black text-[#800000] text-sm">{{ $sections->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Total Questions</span>
                            <span class="font-black text-gray-800 text-sm" id="totalQuestions">{{ $sections->sum(fn($s) => $s->items->count()) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- MODAL 1: ADD CATEGORY (RESTORED) --}}
<div id="addCategoryModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-[#800000]">
        <h3 class="text-2xl font-black text-gray-800 mb-6 text-center">Add New Category</h3>
        
        <form action="{{ route('admin.criteria.storeSection') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Category Name</label>
                    <input type="text" name="section_name" required placeholder="e.g. Peer Evaluation" 
                           class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:border-[#800000] outline-none">
                </div>
            </div>
            <div class="flex space-x-3 mt-8">
                <button type="submit" class="flex-1 py-3 bg-[#800000] text-white font-bold rounded-xl text-sm transition hover:scale-[1.02]">Save Category</button>
                <button type="button" onclick="hideAddCategoryModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: ADD QUESTION --}}
<div id="addQuestionModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-[#FFB800]">
        <h3 class="text-2xl font-black text-gray-800 mb-6 text-center">Add New Question</h3>
        
        <form action="{{ route('admin.criteria.storeItem') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Select Category</label>
                    <select name="section_id" required class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:border-[#FFB800] outline-none">
                        <option value="" disabled selected>-- Choose Category --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Question Text</label>
                    <textarea name="question_text" required class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium h-32 focus:border-[#FFB800] outline-none" placeholder="Enter evaluation question..."></textarea>
                </div>
            </div>
            <div class="flex space-x-3 mt-8">
                <button type="submit" class="flex-1 py-3 bg-[#FFB800] text-[#800000] font-bold rounded-xl text-sm transition hover:scale-[1.02]">Save Question</button>
                <button type="button" onclick="hideAddQuestionModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-50">Cancel</button>
            </div>
        </form>
    </div>
</div>

<footer class="bg-[#660000] text-white py-12">
    <div class="container mx-auto px-10 text-center text-xs">
        <p class="opacity-50">Polytechnic University of the Philippines - Main Campus</p>
        <p class="mt-4 opacity-40 tracking-widest uppercase font-bold">Copyright © {{ date('Y') }} | All Evaluation Data is Protected by Privacy Laws</p>
    </div>
</footer>

<script>
    // --- ADD CATEGORY MODAL ---
    function showAddCategoryModal() { document.getElementById('addCategoryModal').classList.replace('hidden', 'flex'); }
    function hideAddCategoryModal() { document.getElementById('addCategoryModal').classList.replace('flex', 'hidden'); }

    // --- ADD QUESTION MODAL ---
    function showAddQuestionModal() { document.getElementById('addQuestionModal').classList.replace('hidden', 'flex'); }
    function hideAddQuestionModal() { document.getElementById('addQuestionModal').classList.replace('flex', 'hidden'); }

    // --- LOGOUT ---
    function showLogoutModal() { document.getElementById('logoutModal').classList.replace('hidden', 'flex'); }
    function hideLogoutModal() { document.getElementById('logoutModal').classList.replace('flex', 'hidden'); }
    function executeLogout() { window.location.href = "{{ route('home') }}"; }
</script>
@endsection