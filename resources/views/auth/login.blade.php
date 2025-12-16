<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-4xl font-bold text-gray-800">Selamat Datang eToko Bayu</h2>
                <p class="mt-2 text-gray-600">Masuk ke akun kamu untuk berbelanja</p>
            </div>

            <!-- Session Status & Errors -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                </div>

                <!-- Tombol Login -->
                <x-primary-button class="w-full justify-center py-3 text-lg">
                    {{ __('Masuk') }}
                </x-primary-button>
            </form>

            <!-- Link ke Registrasi (Menonjol) -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">Belum punya akun?</p>
                <a href="{{ route('register') }}" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-8 rounded-lg transition shadow-md">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>