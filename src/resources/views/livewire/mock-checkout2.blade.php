<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Payment Gateway 2 - Modern Split</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl flex flex-col md:flex-row overflow-hidden border border-gray-100">
        
        <div class="w-full md:w-2/5 bg-blue-600 p-12 text-white flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-10">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="text-xl font-extrabold tracking-tight">MockPay v2</span>
                </div>

                <h1 class="text-4xl font-extrabold leading-tight mb-4">Complete Your Secure Payment</h1>
                <p class="text-blue-100 text-lg font-light leading-relaxed">
                    You are paying using our secure, encrypted payment gateway. Your financial data is protected.
                </p>
            </div>

            <div class="mt-12 pt-6 border-t border-blue-500 text-blue-100 space-y-3 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>PCI-DSS Compliant Gateway</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>256-bit SSL Encryption</span>
                </div>
            </div>
        </div>

        <div class="w-full md:w-3/5 p-12 lg:p-16">
            <div class="mb-10 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-950">Payment Details</h2>
                
                <div class="flex items-center gap-1.5 opacity-70">
                    <div class="w-10 h-6 border rounded border-gray-200 bg-gray-50 flex items-center justify-center text-[10px] font-bold text-blue-900">VISA</div>
                    <div class="w-10 h-6 border rounded border-gray-200 bg-gray-50 flex items-center justify-center text-[10px] font-bold text-red-700">MC</div>
                </div>
            </div>

            <form action="#" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Cardholder Full Name</label>
                    <input type="text" name="card_name" class="w-full bg-gray-50 text-gray-950 rounded-xl border border-gray-200 px-5 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400" placeholder="e.g. Abdullah Ahmad" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Card Number</label>
                    <div class="relative">
                        <input type="text" name="card_number" class="w-full bg-gray-50 text-gray-950 rounded-xl border border-gray-200 pl-5 pr-12 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400" placeholder="0000 0000 0000 0000" required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Expiration Date</label>
                        <input type="text" name="expiry" class="w-full bg-gray-50 text-gray-950 rounded-xl border border-gray-200 px-5 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400" placeholder="MM / YY" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">CVV / CVC</label>
                        <div class="relative">
                            <input type="password" name="cvv" class="w-full bg-gray-50 text-gray-950 rounded-xl border border-gray-200 px-5 py-3.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder-gray-400" placeholder="•••" maxlength="4" required>
                            <svg class="absolute inset-y-0 right-0 h-full w-12 p-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center pt-2">
                    <input id="save_card" name="save_card" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="save_card" class="ml-2.5 text-sm text-gray-600">Save this card for future purchases</label>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg py-4 px-6 rounded-xl transition duration-200 mt-8 shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Confirm & Pay Now
                </button>
                
            </form>
        </div>
    </div>

</body>
</html>