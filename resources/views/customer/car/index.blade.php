@php
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
@endphp

@extends('layouts.customer', ['activeMenu' => 'cars'])

@section('title', 'AutoDeals - Available Cars')

@section('header')
  <div>
    <h2 class="font-bold text-2xl text-foreground hidden sm:block">Available Cars</h2>
    <p class="text-sm text-secondary font-medium hidden sm:block">Browse our inventory and find your next car</p>
  </div>
@endsection

@section('content')
  <!-- Inventory Section -->
  <div class="flex flex-col rounded-3xl border border-border bg-white shadow-sm overflow-hidden">
    <!-- Controls Header -->
    <form method="GET" action="{{ route('cars.catalog') }}">
      <div
        class="p-5 md:p-6 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
        <h3 class="font-bold text-lg text-foreground">Vehicle Inventory</h3>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
          <div class="relative w-full sm:w-[240px]">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
            <input type="text" name="search" value="{{ $search }}"
              class="w-full h-12 pl-11 pr-4 rounded-2xl ring-1 ring-border focus:ring-2 focus:ring-primary outline-none font-medium text-sm transition-all duration-300 bg-muted/30"
              placeholder="Search brand, model...">
          </div>

          <div class="relative w-full sm:w-[160px]">
            <select name="brand" onchange="this.form.submit()"
              class="w-full h-12 pl-4 pr-10 rounded-2xl ring-1 ring-border focus:ring-2 focus:ring-primary outline-none font-medium text-sm transition-all duration-300 bg-muted/30 text-foreground">
              <option value="">All Brands</option>
              @foreach ($brands as $b)
                <option value="{{ $b }}" {{ $brand === $b ? 'selected' : '' }}>{{ $b }}</option>
              @endforeach
            </select>
          </div>

          @if ($search || $brand)
            <a href="{{ route('cars.catalog') }}"
              class="shrink-0 size-12 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-error/50 hover:text-error text-secondary transition-all duration-200"
              title="Clear filters">
              <i data-lucide="x" class="size-5"></i>
            </a>
          @endif
        </div>
      </div>
    </form>

    <!-- Data Table -->
    <div class="overflow-x-auto">
      <table class="w-full min-w-[900px] text-left border-collapse">
        <thead>
          <tr class="bg-muted/50 border-b border-border">
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[30%]">Vehicle</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[20%]">Details</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[25%]">Price & Condition</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider text-right w-[25%]">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border">
          @forelse ($cars as $car)
            <tr class="hover:bg-muted/30 transition-colors duration-200 group">
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <div class="relative w-20 h-14 rounded-xl overflow-hidden shrink-0 ring-1 ring-border">
                    <img src="{{ carPhoto($car) }}" alt="{{ $car->brand }} {{ $car->model }}"
                      class="w-full h-full object-cover"
                      onerror="this.src='{{ asset('assets/images/image-600x400.png') }}'">
                  </div>
                  <div class="min-w-0">
                    <p class="font-bold text-foreground truncate text-base mb-1">{{ $car->brand }}
                      {{ $car->model }}</p>
                    <div class="flex items-center gap-2">
                      <span
                        class="px-2 py-0.5 rounded-md bg-muted text-secondary text-xs font-bold">{{ $car->year }}</span>
                      <span
                        class="px-2 py-0.5 rounded-md bg-muted text-secondary text-xs font-bold">{{ $car->transmission }}</span>
                      <span
                        class="px-2 py-0.5 rounded-md bg-muted text-secondary text-xs font-bold">{{ $car->fuel_type }}</span>
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex flex-col gap-1">
                  <p class="text-sm font-bold text-foreground">{{ $car->stock_code }}</p>
                  <p class="text-xs font-medium text-secondary">{{ number_format($car->kilometer, 0, ',', '.') }} km •
                    {{ $car->color }}</p>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex flex-col items-start gap-1.5">
                  <p class="font-bold text-foreground text-base">Rp {{ number_format($car->price, 0, ',', '.') }}</p>
                  <span
                    class="px-2.5 py-1 rounded-full {{ conditionStyle($car->condition) }} text-[10px] font-bold uppercase tracking-wide">{{ $car->condition }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <a href="{{ route('customer.cars.show', $car->id) }}"
                  class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 shadow-sm cursor-pointer">
                  <i data-lucide="eye" class="size-4"></i>
                  <span>View Details</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="size-16 bg-muted rounded-2xl flex items-center justify-center">
                    <i data-lucide="car" class="size-8 text-secondary"></i>
                  </div>
                  <p class="font-bold text-foreground text-lg">No cars available</p>
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
          <button
            class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white text-secondary cursor-not-allowed opacity-50"
            disabled>
            <i data-lucide="chevron-left" class="size-5"></i>
          </button>
        @else
          <a href="{{ $cars->previousPageUrl() }}"
            class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer">
            <i data-lucide="chevron-left" class="size-5"></i>
          </a>
        @endif

        @foreach ($cars->getUrlRange(max(1, $cars->currentPage() - 2), min($cars->lastPage(), $cars->currentPage() + 2)) as $page => $url)
          @if ($page == $cars->currentPage())
            <span
              class="size-10 flex items-center justify-center rounded-xl bg-primary text-white font-bold shadow-sm">{{ $page }}</span>
          @else
            <a href="{{ $url }}"
              class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:bg-muted font-bold text-secondary transition-all duration-200 cursor-pointer">{{ $page }}</a>
          @endif
        @endforeach

        @if ($cars->hasMorePages())
          <a href="{{ $cars->nextPageUrl() }}"
            class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer">
            <i data-lucide="chevron-right" class="size-5"></i>
          </a>
        @else
          <button
            class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white text-secondary cursor-not-allowed opacity-50"
            disabled>
            <i data-lucide="chevron-right" class="size-5"></i>
          </button>
        @endif
      </div>
    </div>
  </div>
@endsection
