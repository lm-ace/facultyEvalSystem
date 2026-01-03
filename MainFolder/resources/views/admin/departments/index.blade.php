@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<img id="pdfLogo" src="{{ asset('images/logo.png') }}" class="hidden">

<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-10 py-2 text-white bg-[#800000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
    <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8"> 
        <div>
            <h1 class="font-bold leading-none text-base">EduRate</h1>
            <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
        </div>
    </div>
    
    <div class="flex items-center space-x-4">
        <span class="text-xs font-medium opacity-70 hidden sm:inline tracking-wider uppercase">System Administrator</span>
        <button type="button" onclick="showLogoutModal()" class="bg-white/10 px-4 py-1.5 rounded-lg text-sm font-bold hover:bg-white/20 transition flex items-center border border-white/20">
            <i class="fa-solid fa-right-from-bracket mr-2"></i> Log Out
        </button>
    </div>
</nav>

<div class="fixed top-[48px] left-0 right-0 z-40 bg-white border-b border-gray-200 shadow-sm px-10 py-3">
    <div class="max-w-7xl mx-auto flex items-center space-x-8 text-xs font-bold uppercase tracking-widest">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-chart-pie mr-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.departments') }}" class="flex items-center text-[#800000] border-b-2 border-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-sitemap mr-2"></i> Departments
        </a>
        <a href="#" class="text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-list-check mr-2"></i> Criteria
        </a>
        <a href="#" class="text-gray-400 hover:text-[#800000] pb-1 transition-all">
            <i class="fa-solid fa-file-contract mr-2"></i> Reports
        </a>
    </div>
</div>

<main class="pt-36 pb-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 max-w-4xl">
        
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-gray-800 uppercase tracking-tight">Institutional Departments</h2>
            <p class="text-gray-400 text-sm italic mt-2">Select a department from the dropdown below to manage academic sections.</p>
        </div>

        <div class="bg-white p-10 rounded-3xl shadow-xl border border-gray-100 max-w-2xl mx-auto">
            <div class="space-y-6">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo.png') }}" class="h-24 w-auto opacity-90">
                </div>
                
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Choose College / Department</label>
                    <div class="relative">
                        <select id="deptSelector" onchange="handleDeptSelection(this)" class="w-full p-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-[#800000] outline-none text-gray-700 font-bold transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>-- Select an Institution --</option>
                            <option value="CCIS">College of Computer and Information Sciences (CCIS)</option>
                            <option value="CAF">College of Accountancy and Finance (CAF)</option>
                            <option value="CADBE">College of Architecture and Design (CADBE)</option>
                            <option value="CAL">College of Arts and Letters (CAL)</option>
                            <option value="CBA">College of Business Administration (CBA)</option>
                            <option value="COC">College of Communication (COC)</option>
                            <option value="COED">College of Education (COED)</option>
                            <option value="CE">College of Engineering (CE)</option>
                            <option value="CHK">College of Human Kinetics (CHK)</option>
                            <option value="CL">College of Law (CL)</option>
                            <option value="CPSPA">College of Political Science and Public Administration (CPSPA)</option>
                            <option value="CSSD">College of Social Sciences and Development (CSSD)</option>
                            <option value="CS">College of Science (CS)</option>
                            <option value="CTHTM">College of Tourism, Hospitality and Transportation Management (CTHTM)</option>
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div id="noticeArea" class="hidden animate-fade-in">
                    <div class="p-4 bg-red-50 rounded-2xl border border-red-100 flex items-center text-[#800000]">
                        <i class="fa-solid fa-lock mr-3"></i>
                        <span class="text-xs font-bold uppercase">Locked or unavailable.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="drillDownModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all border-t-8 border-[#800000]">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <div>
                <h3 id="modalTitle" class="text-xl font-black text-[#800000] uppercase tracking-tight leading-none">Select Program</h3>
                <p id="modalSub" class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">CCIS Department</p>
            </div>
            <button onclick="closeDrillDown()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>

        <div id="drillDownContent" class="p-8 space-y-3 max-h-[60vh] overflow-y-auto"></div>

        <div class="p-4 bg-gray-50 flex justify-center border-t border-gray-100">
            <button id="backBtn" onclick="showPrograms()" class="hidden text-[#800000] text-[10px] font-bold uppercase tracking-widest hover:underline">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </button>
        </div>
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 mx-4 transform transition-all scale-95 duration-300 border-t-8 border-[#800000]">
        <div class="bg-[#800000]/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-right-from-bracket text-[#800000] text-3xl"></i>
        </div>
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-gray-800 mb-2">Ready to Leave?</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Are you sure you want to log out of the <strong>Admin Portal</strong>?</p>
        </div>
        <div class="flex flex-col space-y-3">
            <button onclick="executeLogout()" class="w-full py-3 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition active:scale-[0.98]">Yes, Logout</button>
            <button onclick="hideLogoutModal()" class="w-full py-3 border-2 border-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">Cancel</button>
        </div>
    </div>
</div>

<footer class="bg-[#660000] text-white py-12">
    <div class="container mx-auto px-10 text-center text-xs">
        <p class="opacity-50">Polytechnic University of the Philippines - Main Campus</p>
        <p class="mt-4 opacity-40 tracking-widest uppercase font-bold">Copyright © {{ date('Y') }} | All Evaluation Data is Protected by Privacy Laws</p>
    </div>
</footer>

<script>
    const modal = document.getElementById('drillDownModal');
    const content = document.getElementById('drillDownContent');
    const title = document.getElementById('modalTitle');
    const sub = document.getElementById('modalSub');
    const backBtn = document.getElementById('backBtn');
    const notice = document.getElementById('noticeArea');

    const years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    const sections = ['1', '2', '3', '4', '5', '1N', '2N'];

    function showLocked(type) { 
        alert("This " + type + " is currently locked or unavailable."); 
    }

    function handleDeptSelection(select) {
        if (select.value === 'CCIS') {
            notice.classList.add('hidden');
            openCCISDrillDown();
        } else {
            notice.classList.remove('hidden');
        }
    }

    function openCCISDrillDown() {
        modal.classList.remove('hidden'); modal.classList.add('flex');
        showPrograms();
    }

    function showPrograms() {
        title.innerText = "Select Program";
        sub.innerText = "CCIS Academic Programs";
        backBtn.classList.add('hidden');
        content.innerHTML = `
            <button onclick="showYearLevels('BSIT')" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl hover:bg-[#800000] hover:text-white transition-all font-bold text-left flex justify-between items-center group shadow-sm">
                <span>BSIT</span>
                <i class="fa-solid fa-chevron-right text-[10px] opacity-30 group-hover:opacity-100"></i>
            </button>
            <button onclick="showLocked('program')" class="w-full p-4 bg-gray-100 border border-gray-200 opacity-50 rounded-2xl font-bold text-left flex justify-between items-center cursor-not-allowed grayscale group">
                <span>BSCS</span>
                <i class="fa-solid fa-lock text-[10px] opacity-50"></i>
            </button>`;
    }

    function showYearLevels(prog) {
        title.innerText = "Select Year Level";
        sub.innerText = prog;
        backBtn.classList.remove('hidden');
        backBtn.onclick = () => showPrograms();
        content.innerHTML = '';
        
        years.forEach(year => {
            const isFunctional = (year === '3rd Year'); // ONLY 3RD YEAR
            content.innerHTML += `
                <button onclick="${isFunctional ? `showSections('${prog}', '${year}')` : 'showLocked(\'year level\')'}" 
                        class="w-full p-4 ${isFunctional ? 'bg-gray-50 border-gray-100 hover:bg-[#800000] hover:text-white shadow-sm' : 'bg-gray-100 border-gray-200 opacity-50 grayscale cursor-not-allowed'} border rounded-2xl transition-all font-bold text-left flex justify-between items-center group">
                    <span>${year}</span>
                    <i class="fa-solid ${isFunctional ? 'fa-chevron-right opacity-30 group-hover:opacity-100' : 'fa-lock opacity-50'} text-[10px]"></i>
                </button>`;
        });
    }

    function showSections(prog, year) {
        title.innerText = "Select Section";
        sub.innerText = prog + " | " + year;
        backBtn.onclick = () => showYearLevels(prog);
        content.innerHTML = '<div class="grid grid-cols-2 gap-3">';
        
        sections.forEach(sec => {
            const isFunctional = (sec === '3'); // ONLY SECTION 3 IS FUNCTIONAL
            
            if(isFunctional) {
                content.innerHTML += `
                    <a href="/admin/sections/BSIT-3-3" 
                       class="p-5 bg-white text-[#1a202c] rounded-xl transition-all font-bold text-center block shadow-sm border-2 border-[#FFB800] hover:bg-[#FFB800] hover:text-[#800000] active:scale-[0.98] tracking-tight">
                        Section ${sec}
                    </a>`;
            } else {
                content.innerHTML += `
                    <div class="p-5 bg-gray-100 text-gray-400 rounded-xl font-bold text-center block border border-gray-200 opacity-40 grayscale cursor-not-allowed">
                        Section ${sec} <i class="fa-solid fa-lock text-[8px] ml-1"></i>
                    </div>`;
            }
        });
        content.innerHTML += '</div>';
    }

    function closeDrillDown() { 
        modal.classList.add('hidden'); modal.classList.remove('flex'); 
        document.getElementById('deptSelector').value = ""; 
    }

    function showLogoutModal() { document.getElementById('logoutModal').classList.remove('hidden'); document.getElementById('logoutModal').classList.add('flex'); }
    function hideLogoutModal() { document.getElementById('logoutModal').classList.add('hidden'); document.getElementById('logoutModal').classList.remove('flex'); }
    function executeLogout() { window.location.href = "{{ route('home') }}"; }
</script>
@endsection