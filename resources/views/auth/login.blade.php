<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>AutoDeals - Admin Login</title>
  <meta name="description" content="Login to AutoDeals Car Dealership Management Portal">
  <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"
    onload="window.lucideLoaded=true; if(window.initLucide) window.initLucide();"></script>
  <script>
    window.initLucide = function() {
      if (window.lucide) lucide.createIcons();
    };
    document.addEventListener('DOMContentLoaded', function() {
      if (window.lucideLoaded) window.initLucide();
    });
  </script>
  <style type="text/tailwindcss">
    :root {
      --primary: #165DFF;
      --primary-hover: #0E4BD9;
      --foreground: #080C1A;
      --secondary: #6A7686;
      --muted: #EFF2F7;
      --border: #F3F4F3;
      --card-grey: #F1F3F6;
      --card-message: #C9E6FC;
      --success: #30B22D;
      --success-light: #DCFCE7;
      --error: #ED6B60;
      --warning: #FED71F;
      --font-sans: 'Lexend Deca', sans-serif;
    }

    @theme inline {
      --color-primary: var(--primary);
      --color-primary-hover: var(--primary-hover);
      --color-foreground: var(--foreground);
      --color-secondary: var(--secondary);
      --color-muted: var(--muted);
      --color-border: var(--border);
      --color-card-grey: var(--card-grey);
      --color-card-message: var(--card-message);
      --color-success: var(--success);
      --color-success-light: var(--success-light);
      --color-error: var(--error);
      --color-warning: var(--warning);
      --font-sans: var(--font-sans);
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
      -webkit-box-shadow: 0 0 0 30px white inset !important;
      -webkit-text-fill-color: var(--foreground) !important;
      transition: background-color 5000s ease-in-out 0s;
    }
  </style>
</head>

<body class="font-sans bg-white min-h-screen overflow-hidden selection:bg-primary/20 selection:text-primary">

  <!-- Toast Notification Container -->
  <div id="toast-container" class="fixed top-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none"></div>

  <div class="flex min-h-screen w-full">

    <!-- Left Side: Login Form -->
    <div
      class="w-full lg:w-1/2 flex flex-col justify-center px-6 sm:px-12 md:px-20 lg:px-24 xl:px-32 relative z-10 bg-white">

      <div class="w-full max-w-[420px] mx-auto">
        <!-- Header Section -->
        <div class="mb-10">
          <div
            class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-primary/20">
            <i data-lucide="car" class="w-8 h-8 text-white"></i>
          </div>
          <h1 class="text-[32px] leading-tight font-bold text-foreground mb-2">AutoDeals</h1>
          <p class="text-secondary font-medium text-lg">Car Dealership Management</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
          <div class="mb-6 flex items-center gap-3 px-5 py-4 rounded-2xl bg-success text-white font-semibold text-sm">
            <i data-lucide="check-circle" class="size-5 shrink-0"></i>
            <p>{{ session('status') }}</p>
          </div>
        @endif

        <!-- Form Section -->
        <form method="POST" action="{{ route('login') }}" id="loginForm" class="flex flex-col gap-5 w-full">
          @csrf

          <!-- Email Input (Floating Label Style) -->
          <div>
            <div
              class="relative h-[72px] rounded-3xl ring-1 ring-border focus-within:ring-2 focus-within:ring-primary transition-all duration-300 bg-white group @error('email') ring-error @enderror">
              <i data-lucide="mail"
                class="absolute left-6 top-1/2 size-6 shrink-0 -translate-y-1/2 text-secondary group-focus-within:text-primary transition-colors @error('email') text-error @enderror"></i>
              <div
                class="w-[1.5px] h-6 bg-border absolute left-16 top-1/2 -translate-y-1/2 group-focus-within:bg-primary/30 transition-colors">
              </div>
              <input id="inputEmail" type="email" name="email" placeholder=" " value="{{ old('email') }}"
                class="peer absolute inset-0 w-full h-full bg-transparent font-semibold focus:outline-none pb-4 pl-20 pr-6 pt-9 text-foreground"
                required autocomplete="email" autofocus>
              <label for="inputEmail"
                class="absolute left-20 text-secondary text-sm font-medium transition-all duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 top-4 peer-focus:top-4 -translate-y-0 peer-focus:-translate-y-0 cursor-text peer-focus:text-primary">Email
                Address</label>
            </div>
            @error('email')
              <p class="mt-2 text-sm font-medium text-error flex items-center gap-1.5">
                <i data-lucide="alert-circle" class="size-4 shrink-0"></i>
                {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Password Input (Floating Label Style with Toggle) -->
          <div>
            <div
              class="relative h-[72px] rounded-3xl ring-1 ring-border focus-within:ring-2 focus-within:ring-primary transition-all duration-300 bg-white group @error('password') ring-error @enderror">
              <i data-lucide="lock"
                class="absolute left-6 top-1/2 size-6 shrink-0 -translate-y-1/2 text-secondary group-focus-within:text-primary transition-colors @error('password') text-error @enderror"></i>
              <div
                class="w-[1.5px] h-6 bg-border absolute left-16 top-1/2 -translate-y-1/2 group-focus-within:bg-primary/30 transition-colors">
              </div>
              <input id="inputPassword" type="password" name="password" placeholder=" "
                class="peer absolute inset-0 w-full h-full bg-transparent font-semibold focus:outline-none pb-4 pl-20 pr-16 pt-9 text-foreground"
                required autocomplete="current-password">
              <label for="inputPassword"
                class="absolute left-20 text-secondary text-sm font-medium transition-all duration-300 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 top-4 peer-focus:top-4 -translate-y-0 peer-focus:-translate-y-0 cursor-text peer-focus:text-primary">Password</label>
              <button type="button" onclick="togglePassword()"
                class="absolute right-6 top-1/2 -translate-y-1/2 text-secondary hover:text-primary transition-colors cursor-pointer p-1 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50"
                aria-label="Toggle password visibility">
                <i data-lucide="eye" id="eyeIcon" class="size-5"></i>
              </button>
            </div>
            @error('password')
              <p class="mt-2 text-sm font-medium text-error flex items-center gap-1.5">
                <i data-lucide="alert-circle" class="size-4 shrink-0"></i>
                {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Options Row -->
          <div class="flex items-center justify-between mt-1 mb-3">
            <label class="flex items-center gap-2.5 cursor-pointer group">
              <div
                class="relative flex items-center justify-center size-5 rounded border border-border group-hover:border-primary transition-colors bg-white">
                <input type="checkbox" name="remember" class="peer sr-only" {{ old('remember') ? 'checked' : '' }}>
                <i data-lucide="check" class="size-3.5 text-white opacity-0 peer-checked:opacity-100 absolute z-10"></i>
                <div class="absolute inset-0 bg-primary rounded opacity-0 peer-checked:opacity-100 transition-opacity">
                </div>
              </div>
              <span class="text-sm font-medium text-secondary group-hover:text-foreground transition-colors">Remember
                me</span>
            </label>
            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}"
                class="text-sm font-semibold text-primary hover:text-primary-hover hover:underline transition-all">Forgot
                Password?</a>
            @endif
          </div>

          <!-- Submit Button -->
          <button type="submit" id="submitBtn"
            class="w-full h-[60px] bg-primary text-white rounded-full font-bold text-lg hover:bg-primary-hover transition-all duration-300 shadow-lg shadow-primary/20 flex items-center justify-center gap-2 cursor-pointer group">
            <span>Sign In to Portal</span>
            <i data-lucide="arrow-right" class="size-5 group-hover:translate-x-1 transition-transform"></i>
          </button>
        </form>

        <!-- Footer Links -->
        <div class="mt-10 text-center">
          <p class="text-sm text-secondary font-medium">
            Need access? <a href="#" class="text-primary font-semibold hover:underline">Contact Administrator</a>
          </p>
        </div>
      </div>
    </div>

    <!-- Right Side: Image Banner -->
    <div class="hidden lg:block lg:w-1/2 relative bg-muted">
      <img src="{{ asset('assets/images/01-car.jpg') }}" alt="Luxury Car Showroom"
        class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
      <div
        class="absolute bottom-16 left-16 bg-white/95 backdrop-blur-xl p-6 rounded-3xl shadow-2xl border border-white/40 flex items-center gap-5 transform hover:-translate-y-2 transition-transform duration-500 max-w-sm">
        <div class="size-16 bg-primary/10 rounded-2xl flex items-center justify-center shrink-0">
          <i data-lucide="car-front" class="size-8 text-primary"></i>
        </div>
        <div>
          <p class="text-[32px] font-bold text-foreground leading-tight tracking-tight">200+</p>
          <p class="text-secondary font-medium text-lg">Cars in Stock</p>
        </div>
      </div>
    </div>

  </div>

  <script>
    // Password Visibility Toggle
    function togglePassword() {
      const input = document.getElementById('inputPassword');
      const icon = document.getElementById('eyeIcon');

      if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
      } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
      }
      lucide.createIcons();
    }

    // Form submission loading state
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const btn = document.getElementById('submitBtn');
      const originalContent = btn.innerHTML;

      // Use requestAnimationFrame to allow the browser to show the loading state
      // after the form validation passes (native HTML5 validation)
      setTimeout(() => {
        // Only show loading if form is valid (will actually submit)
        if (this.checkValidity()) {
          btn.innerHTML =
            '<i data-lucide="loader-2" class="size-5 animate-spin"></i><span>Authenticating...</span>';
          btn.disabled = true;
          btn.classList.add('opacity-90', 'cursor-not-allowed');
          lucide.createIcons();
        }
      }, 10);
    });

    // Show validation errors as toasts if there are any
    @if ($errors->any())
      document.addEventListener('DOMContentLoaded', function() {
        @foreach ($errors->all() as $error)
          showToast('{{ $error }}', 'error');
        @endforeach
      });
    @endif

    // Toast Notification System
    function showToast(message, type = 'success') {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');

      const styles = {
        success: {
          bg: 'bg-success',
          icon: 'check-circle'
        },
        error: {
          bg: 'bg-error',
          icon: 'alert-circle'
        }
      };

      const style = styles[type];

      toast.className =
        `flex items-center gap-3 px-5 py-4 rounded-2xl shadow-xl transform transition-all duration-300 translate-x-full opacity-0 ${style.bg} text-white pointer-events-auto`;
      toast.innerHTML = `
      <i data-lucide="${style.icon}" class="size-5 shrink-0"></i>
      <p class="font-semibold text-sm">${message}</p>
    `;

      container.appendChild(toast);
      lucide.createIcons();

      requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
      });

      setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
      }, 3500);
    }

    // Handle dummy links
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('a').forEach(link => {
        if (!link.getAttribute('href') || link.getAttribute('href') === '#') {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = document.getElementById('page-not-found-modal');
            const content = document.getElementById('modal-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
              content.classList.remove('scale-95');
              content.classList.add('scale-100');
            }, 10);
          });
        }
      });
    });

    function closePageNotFoundModal() {
      const modal = document.getElementById('page-not-found-modal');
      const content = document.getElementById('modal-content');
      content.classList.remove('scale-100');
      content.classList.add('scale-95');
      setTimeout(() => {
        modal.classList.add('hidden');
      }, 200);
    }
  </script>

  <!-- Page Not Found Modal (for dummy links) -->
  <div id="page-not-found-modal"
    class="fixed inset-0 bg-black/50 z-[200] hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div
      class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl transform scale-95 transition-transform"
      id="modal-content">
      <div class="w-16 h-16 bg-warning/10 rounded-2xl flex items-center justify-center mx-auto mb-5">
        <i data-lucide="alert-triangle" class="w-8 h-8 text-warning"></i>
      </div>
      <h3 class="text-foreground text-xl font-bold mb-2">Link Not Active</h3>
      <p class="text-secondary text-sm mb-8 font-medium">This is a demo interface. Links are not connected to real
        pages.</p>
      <button onclick="closePageNotFoundModal()"
        class="w-full h-12 bg-primary text-white rounded-full font-bold hover:bg-primary-hover transition-all duration-300 cursor-pointer">
        Got it
      </button>
    </div>
  </div>
</body>

</html>
