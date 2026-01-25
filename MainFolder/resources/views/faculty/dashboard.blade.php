@extends('layouts.app')

@section('title', 'Faculty Dashboard')

@section('content')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

<img id="pdfLogo" src="{{ asset('images/logo.png') }}" class="hidden">

<div x-data="{ showEvaluation: false }">
    {{-- NAVIGATION --}}
    <nav class="fixed top-0 left-0 right-0 z-[100] flex items-center justify-between px-10 py-2 text-white bg-[#800000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8">
            <div>
                <h1 class="font-bold leading-none text-base">EduRate</h1>
                <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
            </div>
        </div>

        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" type="button" class="flex items-center focus:outline-none group">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#800000] border-2 border-white/20 transition group-hover:border-white/50">
                    <i class="fa-solid fa-user text-lg"></i>
                </div>
                <i class="fa-solid fa-caret-down text-[10px] ml-2 text-white/80 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                x-cloak
                class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl py-2 z-[110] border border-gray-100 overflow-hidden text-gray-700">

                <button type="button" onclick="showChangePasswordModal()" class="w-full text-left px-5 py-3 text-sm hover:bg-gray-50 flex items-center transition group">
                    <i class="fa-solid fa-key mr-3 text-gray-400 group-hover:text-[#800000]"></i>
                    <span class="font-medium">Change Password</span>
                </button>

                <hr class="border-gray-50">

                <button type="button" onclick="showLogoutModal()" class="w-full text-left px-5 py-3 text-sm text-[#E31E24] font-bold hover:bg-red-50 flex items-center transition group">
                    <i class="fa-solid fa-right-from-bracket mr-3 transform rotate-180 text-[#E31E24]"></i>
                    <span>Log Out</span>
                </button>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-16 bg-gray-50 min-h-screen">
        <div class="container mx-auto px-6 max-w-6xl">

            {{-- WELCOME SECTION --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border-l-8 border-[#800000] mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome, {{$fullName}}!</h2>
                <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base">
                    <p>This faculty evaluation dashboard provides an overview of evaluation results for the current review period.</p>
                    <p class="bg-[#800000]/5 p-3 rounded-lg border border-[#800000]/10 font-medium italic">
                        Access to this dashboard is restricted and intended solely for authorized academic officials.
                    </p>
                </div>
            </div>

            {{-- RATINGS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-xl transition-all duration-300">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Overall Rating</p>
                        <h3 class="text-5xl font-black text-[#800000] mt-2">{{$averageRating}}<span class="text-xl text-gray-400 font-normal"> / 5.0</span></h3>
                        <p class="text-green-600 font-bold text-sm mt-1 uppercase">
                            @if($averageRating >= 4.5) Outstanding Performance
                            @elseif($averageRating >= 3.5) Very Good 
                            @elseif($averageRating >= 2.5) Good 
                            @else Needs Improvement
                            @endif
                        </p>
                    </div>
                    <div class="bg-[#800000]/10 p-5 rounded-2xl text-[#800000] group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-star text-4xl"></i>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-md flex items-center justify-between group hover:shadow-xl transition-all duration-300">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Responses</p>
                        <h3 class="text-5xl font-black text-gray-800 mt-2">{{ $totalEvaluations }}</h3>
                        <p class="text-gray-500 text-sm mt-1 uppercase font-medium">Students participated</p>
                    </div>
                    <div class="bg-blue-50 p-5 rounded-2xl text-blue-600 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-users text-4xl"></i>
                    </div>
                </div>
            </div>

            {{-- EVALUATION DETAILS --}}
            <div id="capture-area" class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                <div class="bg-[#800000] px-8 py-4 flex flex-col md:flex-row justify-between items-center text-white">
                    <h3 class="font-bold text-lg mb-2 md:mb-0 uppercase tracking-wider">Evaluation Details</h3>
                    <div class="flex items-center space-x-3">
                        <div id="statusContainer" class="hidden flex items-center space-x-3">
                            <div id="downloadNotif" class="bg-yellow-500 text-[#800000] px-4 py-1.5 rounded-full text-xs font-bold animate-pulse">
                                <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Downloading...
                            </div>
                            <div id="fileReady" class="hidden bg-green-500 text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-sm">
                                <i class="fa-solid fa-check mr-2"></i> Downloaded
                            </div>
                            <button id="viewBtn" onclick="viewPDF()" class="hidden bg-white text-[#800000] px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-100 shadow-md">
                                <i class="fa-solid fa-eye mr-1"></i> View Report
                            </button>
                        </div>
                        <button id="dlBtn" onclick="startDownload()" class="bg-[#FFB800] hover:bg-[#E6A600] text-[#800000] px-6 py-2 rounded-lg font-bold text-sm transition shadow-md active:scale-95">
                            <i class="fa-solid fa-file-pdf mr-2"></i> Generate PDF Report
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 border-b border-gray-100">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Faculty ID</p>
                            <p class="text-lg font-bold text-[#800000]">{{$facID}}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Faculty Name</p>
                            <p class="text-lg font-bold text-gray-800">{{$fullName}}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Department</p>
                            <p class="text-lg font-bold text-gray-800">{{$deptCode}}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Review Period</p>
                            <p class="text-lg font-bold text-gray-800">{{$reviewPeriodDisplay}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-[#660000] text-white py-12">
        <div class="container mx-auto px-10 text-center text-xs">
            <p class="opacity-50">Polytechnic University of the Philippines - Main Campus</p>
            <p class="mt-4 opacity-40 tracking-widest uppercase font-bold">Copyright © {{ date('Y') }} | All Evaluation Data is Protected by Privacy Laws</p>
        </div>
    </footer>
</div>

<form method="POST" action="{{ route('faculty.changePassword') }}" id="changePasswordForm">
    @csrf
    <div id="changePasswordModal" 
         class="fixed inset-0 z-[120] {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-data="{ showOld: false, showNew: false, showConfirm: false, newPassword: '', confirmPassword: '', get passwordMatch() { return this.newPassword === this.confirmPassword; }, get isValid() { return this.newPassword.length >= 8 && this.passwordMatch && this.newPassword !== '';}}">
         
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden">
            <div class="p-8 bg-[#800000] text-white flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-lock text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-tight">Security Update</h2>
                    <p class="text-[10px] opacity-70 uppercase font-bold">Change your portal password</p>
                </div>
            </div>

            <div class="p-8 space-y-5">
                {{-- Global Error Messages --}}
                @if (session('error'))
                    <div class="p-3 bg-red-100 text-red-700 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Current Password</label>
                    <div class="relative">
                        <input name="current_password" 
                               :type="showOld ? 'text' : 'password'" 
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm @error('current_password') border-red-500 bg-red-50 @enderror">
                        <button type="button" @click="showOld = !showOld" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]">
                            <i class="fa-solid" :class="showOld ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('current_password') <span class="text-red-500 text-[10px] font-bold ml-2">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">New Password</label>
                    <div class="relative">
                        <input name="new_password" x-model="newPassword" :type="showNew ? 'text' : 'password'" 
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm @error('new_password') border-red-500 bg-red-50 @enderror">
                        <button type="button" @click="showNew = !showNew" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]">
                            <i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <p x-show="newPassword.length > 0 && newPassword.length < 8" class="text-orange-500 text-[10px] font-bold ml-2" x-cloak>Must be at least 8 characters</p>
                    @error('new_password') <span class="text-red-500 text-[10px] font-bold ml-2">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Confirm New Password</label>
                    <div class="relative">
                        <input name="new_password_confirmation" x-model="confirmPassword" :type="showConfirm ? 'text' : 'password'" 
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl outline-none focus:border-[#800000] transition pr-12 text-sm">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]">
                            <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <p x-show="confirmPassword.length > 0 && !passwordMatch" class="text-red-500 text-[10px] font-bold ml-2" x-cloak>Passwords do not match</p>
                </div>
            </div>

            <div class="p-8 pt-0 flex gap-3">
                <button type="button" onclick="hideChangePasswordModal()" class="flex-1 py-4 font-bold text-gray-400 hover:bg-gray-50 rounded-2xl transition">Cancel</button>
                <button type="button" 
                        onclick="showPasswordConfirm()"
                        :disabled="!isValid" 
                        :class="isValid ? 'bg-[#800000] hover:bg-[#660000] shadow-xl' : 'bg-gray-300 cursor-not-allowed'"
                        class="flex-[2] py-4 text-white font-black rounded-2xl transition uppercase text-xs tracking-widest">
                    Update Password
                </button>
            </div>
        </div>
    </div>
</form>

<div id="confirmPasswordModal" class="fixed inset-0 z-[130] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-[2.5rem] shadow-2xl p-8 mx-4 border-t-8 border-[#800000]">
        <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-shield-halved text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Security Check</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Are you sure you want to change your password? <br>
                <span class="font-bold text-[#800000]">You will need to use your new credentials on your next login.</span>
            </p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="submitPasswordChange()" class="w-full py-4 bg-[#800000] text-white font-black rounded-2xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98] uppercase text-xs tracking-widest">
                Yes, Update Now
            </button>
            <button onclick="hidePasswordConfirm()" class="w-full py-3 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">
                Cancel
            </button>
        </div>
    </div>
</div>

{{-- SUCCESS MODAL --}}
<div id="successModal" 
     class="fixed inset-0 z-[150] {{ session('success') ? 'flex' : 'hidden' }} items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-[2.5rem] shadow-2xl p-8 mx-4 text-center transform transition-all scale-100 border-t-8 border-green-500">
        
        <div class="bg-green-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <i class="fa-solid fa-check text-green-600 text-4xl"></i>
        </div>

        <h3 class="text-2xl font-black text-gray-800 mb-2">Success!</h3>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            {{ session('success') ?? 'Your password has been successfully updated.' }}
        </p>

        <button onclick="document.getElementById('successModal').classList.replace('flex', 'hidden')" 
                class="w-full py-4 bg-green-500 text-white font-black rounded-2xl shadow-lg hover:bg-green-600 transition active:scale-[0.98] uppercase text-xs tracking-widest">
            Ok
        </button>
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-right-from-bracket text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Ready to Leave?</h3>
            <p class="text-gray-500 text-sm">Are you sure you want to log out?</p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="executeLogout()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl hover:bg-[#660000] transition">Yes, Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition">Cancel</button>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    function showLogoutModal() {
        document.getElementById('logoutModal').classList.replace('hidden', 'flex');
    }

    function hideLogoutModal() {
        document.getElementById('logoutModal').classList.replace('flex', 'hidden');
    }

    function executeLogout() {
        document.getElementById('logout-form').submit();
    }

    function showChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('hidden', 'flex');
    }

    function hideChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('flex', 'hidden');
    }

    function showPasswordConfirm() {
        document.getElementById('changePasswordModal').classList.replace('flex', 'hidden');
        document.getElementById('confirmPasswordModal').classList.replace('hidden', 'flex');
    }

    function hidePasswordConfirm() {
        document.getElementById('confirmPasswordModal').classList.replace('flex', 'hidden');
        document.getElementById('changePasswordModal').classList.replace('hidden', 'flex');
    }

    function submitPasswordChange() {
        document.getElementById('confirmPasswordModal').classList.replace('flex', 'hidden');
        document.getElementById('changePasswordForm').submit();
    }

    const facultyData = {
        id: "{{ $faculty->faculty_code }}",
        name: "{{ $fullName }}",
        department: "{{ $faculty->department->name ?? 'N/A' }}",
        reviewPeriod: "{{ $reviewPeriodDisplay }}",
        averageRating: "{{ $averageRating }}",
        feedbacks: {!! json_encode($feedbacks) !!}
    };

    let generatedPDFBlob = null;

    function startDownload() {
        const { jsPDF } = window.jspdf;
        const statusContainer = document.getElementById('statusContainer');
        const dlNotif = document.getElementById('downloadNotif');
        const fileReady = document.getElementById('fileReady');
        const viewBtn = document.getElementById('viewBtn');
        const dlBtn = document.getElementById('dlBtn');
        const logoImg = document.getElementById('pdfLogo');

        statusContainer.classList.remove('hidden');
        dlBtn.disabled = true;
        dlBtn.classList.add('opacity-50');

        setTimeout(() => {
            const doc = new jsPDF();
            doc.setFillColor(128, 0, 0);
            doc.rect(20, 10, 170, 8, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(10);
            doc.setFont("helvetica", "bold");
            doc.text("Polytechnic University of the Philippines - Main Campus", 105, 15.5, {
                align: 'center'
            });

            if (logoImg) {
                doc.addImage(logoImg, 'PNG', 20, 25, 15, 15);
            }

            doc.setTextColor(128, 0, 0);
            doc.setFontSize(14);
            doc.setFont("helvetica", "bold");
            doc.text("EduRate", 38, 31);
            doc.setFontSize(10);
            doc.text("Faculty Evaluation System", 38, 37);

            doc.setDrawColor(200, 200, 200);
            doc.line(20, 45, 190, 45);

            doc.setFontSize(16);
            doc.text("EduRate Faculty Evaluation Report", 105, 58, {
                align: 'center'
            });

            doc.setTextColor(0, 0, 0);
            doc.setFontSize(11);
            doc.text("Faculty ID:", 20, 75);
            doc.setFont("helvetica", "normal");
            doc.text(facultyData.id, 60, 75);

            doc.setFont("helvetica", "bold");
            doc.text("Faculty Name:", 20, 83);
            doc.setFont("helvetica", "normal");
            doc.text(facultyData.name, 60, 83);

            doc.setFont("helvetica", "bold");
            doc.text("Department:", 20, 91);
            doc.setFont("helvetica", "normal");
            doc.text(facultyData.department, 60, 91);

            doc.setFont("helvetica", "bold"); 
            doc.text("Review Period:", 20, 99); 
            doc.setFont("helvetica", "normal"); 
            doc.text(facultyData.reviewPeriod, 60, 99);

            doc.setFont("helvetica", "bold");
            doc.text("Overall Rating:", 20, 118);
            doc.setFont("helvetica", "normal");
            doc.text(facultyData.averageRating + " / 5.0", 60, 118);
            
            doc.line(20, 108, 190, 108);

            doc.setFont("helvetica", "bold");
            doc.text("Student Feedback:", 20, 128);

            doc.autoTable({
                startY: 133,
                margin: { left: 20, right: 20 },
                theme: 'plain',
                styles: {
                    cellPadding: 4,
                    fontSize: 9,
                    font: 'helvetica',
                    lineColor: [220, 220, 220],
                    lineWidth: 0.1
                },
                body: (facultyData.feedbacks || []).map(f => [f])
            });

            generatedPDFBlob = doc.output('bloburl');
            doc.save(`Faculty_Evaluation_Report_${facultyData.name.replace(/\s+/g,'_')}.pdf`);
            
            dlNotif.classList.add('hidden');
            fileReady.classList.remove('hidden');
            viewBtn.classList.remove('hidden');
        }, 2000);
    }

    function viewPDF() {
        if (generatedPDFBlob) {
            window.open(generatedPDFBlob, '_blank');
        }
    }
</script>
@endsection