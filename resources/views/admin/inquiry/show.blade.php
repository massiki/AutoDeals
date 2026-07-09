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

@section('title', 'AutoDeals - Inquiry Detail')

@section('header')
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.inquiries.index') }}"
      class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer shrink-0">
      <i data-lucide="arrow-left" class="size-5"></i>
    </a>
    <div>
      <h2 class="font-bold text-2xl text-foreground hidden sm:block">Inquiry Detail</h2>
      <p class="text-sm text-secondary font-medium hidden sm:block">{{ $inquiry->buyer_name }} — {{ $inquiry->car?->brand ?? 'Deleted' }} {{ $inquiry->car?->model ?? '' }}</p>
    </div>
  </div>
@endsection

@section('content')
  @if (session('success'))
    <div class="mb-6 flex items-center gap-3 px-5 py-4 rounded-2xl bg-success text-white font-semibold text-sm">
      <i data-lucide="check-circle" class="size-5 shrink-0"></i>
      <p>{{ session('success') }}</p>
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Inquiry Info + Customer -->
    <div class="lg:col-span-2 flex flex-col gap-6">

      <!-- Customer Info Card -->
      <div class="bg-white rounded-3xl ring-1 ring-border p-6 md:p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
          <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="user" class="size-5 text-primary"></i>
          </div>
          <h3 class="text-xl font-bold text-foreground">Customer Information</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex flex-col gap-1">
            <span class="text-sm font-medium text-secondary">Full Name</span>
            <span class="text-base font-bold text-foreground">{{ $inquiry->buyer_name }}</span>
          </div>
          <div class="flex flex-col gap-1">
            <span class="text-sm font-medium text-secondary">Phone Number</span>
            <a href="tel:{{ $inquiry->phone }}" class="text-base font-semibold text-primary hover:underline">{{ $inquiry->phone }}</a>
          </div>
          <div class="flex flex-col gap-1">
            <span class="text-sm font-medium text-secondary">Email Address</span>
            <a href="mailto:{{ $inquiry->email }}" class="text-base font-semibold text-primary hover:underline">{{ $inquiry->email }}</a>
          </div>
          <div class="flex flex-col gap-1">
            <span class="text-sm font-medium text-secondary">Inquiry Date</span>
            <span class="text-base font-semibold text-foreground">{{ $inquiry->inquiry_date->format('d M Y H:i') }}</span>
          </div>
        </div>
      </div>

      <!-- Vehicle Info Card -->
      <div class="bg-white rounded-3xl ring-1 ring-border p-6 md:p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
          <div class="size-10 bg-warning/10 rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="car" class="size-5 text-warning"></i>
          </div>
          <h3 class="text-xl font-bold text-foreground">Vehicle Information</h3>
        </div>

        @if ($inquiry->car)
          <div class="flex flex-col sm:flex-row gap-6">
            <div class="w-full sm:w-48 h-32 rounded-2xl overflow-hidden ring-1 ring-border shrink-0">
              <img src="{{ carPhoto($inquiry->car) }}" alt="Vehicle"
                class="w-full h-full object-cover"
                onerror="this.src='{{ asset('assets/images/image-600x400.png') }}'">
            </div>
            <div class="grid grid-cols-2 gap-x-6 gap-y-4 flex-1">
              <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-secondary">Brand</span>
                <span class="text-base font-semibold text-foreground">{{ $inquiry->car->brand }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-secondary">Model</span>
                <span class="text-base font-semibold text-foreground">{{ $inquiry->car->model }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-secondary">Stock Code</span>
                <span class="text-base font-semibold text-foreground">{{ $inquiry->car->stock_code }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-secondary">Year</span>
                <span class="text-base font-semibold text-foreground">{{ $inquiry->car->year }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-secondary">Price</span>
                <span class="text-base font-bold text-foreground">Rp {{ number_format($inquiry->car->price, 0, ',', '.') }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-secondary">Status</span>
                <span class="text-base font-semibold text-foreground">{{ $inquiry->car->status }}</span>
              </div>
            </div>
          </div>
          <div class="mt-4 pt-4 border-t border-border">
            <a href="{{ route('cars.edit', $inquiry->car->id) }}"
              class="inline-flex items-center gap-2 text-sm text-primary font-semibold hover:underline">
              <i data-lucide="external-link" class="size-4"></i>
              View full vehicle details
            </a>
          </div>
        @else
          <p class="text-secondary font-medium">This vehicle has been deleted from inventory.</p>
        @endif
      </div>

      <!-- Offer & Notes Card -->
      <div class="bg-white rounded-3xl ring-1 ring-border p-6 md:p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
          <div class="size-10 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="file-text" class="size-5 text-success"></i>
          </div>
          <h3 class="text-xl font-bold text-foreground">Offer & Notes</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="flex flex-col gap-1">
            <span class="text-sm font-medium text-secondary">Offer Price</span>
            @if ($inquiry->offer_price)
              <span class="text-2xl font-bold text-foreground">Rp {{ number_format($inquiry->offer_price, 0, ',', '.') }}</span>
            @else
              <span class="text-base font-semibold text-secondary">No offer specified</span>
            @endif
          </div>
          <div class="flex flex-col gap-1">
            <span class="text-sm font-medium text-secondary">Current Status</span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold w-fit {{ inquiryBadge($inquiry->status) }}">
              {{ $inquiry->status }}
            </span>
          </div>
        </div>

        @if ($inquiry->notes)
          <div class="bg-card-grey rounded-2xl p-5">
            <span class="text-sm font-medium text-secondary mb-2 block">Customer Notes</span>
            <p class="text-foreground leading-relaxed">{{ $inquiry->notes }}</p>
          </div>
        @endif
      </div>
    </div>

    <!-- Right: Update Status Form -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-3xl ring-1 ring-border p-6 shadow-sm sticky top-[106px]">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-border">
          <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="settings-2" class="size-5 text-primary"></i>
          </div>
          <h3 class="text-lg font-bold text-foreground">Update Status</h3>
        </div>

        <form method="POST" action="{{ route('admin.inquiries.update', $inquiry->id) }}" class="flex flex-col gap-5">
          @csrf
          @method('PATCH')

          <div class="flex flex-col gap-3">
            @foreach (['Pending', 'Test Drive', 'Approved', 'Rejected'] as $opt)
              @php
                $icon = match ($opt) {
                  'Pending' => 'clock',
                  'Test Drive' => 'calendar-check',
                  'Approved' => 'check-circle',
                  'Rejected' => 'x-circle',
                };
                $color = match ($opt) {
                  'Pending' => 'bg-warning-light text-warning-dark border-warning/30',
                  'Test Drive' => 'bg-info-light text-info-dark border-info/30',
                  'Approved' => 'bg-success-light text-success-dark border-success/30',
                  'Rejected' => 'bg-error-light text-error-dark border-error/30',
                };
              @endphp
              <label class="flex items-center gap-3 p-4 rounded-2xl ring-1 ring-border cursor-pointer hover:bg-muted has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:bg-primary/5 transition-all">
                <input type="radio" name="status" value="{{ $opt }}"
                  class="sr-only peer"
                  {{ $inquiry->status === $opt ? 'checked' : '' }}>
                <div class="size-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                  <i data-lucide="check" class="size-3 text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
                <span class="text-sm font-semibold text-foreground flex-1">{{ $opt }}</span>
              </label>
            @endforeach
          </div>

          <div>
            <label class="form-label">Admin Notes</label>
            <textarea name="notes" rows="4" placeholder="Add internal notes about this inquiry..."
              class="form-input resize-none">{{ old('notes', $inquiry->notes) }}</textarea>
          </div>

          <button type="submit"
            class="w-full h-[52px] bg-primary text-white rounded-full font-bold text-base hover:bg-primary-hover transition-all duration-300 shadow-lg shadow-primary/20 flex items-center justify-center gap-2 cursor-pointer">
            <i data-lucide="save" class="size-5"></i>
            <span>Save Changes</span>
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
