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
        
        <div class="flex justify-between items-end mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-8 border-[#800000] max-w-3xl">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Evaluation Criteria Management</h2>
                <p class="text-gray-600 text-sm">Manage evaluation questions grouped by category. All questions use the standard 1-5 Likert scale.</p>
            </div>
            
            <button onclick="showAddQuestionModal()" class="bg-[#FFB800] hover:bg-[#E6A600] text-[#800000] px-6 py-4 rounded-xl text-xs font-bold uppercase shadow-lg transition active:scale-95 flex items-center h-fit">
                <i class="fa-solid fa-plus mr-2 text-lg"></i> Add New Question
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-8">
            <div class="lg:col-span-3 space-y-10">

                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-6 py-3 flex items-center text-white">
                        <i class="fa-solid fa-book-open mr-3"></i>
                        <h3 class="font-bold text-sm uppercase tracking-widest">Section 1: Instructional Competence</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b">
                                <tr>
                                    <th class="px-6 py-3 w-3/4">Question</th>
                                    <th class="px-6 py-3 w-1/4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Demonstrates mastery of the subject.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(1, this, 'Instructional Competence')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(1, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(1, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Explains concepts clearly and makes them easy to understand.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(2, this, 'Instructional Competence')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(2, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(2, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Used relevant examples or real-world applications to illustrate lessons.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(3, this, 'Instructional Competence')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(3, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(3, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Encourages student participation and questions during discussion.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(4, this, 'Instructional Competence')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(4, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(4, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Uses effective teaching aids (PPT, visual aids, online resources) to enhance learning.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(5, this, 'Instructional Competence')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(5, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(5, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-6 py-3 flex items-center text-white">
                        <i class="fa-solid fa-chalkboard-user mr-3"></i>
                        <h3 class="font-bold text-sm uppercase tracking-widest">Section 2: Classroom Management</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b">
                                <tr>
                                    <th class="px-6 py-3 w-3/4">Question</th>
                                    <th class="px-6 py-3 w-1/4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Starts and ends classes on time.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(6, this, 'Classroom Management')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(6, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(6, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Maintains an orderly and conductive learning environment.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(7, this, 'Classroom Management')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(7, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(7, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Manages class time effectively (not spending too much time on irrelevant topics).</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(8, this, 'Classroom Management')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(8, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(8, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Is approachable and available for consultation during specified hours.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(9, this, 'Classroom Management')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(9, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(9, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Implements class policies fairly and consistently.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(10, this, 'Classroom Management')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(10, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(10, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-6 py-3 flex items-center text-white">
                        <i class="fa-solid fa-file-pen mr-3"></i>
                        <h3 class="font-bold text-sm uppercase tracking-widest">Section 3: Assessment and Feedback</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b">
                                <tr>
                                    <th class="px-6 py-3 w-3/4">Question</th>
                                    <th class="px-6 py-3 w-1/4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Provides clear guidelines and criteria for assignments and projects.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(11, this, 'Assessment and Feedback')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(11, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(11, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Returns quizzes, exams, and projects in a timely manner.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(12, this, 'Assessment and Feedback')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(12, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(12, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Gives constructive feedback to help improve student performance.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(13, this, 'Assessment and Feedback')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(13, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(13, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Computes grades fairly based on the presented syllabus.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(14, this, 'Assessment and Feedback')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(14, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(14, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Assessments align with the learning objectives and content discussed.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(15, this, 'Assessment and Feedback')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(15, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(15, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="bg-[#800000] px-6 py-3 flex items-center text-white">
                        <i class="fa-solid fa-user-tie mr-3"></i>
                        <h3 class="font-bold text-sm uppercase tracking-widest">Section 4: Professionalism</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 text-gray-400 uppercase font-black border-b">
                                <tr>
                                    <th class="px-6 py-3 w-3/4">Question</th>
                                    <th class="px-6 py-3 w-1/4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Shows respect for students regardless of gender, religion, or background.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(16, this, 'Professionalism')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(16, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(16, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Demonstrates enthusiasm in teaching the subject.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(17, this, 'Professionalism')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(17, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(17, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Accepts constructive criticism and suggestions from students.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(18, this, 'Professionalism')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(18, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(18, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Adheres to school policies regarding attendance and syllabus implementation.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(19, this, 'Professionalism')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(19, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(19, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-6 py-4 font-bold text-gray-800">Maintains professional appearance and demeanor.</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showEditQuestionModal(20, this, 'Professionalism')" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                            <button onclick="showDeleteQuestionModal(20, this)" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash-can text-lg"></i></button>
                                            <button onclick="showLogicModal(20, this)" class="text-purple-600 hover:text-purple-800"><i class="fa-solid fa-diagram-project text-lg"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                        <i class="fa-solid fa-scale-balanced mr-3 text-[#800000]"></i> Likert Scale Legend
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="p-3 bg-[#800000]/5 rounded-xl border border-[#800000]/10">
                            <div class="flex items-center text-[#800000] mb-2">
                                <i class="fa-solid fa-circle-info mr-2 text-xs"></i>
                                <span class="text-xs font-bold uppercase">5-Point Scale</span>
                            </div>
                            <div class="text-[10px] text-gray-600 space-y-1">
                                <div class="flex justify-between">
                                    <span>1 - Strongly Disagree</span>
                                    <span class="font-bold text-red-600">Poor</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>2 - Disagree</span>
                                    <span class="font-bold text-orange-600">Fair</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>3 - Neutral</span>
                                    <span class="font-bold text-yellow-600">Satisfactory</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>4 - Agree</span>
                                    <span class="font-bold text-green-600">Very Good</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>5 - Strongly Agree</span>
                                    <span class="font-bold text-emerald-600">Excellent</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-sm mb-4 flex items-center uppercase tracking-widest">
                        <i class="fa-solid fa-chart-simple mr-3 text-[#800000]"></i> Summary
                    </h4>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Categories</span>
                            <span class="font-black text-[#800000] text-sm">4</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Total Questions</span>
                            <span class="font-black text-gray-800 text-sm" id="totalQuestions">20</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="addQuestionModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000]">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Add New Question</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Create a new evaluation criteria question.</p>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Category</label>
                <select id="addQuestionCategory" class="w-full mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#800000] outline-none text-xs font-bold text-gray-700">
                    <option value="">Select Category</option>
                    <option value="Instructional Competence">Instructional Competence</option>
                    <option value="Classroom Management">Classroom Management</option>
                    <option value="Assessment and Feedback">Assessment and Feedback</option>
                    <option value="Professionalism">Professionalism</option>
                </select>
            </div>
            
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Question Text</label>
                <textarea id="addQuestionText" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-[#800000] outline-none text-xs font-medium text-gray-700 h-32" placeholder="Enter evaluation question..."></textarea>
            </div>
        </div>
        
        <div class="flex space-x-3 mt-8">
            <button onclick="saveNewQuestion()" class="flex-1 py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98] text-sm">Save Question</button>
            <button onclick="hideAddQuestionModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98] text-sm">Cancel</button>
        </div>
    </div>
</div>

<div id="editQuestionModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-blue-600">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Edit Question</h3>
        </div>
        
        <div class="space-y-4">
            <input type="hidden" id="editQuestionId">
            
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Category</label>
                <select id="editQuestionCategory" class="w-full mt-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:border-blue-600 outline-none text-xs font-bold text-gray-700">
                    <option value="Instructional Competence">Instructional Competence</option>
                    <option value="Classroom Management">Classroom Management</option>
                    <option value="Assessment and Feedback">Assessment and Feedback</option>
                    <option value="Professionalism">Professionalism</option>
                </select>
            </div>

            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Question Text</label>
                <textarea id="editQuestionText" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-blue-600 outline-none text-xs font-medium text-gray-700 h-32" placeholder="Enter evaluation question..."></textarea>
            </div>
        </div>
        
        <div class="flex space-x-3 mt-8">
            <button onclick="updateQuestion()" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition active:scale-[0.98] text-sm">Update Question</button>
            <button onclick="hideEditQuestionModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98] text-sm">Cancel</button>
        </div>
    </div>
</div>

<div id="deleteQuestionModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-red-600">
        <div class="text-center mb-6">
            <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-trash-can text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-800 mb-2">Delete Question</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Are you sure you want to delete this question?</p>
        </div>
        
        <div class="bg-red-50 p-4 rounded-xl border border-red-100 mb-6">
            <p id="deleteQuestionText" class="text-sm font-medium text-gray-700"></p>
        </div>
        
        <input type="hidden" id="deleteQuestionId">
        
        <div class="flex space-x-3">
            <button onclick="confirmDeleteQuestion()" class="flex-1 py-3 bg-red-600 text-white font-bold rounded-xl shadow-lg hover:bg-red-700 transition active:scale-[0.98] text-sm">Delete Question</button>
            <button onclick="hideDeleteQuestionModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98] text-sm">Cancel</button>
        </div>
    </div>
</div>

<div id="logicModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-purple-600">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Conditional Logic Setup</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Set up branching logic based on Likert scale responses.</p>
        </div>
        
        <div class="space-y-4">
            <input type="hidden" id="logicQuestionId">
            
            <div class="p-4 bg-purple-50 rounded-xl border border-purple-100">
                <div class="flex items-center text-purple-600 mb-2">
                    <i class="fa-solid fa-diagram-project mr-2"></i>
                    <span class="text-xs font-bold uppercase">Current Question</span>
                </div>
                <p class="text-sm text-gray-700 font-medium" id="logicQuestionText"></p>
            </div>
            
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">If average rating is:</label>
                <div class="grid grid-cols-3 gap-2 mt-2">
                    <button onclick="selectLogicCondition('low')" class="logic-btn py-2 bg-gray-100 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-200 transition" data-range="1-2.4">
                        <div class="text-red-600 font-black">Low (1-2.4)</div>
                        <div class="text-[9px] text-gray-500 mt-1">Needs Improvement</div>
                    </button>
                    <button onclick="selectLogicCondition('medium')" class="logic-btn py-2 bg-gray-100 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-200 transition" data-range="2.5-3.4">
                        <div class="text-yellow-600 font-black">Medium (2.5-3.4)</div>
                        <div class="text-[9px] text-gray-500 mt-1">Satisfactory</div>
                    </button>
                    <button onclick="selectLogicCondition('high')" class="logic-btn py-2 bg-gray-100 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-200 transition" data-range="3.5-5">
                        <div class="text-green-600 font-black">High (3.5-5)</div>
                        <div class="text-[9px] text-gray-500 mt-1">Excellent</div>
                    </button>
                </div>
            </div>
            
            <div id="logicActionSection">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Then take action:</label>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <input type="radio" id="logicActionFollow" name="logicAction" value="follow" class="accent-purple-600">
                        <label for="logicActionFollow" class="ml-2 text-xs text-gray-700">
                            <span class="font-bold">Show follow-up question:</span>
                            <select class="ml-2 px-2 py-1 bg-white border border-gray-300 rounded text-xs">
                                <option>What specific areas need improvement?</option>
                                <option>Suggest additional learning resources</option>
                                <option>Request mentoring session</option>
                            </select>
                        </label>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <input type="radio" id="logicActionSkip" name="logicAction" value="skip" class="accent-purple-600">
                        <label for="logicActionSkip" class="ml-2 text-xs text-gray-700 font-bold">Skip to next category</label>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <input type="radio" id="logicActionFlag" name="logicAction" value="flag" class="accent-purple-600">
                        <label for="logicActionFlag" class="ml-2 text-xs text-gray-700 font-bold">Flag for review by department head</label>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <h4 class="text-xs font-bold text-gray-800 mb-2 uppercase">Active Logic Rules</h4>
                <div class="space-y-2" id="activeLogicRules">
                    <div class="flex items-center justify-between bg-purple-50 p-3 rounded-xl border border-purple-100">
                        <div>
                            <span class="text-xs font-bold text-purple-700">If rating is Low (1-2.4)</span>
                            <span class="text-gray-400 text-[10px] block">→ Flag for review by department head</span>
                        </div>
                        <button class="text-red-500 hover:text-red-700" onclick="removeLogicRule(this)">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-3 mt-8">
            <button onclick="saveLogic()" class="flex-1 py-3 bg-purple-600 text-white font-bold rounded-xl shadow-lg hover:bg-purple-700 transition active:scale-[0.98] text-sm">Save Logic Rules</button>
            <button onclick="hideLogicModal()" class="flex-1 py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98] text-sm">Cancel</button>
        </div>
    </div>
</div>

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

<script>
    // --- Logout Functions ---
    function showLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => { modal.querySelector('div').classList.remove('scale-95'); modal.querySelector('div').classList.add('scale-100'); }, 10);
    }
    function hideLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }
    function executeLogout() { 
        window.location.href = "{{ route('home') }}"; 
    }

    // --- Question Management Functions ---
    function showAddQuestionModal() {
        const modal = document.getElementById('addQuestionModal');
        document.getElementById('addQuestionText').value = '';
        document.getElementById('addQuestionCategory').value = '';
        
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => { modal.querySelector('div').classList.remove('scale-95'); modal.querySelector('div').classList.add('scale-100'); }, 10);
    }
    function hideAddQuestionModal() {
        const modal = document.getElementById('addQuestionModal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    function showEditQuestionModal(questionId, button, category) {
        const row = button.closest('tr');
        
        // Use simpler selector now that tables are cleaner
        const questionText = row.querySelector('.font-bold').textContent.trim();
        
        document.getElementById('editQuestionId').value = questionId;
        document.getElementById('editQuestionText').value = questionText;
        document.getElementById('editQuestionCategory').value = category; // Pre-select based on passed category
        
        const modal = document.getElementById('editQuestionModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => { modal.querySelector('div').classList.remove('scale-95'); modal.querySelector('div').classList.add('scale-100'); }, 10);
    }

    function hideEditQuestionModal() {
        const modal = document.getElementById('editQuestionModal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    function showDeleteQuestionModal(questionId, button) {
        const row = button.closest('tr');
        const questionText = row.querySelector('.font-bold.text-gray-800').textContent;
        
        document.getElementById('deleteQuestionId').value = questionId;
        document.getElementById('deleteQuestionText').textContent = questionText;
        
        const modal = document.getElementById('deleteQuestionModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => { modal.querySelector('div').classList.remove('scale-95'); modal.querySelector('div').classList.add('scale-100'); }, 10);
    }
    function hideDeleteQuestionModal() {
        const modal = document.getElementById('deleteQuestionModal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    function showLogicModal(questionId, button) {
        const row = button.closest('tr');
        const questionText = row.querySelector('.font-bold.text-gray-800').textContent;
        
        document.getElementById('logicQuestionId').value = questionId;
        document.getElementById('logicQuestionText').textContent = questionText;
        
        document.querySelectorAll('.logic-btn').forEach(btn => {
            btn.classList.remove('bg-purple-100', 'text-purple-700', 'border-purple-200');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });
        
        const modal = document.getElementById('logicModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => { modal.querySelector('div').classList.remove('scale-95'); modal.querySelector('div').classList.add('scale-100'); }, 10);
    }
    function hideLogicModal() {
        const modal = document.getElementById('logicModal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    // --- Question Actions ---
    function saveNewQuestion() {
        const questionText = document.getElementById('addQuestionText').value;
        const questionCategory = document.getElementById('addQuestionCategory').value;
        
        if (!questionText.trim()) { alert('Please enter question text'); return; }
        if (!questionCategory) { alert('Please select a category'); return; }
        
        console.log('Saving new question:', { questionText, questionCategory });
        alert('Question added successfully!');
        hideAddQuestionModal();
        
        const totalQuestions = parseInt(document.getElementById('totalQuestions').textContent);
        document.getElementById('totalQuestions').textContent = totalQuestions + 1;
    }

    function updateQuestion() {
        const questionId = document.getElementById('editQuestionId').value;
        const questionText = document.getElementById('editQuestionText').value;
        const questionCategory = document.getElementById('editQuestionCategory').value;
        
        if (!questionText.trim()) { alert('Please enter question text'); return; }
        if (!questionCategory) { alert('Please select a category'); return; }
        
        console.log('Updating question:', { questionId, questionText, questionCategory });
        alert('Question updated successfully!');
        hideEditQuestionModal();
    }

    function confirmDeleteQuestion() {
        const questionId = document.getElementById('deleteQuestionId').value;
        console.log('Deleting question:', questionId);
        alert('Question deleted successfully!');
        hideDeleteQuestionModal();
        
        const totalQuestions = parseInt(document.getElementById('totalQuestions').textContent);
        document.getElementById('totalQuestions').textContent = totalQuestions - 1;
    }

    function selectLogicCondition(type) {
        document.querySelectorAll('.logic-btn').forEach(btn => {
            btn.classList.remove('bg-purple-100', 'text-purple-700', 'border-purple-200', 'border');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });
        
        event.target.closest('.logic-btn').classList.remove('bg-gray-100', 'text-gray-600');
        event.target.closest('.logic-btn').classList.add('bg-purple-100', 'text-purple-700', 'border', 'border-purple-200');
    }

    function removeLogicRule(button) {
        if (confirm('Remove this logic rule?')) {
            button.closest('.flex.items-center.justify-between').remove();
        }
    }

    function saveLogic() {
        const questionId = document.getElementById('logicQuestionId').value;
        const selectedCondition = document.querySelector('.logic-btn.bg-purple-100');
        
        if (!selectedCondition) { alert('Please select a rating range'); return; }
        
        const selectedAction = document.querySelector('input[name="logicAction"]:checked');
        if (!selectedAction) { alert('Please select an action'); return; }
        
        console.log('Saving logic for question:', { 
            questionId, 
            condition: selectedCondition.querySelector('.font-black').textContent,
            action: selectedAction.value 
        });
        
        alert('Logic rules saved successfully!');
        hideLogicModal();
    }
</script>
@endsection