@extends('layouts.app')

@section('title', 'Login')

@section('content')
@php
    // Define dynamic labels based on the role
    $identifierLabel = 'Username';
    $placeholder = 'Enter your username';

    if($role == 'student') {
        $identifierLabel = 'Student Number';
        $placeholder = 'e.g. 2021-00001-MN-0';
    } elseif($role == 'faculty') {
        $identifierLabel = 'Faculty ID';
        $placeholder = 'Enter Faculty ID';
    }
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">

<nav class="fixed top-0 left-0 right-0 z-50 px-4 md:px-10 py-2 text-white bg-[#660000]/90 backdrop-blur-md shadow-lg transition-all duration-300">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8"> 
            <div>
                <h1 class="font-bold leading-none text-base">EduRate</h1>
                <p class="text-[9px] tracking-tighter uppercase opacity-80">Faculty Evaluation System</p>
            </div>
        </div>
        
        <div class="hidden md:flex items-center space-x-1">
            <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Home</a>
            <a href="{{ route('about') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">About</a>
            <a href="{{ route('how-it-works') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">How It Works</a>
            <a href="{{ route('contact') }}" class="px-4 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10">Contact</a>
        </div>

        <div class="md:hidden flex items-center">
            <button id="mobile-menu-btn" class="text-white focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-[#660000] border-t border-white/10 shadow-xl flex flex-col p-4 space-y-3">
        <a href="{{ route('home') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">Home</a>
        <a href="{{ route('about') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">About</a>
        <a href="{{ route('how-it-works') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">How It Works</a>
        <a href="{{ route('contact') }}" class="block w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">Contact</a>
    </div>
</nav>

<div class="relative min-h-screen flex items-center justify-center bg-cover bg-center pt-16 md:pt-0" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-xl shadow-2xl p-6 md:p-8 mx-4 my-8 md:my-0 animate-fade-in-up">
        
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 md:h-16 mx-auto mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">Hi, PUPian!</h2>
            <p class="text-gray-500 text-xs md:text-sm">Enter your credentials to continue</p>
        </div>

        <div class="flex items-center justify-center space-x-2 mb-6 md:mb-8 text-[#800000]">
            <div class="bg-[#800000] p-2 rounded-full text-white">
                @if($role == 'student') <i class="fa-solid fa-graduation-cap"></i>
                @elseif($role == 'faculty') <i class="fa-solid fa-user-tie"></i>
                @else <i class="fa-solid fa-shield-halved"></i> @endif
            </div>
            <span class="font-bold uppercase tracking-wider text-sm md:text-base">{{ ucfirst($role) }} Login</span>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 md:p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center text-red-800">
                    <i class="fa-solid fa-circle-exclamation mr-2 text-sm"></i>
                    <span class="text-xs md:text-sm font-medium">{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <form id="loginForm" action="{{ route('login.process') }}" method="POST" class="space-y-4 md:space-y-5" autocomplete="off">
            @csrf 
            <input type="hidden" name="role" value="{{ $role }}">
            
            <div>
                <label class="text-[10px] md:text-xs font-bold text-gray-400 uppercase">{{ $identifierLabel }}</label>
                <input type="text" name="username" required
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none text-sm md:text-base transition-colors" 
                    placeholder="{{ $placeholder }}"
                    value="{{ old('username') }}" autocomplete="off">
            </div>
            
            <div>
                <label class="text-[10px] md:text-xs font-bold text-gray-400 uppercase">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required 
                        class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none pr-12 text-sm md:text-base transition-colors" 
                        placeholder="Enter your password" autocomplete="new-password">
                    
                    <button type="button" onclick="togglePasswordVisibility()" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#800000] focus:outline-none mt-1">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="text-right mt-2">
                    <button type="button" onclick="openForgotModal()" class="text-xs font-semibold text-[#800000] hover:underline hover:text-[#660000] transition-colors">
                        Forgot Password?
                    </button>
                </div>
            </div>
            
            <button type="submit" class="w-full py-3 bg-[#800000] text-white font-bold rounded-lg hover:bg-[#660000] transition shadow-lg mb-3 active:scale-[0.98]">
                Login
            </button>
            
            <a href="{{ route('login') }}" 
               class="block w-full text-center py-3 border-2 border-[#800000]/20 bg-white text-[#800000] font-bold rounded-lg transition-all duration-300 hover:bg-[#FFB800] hover:text-[#800000] hover:border-[#FFB800] shadow-sm text-xs md:text-sm uppercase tracking-tight active:scale-[0.98]">
                Back to Role Selection
            </a>
        </form>

        <div class="mt-6 md:mt-8 text-center">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-[#800000] text-xs font-medium transition flex items-center justify-center group">
                <i class="fa-solid fa-arrow-left mr-2 transition-transform group-hover:-translate-x-1"></i> Back to Homepage
            </a>
        </div>
    </div>

    <div class="absolute bottom-4 md:bottom-6 left-0 right-0 text-center text-white/50 text-[10px] md:text-xs z-10 px-4">
        Copyright © {{ date('Y') }} | EduRate, Polytechnic University of the Philippines - Main Campus
    </div>
</div>

<div id="forgotEmailModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity duration-300">
    <div class="bg-white w-full max-w-sm rounded-xl shadow-2xl p-6 animate-fade-in-up relative">
        <button onclick="closeForgotModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        
        <div class="text-center mb-6">
            <div class="bg-red-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-[#800000]">
                <i class="fa-solid fa-envelope text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Reset Password</h3>
            <p class="text-xs text-gray-500 mt-1 px-4">Enter your registered email address and we'll send you a verification code.</p>
        </div>

        <div id="emailError" class="hidden mb-4 text-center text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100"></div>

        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email Address</label>
                <input type="email" id="resetEmail" 
                    class="w-full mt-1 px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none text-sm transition-all" 
                    placeholder="name@example.com">
            </div>
            
            <button onclick="sendOtp()" id="sendOtpBtn" class="w-full py-3 bg-[#800000] text-white font-bold rounded-lg hover:bg-[#660000] transition shadow-md active:scale-[0.98]">
                Send Code
            </button>
        </div>
    </div>
</div>

<div id="forgotOtpModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity duration-300">
    <div class="bg-white w-full max-w-sm rounded-xl shadow-2xl p-6 animate-fade-in-up relative">
        <div class="text-center mb-6">
            <div class="bg-yellow-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-yellow-600">
                <i class="fa-solid fa-shield-halved text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Enter Verification Code</h3>
            <p class="text-xs text-gray-500 mt-1">We sent a 6-digit code to <br><span id="displayEmail" class="font-bold text-[#800000]"></span></p>
        </div>

        <div id="otpError" class="hidden mb-4 text-center text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100"></div>

        <div class="flex justify-center space-x-2 mb-6">
            @for($i=1; $i<=6; $i++)
                <input type="text" maxlength="1" class="otp-input w-10 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:border-[#800000] focus:ring-1 focus:ring-[#800000] outline-none transition-all" oninput="moveFocus(this)" onkeydown="handleBackspace(event, this)">
            @endfor
        </div>
        
        <button onclick="verifyOtp()" id="verifyOtpBtn" class="w-full py-3 bg-[#800000] text-white font-bold rounded-lg hover:bg-[#660000] transition shadow-md active:scale-[0.98]">
            Verify Code
        </button>
        <button onclick="backToEmail()" class="w-full mt-3 py-2 text-xs text-gray-500 hover:text-[#800000] transition-colors">Wrong email? Change it</button>
    </div>
</div>

<div id="forgotNewPassModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity duration-300">
    <div class="bg-white w-full max-w-sm rounded-xl shadow-2xl p-6 animate-fade-in-up relative">
        <div class="text-center mb-6">
            <div class="bg-green-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-green-600">
                <i class="fa-solid fa-lock text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Set New Password</h3>
            <p class="text-xs text-gray-500 mt-1">Please create a strong password for your account.</p>
        </div>

        <div id="passError" class="hidden mb-4 text-center text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100"></div>

        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">New Password</label>
                <input type="password" id="newPassword" class="w-full mt-1 px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none text-sm transition-all" placeholder="Enter new password">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Confirm Password</label>
                <input type="password" id="confirmPassword" class="w-full mt-1 px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none text-sm transition-all" placeholder="Re-enter password">
            </div>
            
            <button onclick="submitNewPassword()" id="resetBtn" class="w-full py-3 bg-[#800000] text-white font-bold rounded-lg hover:bg-[#660000] transition shadow-md active:scale-[0.98] mt-2">
                Reset Password
            </button>
        </div>
    </div>
</div>

<script>
    // 1. CLEAR FORM ON PAGE LOAD / BACK BUTTON
    // This event fires when the page is loaded, even from the bfcache (back-forward cache)
    window.addEventListener('pageshow', function(event) {
        var form = document.getElementById('loginForm');
        if (form) {
            form.reset(); 
        }
        // Force clear password specifically for extra security
        var passInput = document.querySelector('input[name="password"]');
        if(passInput) passInput.value = "";
    });

    // Toggle Password Visibility (Login Form)
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    // Mobile Menu Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        if(btn && menu){
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    });

    // ============= FORGOT PASSWORD LOGIC =============

    let currentEmail = '';

    function openForgotModal() {
        document.getElementById('forgotEmailModal').classList.remove('hidden');
    }

    function closeForgotModal() {
        document.getElementById('forgotEmailModal').classList.add('hidden');
        document.getElementById('forgotOtpModal').classList.add('hidden');
        document.getElementById('forgotNewPassModal').classList.add('hidden');
        // Reset Inputs to clean state
        document.getElementById('resetEmail').value = '';
        document.querySelectorAll('.otp-input').forEach(i => i.value = '');
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmPassword').value = '';
        // Hide errors
        document.getElementById('emailError').classList.add('hidden');
        document.getElementById('otpError').classList.add('hidden');
        document.getElementById('passError').classList.add('hidden');
    }

    function backToEmail() {
        document.getElementById('forgotOtpModal').classList.add('hidden');
        document.getElementById('forgotEmailModal').classList.remove('hidden');
    }

    // Auto-focus logic for OTP inputs
    function moveFocus(element) {
        if (element.value.length >= 1) {
            const next = element.nextElementSibling;
            if (next && next.tagName === 'INPUT') {
                next.focus();
            }
        }
    }

    // Handle backspace in OTP inputs
    function handleBackspace(event, element) {
        if (event.key === 'Backspace' && element.value.length === 0) {
            const prev = element.previousElementSibling;
            if (prev && prev.tagName === 'INPUT') {
                prev.focus();
            }
        }
    }

    // ---- AJAX FUNCTIONS ----
    // NOTE: You must create the backend routes for these to work.

    async function sendOtp() {
        const email = document.getElementById('resetEmail').value;
        const btn = document.getElementById('sendOtpBtn');
        const errorDiv = document.getElementById('emailError');

        if(!email) {
            errorDiv.textContent = "Please enter your email address.";
            errorDiv.classList.remove('hidden');
            return;
        }

        // Loading State
        btn.disabled = true;
        const originalText = btn.innerText;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Sending...';
        errorDiv.classList.add('hidden');

        try {
            // REQUEST TO BACKEND
            const response = await fetch("{{ route('forgot.sendOtp') }}", { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: email })
            });

            const data = await response.json();

            if (data.success) {
                currentEmail = email;
                document.getElementById('displayEmail').innerText = email;
                document.getElementById('forgotEmailModal').classList.add('hidden');
                document.getElementById('forgotOtpModal').classList.remove('hidden');
                // Focus first OTP input
                setTimeout(() => document.querySelector('.otp-input').focus(), 100);
            } else {
                errorDiv.textContent = data.message || "Email not found in our records.";
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error(error);
            errorDiv.textContent = "Something went wrong. Please check your connection.";
            errorDiv.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    async function verifyOtp() {
        let otp = '';
        document.querySelectorAll('.otp-input').forEach(input => otp += input.value);
        
        const btn = document.getElementById('verifyOtpBtn');
        const errorDiv = document.getElementById('otpError');

        if(otp.length !== 6) {
            errorDiv.textContent = "Please enter the complete 6-digit code.";
            errorDiv.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        const originalText = btn.innerText;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Verifying...';
        errorDiv.classList.add('hidden');

        try {
            const response = await fetch("{{ route('forgot.verifyOtp') }}", { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: currentEmail, otp: otp })
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('forgotOtpModal').classList.add('hidden');
                document.getElementById('forgotNewPassModal').classList.remove('hidden');
            } else {
                errorDiv.textContent = "Invalid or expired code. Please try again.";
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            errorDiv.textContent = "System error occurred.";
            errorDiv.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    async function submitNewPassword() {
        const password = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmPassword').value;
        const btn = document.getElementById('resetBtn');
        const errorDiv = document.getElementById('passError');

        if(password !== confirm) {
            errorDiv.textContent = "Passwords do not match.";
            errorDiv.classList.remove('hidden');
            return;
        }
        
        if(password.length < 8) {
            errorDiv.textContent = "Password must be at least 8 characters long.";
            errorDiv.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        const originalText = btn.innerText;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Resetting...';
        errorDiv.classList.add('hidden');

        try {
            const response = await fetch("{{ route('forgot.resetPassword') }}", { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: currentEmail, password: password })
            });

            const data = await response.json();

            if (data.success) {
                alert("Success! Your password has been changed. You can now login.");
                closeForgotModal();
                // Optionally fill the username field for convenience
                // document.querySelector('input[name="username"]').value = ... (if using email as username)
            } else {
                errorDiv.textContent = data.message || "Failed to reset password.";
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            errorDiv.textContent = "System error.";
            errorDiv.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    /* Chrome, Safari, Edge, Opera - Hide number arrows */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
</style>
@endsection