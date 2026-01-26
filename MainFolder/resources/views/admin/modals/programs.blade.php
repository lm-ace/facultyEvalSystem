<div id="addProgramModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/20 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all relative flex flex-col">
        <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Add Program</h3>
            <button type="button" onclick="toggleModal('addProgramModal', false)" class="text-white/70 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.programs.store') }}" method="POST">
            @csrf
            <input type="hidden" name="department_id" id="add-program-dept-id">
            
            <div class="p-6 md:p-8 space-y-5">
                <div class="p-4 bg-red-50 rounded-xl border border-red-100">
                    <p class="text-[10px] font-bold text-[#800000] uppercase tracking-wide mb-1">Target Department</p>
                    <span id="program-dept-target" class="text-sm font-bold text-gray-800"></span>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Program Code</label>
                    <input name="code" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all placeholder-gray-300" placeholder="e.g., BSIT" required>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Program Name</label>
                    <input name="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all placeholder-gray-300" placeholder="e.g., Bachelor of Science in IT" required>
                </div>

                <button type="submit" class="w-full bg-[#800000] text-white py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide shadow-lg hover:bg-[#660000] hover:shadow-xl transition-all transform active:scale-[0.98] mt-4">
                    Save Program
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editProgramModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/20 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all relative flex flex-col">
        <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold uppercase text-sm tracking-wider">Edit Program</h3>
            <button type="button" onclick="toggleModal('editProgramModal', false)" class="text-white/70 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <form id="editProgramForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 md:p-8 space-y-5">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Program Code</label>
                    <input id="edit-program-code" name="code" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1 mb-1 block">Program Name</label>
                    <input id="edit-program-name" name="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] outline-none transition-all" required>
                </div>

                <button type="submit" class="w-full bg-[#800000] text-white py-3.5 rounded-xl font-bold text-sm uppercase tracking-wide shadow-lg hover:bg-[#660000] hover:shadow-xl transition-all transform active:scale-[0.98] mt-4">
                    Update Program
                </button>
            </div>
        </form>
    </div>
</div>