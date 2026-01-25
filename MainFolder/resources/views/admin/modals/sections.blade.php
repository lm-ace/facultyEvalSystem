{{-- ======================================================================= --}}
{{-- ADD CLASS SECTION MODAL --}}
{{-- ======================================================================= --}}
<div id="addClassModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
        <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold uppercase text-sm">Add Class Section</h3>
            <button type="button" onclick="toggleModal('addClassModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        {{-- STANDARD FORM --}}
        <form action="{{ route('admin.sections.store') }}" method="POST">
            @csrf
            
            {{-- CRITICAL: These hidden inputs allow the controller to redirect us back correctly --}}
            <input type="hidden" name="department_id" id="add-class-dept-id">
            <input type="hidden" name="course_id" id="add-class-course-id">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label>
                    {{-- Note: The 'oninput' triggers the JS to fetch subjects for this year level --}}
                    <input type="number" name="year_level" id="new-section-year" oninput="loadSubjectsForClass(this.value, 'add-subject-list')" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g. 1" min="1" max="4" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Block</label>
                    <input name="block" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g. A" required>
                </div>
                
                {{-- Dynamic Subject List Container --}}
                <div class="border-t pt-4">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Assign Subjects</label>
                    <div id="add-subject-list" class="h-32 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50 text-sm grid grid-cols-1 gap-1">
                        <p class="text-gray-400 italic text-xs text-center mt-4">Enter a Year Level to see subjects.</p>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">
                    Save Class
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================================= --}}
{{-- EDIT CLASS SECTION MODAL --}}
{{-- ======================================================================= --}}
<div id="editClassModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
        <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold uppercase text-sm">Edit Class Section</h3>
            <button type="button" onclick="toggleModal('editClassModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        {{-- STANDARD FORM with Dynamic Action --}}
        <form id="editSectionForm" method="POST">
            @csrf
            @method('PUT')
            
            {{-- We need these hidden inputs to redirect back correctly after update --}}
            <input type="hidden" name="department_id" id="edit-class-dept-id">
            <input type="hidden" name="course_id" id="edit-class-course-id">

            <div class="p-6 space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label>
                    <input type="number" name="year_level" id="edit-section-year" oninput="loadSubjectsForClass(this.value, 'edit-subject-list')" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="4" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Block</label>
                    <input name="block" id="edit-section-block" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                </div>
                <div class="border-t pt-4">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Assigned Subjects</label>
                    <div id="edit-subject-list" class="h-32 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50 text-sm grid grid-cols-1 gap-1"></div>
                </div>
                <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">
                    Update Class
                </button>
            </div>
        </form>
    </div>
</div>