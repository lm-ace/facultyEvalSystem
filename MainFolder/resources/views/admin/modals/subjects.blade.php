{{-- ======================================================================= --}}
{{-- ADD SUBJECT MODAL --}}
{{-- ======================================================================= --}}
<div id="addSubjectModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
        <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold uppercase text-sm">Add Subject</h3>
            <button onclick="toggleModal('addSubjectModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        {{-- STANDARD FORM --}}
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf
            {{-- Hidden IDs populated by JS when you open the modal --}}
            <input type="hidden" name="department_id" id="add-subject-dept-id">
            <input type="hidden" name="course_id" id="add-subject-course-id">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Subject Code</label>
                    <input name="subject_code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g. COMP 101" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Description</label>
                    <input name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" placeholder="e.g. Intro to Computing" required>
                </div>
                <div class="flex gap-4">
                    <div class="w-1/2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label>
                        <input type="number" name="year_level" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="4" required>
                    </div>
                    <div class="w-1/2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Credits</label>
                        <input type="number" name="credits" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="10" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">
                    Save Subject
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================================= --}}
{{-- EDIT SUBJECT MODAL --}}
{{-- ======================================================================= --}}
<div id="editSubjectModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in pointer-events-none">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all pointer-events-auto border-2 border-gray-100">
        <div class="bg-[#800000] px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold uppercase text-sm">Edit Subject</h3>
            <button onclick="toggleModal('editSubjectModal', false)" class="text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        {{-- STANDARD FORM with Dynamic Action --}}
        <form id="editSubjectForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Subject Code</label>
                    <input id="edit-subject-code" name="subject_code" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Description</label>
                    <input id="edit-subject-name" name="name" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" required>
                </div>
                <div class="flex gap-4">
                    <div class="w-1/2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Year Level</label>
                        <input id="edit-subject-year" type="number" name="year_level" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="5" required>
                    </div>
                    <div class="w-1/2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Credits</label>
                        <input id="edit-subject-credits" type="number" name="credits" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:border-[#800000] outline-none" min="1" max="10" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-[#800000] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:bg-[#660000] mt-2">
                    Update Subject
                </button>
            </div>
        </form>
    </div>
</div>