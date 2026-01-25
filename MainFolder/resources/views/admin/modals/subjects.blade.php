<div id="addSubjectModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">
        
        <div class="bg-[#800000] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Add Subject</h3>
            <button onclick="toggleModal('addSubjectModal', false)" class="text-white/80 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto custom-scrollbar p-6">
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <input type="hidden" name="department_id" id="add-subject-dept-id">
                <input type="hidden" name="course_id" id="add-subject-course-id">
                
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Subject Code</label>
                        <input name="subject_code" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all placeholder-gray-300" placeholder="e.g. COMP 101" required>
                    </div>
                    
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Description</label>
                        <input name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all placeholder-gray-300" placeholder="e.g. Intro to Computing" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Year Level</label>
                            <input type="number" name="year_level" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all placeholder-gray-300" placeholder="1" min="1" max="4" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Credits</label>
                            <input type="number" name="credits" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all placeholder-gray-300" placeholder="3" min="1" max="10" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-[#660000] hover:shadow-xl transition-all transform active:scale-[0.98] mt-2">
                        Save Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editSubjectModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">
        <div class="bg-[#800000] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Edit Subject</h3>
            <button onclick="toggleModal('editSubjectModal', false)" class="text-white/80 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto custom-scrollbar p-6">
            <form id="editSubjectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Subject Code</label>
                        <input id="edit-subject-code" name="subject_code" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all" required>
                    </div>
                    
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Description</label>
                        <input id="edit-subject-name" name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Year Level</label>
                            <input id="edit-subject-year" type="number" name="year_level" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all" min="1" max="5" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Credits</label>
                            <input id="edit-subject-credits" type="number" name="credits" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all" min="1" max="10" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-[#660000] hover:shadow-xl transition-all transform active:scale-[0.98] mt-2">
                        Update Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>