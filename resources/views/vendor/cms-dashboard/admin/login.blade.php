<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lazy CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #f0f0f1; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-[320px]">
        <div class="text-center mb-8">
            <h1 class="text-[32px] font-bold text-[#3c434a]">Lazy CMS</h1>
        </div>
        
        <form action="{{ route('admin.login') }}" method="POST" class="bg-white p-6 shadow-sm border border-[#c3c4c7]">
            @csrf
            <div class="mb-4">
                <label class="block text-[14px] text-[#3c434a] mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-[#8c8f94] p-2 focus:border-[#2271b1] focus:ring-1 focus:ring-[#2271b1] outline-none" required>
            </div>
            <div class="mb-4">
                <label class="block text-[14px] text-[#3c434a] mb-1">Password</label>
                <input type="password" name="password" class="w-full border border-[#8c8f94] p-2 focus:border-[#2271b1] focus:ring-1 focus:ring-[#2271b1] outline-none" required>
            </div>
            <div class="flex items-center justify-between mt-6">
                <label class="flex items-center text-[13px] text-[#3c434a]">
                    <input type="checkbox" name="remember" class="mr-2"> Remember Me
                </label>
                <button type="submit" class="bg-[#2271b1] text-white px-4 py-1.5 rounded-[3px] text-[13px] font-semibold hover:bg-[#135e96]">Log In</button>
            </div>
        </form>
    </div>
</body>
</html>
