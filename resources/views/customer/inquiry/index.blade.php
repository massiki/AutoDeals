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

  function inquiryStatusStyle($status): string
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

@extends('layouts.customer', ['activeMenu' => 'inquiries'])

@section('title', 'AutoDeals - My Inquiries')

@section('header')
  <div>
    <h2 class="font-bold text-2xl text-foreground hidden sm:block">My Inquiries</h2>
    <p class="text-sm text-secondary font-medium hidden sm:block">Track your vehicle inquiries and their status</p>
  </div>
@endsection

@section('content')
  <div class="flex flex-col rounded-3xl border border-border bg-white shadow-sm overflow-hidden">

    <!-- Header -->
    <div class="p-5 md:p-6 border-b border-border bg-white">
      <h3 class="font-bold text-lg text-foreground">All Inquiries</h3>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
      <table class="w-full min-w-[800px] text-left border-collapse">
        <thead>
          <tr class="bg-muted/50 border-b border-border">
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[30%]">Vehicle</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[18%]">Inquiry Date</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[18%]">Offer Price</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[18%]">Status</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider text-right w-[16%]">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border">
          @forelse ($inquiries as $inquiry)
            <tr class="hover:bg-muted/30 transition-colors duration-200 group">
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <div class="relative w-20 h-14 rounded-xl overflow-hidden shrink-0 ring-1 ring-border">
                    @if ($inquiry->car)
                      <img src="{{ carPhoto($inquiry->car) }}" alt="{{ $inquiry->car->brand }} {{ $inquiry->car->model }}"
                        class="w-full h-full object-cover"
                        onerror="this.src='{{ asset('assets/images/image-600x400.png') }}'">
                    @else
                      <div class="w-full h-full bg-muted flex items-center justify-center">
                        <i data-lucide="car" class="size-6 text-secondary"></i>
                      </div>
                    @endif
                  </div>
                  <div class="min-w-0">
                    @if ($inquiry->car)
                      <p class="font-bold text-foreground truncate text-base mb-0.5">{{ $inquiry->car->brand }}
                        {{ $inquiry->car->model }}</p>
                      <p class="text-xs font-medium text-secondary">{{ $inquiry->car->stock_code }}</p>
                    @else
                      <p class="font-bold text-foreground truncate text-base mb-0.5">Vehicle Deleted</p>
                      <p class="text-xs font-medium text-secondary">No longer in inventory</p>
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
                  <p class="font-bold text-foreground text-sm">Rp {{ number_format($inquiry->offer_price, 0, ',', '.') }}
                  </p>
                @else
                  <p class="text-sm font-medium text-secondary">Not specified</p>
                @endif
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ inquiryStatusStyle($inquiry->status) }}">
                  @php
                    $icon = match ($inquiry->status) {
                        'Pending' => 'clock',
                        'Test Drive' => 'calendar-check',
                        'Approved' => 'check-circle',
                        'Rejected' => 'x-circle',
                        default => 'help-circle',
                    };
                  @endphp
                  <i data-lucide="{{ $icon }}" class="size-3.5"></i>
                  {{ $inquiry->status }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                @if ($inquiry->car)
                  <a href="{{ route('customer.cars.show', $inquiry->car->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary font-semibold text-sm transition-all duration-200">
                    <i data-lucide="eye" class="size-4"></i>
                    <span>View Car</span>
                  </a>
                @else
                  <span class="text-xs font-medium text-secondary">Unavailable</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="size-16 bg-muted rounded-2xl flex items-center justify-center">
                    <i data-lucide="message-square" class="size-8 text-secondary"></i>
                  </div>
                  <p class="font-bold text-foreground text-lg">No inquiries yet</p>
                  <p class="text-sm font-medium text-secondary">Browse available cars and submit your first inquiry.</p>
                  <a href="{{ route('cars.catalog') }}"
                    class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 shadow-sm">
                    <i data-lucide="car" class="size-4"></i>
                    <span>Browse Cars</span>
                  </a>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if ($inquiries->hasPages())
      <div class="p-5 border-t border-border bg-white flex items-center justify-between flex-col sm:flex-row gap-4">
        <p class="text-sm font-medium text-secondary">
          Showing {{ $inquiries->firstItem() ?? 0 }} to {{ $inquiries->lastItem() ?? 0 }} of {{ $inquiries->total() }}
          entries
        </p>
        <div class="flex items-center gap-2">
          @if ($inquiries->onFirstPage())
            <button
              class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white text-secondary cursor-not-allowed opacity-50"
              disabled>
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
              <span
                class="size-10 flex items-center justify-center rounded-xl bg-primary text-white font-bold shadow-sm">{{ $page }}</span>
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
            <button
              class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white text-secondary cursor-not-allowed opacity-50"
              disabled>
              <i data-lucide="chevron-right" class="size-5"></i>
            </button>
          @endif
        </div>
      </div>
    @endif
  </div>
@endsection
