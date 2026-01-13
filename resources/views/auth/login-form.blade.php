@extends('layouts.app')

@section('content')
@php
    $identifierLabel = 'User ID';
    $placeholder = 'Enter your User-ID';

    if($role == 'student') {
        $placeholder = 'e.g. 2023-00123-MN-0';
    } elseif($role == 'faculty') {
        $identifierLabel = 'Faculty ID';
        $placeholder = 'e.g. FAC-001';
    } elseif($role == 'admin') {
        $identifierLabel = 'Admin ID';
        $placeholder = 'e.g. ADMIN-001';
    }
@endphp

<div class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-xl shadow-2xl p-8 mx-4">
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Hi, PUPian!</h2>
            <p class="text-gray-500 text-sm">Enter your credentials to continue</p>
        </div>

        <div class="flex items-center justify-center space-x-2 mb-6 text-[#800000]">
            <div class="bg-[#800000] p-2 rounded-full text-white">
                <i class="fa-solid @if($role == 'student') fa-graduation-cap @elseif($role == 'faculty') fa-user-tie @else fa-shield-halved @endif"></i>
            </div>
            <span class="font-bold uppercase tracking-wider">{{ ucfirst($role) }} Login</span>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORM START --}}
        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
            @csrf 
            <input type="hidden" name="role" value="{{ $role }}">
            
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase">{{ $identifierLabel }}</label>
                <input type="text" name="user_id" required 
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none" 
                    placeholder="{{ $placeholder }}" value="{{ old('user_id') }}">
            </div>
            
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required 
                        class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none pr-12" 
                        placeholder="Enter your password">
                    
                    <button type="button" onclick="togglePasswordVisibility()" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#800000]">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="w-full py-3 bg-[#800000] text-white font-bold rounded-lg hover:bg-[#660000] transition shadow-lg">
                Login
            </button>
            
            <a href="{{ route('login') }}" class="block w-full text-center py-3 border-2 border-[#800000]/20 text-[#800000] font-bold rounded-lg hover:bg-[#FFB800] transition text-sm">
                BACK TO ROLE SELECTION
            </a>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection