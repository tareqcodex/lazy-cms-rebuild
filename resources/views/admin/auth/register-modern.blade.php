<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Lazy CMS Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .modern-card { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
        .wp-input { border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 14px; width: 100%; transition: all 0.2s; background: #f9fafb; }
        .wp-input:focus { border-color: #6366f1; ring: 2px; ring-color: #6366f1; outline: none; background: #fff; }
        .btn-modern { background: #4f46e5; color: white; padding: 10px; border-radius: 8px; font-weight: 600; transition: background 0.2s; width: 100%; }
        .btn-modern:hover { background: #4338ca; }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 20px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 0px;
            line-height: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 50px;
        }
    </style>
</head>
<body class="flex items-center justify-center min-height-screen p-4" style="min-height: 100vh;">
    <div class="modern-card w-full max-w-[450px] p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Create Account</h1>
            <p class="text-gray-500 text-sm">Join the elite administrative legends</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-3 mb-4 text-xs text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" class="wp-input" placeholder="Tony Stark" value="{{ old('name') }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" class="wp-input" placeholder="legend@lazycms.com" value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" class="wp-input" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" data-target="password">
                        <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div style="position: relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="wp-input" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" data-target="password_confirmation">
                        <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div id="match-feedback" class="text-[11px] font-semibold mt-1 min-h-[15px]"></div>
            </div>

            <button type="submit" class="btn-modern">Register Now</button>
        </form>

        <p class="text-center mt-6 text-sm text-gray-500">
            Already a Legend? <a href="{{ route('admin.login') }}" class="text-indigo-600 hover:text-indigo-500 font-semibold">Sign in</a>
        </p>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.getAttribute('data-target'));
                const type = target.type === 'password' ? 'text' : 'password';
                target.type = type;
                this.style.color = type === 'text' ? '#4f46e5' : '#9ca3af';
            });
        });

        const pwd = document.getElementById('password');
        const confirmPwd = document.getElementById('password_confirmation');
        const matchFeedback = document.getElementById('match-feedback');

        function validateMatch() {
            if (confirmPwd.value.length > 0) {
                if (pwd.value !== confirmPwd.value) {
                    matchFeedback.innerText = "Passwords do not match! ❌";
                    matchFeedback.style.color = "#ef4444";
                } else {
                    matchFeedback.innerText = "Passwords matched! ✅";
                    matchFeedback.style.color = "#10b981";
                }
            } else {
                matchFeedback.innerText = "";
            }
        }

        pwd.addEventListener('input', validateMatch);
        confirmPwd.addEventListener('input', validateMatch);
    </script>
</body>
</html>
