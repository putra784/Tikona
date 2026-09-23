<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Tikona</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-[#F8F5EF] font-['Urbanist']">

    <div class="min-h-screen flex">

        <!-- Left -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#E29C23] items-center justify-center p-12">

            <div class="max-w-md text-white">

                <h1 class="text-5xl font-bold mb-4">
                    Join Tikona
                </h1>

                <p class="text-xl leading-relaxed">
                    Create your account and enjoy
                    an easier way to order your favorite coffee.
                </p>

            </div>

        </div>


        <!-- Right -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">

            <div class="w-full max-w-md">

                <!-- Header -->
                <div class="text-center mb-8">

                    <h2 class="text-3xl font-bold text-gray-900">
                        Create Account
                    </h2>

                    <p class="mt-2 text-gray-500">
                        Start your journey with Tikona
                    </p>

                </div>


                <!-- Validation Error -->
                @if ($errors->any())

                    <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4">

                        <ul class="text-sm text-red-600 space-y-1">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif


                <!-- Email Register -->
                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    <!-- Name -->
                    <div>

                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Name
                        </label>

                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            autocomplete="name" placeholder="Your name"
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3.5
                                   outline-none
                                   focus:border-[#E29C23]
                                   focus:ring-2 focus:ring-[#E29C23]/20">

                    </div>


                    <!-- Email -->
                    <div>

                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>

                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            autocomplete="email" placeholder="you@example.com"
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3.5
                                   outline-none
                                   focus:border-[#E29C23]
                                   focus:ring-2 focus:ring-[#E29C23]/20">

                    </div>


                    <!-- Password -->
                    <div>

                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>

                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            placeholder="Create a password"
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3.5
                                   outline-none
                                   focus:border-[#E29C23]
                                   focus:ring-2 focus:ring-[#E29C23]/20">

                    </div>


                    <!-- Confirm Password -->
                    <div>

                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirm Password
                        </label>

                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            autocomplete="new-password" placeholder="Confirm your password"
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3.5
                                   outline-none
                                   focus:border-[#E29C23]
                                   focus:ring-2 focus:ring-[#E29C23]/20">

                    </div>


                    <button type="submit"
                        class="w-full rounded-xl bg-[#E29C23] py-3.5
                               font-semibold text-white
                               transition hover:bg-[#D58F18]">

                        Create Account

                    </button>

                </form>


                <!-- Divider -->
                <div class="flex items-center gap-4 my-7">

                    <div class="h-px flex-1 bg-gray-200"></div>

                    <span class="text-sm text-gray-400">
                        OR
                    </span>

                    <div class="h-px flex-1 bg-gray-200"></div>

                </div>


                <!-- Google -->
                <a href="{{ route('google.login') }}"
                    class="w-full flex items-center justify-center gap-3
                           rounded-xl border border-gray-200
                           bg-white py-3.5
                           font-semibold text-gray-700
                           transition hover:bg-gray-50">

                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">

                        <path
                            d="M21.35 12.27c0-.71-.06-1.39-.18-2.05H12v3.88h5.24a4.48 4.48 0 0 1-1.94 2.94v2.45h3.14c1.84-1.69 2.91-4.18 2.91-7.22Z"
                            fill="#4285F4" />

                        <path
                            d="M12 21.5c2.63 0 4.84-.87 6.45-2.36l-3.14-2.45c-.87.58-1.98.93-3.31.93-2.54 0-4.7-1.72-5.47-4.03H3.28v2.53A9.75 9.75 0 0 0 12 21.5Z"
                            fill="#34A853" />

                        <path d="M6.53 13.59a5.86 5.86 0 0 1 0-3.18V7.88H3.28a9.75 9.75 0 0 0 0 8.24l3.25-2.53Z"
                            fill="#FBBC05" />

                        <path
                            d="M12 6.38c1.43 0 2.72.49 3.73 1.46l2.8-2.8C16.83 3.47 14.62 2.5 12 2.5a9.75 9.75 0 0 0-8.72 5.38l3.25 2.53C7.3 8.1 9.46 6.38 12 6.38Z"
                            fill="#EA4335" />

                    </svg>

                    Continue with Google

                </a>


                <!-- Login -->
                <p class="text-center text-sm text-gray-500 mt-8">

                    Already have an account?

                    <a href="{{ route('login') }}" class="font-semibold text-[#D58F18] hover:underline">

                        Login

                    </a>

                </p>

            </div>

        </div>

    </div>

</body>

</html>
