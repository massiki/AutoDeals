@php
use App\Http\Controllers\admin\DashboardController;
use Illuminate\Support\Facades\Storage;

function carPhoto($car): string
{
    $photos = $car->photos;
    if (!empty($photos) && isset($photos[0])) {
        $path = $photos[0];
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }
    }
    return asset('assets/images/image-600x400.png');
}

function conditionStyle($condition): string
{
    return match ($condition) {
        'New' => 'bg-success-light text-success-dark',
        'Excellent' => 'bg-info-light text-info-dark',
        'Good' => 'bg-warning-light text-warning-dark',
        'Fair' => 'bg-alert-light text-alert-dark',
        'Poor' => 'bg-error-light text-error-dark',
        default => 'bg-muted text-secondary',
    };
}

function statusStyle($status): string
{
    return match ($status) {
        'Available' => 'bg-success text-white',
        'Reserved' => 'bg-warning text-foreground',
        'Sold' => 'bg-secondary text-white',
        default => 'bg-muted text-secondary',
    };
}
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>AutoDeals - Inventory Management</title>
<meta name="description" content="Car inventory management dashboard for AutoDeals.">
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js" onload="window.lucideLoaded=true; if(window.initLucide) window.initLucide();"></script>
<script>
  window.initLucide = function() { if(window.lucide) lucide.createIcons(); };
  document.addEventListener('DOMContentLoaded', function() { if(window.lucideLoaded) window.initLucide(); });
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
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-position: right 16px center;
    padding-right: 48px;
  }
  .scrollbar-hide::-webkit-scrollbar { display: none; }
  .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
</head>
<body class="font-sans bg-muted min-h-screen overflow-x-hidden flex">

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/80 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="flex flex-col w-[280px] shrink-0 h-screen fixed inset-y-0 left-0 z-50 bg-white border-r border-border transform -translate-x-full lg:translate-x-0 transition-transform duration-300 overflow-hidden">
  <div class="flex items-center justify-between border-b border-border h-[90px] px-5 gap-3 shrink-0">
    <div class="flex items-center gap-3">
      <div class="w-11 h-11 bg-primary rounded-xl flex items-center justify-center shrink-0 shadow-sm">
        <i data-lucide="car" class="w-6 h-6 text-white"></i>
      </div>
      <h1 class="font-bold text-xl text-foreground tracking-tight">AutoDeals</h1>
    </div>
    <button onclick="toggleSidebar()" aria-label="Close sidebar" class="lg:hidden size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
      <i data-lucide="x" class="size-6 text-secondary"></i>
    </button>
  </div>

  <div class="flex flex-col p-5 gap-6 overflow-y-auto flex-1">
    <div class="flex flex-col gap-4">
      <h3 class="font-medium text-xs text-secondary uppercase tracking-wider px-2">Main Menu</h3>
      <div class="flex flex-col gap-1">
        <a href="{{ route('dashboard') }}" class="group active cursor-pointer">
          <div class="flex items-center rounded-2xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
            <i data-lucide="layout-dashboard" class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground transition-all duration-300"></i>
            <span class="font-medium text-secondary group-[.active]:font-bold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Dashboard</span>
          </div>
        </a>
        <a href="#" class="group cursor-pointer" onclick="openPageNotFoundModal(event)">
          <div class="flex items-center rounded-2xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
            <i data-lucide="car-front" class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground transition-all duration-300"></i>
            <span class="font-medium text-secondary group-[.active]:font-bold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Inventory</span>
          </div>
        </a>
        <a href="#" class="group cursor-pointer" onclick="openPageNotFoundModal(event)">
          <div class="flex items-center rounded-2xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
            <i data-lucide="users" class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground transition-all duration-300"></i>
            <span class="font-medium text-secondary group-[.active]:font-bold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Customers</span>
          </div>
        </a>
        <a href="#" class="group cursor-pointer" onclick="openPageNotFoundModal(event)">
          <div class="flex items-center rounded-2xl p-4 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all duration-300">
            <i data-lucide="dollar-sign" class="size-6 text-secondary group-[.active]:text-primary group-hover:text-foreground transition-all duration-300"></i>
            <span class="font-medium text-secondary group-[.active]:font-bold group-[.active]:text-foreground group-hover:text-foreground transition-all duration-300">Sales</span>
          </div>
        </a>
      </div>
    </div>
  </div>

  <div class="p-5 border-t border-border bg-white shrink-0">
    <form method="POST" action="{{ route('logout') }}" id="logout-form">
      @csrf
      <button type="submit" class="group cursor-pointer w-full">
        <div class="flex items-center justify-between p-3 rounded-2xl ring-1 ring-border group-hover:ring-error/50 group-hover:bg-error/5 transition-all duration-300 w-full">
          <div class="flex items-center gap-3 min-w-0">
            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop" alt="{{ Auth::user()->name }}" class="size-10 rounded-full object-cover ring-2 ring-white shadow-sm shrink-0">
            <div class="min-w-0 text-left">
              <p class="text-sm font-bold text-foreground truncate">{{ Auth::user()->name }}</p>
              <p class="text-xs font-medium text-secondary truncate">Administrator</p>
            </div>
          </div>
          <div class="size-8 rounded-xl bg-white flex items-center justify-center shrink-0 group-hover:bg-error/10 transition-colors">
            <i data-lucide="log-out" class="size-4 text-secondary group-hover:text-error transition-colors"></i>
          </div>
        </div>
      </button>
    </form>
  </div>
</aside>

<!-- MAIN CONTENT -->
<main class="flex-1 lg:ml-[280px] flex flex-col min-h-screen w-full">
  <header class="flex items-center justify-between w-full h-[90px] shrink-0 border-b border-border bg-white px-5 md:px-8 sticky top-0 z-30">
    <div class="flex items-center gap-4">
      <button onclick="toggleSidebar()" aria-label="Open menu" class="lg:hidden size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
        <i data-lucide="menu" class="size-6 text-foreground"></i>
      </button>
      <div>
        <h2 class="font-bold text-2xl text-foreground hidden sm:block">Dashboard</h2>
        <p class="text-sm text-secondary font-medium hidden sm:block">Overview of your dealership</p>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <button onclick="openSearchModal()" class="size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer bg-muted/50" aria-label="Search">
        <i data-lucide="search" class="size-5 text-foreground"></i>
      </button>
      <button class="size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer relative bg-muted/50" aria-label="Notifications">
        <i data-lucide="bell" class="size-5 text-foreground"></i>
        <span class="absolute top-2 right-2 size-2.5 rounded-full bg-error border-2 border-white"></span>
      </button>
      <button onclick="openPageNotFoundModal(event)" class="hidden sm:flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full font-bold hover:bg-primary-hover transition-all duration-300 cursor-pointer shadow-sm">
        <i data-lucide="plus" class="size-5"></i>
        <span>Add Vehicle</span>
      </button>
    </div>
  </header>

  <div class="flex-1 overflow-y-auto p-5 md:p-8">

    <!-- Stats Grid 1 (3 items) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-4 md:mb-6">
      <div class="flex flex-col rounded-3xl border border-border p-6 gap-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="size-12 bg-primary/10 rounded-2xl flex items-center justify-center shrink-0">
              <i data-lucide="car" class="size-6 text-primary"></i>
            </div>
            <p class="font-semibold text-secondary">Total Cars</p>
          </div>
        </div>
        <div class="flex items-end justify-between">
          <p class="font-bold text-[36px] leading-none text-foreground">{{ $stats['total'] }}</p>
          <span class="flex items-center gap-1 text-success text-sm font-bold bg-success-light px-2 py-1 rounded-lg mb-1">
            <i data-lucide="trending-up" class="size-3"></i> Total
          </span>
        </div>
      </div>

      <div class="flex flex-col rounded-3xl border border-border p-6 gap-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="size-12 bg-success/10 rounded-2xl flex items-center justify-center shrink-0">
              <i data-lucide="check-circle" class="size-6 text-success"></i>
            </div>
            <p class="font-semibold text-secondary">Available</p>
          </div>
        </div>
        <div class="flex items-end justify-between">
          <p class="font-bold text-[36px] leading-none text-foreground">{{ $stats['available'] }}</p>
          <p class="text-sm font-medium text-secondary mb-1">{{ $stats['total'] > 0 ? round($stats['available'] / $stats['total'] * 100) : 0 }}% of stock</p>
        </div>
      </div>

      <div class="flex flex-col rounded-3xl border border-border p-6 gap-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="size-12 bg-warning/10 rounded-2xl flex items-center justify-center shrink-0">
              <i data-lucide="clock" class="size-6 text-warning-dark"></i>
            </div>
            <p class="font-semibold text-secondary">Reserved</p>
          </div>
        </div>
        <div class="flex items-end justify-between">
          <p class="font-bold text-[36px] leading-none text-foreground">{{ $stats['reserved'] }}</p>
          <p class="text-sm font-medium text-secondary mb-1">{{ $stats['total'] > 0 ? round($stats['reserved'] / $stats['total'] * 100) : 0 }}% of stock</p>
        </div>
      </div>
    </div>

    <!-- Stats Grid 2 (2 items) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-8">
      <div class="flex flex-col rounded-3xl border border-border p-6 gap-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="size-12 bg-info-light rounded-2xl flex items-center justify-center shrink-0">
              <i data-lucide="trending-up" class="size-6 text-info-dark"></i>
            </div>
            <p class="font-semibold text-secondary">Sold This Month</p>
          </div>
        </div>
        <div class="flex items-end justify-between">
          <p class="font-bold text-[36px] leading-none text-foreground">{{ $stats['sold_this_month'] }}</p>
          <span class="flex items-center gap-1 text-success text-sm font-bold bg-success-light px-2 py-1 rounded-lg mb-1">
            <i data-lucide="trending-up" class="size-3"></i> {{ $stats['sold'] }} total sold
          </span>
        </div>
      </div>

      <div class="flex flex-col rounded-3xl border border-border p-6 gap-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="size-12 bg-card-message rounded-2xl flex items-center justify-center shrink-0">
              <i data-lucide="dollar-sign" class="size-6 text-primary"></i>
            </div>
            <p class="font-semibold text-secondary">Total Stock Value</p>
          </div>
        </div>
        <div class="flex items-end justify-between">
          <p class="font-bold text-[32px] leading-none text-foreground">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</p>
          <p class="text-sm font-medium text-secondary mb-1">Estimated</p>
        </div>
      </div>
    </div>

    <!-- Inventory Section -->
    <div class="flex flex-col rounded-3xl border border-border bg-white shadow-sm overflow-hidden">

      <!-- Controls Header -->
      <form method="GET" action="{{ route('dashboard') }}">
        <div class="p-5 md:p-6 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
          <h3 class="font-bold text-lg text-foreground">Vehicle Inventory</h3>

          <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-[240px]">
              <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
              <input type="text" name="search" value="{{ $search }}" class="w-full h-12 pl-11 pr-4 rounded-2xl ring-1 ring-border focus:ring-2 focus:ring-primary outline-none font-medium text-sm transition-all duration-300 bg-muted/30" placeholder="Search stock code, model...">
            </div>

            <div class="relative w-full sm:w-[160px]">
              <select name="brand" onchange="this.form.submit()" class="w-full h-12 pl-4 pr-10 rounded-2xl ring-1 ring-border focus:ring-2 focus:ring-primary outline-none font-medium text-sm transition-all duration-300 bg-muted/30 text-foreground">
                <option value="">All Brands</option>
                @foreach ($brands as $b)
                  <option value="{{ $b }}" {{ $brand === $b ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
              </select>
            </div>

            @if ($search || $brand)
              <a href="{{ route('dashboard') }}" class="shrink-0 size-12 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-error/50 hover:text-error text-secondary transition-all duration-200 cursor-pointer" title="Clear filters">
                <i data-lucide="x" class="size-5"></i>
              </a>
            @endif
          </div>
        </div>
      </form>

      <!-- Data Table -->
      <div class="overflow-x-auto">
        <table class="w-full min-w-[1000px] text-left border-collapse">
          <thead>
            <tr class="bg-muted/50 border-b border-border">
              <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[30%]">Vehicle</th>
              <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[20%]">Details</th>
              <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[20%]">Price & Condition</th>
              <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[15%]">Status</th>
              <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider text-right w-[15%]">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">

            @forelse ($cars as $car)
              <tr class="hover:bg-muted/30 transition-colors duration-200 group">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-4">
                    <div class="relative w-20 h-14 rounded-xl overflow-hidden shrink-0 ring-1 ring-border">
                      <img src="{{ carPhoto($car) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/images/image-600x400.png') }}'">
                    </div>
                    <div class="min-w-0">
                      <p class="font-bold text-foreground truncate text-base mb-1">{{ $car->brand }} {{ $car->model }}</p>
                      <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-md bg-muted text-secondary text-xs font-bold">{{ $car->year }}</span>
                        <span class="px-2 py-0.5 rounded-md bg-muted text-secondary text-xs font-bold">{{ $car->transmission }}</span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col gap-1">
                    <p class="text-sm font-bold text-foreground">{{ $car->stock_code }}</p>
                    <p class="text-xs font-medium text-secondary">{{ number_format($car->kilometer, 0, ',', '.') }} km • {{ $car->color }}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col items-start gap-1.5">
                    <p class="font-bold text-foreground text-base">Rp {{ number_format($car->price, 0, ',', '.') }}</p>
                    <span class="px-2.5 py-1 rounded-full {{ conditionStyle($car->condition) }} text-[10px] font-bold uppercase tracking-wide">{{ $car->condition }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-full {{ statusStyle($car->status) }} text-xs font-bold shadow-sm">
                    {{ $car->status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button class="size-9 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer" title="View Details">
                      <i data-lucide="eye" class="size-4"></i>
                    </button>
                    <button class="size-9 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer" title="Edit">
                      <i data-lucide="edit" class="size-4"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-16 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="size-16 bg-muted rounded-2xl flex items-center justify-center">
                      <i data-lucide="car" class="size-8 text-secondary"></i>
                    </div>
                    <p class="font-bold text-foreground text-lg">No vehicles found</p>
                    <p class="text-sm font-medium text-secondary">Try adjusting your search or filter criteria.</p>
                  </div>
                </td>
              </tr>
            @endforelse

          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="p-5 border-t border-border bg-white flex items-center justify-between flex-col sm:flex-row gap-4">
        <p class="text-sm font-medium text-secondary">
          Showing {{ $cars->firstItem() ?? 0 }} to {{ $cars->lastItem() ?? 0 }} of {{ $cars->total() }} entries
        </p>
        <div class="flex items-center gap-2">
          @if ($cars->onFirstPage())
            <button class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white text-secondary cursor-not-allowed opacity-50" disabled>
              <i data-lucide="chevron-left" class="size-5"></i>
            </button>
          @else
            <a href="{{ $cars->previousPageUrl() }}" class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer">
              <i data-lucide="chevron-left" class="size-5"></i>
            </a>
          @endif

          @foreach ($cars->getUrlRange(max(1, $cars->currentPage() - 2), min($cars->lastPage(), $cars->currentPage() + 2)) as $page => $url)
            @if ($page == $cars->currentPage())
              <span class="size-10 flex items-center justify-center rounded-xl bg-primary text-white font-bold shadow-sm cursor-pointer">{{ $page }}</span>
            @else
              <a href="{{ $url }}" class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:bg-muted font-bold text-secondary transition-all duration-200 cursor-pointer">{{ $page }}</a>
            @endif
          @endforeach

          @if ($cars->hasMorePages())
            <a href="{{ $cars->nextPageUrl() }}" class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer">
              <i data-lucide="chevron-right" class="size-5"></i>
            </a>
          @else
            <button class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white text-secondary cursor-not-allowed opacity-50" disabled>
              <i data-lucide="chevron-right" class="size-5"></i>
            </button>
          @endif
        </div>
      </div>

    </div>
  </div>
</main>

<!-- Search Modal -->
<div id="search-modal" class="fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm">
  <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[80vh] overflow-hidden shadow-2xl flex flex-col">
    <div class="p-4 border-b border-border shrink-0">
      <div class="flex items-center gap-3 bg-muted/50 rounded-2xl px-4 ring-1 ring-border focus-within:ring-primary transition-all">
        <i data-lucide="search" class="size-5 text-secondary"></i>
        <input type="text" id="search-input" placeholder="Search vehicles, stock codes, or customers..." class="flex-1 py-4 bg-transparent outline-none text-foreground font-medium placeholder:text-secondary" oninput="handleSearch(this.value)">
        <kbd class="hidden sm:inline-flex items-center gap-1 px-2 py-1 bg-white rounded-lg text-xs font-bold text-secondary border border-border shadow-sm">ESC</kbd>
      </div>
    </div>
    <div class="p-4 overflow-y-auto flex-1">
      <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-3 px-2">Recent Searches</p>
      <div class="flex flex-col gap-1">
        <a href="#" onclick="openPageNotFoundModal(event)" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-muted transition-all cursor-pointer group">
          <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-primary/20 transition-colors">
            <i data-lucide="car" class="size-5 text-primary"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-bold text-foreground truncate">Toyota Camry 2023</p>
            <p class="text-xs font-medium text-secondary truncate">Inventory Search</p>
          </div>
          <i data-lucide="arrow-right" class="size-4 text-secondary opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </a>
        <a href="#" onclick="openPageNotFoundModal(event)" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-muted transition-all cursor-pointer group">
          <div class="size-10 bg-warning/10 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-warning/20 transition-colors">
            <i data-lucide="hash" class="size-5 text-warning-dark"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-bold text-foreground truncate">INV-004</p>
            <p class="text-xs font-medium text-secondary truncate">Stock Code Search</p>
          </div>
          <i data-lucide="arrow-right" class="size-4 text-secondary opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Page Not Found Modal -->
<div id="page-not-found-modal" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm">
  <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl">
    <div class="w-16 h-16 bg-warning-light rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-sm">
      <i data-lucide="alert-triangle" class="w-8 h-8 text-warning-dark"></i>
    </div>
    <h3 class="text-foreground text-xl font-bold mb-2">Page Not Available</h3>
    <p class="text-secondary text-sm font-medium mb-8">This page hasn't been created yet. Generate it using the chat!</p>
    <button onclick="closePageNotFoundModal()" class="w-full px-4 py-3.5 bg-primary text-white rounded-full font-bold hover:bg-primary-hover transition-all duration-200 cursor-pointer shadow-sm">
      Got it
    </button>
  </div>
</div>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
    document.body.classList.toggle('overflow-hidden');
  }

  function openSearchModal() {
    const modal = document.getElementById('search-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => document.getElementById('search-input').focus(), 10);
  }

  function closeSearchModal() {
    const modal = document.getElementById('search-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  document.getElementById('search-modal').addEventListener('click', function(e) {
    if (e.target === this) closeSearchModal();
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSearchModal();
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
      e.preventDefault();
      openSearchModal();
    }
  });

  function handleSearch(query) {
    console.log('Searching inventory:', query);
  }

  function openPageNotFoundModal(e) {
    e.preventDefault();
    document.getElementById('page-not-found-modal').classList.remove('hidden');
  }

  function closePageNotFoundModal() {
    document.getElementById('page-not-found-modal').classList.add('hidden');
  }
</script>
</body>
</html>
