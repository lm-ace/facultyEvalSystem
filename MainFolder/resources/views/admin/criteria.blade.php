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
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <div class="lg:col-span-3 space-y-10">
                <div class="flex justify-between items-end">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-[#800000] flex-1">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Evaluation Criteria Management</h2>
                        <p class="text-gray-600 text-sm">Manage evaluation questions grouped by category. All questions use the standard 1-5 Likert scale.</p>
                    </div>

                    <button onclick="showAddQuestionModal()" class="ml-6 bg-[#FFB800] hover:bg-[#E6A600] text-[#800000] px-6 py-4 rounded-xl text-xs font-bold uppercase shadow-lg transition active:scale-95 flex items-center h-fit">
                        <i class="fa-solid fa-plus mr-2 text-lg"></i> Add New Question
                    </button>
                </div>

                @php
                    $icons = [
                        1 => 'fa-book-open',
                        2 => 'fa-chalkboard-user',
                        3 => 'fa-file-pen',
                        4 => 'fa-user-tie'
                    ];
                @endphp

                @forelse($sections as $section)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-6 py-3 flex items-center text-white">
                        <i class="fa-solid {{ $icons[$section->section_number] ?? 'fa-star' }} mr-3"></i>
                        <h3 class="font-bold text-sm uppercase tracking-widest">
                            Section {{ $section->section_number }}: {{ $section->section_name }}
                        </h3>
                    </div>
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b">
                            <tr>
                                <th class="px-6 py-3 w-3/4">Question</th>
                                <th class="px-6 py-3 w-1/4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($section->items as $item)
                            <tr data-question-id="{{ $item->id }}" data-category="{{ $section->section_name }}" class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $item->question_text }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="showEditQuestionModal({{ $item->id }}, this)" class="text-blue-600 hover:text-blue-800">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-400 italic">
                                    No questions added to this section yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @empty
                <div class="bg-white p-10 rounded-2xl shadow-sm text-center">
                    <p class="text-gray-500">No criteria sections found in the database.</p>
                </div>
                @endforelse

            </div>

            <div class="lg:col-span-1 space-y-6 lg:mt-[136px]">
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

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                        <i class="fa-solid fa-chart-simple mr-3 text-[#800000]"></i> Summary
                    </h4>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Categories</span>
                            <span class="font-black text-[#800000] text-sm">{{ isset($sections) ? $sections->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Total Questions</span>
                            <span class="font-black text-gray-800 text-sm" id="totalQuestions">{{ $totalQuestions ?? 0 }}</span>
                        </div>
                    </div>

                    <hr class="border-gray-100 my-4">

                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Average Rating Is:</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-[10px] font-bold text-emerald-600"> 5.00 – 4.50</span>
                            <span class="text-[9px] text-gray-400 font-medium uppercase">Outstanding</span>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-[10px] font-bold text-green-600"> 4.49 – 3.50</span>
                            <span class="text-[9px] text-gray-400 font-medium uppercase">Very Satisfactory</span>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-[10px] font-bold text-amber-500"> 3.49 – 2.50</span>
                            <span class="text-[9px] text-gray-400 font-medium uppercase">Satisfactory</span>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-[10px] font-bold text-orange-500"> 2.49 – 1.50</span>
                            <span class="text-[9px] text-gray-400 font-medium uppercase">Needs Improvement</span>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-[10px] font-bold text-red-600"> 1.49 – 1.00</span>
                            <span class="text-[9px] text-gray-400 font-medium uppercase">Unsatisfactory</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<div id="addQuestionModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-[#800000]">
        <h3 class="text-2xl font-black text-gray-800 mb-6 text-center">Add New Question</h3>
        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Category</label>
                <select id="addQuestionCategory" class="w-full mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold">
                    <option value="">Select Category</option>
                    @if(isset($sections))
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Question Text</label>
                <textarea id="addQuestionText" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium h-32" placeholder="Enter question..."></textarea>
            </div>
        </div>
        <div class="flex space-x-3 mt-8">
            <button onclick="saveNewQuestion()" class="flex-1 py-3 bg-[#800000] text-white font-bold rounded-xl text-sm transition hover:scale-[1.02]">Save</button>
            <button onclick="hideAddQuestionModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-50">Cancel</button>
        </div>
    </div>
</div>

<div id="editQuestionModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-blue-600">
        <h3 class="text-2xl font-black text-gray-800 mb-6 text-center">Edit Question</h3>
        <div class="space-y-4">
            <input type="hidden" id="editQuestionId">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Category</label>
                <select id="editQuestionCategory" disabled class="w-full mt-1 px-4 py-2 bg-gray-100 border border-gray-200 rounded-xl text-xs font-bold cursor-not-allowed">
                    @if(isset($sections))
                        @foreach($sections as $section)
                        <option value="{{ $section->section_name }}">{{ $section->section_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Question Text</label>
                <textarea id="editQuestionText" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium h-32"></textarea>
            </div>
        </div>
        <div class="flex space-x-3 mt-8">
            <button onclick="updateQuestion()" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl text-sm transition hover:bg-blue-700">Save Changes</button>
            <button onclick="showDeleteConfirm()" class="py-3 px-4 bg-red-600 text-white font-bold rounded-xl text-sm transition hover:bg-red-700">Delete</button>
            <button onclick="hideEditQuestionModal()" class="py-3 px-4 border-2 border-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-100">Cancel</button>
        </div>
    </div>
</div>

<div id="confirmDeleteModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 text-center border-t-8 border-red-600">
        <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-trash-can text-2xl"></i>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-2">Delete Question?</h3>
        <p class="text-gray-500 text-sm mb-8">This action cannot be undone. Do you wish to continue?</p>
        <div class="flex space-x-3">
            <button onclick="executeDelete()" class="flex-1 py-3 bg-red-600 text-white font-bold rounded-xl text-sm transition hover:bg-red-700">Confirm Delete</button>
            <button onclick="hideDeleteConfirm()" class="flex-1 py-3 bg-gray-100 text-gray-400 font-bold rounded-xl text-sm transition hover:bg-gray-200">Cancel</button>
        </div>
    </div>
</div>

<div id="successModal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 text-center border-t-8 border-green-500">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
            <i class="fa-solid fa-check text-2xl"></i>
        </div>
        <h3 class="text-xl font-black text-gray-800 mb-2">Awesome!</h3>
        <p id="successMessage" class="text-gray-500 text-sm mb-8">Operation was successful.</p>
        <button onclick="hideSuccessModal()" class="w-full py-3 bg-green-500 text-white font-bold rounded-xl text-sm transition hover:bg-green-600">Got it!</button>
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 border-t-8 border-[#800000]">
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

<script>
    function showSuccess(msg) {
        document.getElementById('successMessage').innerText = msg;
        document.getElementById('successModal').classList.replace('hidden', 'flex');
    }

    function hideSuccessModal() {
        document.getElementById('successModal').classList.replace('flex', 'hidden');
    }

    function showAddQuestionModal() {
        document.getElementById('addQuestionModal').classList.replace('hidden', 'flex');
    }

    function hideAddQuestionModal() {
        document.getElementById('addQuestionModal').classList.replace('flex', 'hidden');
    }

    function saveNewQuestion() {
        hideAddQuestionModal();
        showSuccess('New question added to criteria!');
    }

    function showEditQuestionModal(questionId, button) {
        const row = button.closest('tr');
        const text = row.querySelector('td.font-bold').textContent.trim();
        const cat = row.dataset.category;

        document.getElementById('editQuestionId').value = questionId;
        document.getElementById('editQuestionText').value = text;
        document.getElementById('editQuestionCategory').value = cat;
        document.getElementById('editQuestionModal').classList.replace('hidden', 'flex');
    }

    function hideEditQuestionModal() {
        document.getElementById('editQuestionModal').classList.replace('flex', 'hidden');
    }

    function updateQuestion() {
        const id = document.getElementById('editQuestionId').value;
        const newText = document.getElementById('editQuestionText').value;
        const row = document.querySelector(`tr[data-question-id="${id}"]`);

        if (row) row.querySelector('td.font-bold').textContent = newText;

        hideEditQuestionModal();
        showSuccess('Question updated successfully!');
    }

    function showDeleteConfirm() {
        document.getElementById('confirmDeleteModal').classList.replace('hidden', 'flex');
    }

    function hideDeleteConfirm() {
        document.getElementById('confirmDeleteModal').classList.replace('flex', 'hidden');
    }

    function executeDelete() {
        const id = document.getElementById('editQuestionId').value;
        const row = document.querySelector(`tr[data-question-id="${id}"]`);

        if (row) row.remove();

        hideDeleteConfirm();
        hideEditQuestionModal();
        showSuccess('Question removed from the system.');
    }

    function showLogoutModal() {
        document.getElementById('logoutModal').classList.replace('hidden', 'flex');
    }

    function hideLogoutModal() {
        document.getElementById('logoutModal').classList.replace('flex', 'hidden');
    }

    function executeLogout() {
        window.location.href = "{{ route('home') }}";
    }
</script>
@endsection