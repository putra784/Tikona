<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment - Tikona</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-[#F8F5EF] font-['Urbanist']">

    <div class="max-w-2xl mx-auto px-6 py-12">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Payment
            </h1>

            <p class="mt-2 text-gray-500">
                Complete your payment for order #{{ $transaction->id }}
            </p>
        </div>


        {{-- Order Summary --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm">

            <h2 class="text-xl font-bold text-gray-900 mb-5">
                Order Summary
            </h2>

            <div class="space-y-4">

                @foreach ($transaction->details as $detail)
                    <div class="flex justify-between items-center">

                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $detail->product->name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $detail->quantity }} ×
                                Rp {{ number_format($detail->price, 0, ',', '.') }}
                            </p>
                        </div>

                        <p class="font-semibold text-gray-800">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </p>

                    </div>
                @endforeach

            </div>


            <div class="border-t border-gray-200 my-6"></div>


            <div class="flex justify-between items-center">

                <span class="font-semibold text-gray-700">
                    Total
                </span>

                <span class="text-xl font-bold text-[#E29C23]">
                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                </span>

            </div>

        </div>


        {{-- Payment --}}
        <div class="mt-6">

            @if (!$snapToken)
                {{-- Generate Snap Token --}}
                <form action="{{ route('order.payment.process', $transaction) }}" method="POST">

                    @csrf

                    <button type="submit"
                        class="w-full rounded-xl bg-[#E29C23] py-4
                               font-semibold text-white
                               transition hover:bg-[#D58F18]">
                        Continue to Payment
                    </button>

                </form>
            @else
                {{-- Snap token sudah tersedia --}}
                <div class="text-center">

                    <p class="text-gray-500 mb-4">
                        Opening payment...
                    </p>

                    <button type="button" onclick="openPayment()"
                        class="w-full rounded-xl bg-[#E29C23] py-4
                               font-semibold text-white
                               transition hover:bg-[#D58F18]">
                        Open Payment
                    </button>

                </div>
            @endif

        </div>

    </div>


    {{-- Midtrans Snap --}}
    @if ($snapToken)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>

        <script>
            function openPayment() {

                window.snap.pay('{{ $snapToken }}', {

                    onSuccess: function(result) {

                        fetch("{{ route('order.payment.status', $transaction) }}", {
                                method: "GET",
                                headers: {
                                    "Accept": "application/json",
                                    "X-Requested-With": "XMLHttpRequest"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {

                                console.log("Payment status:", data);

                                if (
                                    data.success &&
                                    data.payment_status === 'paid'
                                ) {
                                    window.location.href =
                                        "{{ route('order.success', $transaction) }}";
                                } else {
                                    alert('Payment has not been confirmed yet.');
                                }

                            })
                            .catch(error => {
                                console.error(error);
                                alert('Unable to check payment status.');
                            });
                    },

                    onPending: function(result) {

                        console.log('Payment pending:', result);

                        // Bisa diarahkan ke halaman status payment
                        window.location.href =
                            "{{ route('order.success', $transaction) }}";
                    },

                    onError: function(result) {

                        console.log('Payment error:', result);

                        alert('Payment failed. Please try again.');
                    },

                    onClose: function() {

                        console.log('Payment popup closed.');

                    }

                });

            }


            // Otomatis buka Midtrans
            document.addEventListener('DOMContentLoaded', function() {

                openPayment();

            });
        </script>
    @endif

</body>

</html>
