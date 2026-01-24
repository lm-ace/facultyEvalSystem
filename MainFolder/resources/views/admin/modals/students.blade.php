{{-- ======================================================================= --}}
{{-- ADD STUDENT MODAL (Modern UI) --}}
{{-- ======================================================================= --}}
<div id="addStudentModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/20 backdrop-blur-sm">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-[550px] overflow-hidden transform transition-all relative max-h-[90vh] flex flex-col">

        <div class="p-8 overflow-y-auto custom-scrollbar">
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf
                <input type="hidden" name="department_id" id="add-student-dept-id">
                <input type="hidden" name="course_id" id="add-student-course-id">

                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-[#800000]/10 text-[#800000] rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-user-graduate text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Register Student</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">New Enrollment</p>
                </div>

                <div class="space-y-4">
                    {{-- ID & Email --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Student #</label>
                            <input name="student_number" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Email</label>
                            <input type="email" name="email" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all" required>
                        </div>
                    </div>

                    {{-- Names --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">First Name</label><input name="first_name" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none" required></div>
                        <div class="space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Middle Name</label><input name="middle_name" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Last Name</label><input name="last_name" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none" required></div>
                        <div class="space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Suffix</label><input name="suffix" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none"></div>
                    </div>
                    {{-- ADD THIS NEW FIELD --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Contact Number</label>
                        <div class="relative">
                            <input name="contact_no" class="w-full pl-3 pr-8 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all" placeholder="0912 345 6789">
                            <i class="fa-solid fa-phone absolute right-3 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Section Info --}}
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Section Assignment</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <select id="add_student_year" onchange="filterStudentSections(this.value, 'add_student_section')" class="w-full px-3 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none cursor-pointer">
                                    <option value="">Select Year</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div>
                                <select name="section_id" id="add_student_section" class="w-full px-3 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none cursor-pointer disabled:opacity-50" disabled>
                                    <option value="">Select Year First</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="toggleModal('addStudentModal', false)" class="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold uppercase text-xs hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-xs shadow-lg hover:bg-[#660000] transition-all">Enroll Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================================= --}}
{{-- EDIT STUDENT MODAL (Modern UI) --}}
{{-- ======================================================================= --}}
<div id="editStudentModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/20 backdrop-blur-sm">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-[550px] overflow-hidden transform transition-all relative max-h-[90vh] flex flex-col">

        <div class="p-8 overflow-y-auto custom-scrollbar">
            <form id="editStudentForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="department_id" id="edit-student-dept-id">
                <input type="hidden" name="course_id" id="edit-student-course-id">

                <div class="text-center mb-6">
                    <h3 class="text-xl font-black text-gray-800">Edit Student</h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Student Number</label>
                        <input id="edit-student-number" class="w-full px-3 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-500 cursor-not-allowed" readonly>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">First Name</label><input name="first_name" id="edit-student-fname" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]" required></div>
                        <div class="space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Middle Name</label><input name="middle_name" id="edit-student-mname" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Last Name</label><input name="last_name" id="edit-student-lname" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]" required></div>
                        <div class="space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Suffix</label><input name="suffix" id="edit-student-suffix" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]"></div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Email</label>
                        <input type="email" name="email" id="edit-student-email" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Contact Number</label>
                        <div class="relative">
                            <input name="contact_no" id="edit-student-contact" class="w-full pl-3 pr-8 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all">
                            <i class="fa-solid fa-phone absolute right-3 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Section Info --}}
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Section Assignment</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <select id="edit-student-year" onchange="filterStudentSections(this.value, 'edit-student-section')" class="w-full px-3 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none cursor-pointer">
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div>
                                <select name="section_id" id="edit-student-section" class="w-full px-3 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none cursor-pointer"></select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="toggleModal('editStudentModal', false)" class="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold uppercase text-xs hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-xs shadow-lg hover:bg-[#660000] transition-all">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
