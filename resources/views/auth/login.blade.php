<x-guest-layout>
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-6">
            <x-text-input id="email" class="block w-full px-6 py-4 bg-white border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 text-gray-900" 
                          type="email" name="email" :value="old('email')" placeholder="Email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <x-text-input id="password" class="block w-full px-6 py-4 bg-white border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 text-gray-900" 
                          type="password" name="password" placeholder="Password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-8">
            <label class="flex items-center text-white">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-white hover:text-blue-200 transition">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Tombol Login -->
        <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold py-4 rounded-xl transition shadow-lg text-lg">
            Masuk
        </button>

        <!-- Divider -->
        <div class="my-8 text-center text-white opacity-70">
            <span class="px-4 bg-transparent">atau lanjut dengan</span>
            <hr class="border-white opacity-30 absolute left-0 right-0 top-1/2 -translate-y-1/2">
        </div>

        <!-- Social Login -->

        <!-- Link Register -->
        <p class="text-center text-white mt-8">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-medium text-blue-300 hover:text-blue-200 transition">
                Daftar di sini
            </a>
        </p>
    </form>
</x-guest-layout>