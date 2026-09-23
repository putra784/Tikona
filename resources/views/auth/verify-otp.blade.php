<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify Email - Tikona</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body class="min-h-screen bg-[#F8F5EF] font-['Urbanist']">

    <div class="min-h-screen flex items-center justify-center px-6">

        <div class="w-full max-w-md">

            <!-- Logo / Brand -->
            <div class="text-center mb-8">

                <div
                    class="mx-auto mb-6 w-16 h-16
                           flex items-center justify-center
                           rounded-2xl bg-[#E29C23]">

                    <span class="text-2xl font-bold text-white">
                        T
                    </span>

                </div>

                <h1 class="text-3xl font-bold text-gray-900">
                    Verify Your Email
                </h1>

                <p class="mt-3 text-gray-500 leading-relaxed">

                    We've sent a verification code to

                    <br>

                    <span class="font-semibold text-gray-700">
                        {{ session('verification_email') ?? old('email') }}
                    </span>

                </p>

            </div>


            <!-- Success -->
            @if (session('success'))
                <div
                    class="mb-5 rounded-xl
                           bg-green-50 border border-green-200
                           p-4">

                    <p class="text-sm text-green-600">
                        {{ session('success') }}
                    </p>

                </div>
            @endif


            <!-- Error -->
            @if ($errors->any())

                <div
                    class="mb-5 rounded-xl
                           bg-red-50 border border-red-200
                           p-4">

                    <ul class="text-sm text-red-600 space-y-1">

                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <!-- OTP Form -->
            <form action="{{ route('otp.verify') }}" method="POST" class="bg-white rounded-3xl p-7 shadow-sm">

                @csrf


                <div class="mb-6">

                    <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2 text-center">

                        Enter verification code

                    </label>

                    <input type="text" id="otp" name="otp" inputmode="numeric" autocomplete="one-time-code"
                        maxlength="6" pattern="[0-9]{6}" required placeholder="000000"
                        class="w-full rounded-xl border border-gray-200
                               bg-gray-50 px-4 py-4
                               text-center text-2xl font-bold
                               tracking-[0.5em]
                               outline-none
                               focus:border-[#E29C23]
                               focus:ring-2 focus:ring-[#E29C23]/20">

                </div>


                <button type="submit"
                    class="w-full rounded-xl
                           bg-[#E29C23] py-3.5
                           font-semibold text-white
                           transition hover:bg-[#D58F18]">

                    Verify Email

                </button>

            </form>


            <!-- Resend -->
            <div class="text-center mt-6">

                <p class="text-sm text-gray-500">
                    Didn't receive the code?
                </p>

                <form action="{{ route('otp.resend') }}" method="POST" class="inline">

                    @csrf

                    <button type="submit"
                        class="mt-2 text-sm
                               font-semibold
                               text-[#D58F18]
                               hover:underline">

                        Resend OTP

                    </button>

                </form>

            </div>


            <!-- Back -->
            <div class="text-center mt-6">

                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">

                    ← Back to Login

                </a>

            </div>

        </div>

    </div>

</body>

</html>
