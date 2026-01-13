@extends('layouts.app')

@section('content')
<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-10 py-2 text-white bg-[#660000]/85 backdrop-blur-md shadow-lg transition-all duration-300">
    <div class="flex items-center space-x-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8"> 
        <div>
            <h1 class="font-bold leading-none text-base">EduRate</h1>
            <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
        </div>
    </div>
    
    <div class="hidden space-x-1 md:flex items-center">
        <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Home</a>
        <a href="{{ route('about') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">About</a>
        <a href="{{ route('how-it-works') }}" class="bg-white/20 px-4 py-1.5 rounded-lg text-sm font-semibold transition hover:bg-white/30">How It Works</a>
        <a href="{{ route('contact') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Contact</a>
        <a href="{{ route('login') }}" class="ml-4 border border-white/40 px-5 py-1.5 rounded-lg text-sm font-bold hover:bg-white hover:text-[#800000] transition">Login</a>
    </div>
</nav>

<section class="relative h-[45vh] bg-cover bg-center" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/80 flex flex-col items-center justify-center text-center text-white px-4">
        <h2 class="text-4xl md:text-5xl font-bold mb-2 text-white">How It Works</h2>
        <p class="text-lg opacity-90 max-w-2xl text-yellow-400 font-medium">Evaluation process guide for Students and Faculty Members</p>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6 max-w-7xl">
        
        <div class="text-center mb-16">
            <h3 class="text-2xl font-bold text-gray-800">System Workflow</h3>
            <p class="text-gray-500 text-sm mt-2">Find your role below to see how to use the system</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 relative items-start">
            
            <div class="hidden md:block absolute left-1/2 top-0 bottom-0 w-px bg-gray-200 -translate-x-1/2 z-0"></div>

            <div class="group relative z-10 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-center space-x-3 mb-8 bg-[#800000]/5 p-4 rounded-lg border-l-4 border-[#800000]">
                    <i class="fa-solid fa-user-graduate text-[#800000] text-xl"></i>
                    <h4 class="text-xl font-bold text-[#800000]">For Students</h4>
                </div>
                <div class="space-y-8 relative pl-2">
                    <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200"></div>
                    <div class="relative flex items-start space-x-6">
                        <div class="z-10 bg-white border-2 border-[#800000] w-12 h-12 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-[#800000] font-bold text-lg">1</span>
                        </div>
                        <div class="pb-2">
                            <h5 class="text-lg font-bold text-gray-800">Login Account</h5>
                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">Log in using your Student Number and registered password. Ensure you are officially enrolled.</p>
                        </div>
                    </div>
                    <div class="relative flex items-start space-x-6">
                        <div class="z-10 bg-white border-2 border-[#800000] w-12 h-12 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-[#800000] font-bold text-lg">2</span>
                        </div>
                        <div class="pb-2">
                            <h5 class="text-lg font-bold text-gray-800">Evaluate Faculty</h5>
                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">Navigate to "Pending Evaluations". Select a professor from your subject list and answer the survey.</p>
                        </div>
                    </div>
                    <div class="relative flex items-start space-x-6">
                        <div class="z-10 bg-white border-2 border-[#800000] w-12 h-12 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-[#800000] font-bold text-lg">3</span>
                        </div>
                        <div class="pb-2">
                            <h5 class="text-lg font-bold text-gray-800">Submit & Verify</h5>
                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">Review your ratings and comments. Once submitted, a confirmation receipt will be generated.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative z-10 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-center space-x-3 mb-8 bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                    <i class="fa-solid fa-chalkboard-user text-yellow-700 text-xl"></i>
                    <h4 class="text-xl font-bold text-yellow-700">For Faculty Members</h4>
                </div>
                <div class="space-y-8 relative pl-2">
                    <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200"></div>
                    <div class="relative flex items-start space-x-6">
                        <div class="z-10 bg-white border-2 border-yellow-500 w-12 h-12 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-yellow-600 font-bold text-lg">1</span>
                        </div>
                        <div class="pb-2">
                            <h5 class="text-lg font-bold text-gray-800">Access Portal</h5>
                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">Log in to the Faculty Portal. Update your profile and view the list of handled subjects.</p>
                        </div>
                    </div>
                    <div class="relative flex items-start space-x-6">
                        <div class="z-10 bg-white border-2 border-yellow-500 w-12 h-12 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-yellow-600 font-bold text-lg">2</span>
                        </div>
                        <div class="pb-2">
                            <h5 class="text-lg font-bold text-gray-800">View Analytics</h5>
                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">Once the evaluation period closes and grades are submitted, access your detailed performance dashboard.</p>
                        </div>
                    </div>
                    <div class="relative flex items-start space-x-6">
                        <div class="z-10 bg-white border-2 border-yellow-500 w-12 h-12 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-yellow-600 font-bold text-lg">3</span>
                        </div>
                        <div class="pb-2">
                            <h5 class="text-lg font-bold text-gray-800">Generate Report</h5>
                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">Download the PDF summary of your evaluation for documentation, promotion, or accreditation purposes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 bg-white p-8 rounded-xl shadow-lg border-t-4 border-[#800000] relative z-20">
            <h3 class="text-xl font-bold text-[#800000] mb-6 flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-2"></i> Important Reminders
            </h3>
            
            <ul class="space-y-3 pl-2">
                <li class="flex items-start text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-3 text-lg leading-none">•</span> 
                    <span>Evaluation is anonymous and confidential for all parties involved.</span>
                </li>
                <li class="flex items-start text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-3 text-lg leading-none">•</span> 
                    <span>For students, completing the evaluation is a mandatory requirement to view grades.</span>
                </li>
                <li class="flex items-start text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-3 text-lg leading-none">•</span> 
                    <span>Faculty evaluation results are released only <strong>after</strong> grades have been submitted.</span>
                </li>
                <li class="flex items-start text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-3 text-lg leading-none">•</span> 
                    <span>Student identity remains hidden in all evaluation reports and comments.</span>
                </li>
                <li class="flex items-start text-gray-700 text-sm">
                    <span class="text-yellow-600 mr-3 text-lg leading-none">•</span> 
                    <span>Please provide honest, constructive, and professional feedback.</span>
                </li>
            </ul>
        </div>
    </div>
</section>

<footer class="bg-[#660000] text-white pt-16 pb-6 relative z-20">
    <div class="container mx-auto px-10 grid grid-cols-1 md:grid-cols-3 gap-12 text-left">
        <div>
            <div class="flex items-center space-x-3 mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
                <div><h4 class="font-bold leading-none">EduRate</h4><p class="text-xs">Faculty Evaluation System</p></div>
            </div>
            <p class="text-sm opacity-70">Polytechnic University of the Philippines - Main Campus</p>
        </div>
        <div>
            <h4 class="font-bold mb-4">Quick Links</h4>
            <ul class="text-sm space-y-2 opacity-70">
                <li><a href="{{ route('home') }}" class="hover:underline hover:text-yellow-400 transition">Home</a></li>
                <li><a href="{{ route('about') }}" class="hover:underline hover:text-yellow-400 transition">About</a></li>
                <li><a href="{{ route('how-it-works') }}" class="hover:underline hover:text-yellow-400 transition">How It Works</a></li>
                <li><a href="{{ route('contact') }}" class="hover:underline hover:text-yellow-400 transition">Contact</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-4">Contact Us</h4>
            <ul class="text-sm space-y-2 opacity-70">
                <li>PUP Main Campus, Sta. Mesa Manila</li>
                <li>Email: <a href="mailto:edurate@pup.edu.ph" class="hover:underline">edurate@pup.edu.ph</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 mt-16 pt-6 text-center text-xs opacity-50">
        Copyright © 2026 | EduRate, Polytechnic University of the Philippines
    </div>
</footer>
@endsection