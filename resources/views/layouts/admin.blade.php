<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'AutoDeals')</title>
  <meta name="description" content="AutoDeals Car Dealership Management System.">
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
      --accent-blue: #C9E6FC;
      --accent-teal: #82D9D7;
      --accent-sky: #DBEAFE;
      --success: #30B22D;
      --success-light: #DCFCE7;
      --success-dark: #166534;
      --error: #ED6B60;
      --error-light: #FEE2E2;
      --error-lighter: #FEF2F2;
      --error-dark: #991B1B;
      --warning: #FED71F;
      --warning-light: #FEF9C3;
      --warning-dark: #854D0E;
      --info: #165DFF;
      --info-light: #DBEAFE;
      --info-dark: #1E40AF;
      --alert: #F97316;
      --alert-light: #FFEDD5;
      --alert-dark: #9A3412;
      --gray-50: #F9FAFB;
      --gray-100: #F1F3F6;
      --gray-200: #E5E7EB;
      --gray-500: #6A7686;
      --gray-600: #4B5563;
      --gray-700: #374151;
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
      --color-accent-blue: var(--accent-blue);
      --color-accent-teal: var(--accent-teal);
      --color-accent-sky: var(--accent-sky);
      --color-success: var(--success);
      --color-success-light: var(--success-light);
      --color-success-dark: var(--success-dark);
      --color-error: var(--error);
      --color-error-light: var(--error-light);
      --color-error-lighter: var(--error-lighter);
      --color-error-dark: var(--error-dark);
      --color-warning: var(--warning);
      --color-warning-light: var(--warning-light);
      --color-warning-dark: var(--warning-dark);
      --color-info: var(--info);
      --color-info-light: var(--info-light);
      --color-info-dark: var(--info-dark);
      --color-alert: var(--alert);
      --color-alert-light: var(--alert-light);
      --color-alert-dark: var(--alert-dark);
      --color-gray-50: var(--gray-50);
      --color-gray-100: var(--gray-100);
      --color-gray-200: var(--gray-200);
      --color-gray-500: var(--gray-500);
      --color-gray-600: var(--gray-600);
      --color-gray-700: var(--gray-700);
      --font-sans: var(--font-sans);
    }

    select {
      @apply appearance-none bg-no-repeat cursor-pointer;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236A7686' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
      background-position: right 16px center;
      padding-right: 40px;
    }

    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    .form-input {
      @apply w-full bg-white ring-1 ring-border rounded-2xl px-4 py-3.5 text-foreground font-medium focus:ring-2 focus:ring-primary outline-none transition-all duration-300 placeholder:text-secondary/60;
    }

    .form-label {
      @apply block text-sm font-semibold text-foreground mb-2;
    }
  </style>
  @stack('styles')
</head>

<body class="font-sans bg-muted min-h-screen overflow-x-hidden flex">

  <!-- Mobile Overlay -->
  <div id="sidebar-overlay" class="fixed inset-0 bg-black/80 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

  <!-- SIDEBAR -->
  <aside id="sidebar"
    class="flex flex-col w-[280px] shrink-0 h-screen fixed inset-y-0 left-0 z-50 bg-white border-r border-border transform -translate-x-full lg:translate-x-0 transition-transform duration-300 overflow-hidden">
    <div class="flex items-center justify-between border-b border-border h-[90px] px-5 gap-3 shrink-0">
      <div class="flex items-center gap-3">
        <div class="w-11 h-11 bg-primary rounded-xl flex items-center justify-center shrink-0 shadow-sm">
          <i data-lucide="car" class="w-6 h-6 text-white"></i>
        </div>
        <h1 class="font-bold text-xl text-foreground tracking-tight">AutoDeals</h1>
      </div>
      <button onclick="toggleSidebar()" aria-label="Close sidebar"
        class="lg:hidden size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
        <i data-lucide="x" class="size-6 text-secondary"></i>
      </button>
    </div>

    <div class="flex flex-col p-5 gap-6 overflow-y-auto flex-1">
      <div class="flex flex-col gap-4">
        <h3 class="font-medium text-xs text-secondary uppercase tracking-wider px-2">Main Menu</h3>
        <div class="flex flex-col gap-1">
          @php $active = $activeMenu ?? 'dashboard'; @endphp
          <a href="{{ route('dashboard') }}" class="group {{ $active === 'dashboard' ? 'active' : '' }} cursor-pointer">
            <div
              class="flex items-center rounded-2xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
              <i data-lucide="layout-dashboard"
                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground transition-all duration-300"></i>
              <span
                class="font-medium text-secondary group-[.active]:font-bold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Dashboard</span>
            </div>
          </a>
          <a href="{{ route('cars.create') }}"
            class="group {{ $active === 'inventory' ? 'active' : '' }} cursor-pointer">
            <div
              class="flex items-center rounded-2xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
              <i data-lucide="car-front"
                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground transition-all duration-300"></i>
              <span
                class="font-medium text-secondary group-[.active]:font-bold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Inventory</span>
            </div>
          </a>
          <a href="{{ route('users.index') }}" class="group {{ $active === 'users' ? 'active' : '' }} cursor-pointer">
            <div
              class="flex items-center rounded-2xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
              <i data-lucide="users"
                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground transition-all duration-300"></i>
              <span
                class="font-medium text-secondary group-[.active]:font-bold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Users</span>
            </div>
          </a>
          <a href="{{ route('admin.inquiries.index') }}" class="group {{ $active === 'inquiries' ? 'active' : '' }} cursor-pointer">
            <div
              class="flex items-center rounded-2xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
              <i data-lucide="message-square"
                class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground transition-all duration-300"></i>
              <span
                class="font-medium text-secondary group-[.active]:font-bold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Inquiries</span>
            </div>
          </a>
        </div>
      </div>
    </div>

    <div class="p-5 border-t border-border bg-white shrink-0">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="group cursor-pointer w-full">
          <div
            class="flex items-center justify-between p-3 rounded-2xl ring-1 ring-border group-hover:ring-error/50 group-hover:bg-error/5 transition-all duration-300 w-full">
            <div class="flex items-center gap-3 min-w-0">
              <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop"
                alt="{{ Auth::user()->name }}"
                class="size-10 rounded-full object-cover ring-2 ring-white shadow-sm shrink-0">
              <div class="min-w-0 text-left">
                <p class="text-sm font-bold text-foreground truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs font-medium text-secondary truncate">{{ ucfirst(Auth::user()->role) }}</p>
              </div>
            </div>
            <div
              class="size-8 rounded-xl bg-white flex items-center justify-center shrink-0 group-hover:bg-error/10 transition-colors">
              <i data-lucide="log-out" class="size-4 text-secondary group-hover:text-error transition-colors"></i>
            </div>
          </div>
        </button>
      </form>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 lg:ml-[280px] flex flex-col min-h-screen w-full">
    <!-- Top Navbar -->
    <header
      class="flex items-center justify-between h-[90px] px-5 md:px-8 bg-white border-b border-border shrink-0 sticky top-0 z-30">
      <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()"
          class="lg:hidden size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all cursor-pointer bg-white">
          <i data-lucide="menu" class="size-6 text-foreground"></i>
        </button>
        @section('header')
          <div>
            <h2 class="font-bold text-2xl text-foreground hidden sm:block">Dashboard</h2>
            <p class="text-sm text-secondary font-medium hidden sm:block">Overview</p>
          </div>
        @show
      </div>
    </header>

    <!-- Page Content -->
    <div class="flex-1 overflow-y-auto p-5 md:p-8">
      @yield('content')
    </div>
  </main>

  <!-- Session Flash Toast -->
  @if (session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ session('success') }}', 'success');
      });
    </script>
  @endif

  <!-- Toast Notification -->
  <div id="toast"
    class="fixed bottom-5 right-5 transform translate-y-20 opacity-0 transition-all duration-300 z-[100] flex items-center gap-3 bg-foreground text-white px-5 py-4 rounded-2xl shadow-xl pointer-events-none">
    <i data-lucide="check-circle" class="size-5 text-success"></i>
    <span id="toastMessage" class="font-medium text-sm"></span>
  </div>

  <!-- Page Not Found Modal -->
  <div id="page-not-found-modal"
    class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-6 md:p-8 max-w-sm w-full text-center shadow-2xl">
      <div class="w-16 h-16 bg-warning/10 rounded-full flex items-center justify-center mx-auto mb-5">
        <i data-lucide="alert-triangle" class="w-8 h-8 text-warning"></i>
      </div>
      <h3 class="text-foreground text-xl font-bold mb-2">Page Not Available</h3>
      <p class="text-secondary text-sm mb-8">This feature is not implemented in the current demo.</p>
      <button onclick="closePageNotFoundModal()"
        class="w-full px-4 py-3 bg-primary text-white rounded-full font-bold hover:bg-primary-hover transition-all cursor-pointer">
        Got it
      </button>
    </div>
  </div>

  @yield('modals')

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
      document.body.classList.toggle('overflow-hidden');
    }

    function openPageNotFoundModal(e) {
      e.preventDefault();
      document.getElementById('page-not-found-modal').classList.remove('hidden');
    }

    function closePageNotFoundModal() {
      document.getElementById('page-not-found-modal').classList.add('hidden');
    }

    function showToast(message, type = 'success') {
      const toast = document.getElementById('toast');
      const msgEl = document.getElementById('toastMessage');
      const iconEl = toast.querySelector('i');

      msgEl.textContent = message;

      if (type === 'error') {
        iconEl.setAttribute('data-lucide', 'alert-circle');
        iconEl.className = 'size-5 text-error';
      } else if (type === 'info') {
        iconEl.setAttribute('data-lucide', 'info');
        iconEl.className = 'size-5 text-primary';
      } else {
        iconEl.setAttribute('data-lucide', 'check-circle');
        iconEl.className = 'size-5 text-success';
      }

      lucide.createIcons();
      toast.classList.remove('translate-y-20', 'opacity-0');

      setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
      }, 3000);
    }
  </script>
  @yield('scripts')

</body>

</html>
