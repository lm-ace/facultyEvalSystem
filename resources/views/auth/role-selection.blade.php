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
        <a href="{{ route('how-it-works') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">How It Works</a>
        <a href="{{ route('contact') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Contact</a>
    </div>
</nav>

<div class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-xl shadow-2xl p-8 mx-4">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Hi, PUPian!</h2>
            <p class="text-gray-500 text-sm">Select your role to continue</p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('login.role', 'student') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-transparent hover:border-[#800000]/20 group">
                <div class="bg-[#800000] w-12 h-12 rounded-full flex items-center justify-center text-white mr-4 shadow-md group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Student</h4>
                    <p class="text-xs text-gray-500">Evaluate your faculty members</p>
                </div>
            </a>

            <a href="{{ route('login.role', 'faculty') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-transparent hover:border-[#800000]/20 group">
                <div class="bg-[#800000] w-12 h-12 rounded-full flex items-center justify-center text-white mr-4 shadow-md group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-tie text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Faculty</h4>
                    <p class="text-xs text-gray-500">View your evaluation results</p>
                </div>
            </a>

            <a href="{{ route('login.role', 'admin') }}" class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-transparent hover:border-[#800000]/20 group">
                <div class="bg-[#800000] w-12 h-12 rounded-full flex items-center justify-center text-white mr-4 shadow-md group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Administrator</h4>
                    <p class="text-xs text-gray-500">Manage the evaluation system</p>
                </div>
            </a>
        </div>

        <div class="mt-6 text-center border-t pt-4">
            <button onclick="toggleModal('forgotPasswordModal')" class="text-sm text-[#800000] hover:underline font-semibold">
                Forgot your password?
            </button>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-[#800000] text-sm font-medium transition flex items-center justify-center group">
                <i class="fa-solid fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i> Back to Homepage
            </a>
        </div>
    </div>

    <div class="absolute bottom-6 left-0 right-0 text-center text-white/50 text-xs z-10 px-4">
        Copyright © {{ date('Y') }} | EduRate, Polytechnic University of the Philippines - Main Campus
    </div>
</div>

{{-- MODAL STARTS HERE --}}
<div id="forgotPasswordModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-900/75 transition-opacity backdrop-blur-sm" onclick="toggleModal('forgotPasswordModal')"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg">
                
                <button onclick="toggleModal('forgotPasswordModal')" class="absolute top-4 right-4 text-gray-400 hover:text-[#800000] z-50 transition-colors">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>

                {{-- STEP 1: ENTER EMAIL --}}
                <div id="step1-email" class="p-8">
                    <div class="text-center mb-6">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 mb-4 animate-bounce-slow">
                            <i class="fa-solid fa-envelope text-[#800000] text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Reset Password</h3>
                        <p class="text-sm text-gray-500 mt-2">Enter your email address and we'll send you a verification code.</p>
                    </div>
                    
                    <form action="{{ route('password.send-code') }}" method="POST" id="emailForm">
                        @csrf
                        <div class="mb-5 text-left">
                            <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-at"></i>
                                </span>
                                <input type="email" name="email" id="email" required 
                                       class="w-full pl-10 pr-3 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#800000] focus:border-[#800000] transition-all"
                                       placeholder="juandelacruz@iskolarngbayan.pup.edu.ph"
                                       value="{{ old('email') }}">
                            </div>
                            
                            {{-- ERROR MESSAGE FOR EMAIL --}}
                            @error('email')
                                <p class="text-red-500 text-xs mt-1 font-medium flex items-center">
                                    <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-[#800000] hover:bg-[#660000] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#800000] transition-colors">
                            Send Verification Code
                        </button>
                    </form>
                </div>

                {{-- STEP 2: VERIFY CODE & RESET --}}
                <div id="step2-code" class="p-8 hidden">
                    
                    {{-- SUCCESS MESSAGE (Only shows if code was sent) --}}
                    @if (session('status') == 'code-sent')
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-green-600 text-xl"></i>
                            <div>
                                <p class="font-bold text-sm">Code Sent Successfully!</p>
                                <p class="text-xs">We sent a 6-digit code to <strong>{{ session('email') }}</strong>.</p>
                            </div>
                        </div>
                    @endif

                    <div class="text-center mb-6">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-50 mb-4">
                            <i class="fa-solid fa-shield-check text-green-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Enter Security Code</h3>
                        <p class="text-sm text-gray-500 mt-2">Please check your email for the 6-digit code.</p>
                    </div>

                    <form action="{{ route('password.verify-code') }}" method="POST">
                        @csrf
                        {{-- Hidden email field populated by JS --}}
                        <input type="hidden" name="email" id="hidden_email" value="{{ session('email') ?? old('email') }}">
                        
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 text-center">Verification Code</label>
                            <input type="text" name="code" placeholder="000000" maxlength="6" 
                                   class="text-center w-full tracking-[1em] font-bold text-2xl py-3 border @error('code') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#800000] focus:border-transparent outline-none transition-all">
                            
                            {{-- ERROR MESSAGE FOR CODE --}}
                            @error('code')
                                <p class="text-red-500 text-xs mt-1 text-center font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-4 mb-6 text-left">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">New Password</label>
                                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#800000] focus:border-[#800000]">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#800000] focus:border-[#800000]">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 rounded-lg font-bold text-white bg-[#800000] hover:bg-[#660000] transition-colors shadow-md">
                            Reset Password
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        modal.classList.toggle('hidden');
    }

    document.addEventListener("DOMContentLoaded", function() {
        // LOGIC: If Email Sent OR Error in Step 2 -> Open Modal & Show Step 2
        @if (session('status') == 'code-sent' || $errors->has('code') || $errors->has('password'))
            const modal = document.getElementById('forgotPasswordModal');
            modal.classList.remove('hidden');
            
            // Hide Step 1, Show Step 2
            document.getElementById('step1-email').classList.add('hidden');
            document.getElementById('step2-code').classList.remove('hidden');
            
            // Ensure hidden email is filled
            const email = "{{ session('email') ?? old('email') }}";
            if(document.getElementById('hidden_email')) {
                document.getElementById('hidden_email').value = email;
            }
        @endif

        // LOGIC: If Error in Step 1 (Email not found) -> Open Modal (Stay on Step 1)
        @if ($errors->has('email'))
            const modal = document.getElementById('forgotPasswordModal');
            modal.classList.remove('hidden');
        @endif
    });
</script>
@endsection