@extends('layouts.app')

@section('title', 'Contact Us')
@section('content')
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
            <a href="{{ route('contact') }}" class="bg-white/20 px-4 py-1.5 rounded-lg text-sm font-semibold transition hover:bg-white/30">Contact</a>
            <a href="{{ route('login') }}" class="ml-4 border border-white/40 px-5 py-1.5 rounded-lg text-sm font-bold hover:bg-white hover:text-[#800000] transition">Login</a>
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
        <a href="{{ route('contact') }}" class="block w-full px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition">Contact</a>
        <a href="{{ route('login') }}" class="block w-full text-center border border-white/40 px-4 py-2 rounded-lg font-bold hover:bg-white hover:text-[#800000] transition">
            Login
        </a>
    </div>
</nav>

<section class="relative h-[40vh] md:h-[45vh] bg-cover bg-center mt-12 md:mt-0" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70 flex flex-col items-center justify-center text-center text-white px-4">
        <h2 class="text-3xl md:text-5xl font-bold mb-2">Contact Us</h2>
        <p class="text-base md:text-lg opacity-90 max-w-2xl text-yellow-400 font-medium">Have questions? We're here to help</p>
    </div>
</section>

<section class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6 max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
            
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl border border-gray-100 order-2 lg:order-1">
                <h3 class="text-xl md:text-2xl font-bold text-[#800000] mb-6">Send us a Message</h3>
                <form action="#" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Name</label>
                            <input type="text" placeholder="Your name" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none transition">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Email</label>
                            <input type="email" placeholder="your@email.com" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase ml-1">Subject</label>
                        <input type="text" placeholder="What's this about?" class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none transition">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase ml-1">Message</label>
                        <textarea rows="4" placeholder="Your message..." class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#800000] outline-none transition"></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-[#800000] text-white font-bold rounded-xl shadow-lg hover:bg-[#660000] transition duration-300 uppercase tracking-widest text-sm">
                        Send Message
                    </button>
                </form>
            </div>

            <div class="order-1 lg:order-2">
                <h3 class="text-xl md:text-2xl font-bold text-[#800000] mb-4">Contact Information</h3>
                <p class="text-gray-600 mb-8 leading-relaxed text-sm md:text-base">
                    If you have any questions about the Faculty Evaluation System or need technical support, please don't hesitate to reach out to us.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div class="bg-white p-5 md:p-6 rounded-xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="bg-[#800000]/10 p-3 rounded-lg text-[#800000] shrink-0">
                            <i class="fa-solid fa-location-dot text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm md:text-base">Address</h4>
                            <p class="text-xs text-gray-500 leading-tight mt-1">PUP Main Campus, Sta. Mesa Manila, Philippines</p>
                        </div>
                    </div>

                    <div class="bg-white p-5 md:p-6 rounded-xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="bg-[#800000]/10 p-3 rounded-lg text-[#800000] shrink-0">
                            <i class="fa-solid fa-phone text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm md:text-base">Phone</h4>
                            <p class="text-xs text-gray-500 leading-tight mt-1">+63 (XXX) XXX-XXXX</p>
                        </div>
                    </div>

                    <div class="bg-white p-5 md:p-6 rounded-xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="bg-[#800000]/10 p-3 rounded-lg text-[#800000] shrink-0">
                            <i class="fa-solid fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm md:text-base">Email</h4>
                            <p class="text-xs text-gray-500 leading-tight mt-1 break-all">edurate@pup.edu.ph</p>
                            <p class="text-xs text-gray-500 leading-tight break-all">support@pup.edu.ph</p>
                        </div>
                    </div>

                    <div class="bg-white p-5 md:p-6 rounded-xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="bg-[#800000]/10 p-3 rounded-lg text-[#800000] shrink-0">
                            <i class="fa-solid fa-clock text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm md:text-base">Office Hours</h4>
                            <p class="text-xs text-gray-500 leading-tight mt-1">Monday - Friday</p>
                            <p class="text-xs text-gray-500 leading-tight">8:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-[#660000] text-white pt-12 md:pt-16 pb-6 relative z-20">
    <div class="container mx-auto px-6 md:px-10 grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 text-center md:text-left">
        <div>
            <div class="flex items-center justify-center md:justify-start space-x-3 mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
                <div class="text-left"><h4 class="font-bold leading-none">EduRate</h4><p class="text-xs">Faculty Evaluation System</p></div>
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
    <div class="border-t border-white/10 mt-12 md:mt-16 pt-6 text-center text-xs opacity-50 px-4">
        Copyright © 2026 | EduRate, Polytechnic University of the Philippines
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        if(btn && menu){
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>
@endsection