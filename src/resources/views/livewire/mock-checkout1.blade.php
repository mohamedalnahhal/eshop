<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-gray-50">

<div class="min-h-screen py-12">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">Checkout Mockup</h1>
            <p class="text-gray-500 mt-2">Test Order</p>
        </div>

        <form action="#" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div>
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Billing Address</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700"><i class="fa fa-user"></i> Full Name</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="John M. Doe" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700"><i class="fa fa-envelope"></i> Email</label>
                            <input type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="john@example.com" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700"><i class="fa fa-address-card"></i> Address</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="542 W. 15th Street" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700"><i class="fa fa-institution"></i> City</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="New York" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="NY" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Zip</label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="10001" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Payment</h2>
                    
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Accepted Cards</label>
                            <div class="flex gap-2 text-2xl text-gray-600">
                                <span class="bg-blue-800 text-white px-2 py-1 rounded text-xs font-bold">VISA</span>
                                <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">MasterCard</span>
                                <span class="bg-blue-400 text-white px-2 py-1 rounded text-xs font-bold">AMEX</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name on Card</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="John More Doe" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Credit card number</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="1111-2222-3333-4444" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Exp Month</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="September" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Exp Year</label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="2026" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" placeholder="352" required>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 border-t pt-6">
                <button type="submit" class="w-full bg-blue-800 border border-transparent rounded-md shadow-sm py-3 px-4 text-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Continue to checkout
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>