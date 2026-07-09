@php
  use Illuminate\Support\Facades\Storage;

  function carPhoto($car): string
  {
      if (!$car) return asset('assets/images/image-600x400.png');
      $photos = $car->photos;
      if (!empty($photos) && isset($photos[0])) {
          $path = $photos[0];
          if (Storage::disk('public')->exists($path)) {
              return Storage::url($path);
          }
      }
      return asset('assets/images/image-600x400.png');
  }

  function inquiryBadge($status): string
  {
      return match ($status) {
          'Pending' => 'bg-warning-light text-warning-dark',
          'Test Drive' => 'bg-info-light text-info-dark',
          'Approved' => 'bg-success-light text-success-dark',
          'Rejected' => 'bg-error-light text-error-dark',
          default => 'bg-muted text-secondary',
      };
  }
@endphp

@extends('layouts.admin', ['activeMenu' => 'inquiries'])

@section('title', 'AutoDeals - Inquiry Management')

@section('header')
  <div>
    <h2 class="font-bold text-2xl text-foreground hidden sm:block">Inquiry Management</h2>
    <p class="text-sm text-secondary font-medium hidden sm:block">Manage customer inquiries and requests</p>
  </div>
@endsection

@section('content')
  <div class="flex flex-col rounded-3xl border border-border bg-white shadow-sm overflow-hidden">
    <!-- Controls Header -->
    <form method="GET" action="{{ route('admin.inquiries.index') }}">
      <div class="p-5 md:p-6 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
        <h3 class="font-bold text-lg text-foreground">All Inquiries</h3>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
          <div class="relative w-full sm:w-[220px]">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
            <input type="text" name="search" value="{{ $search }}"
              class="w-full h-12 pl-11 pr-4 rounded-2xl ring-1 ring-border focus:ring-2 focus:ring-primary outline-none font-medium text-sm transition-all duration-300 bg-muted/30"
              placeholder="Search name, email, car...">
          </div>

          <div class="relative w-full sm:w-[160px]">
            <select name="status" onchange="this.form.submit()"
              class="w-full h-12 pl-4 pr-10 rounded-2xl ring-1 ring-border focus:ring-2 focus:ring-primary outline-none font-medium text-sm transition-all duration-300 bg-muted/30 text-foreground">
              <option value="">All Status</option>
              <option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
              <option value="Test Drive" {{ $status === 'Test Drive' ? 'selected' : '' }}>Test Drive</option>
              <option value="Approved" {{ $status === 'Approved' ? 'selected' : '' }}>Approved</option>
              <option value="Rejected" {{ $status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
          </div>

          @if ($search || $status)
            <a href="{{ route('admin.inquiries.index') }}"
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
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[22%]">Customer</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[20%]">Vehicle</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[15%]">Date</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[15%]">Offer Price</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[13%]">Status</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider text-right w-[15%]">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border">
          @forelse ($inquiries as $inquiry)
            <tr class="hover:bg-muted/30 transition-colors duration-200 group">
              <td class="px-6 py-4">
                <div class="flex flex-col">
                  <p class="font-bold text-foreground text-sm">{{ $inquiry->buyer_name }}</p>
                  <p class="text-xs font-medium text-secondary">{{ $inquiry->email }}</p>
                  <p class="text-xs font-medium text-secondary">{{ $inquiry->phone }}</p>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="relative w-16 h-11 rounded-xl overflow-hidden shrink-0 ring-1 ring-border">
                    @if ($inquiry->car)
                      <img src="{{ carPhoto($inquiry->car) }}" alt="Vehicle"
                        class="w-full h-full object-cover"
                        onerror="this.src='{{ asset('assets/images/image-600x400.png') }}'">
                    @else
                      <div class="w-full h-full bg-muted flex items-center justify-center">
                        <i data-lucide="car" class="size-5 text-secondary"></i>
                      </div>
                    @endif
                  </div>
                  <div class="min-w-0">
                    @if ($inquiry->car)
                      <p class="font-bold text-foreground text-sm truncate">{{ $inquiry->car->brand }} {{ $inquiry->car->model }}</p>
                      <p class="text-xs text-secondary">{{ $inquiry->car->stock_code }}</p>
                    @else
                      <p class="text-sm font-medium text-secondary">Deleted</p>
                    @endif
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-bold text-foreground">{{ $inquiry->inquiry_date->format('d M Y') }}</p>
                <p class="text-xs font-medium text-secondary">{{ $inquiry->inquiry_date->format('H:i') }}</p>
              </td>
              <td class="px-6 py-4">
                @if ($inquiry->offer_price)
                  <p class="font-bold text-foreground text-sm">Rp {{ number_format($inquiry->offer_price, 0, ',', '.') }}</p>
                @else
                  <p class="text-sm text-secondary">—</p>
                @endif
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold {{ inquiryBadge($inquiry->status) }}">
                  {{ $inquiry->status }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <a href="{{ route('admin.inquiries.show', $inquiry->id) }}"
                  class="size-9 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer"
                  title="View Details">
                  <i data-lucide="eye" class="size-4"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="size-16 bg-muted rounded-2xl flex items-center justify-center">
                    <i data-lucide="message-square" class="size-8 text-secondary"></i>
                  </div>
                  <p class="font-bold text-foreground text-lg">No inquiries found</p>
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
        Showing {{ $inquiries->firstItem() ?? 0 }} to {{ $inquiries->lastItem() ?? 0 }} of {{ $inquiries->total() }} entries
      </p>
      <div class="flex items-center gap-2">
        @if ($inquiries->onFirstPage())
          <button class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white text-secondary cursor-not-allowed opacity-50" disabled>
            <i data-lucide="chevron-left" class="size-5"></i>
          </button>
        @else
          <a href="{{ $inquiries->previousPageUrl() }}"
            class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer">
            <i data-lucide="chevron-left" class="size-5"></i>
          </a>
        @endif

        @foreach ($inquiries->getUrlRange(max(1, $inquiries->currentPage() - 2), min($inquiries->lastPage(), $inquiries->currentPage() + 2)) as $page => $url)
          @if ($page == $inquiries->currentPage())
            <span class="size-10 flex items-center justify-center rounded-xl bg-primary text-white font-bold shadow-sm">{{ $page }}</span>
          @else
            <a href="{{ $url }}"
              class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:bg-muted font-bold text-secondary transition-all duration-200 cursor-pointer">{{ $page }}</a>
          @endif
        @endforeach

        @if ($inquiries->hasMorePages())
          <a href="{{ $inquiries->nextPageUrl() }}"
            class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer">
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
@endsection
