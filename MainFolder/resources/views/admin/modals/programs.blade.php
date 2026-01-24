{{-- ADD PROGRAM MODAL --}}
<div id="addProgramModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
        <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold uppercase text-sm">Add Program</h3>
            <button type="button" onclick="toggleModal('addProgramModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        {{-- STANDARD FORM --}}
        <form action="{{ route('admin.programs.store') }}" method="POST">
            @csrf
            {{-- Hidden ID populated by JS when you open the modal --}}
            <input type="hidden" name="department_id" id="add-program-dept-id">
            
            <div class="p-6 space-y-4">
                <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                    <p class="text-[10px] font-bold text-[#800000] uppercase tracking-wide">
                        Target Dept: <span id="program-dept-target" class="text-gray-700"></span>
                    </p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Program Code</label>
                    <input name="code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g., BSIT" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Program Name</label>
                    <input name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g., Bachelor of Science in IT" required>
                </div>
                <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">
                    Save Program
                </button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT PROGRAM MODAL --}}
<div id="editProgramModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
        <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold uppercase text-sm">Edit Program</h3>
            <button type="button" onclick="toggleModal('editProgramModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        {{-- STANDARD FORM with Dynamic Action --}}
        <form id="editProgramForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Program Code</label>
                    <input id="edit-program-code" name="code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Program Name</label>
                    <input id="edit-program-name" name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                </div>
                <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">
                    Update Program
                </button>
            </div>
        </form>
    </div>
</div>