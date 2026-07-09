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

@extends('layouts.customer', ['activeMenu' => 'dashboard'])

@section('title', 'AutoDeals - Dashboard')

@section('header')
  <div>
    <h2 class="font-bold text-2xl text-foreground hidden sm:block">Dashboard</h2>
    <p class="text-sm text-secondary font-medium hidden sm:block">Welcome back, {{ auth()->user()->name }}</p>
  </div>
@endsection

@section('content')
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-6">
    <div
      class="flex flex-col rounded-3xl border border-border p-6 gap-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="size-12 bg-primary/10 rounded-2xl flex items-center justify-center shrink-0">
            <i data-lucide="car" class="size-6 text-primary"></i>
          </div>
          <p class="font-semibold text-secondary">Available Cars</p>
        </div>
      </div>
      <div class="flex items-end justify-between">
        <p class="font-bold text-[36px] leading-none text-foreground">{{ $totalAvailable }}</p>
        <a href="{{ route('cars.catalog') }}" class="text-sm font-semibold text-primary hover:underline">Browse</a>
      </div>
    </div>

    <div
      class="flex flex-col rounded-3xl border border-border p-6 gap-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="size-12 bg-info-light rounded-2xl flex items-center justify-center shrink-0">
            <i data-lucide="message-square" class="size-6 text-info-dark"></i>
          </div>
          <p class="font-semibold text-secondary">My Inquiries</p>
        </div>
      </div>
      <div class="flex items-end justify-between">
        <p class="font-bold text-[36px] leading-none text-foreground">{{ $myInquiries }}</p>
        <a href="{{ route('customer.inquiries') }}" class="text-sm font-semibold text-primary hover:underline">View
          all</a>
      </div>
    </div>

    <div
      class="flex flex-col rounded-3xl border border-border p-6 gap-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="size-12 bg-warning/10 rounded-2xl flex items-center justify-center shrink-0">
            <i data-lucide="clock" class="size-6 text-warning-dark"></i>
          </div>
          <p class="font-semibold text-secondary">Active Inquiries</p>
        </div>
      </div>
      <div class="flex items-end justify-between">
        <p class="font-bold text-[36px] leading-none text-foreground">{{ $activeInquiries }}</p>
        <p class="text-sm font-medium text-secondary mb-1">Pending / Test Drive</p>
      </div>
    </div>
  </div>

  <!-- Recent Cars + Recent Inquiries Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Recent Cars -->
    <div class="flex flex-col rounded-3xl border border-border bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between p-5 md:p-6 border-b border-border">
        <div class="flex items-center gap-3">
          <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="car-front" class="size-5 text-primary"></i>
          </div>
          <h3 class="font-bold text-lg text-foreground">Recent Cars</h3>
        </div>
        <a href="{{ route('cars.catalog') }}" class="text-sm font-semibold text-primary hover:underline">View all</a>
      </div>

      <div class="p-5 md:p-6">
        @if ($recentCars->count() > 0)
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($recentCars as $car)
              <a href="{{ route('customer.cars.show', $car->id) }}"
                class="flex flex-col rounded-2xl ring-1 ring-border overflow-hidden hover:ring-primary hover:shadow-sm transition-all duration-300 group">
                <div class="aspect-[16/9] overflow-hidden bg-muted">
                  <img src="{{ carPhoto($car) }}" alt="{{ $car->brand }} {{ $car->model }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    onerror="this.src='{{ asset('assets/images/image-600x400.png') }}'">
                </div>
                <div class="p-3 flex flex-col gap-1">
                  <p class="font-bold text-foreground text-sm truncate">{{ $car->brand }} {{ $car->model }}</p>
                  <div class="flex items-center justify-between">
                    <p class="font-bold text-primary text-sm">Rp {{ number_format($car->price, 0, ',', '.') }}</p>
                    <span class="text-xs text-secondary">{{ $car->year }}</span>
                  </div>
                </div>
              </a>
            @endforeach
          </div>
        @else
          <div class="flex flex-col items-center gap-3 py-10">
            <div class="size-14 bg-muted rounded-2xl flex items-center justify-center">
              <i data-lucide="car" class="size-7 text-secondary"></i>
            </div>
            <p class="font-bold text-foreground">No cars available</p>
            <p class="text-sm text-secondary">Check back later for new inventory.</p>
          </div>
        @endif
      </div>
    </div>

    <!-- Recent Inquiries -->
    <div class="flex flex-col rounded-3xl border border-border bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between p-5 md:p-6 border-b border-border">
        <div class="flex items-center gap-3">
          <div class="size-10 bg-info-light rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="message-square" class="size-5 text-info-dark"></i>
          </div>
          <h3 class="font-bold text-lg text-foreground">Recent Inquiries</h3>
        </div>
        <a href="{{ route('customer.inquiries') }}" class="text-sm font-semibold text-primary hover:underline">View
          all</a>
      </div>

      <div class="p-5 md:p-6">
        @if ($recentInquiries->count() > 0)
          <div class="flex flex-col gap-3">
            @foreach ($recentInquiries as $inquiry)
              <div class="flex items-center gap-4 p-3 rounded-2xl bg-muted/30 hover:bg-muted/60 transition-colors">
                <div class="relative w-16 h-11 rounded-xl overflow-hidden shrink-0 ring-1 ring-border">
                  @if ($inquiry->car)
                    <img src="{{ carPhoto($inquiry->car) }}" alt="Vehicle" class="w-full h-full object-cover"
                      onerror="this.src='{{ asset('assets/images/image-600x400.png') }}'">
                  @else
                    <div class="w-full h-full bg-muted flex items-center justify-center">
                      <i data-lucide="car" class="size-5 text-secondary"></i>
                    </div>
                  @endif
                </div>
                <div class="flex-1 min-w-0">
                  @if ($inquiry->car)
                    <p class="font-bold text-foreground text-sm truncate">{{ $inquiry->car->brand }}
                      {{ $inquiry->car->model }}</p>
                  @else
                    <p class="font-bold text-foreground text-sm">Deleted vehicle</p>
                  @endif
                  <p class="text-xs text-secondary">{{ $inquiry->inquiry_date->format('d M Y') }}</p>
                </div>
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold shrink-0 {{ inquiryBadge($inquiry->status) }}">
                  {{ $inquiry->status }}
                </span>
              </div>
            @endforeach
          </div>
        @else
          <div class="flex flex-col items-center gap-3 py-10">
            <div class="size-14 bg-muted rounded-2xl flex items-center justify-center">
              <i data-lucide="message-square" class="size-7 text-secondary"></i>
            </div>
            <p class="font-bold text-foreground">No inquiries yet</p>
            <p class="text-sm text-secondary">Browse cars and submit your first inquiry.</p>
            <a href="{{ route('cars.catalog') }}"
              class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 shadow-sm mt-1">
              <i data-lucide="car" class="size-4"></i>
              <span>Browse Cars</span>
            </a>
          </div>
        @endif
      </div>
    </div>

  </div>
@endsection
