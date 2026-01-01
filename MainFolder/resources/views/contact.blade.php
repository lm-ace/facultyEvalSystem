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
        <a href="{{ route('contact') }}" class="bg-white/20 px-4 py-1.5 rounded-lg text-sm font-semibold transition hover:bg-white/30">Contact</a>
        <a href="{{ route('login') }}" class="ml-4 border border-white/40 px-5 py-1.5 rounded-lg text-sm font-bold hover:bg-white hover:text-[#800000] transition">Login</a>
    </div>
</nav>

<section class="relative h-[45vh] bg-cover bg-center" style="background-image: url('{{ asset('images/lagoon.jpg') }}');">
    <div class="absolute inset-0 bg-[#4D0000]/70 flex flex-col items-center justify-center text-center text-white px-4">
        <h2 class="text-4xl md:text-5xl font-bold mb-2">Contact Us</h2>
        <p class="text-lg opacity-90 max-w-2xl text-yellow-400 font-medium">Have questions? We're here to help</p>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                <h3 class="text-2xl font-bold text-[#800000] mb-6">Send us a Message</h3>
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

            <div>
                <h3 class="text-2xl font-bold text-[#800000] mb-4">Contact Information</h3>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    If you have any questions about the Faculty Evaluation System or need technical support, please don't hesitate to reach out to us.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="bg-[#800000]/10 p-3 rounded-lg text-[#800000]">
                            <i class="fa-solid fa-location-dot text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Address</h4>
                            <p class="text-xs text-gray-500 leading-tight mt-1">PUP Main Campus, Sta. Mesa Manila, Philippines</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="bg-[#800000]/10 p-3 rounded-lg text-[#800000]">
                            <i class="fa-solid fa-phone text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Phone</h4>
                            <p class="text-xs text-gray-500 leading-tight mt-1">+63 (XXX) XXX-XXXX</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="bg-[#800000]/10 p-3 rounded-lg text-[#800000]">
                            <i class="fa-solid fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Email</h4>
                            <p class="text-xs text-gray-500 leading-tight mt-1">edurate@pup.edu.ph</p>
                            <p class="text-xs text-gray-500 leading-tight">support@pup.edu.ph</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="bg-[#800000]/10 p-3 rounded-lg text-[#800000]">
                            <i class="fa-solid fa-clock text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Office Hours</h4>
                            <p class="text-xs text-gray-500 leading-tight mt-1">Monday - Friday</p>
                            <p class="text-xs text-gray-500 leading-tight">8:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-[#660000] text-white pt-16 pb-6">
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
                <li><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
                <li><a href="{{ route('about') }}" class="hover:underline">About</a></li>
                <li><a href="{{ route('how-it-works') }}" class="hover:underline">How It Works</a></li>
                <li><a href="{{ route('contact') }}" class="hover:underline">Contact</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold mb-4">Contact Us</h4>
            <ul class="text-sm space-y-2 opacity-70 text-xs">
                <li>PUP Main Campus, Sta. Mesa Manila, Philippines</li>
                <li class="pt-2">Email: edurate@pup.edu.ph</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 mt-16 pt-6 text-center text-xs opacity-50">
        Copyright © 2026 | EduRate, Polytechnic University of the Philippines
    </div>
</footer>
@endsection