<div id="addProgramModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[85vh]">
        
        <div class="bg-[#800000] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Add Program</h3>
            <button type="button" onclick="toggleModal('addProgramModal', false)" class="text-white/80 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto custom-scrollbar p-6">
            <form action="{{ route('admin.programs.store') }}" method="POST">
                @csrf
                <input type="hidden" name="department_id" id="add-program-dept-id">
                
                <div class="space-y-4">
                    <div class="p-3 bg-red-50 rounded-xl border border-red-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#800000] shadow-sm flex-shrink-0">
                            <i class="fa-solid fa-building-columns text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-[#800000] uppercase tracking-wide opacity-70">Target Department</p>
                            <span id="program-dept-target" class="text-xs font-extrabold text-gray-800 leading-tight block"></span>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Program Code</label>
                        <input name="code" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all placeholder-gray-300" placeholder="e.g., BSIT" required>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Program Name</label>
                        <input name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:ring-4 focus:ring-[#800000]/5 outline-none transition-all placeholder-gray-300" placeholder="e.g., Bachelor of Science in Information Technology" required>
                    </div>

                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-[#660000] transition-all transform active:scale-[0.98] mt-2">
                        Save Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editProgramModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[85vh]">
        <div class="bg-[#800000] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Edit Program</h3>
            <button type="button" onclick="toggleModal('editProgramModal', false)" class="text-white/80 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto custom-scrollbar p-6">
            <form id="editProgramForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Program Code</label>
                        <input id="edit-program-code" name="code" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Program Name</label>
                        <input id="edit-program-name" name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                    </div>

                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-[#660000] transition-all transform active:scale-[0.98] mt-2">
                        Update Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addClassModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">
        
        <div class="bg-[#800000] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Add Class Section</h3>
            <button type="button" onclick="toggleModal('addClassModal', false)" class="text-white/80 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto custom-scrollbar p-6">
            <form action="{{ route('admin.sections.store') }}" method="POST">
                @csrf
                <input type="hidden" name="department_id" id="add-class-dept-id">
                <input type="hidden" name="course_id" id="add-class-course-id">
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Year Level</label>
                            <input type="number" name="year_level" id="new-section-year" oninput="loadSubjectsForClass(this.value, 'add-subject-list')" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all placeholder-gray-300" placeholder="1" min="1" max="4" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Block / Section</label>
                            <input name="block" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all placeholder-gray-300" placeholder="A" required>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Assign Subjects</label>
                            <span class="text-[9px] text-gray-400 bg-white px-2 py-0.5 rounded border border-gray-200 font-medium">Auto-filter</span>
                        </div>
                        
                        <div id="add-subject-list" class="h-36 overflow-y-auto pr-2 grid grid-cols-1 gap-2 custom-scrollbar">
                            <div class="flex flex-col items-center justify-center h-full text-gray-400">
                                <i class="fa-solid fa-layer-group text-xl mb-1 opacity-50"></i>
                                <p class="text-[10px] italic">Enter Year Level to load.</p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-[#660000] transition-all transform active:scale-[0.98]">
                        Save Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editClassModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[500px] overflow-hidden transform transition-all relative flex flex-col max-h-[90vh]">
        <div class="bg-[#800000] px-5 py-3 flex justify-between items-center flex-shrink-0">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Edit Class Section</h3>
            <button type="button" onclick="toggleModal('editClassModal', false)" class="text-white/80 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto custom-scrollbar p-6">
            <form id="editSectionForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="department_id" id="edit-class-dept-id">
                <input type="hidden" name="course_id" id="edit-class-course-id">

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Year Level</label>
                            <input type="number" name="year_level" id="edit-section-year" oninput="loadSubjectsForClass(this.value, 'edit-subject-list')" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" min="1" max="4" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Block / Section</label>
                            <input name="block" id="edit-section-block" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Assigned Subjects</label>
                        <div id="edit-subject-list" class="h-36 overflow-y-auto pr-2 grid grid-cols-1 gap-2 custom-scrollbar"></div>
                    </div>

                    <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm uppercase tracking-widest shadow-md hover:bg-[#660000] transition-all transform active:scale-[0.98]">
                        Update Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>