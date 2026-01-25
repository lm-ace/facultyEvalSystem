<div id="addStudentModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">
        
        <div class="bg-[#800000] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Register Student</h3>
            <button type="button" onclick="toggleModal('addStudentModal', false)" class="text-white/80 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="overflow-y-auto custom-scrollbar p-6">
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf
                <input type="hidden" name="department_id" id="add-student-dept-id">
                <input type="hidden" name="course_id" id="add-student-course-id">

                <div class="space-y-3">
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Student #</label>
                            <input name="student_number" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Email</label>
                            <input type="email" name="email" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">First Name</label>
                            <input name="first_name" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Middle Name</label>
                            <input name="middle_name" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Last Name</label>
                            <input name="last_name" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Suffix</label>
                            <input name="suffix" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Contact Number</label>
                        <div class="relative">
                            <input name="contact_no" class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" placeholder="0912 345 6789">
                            <i class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mt-2">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Section Assignment</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <select id="add_student_year" onchange="filterStudentSections(this.value, 'add_student_section')" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:border-[#800000] outline-none cursor-pointer">
                                    <option value="">Select Year</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div>
                                <select name="section_id" id="add_student_section" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:border-[#800000] outline-none cursor-pointer disabled:opacity-50" disabled>
                                    <option value="">Select Year First</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="toggleModal('addStudentModal', false)" class="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold uppercase text-xs hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-xs shadow-md hover:bg-[#660000] transition-all transform active:scale-[0.98]">Enroll Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editStudentModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">

        <div class="bg-[#800000] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Edit Student</h3>
            <button type="button" onclick="toggleModal('editStudentModal', false)" class="text-white/80 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="overflow-y-auto custom-scrollbar p-6">
            <form id="editStudentForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="department_id" id="edit-student-dept-id">
                <input type="hidden" name="course_id" id="edit-student-course-id">

                <div class="space-y-3">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Student Number</label>
                        <input id="edit-student-number" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-500 cursor-not-allowed outline-none" readonly>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">First Name</label>
                            <input name="first_name" id="edit-student-fname" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Middle Name</label>
                            <input name="middle_name" id="edit-student-mname" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Last Name</label>
                            <input name="last_name" id="edit-student-lname" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Suffix</label>
                            <input name="suffix" id="edit-student-suffix" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Email</label>
                        <input type="email" name="email" id="edit-student-email" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Contact Number</label>
                        <div class="relative">
                            <input name="contact_no" id="edit-student-contact" class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all">
                            <i class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mt-2">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Section Assignment</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <select id="edit-student-year" onchange="filterStudentSections(this.value, 'edit-student-section')" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:border-[#800000] outline-none cursor-pointer">
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div>
                                <select name="section_id" id="edit-student-section" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:border-[#800000] outline-none cursor-pointer"></select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="toggleModal('editStudentModal', false)" class="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold uppercase text-xs hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-xs shadow-md hover:bg-[#660000] transition-all transform active:scale-[0.98]">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>