{{-- ======================================================================= --}}
{{-- ADD FACULTY MODAL (Modern UI) --}}
{{-- ======================================================================= --}}
<div id="addFacultyModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/20 backdrop-blur-sm">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-[550px] overflow-hidden transform transition-all relative max-h-[90vh] flex flex-col">
        
        <div class="p-8 overflow-y-auto custom-scrollbar">
            <form action="{{ route('admin.faculty.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="department_id" id="add-faculty-dept-id">
                <input type="hidden" name="course_id" id="add-faculty-course-id">

                {{-- Header & Profile Pic --}}
                <div class="flex flex-col items-center mb-6">
                    <div class="relative group cursor-pointer w-28 h-28">
                        <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-4 border-white shadow-md">
                            <img id="add-faculty-preview" src="{{ asset('images/default-avatar.png') }}" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-all">
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 group-hover:text-gray-600 transition-colors">
                                <i class="fa-solid fa-camera text-2xl"></i>
                            </div>
                        </div>
                        <div class="absolute bottom-1 right-1 bg-[#800000] text-white rounded-full w-8 h-8 flex items-center justify-center border-2 border-white shadow-sm hover:scale-110 transition-transform">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </div>
                        <input type="file" name="profile_picture" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this, 'add-faculty-preview')">
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mt-3 tracking-tight">Add New Faculty</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Create Profile</p>
                </div>

                {{-- Form Fields --}}
                <div class="space-y-4">
                    {{-- Row 1: Code & Email --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Faculty ID</label>
                            <div class="relative">
                                <input name="faculty_code" class="w-full pl-3 pr-8 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all" placeholder="FC-2024-01" required>
                                <i class="fa-solid fa-id-card absolute right-3 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Email</label>
                            <div class="relative">
                                <input type="email" name="email" class="w-full pl-3 pr-8 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all" placeholder="email@pup.edu.ph" required>
                                <i class="fa-solid fa-envelope absolute right-3 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Name --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">First Name</label>
                            <input name="first_name" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Middle Name</label>
                            <input name="middle_name" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Last Name</label>
                            <input name="last_name" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Suffix</label>
                            <input name="suffix" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all" placeholder="Jr.">
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Contact Number</label>
                        <div class="relative">
                            <input name="contact_no" class="w-full pl-3 pr-8 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none transition-all" placeholder="0912 345 6789">
                            <i class="fa-solid fa-phone absolute right-3 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Subjects --}}
                    <div class="pt-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 mb-2 block">Qualified Subjects</label>
                        <div id="faculty-subject-list" class="h-28 overflow-y-auto border-2 border-gray-100 rounded-xl p-2 bg-gray-50 text-sm grid grid-cols-1 gap-1">
                            {{-- JS Fills this --}}
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="toggleModal('addFacultyModal', false)" class="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold uppercase text-xs hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-xs shadow-lg hover:bg-[#660000] hover:shadow-xl transition-all transform hover:-translate-y-0.5">Save Faculty</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================================= --}}
{{-- EDIT FACULTY MODAL (Modern UI) --}}
{{-- ======================================================================= --}}
<div id="editFacultyModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center animate-fade-in bg-black/20 backdrop-blur-sm">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-[550px] overflow-hidden transform transition-all relative max-h-[90vh] flex flex-col">
        
        <div class="p-8 overflow-y-auto custom-scrollbar">
            <form id="editFacultyForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="department_id" id="edit-faculty-dept-id">
                <input type="hidden" name="course_id" id="edit-faculty-course-id">

                <div class="flex flex-col items-center mb-6">
                    <div class="relative group cursor-pointer w-24 h-24">
                        <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-4 border-white shadow-md">
                            {{-- We can't easily preview the existing image here without more JS, so we use a placeholder or icon --}}
                            <img id="edit-faculty-preview" src="{{ asset('images/default-avatar.png') }}" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute bottom-0 right-0 bg-gray-700 text-white rounded-full w-7 h-7 flex items-center justify-center border-2 border-white shadow-sm">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </div>
                        <input type="file" name="profile_picture" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this, 'edit-faculty-preview')">
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mt-3">Edit Profile</h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Email</label>
                        <input type="email" id="edit-faculty-email" name="email" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000] focus:bg-white outline-none" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">First Name</label><input id="edit-faculty-fname" name="first_name" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]" required></div>
                        <div class="space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Middle Name</label><input id="edit-faculty-mname" name="middle_name" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Last Name</label><input id="edit-faculty-lname" name="last_name" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]" required></div>
                        <div class="space-y-1"><label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Suffix</label><input id="edit-faculty-suffix" name="suffix" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]"></div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2">Contact No.</label>
                        <input id="edit-faculty-contact" name="contact_no" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:border-[#800000]">
                    </div>

                    <div class="pt-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase ml-2 mb-2 block">Qualified Subjects</label>
                        <div id="edit-faculty-subject-list" class="h-28 overflow-y-auto border-2 border-gray-100 rounded-xl p-2 bg-gray-50 text-sm grid grid-cols-1 gap-1"></div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="toggleModal('editFacultyModal', false)" class="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold uppercase text-xs hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 bg-[#800000] text-white py-3 rounded-xl font-bold uppercase text-xs shadow-lg hover:bg-[#660000] transition-all">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>