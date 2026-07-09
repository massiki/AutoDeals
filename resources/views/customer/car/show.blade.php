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

  function statusStyle($status): string
  {
      return match ($status) {
          'Available' => 'bg-success text-white',
          'Reserved' => 'bg-warning text-foreground',
          'Sold' => 'bg-secondary text-white',
          default => 'bg-muted text-secondary',
      };
  }

  function fuelIcon($fuel): string
  {
      return match ($fuel) {
          'Bensin' => 'fuel',
          'Diesel' => 'fuel',
          'Hybrid' => 'battery-charging',
          'Electric' => 'zap',
          default => 'fuel',
      };
  }
@endphp

@extends('layouts.customer', ['activeMenu' => 'cars'])

@section('title', "AutoDeals - {$car->brand} {$car->model}")

@section('header')
  <div class="flex items-center gap-4">
    <a href="{{ route('cars.catalog') }}"
      class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer shrink-0">
      <i data-lucide="arrow-left" class="size-5"></i>
    </a>
    <div>
      <h2 class="font-bold text-2xl text-foreground hidden sm:block">{{ $car->brand }} {{ $car->model }}</h2>
      <p class="text-sm text-secondary font-medium hidden sm:block">Vehicle Details & Inquire</p>
    </div>
  </div>
@endsection

@section('content')
  <div class="max-w-6xl mx-auto">

    <!-- Hero Image & Badges -->
    <div class="relative w-full h-[250px] md:h-[400px] rounded-3xl overflow-hidden mb-6 ring-1 ring-border shadow-sm group">
      <img src="{{ carPhoto($car) }}" alt="{{ $car->brand }} {{ $car->model }}"
        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
        onerror="this.src='{{ asset('assets/images/image-600x400.png') }}'">

      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

      <div class="absolute top-5 left-5 flex flex-wrap gap-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full {{ statusStyle($car->status) }} text-sm font-bold shadow-sm backdrop-blur-sm">
          <span class="size-2 rounded-full bg-white animate-pulse"></span>
          {{ $car->status }}
        </span>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-white/90 text-sm font-bold shadow-sm backdrop-blur-sm">
          {{ $car->condition }}
        </span>
      </div>

      <div class="absolute bottom-6 left-6 right-6">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-md">{{ $car->year }} {{ $car->brand }} {{ $car->model }}</h1>
        <p class="text-white/90 font-bold text-2xl drop-shadow-md">Rp {{ number_format($car->price, 0, ',', '.') }}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Left: Specs + Photos -->
      <div class="lg:col-span-2 flex flex-col gap-6">

        <!-- Photo Gallery -->
        @if (!empty($photos))
          <div class="bg-white rounded-3xl ring-1 ring-border p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-border">
              <div class="size-9 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="image" class="size-5 text-primary"></i>
              </div>
              <h3 class="text-lg font-bold text-foreground">Photo Gallery</h3>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
              @foreach ($photos as $photo)
                <div class="aspect-video rounded-2xl overflow-hidden ring-1 ring-border">
                  <img src="{{ $photo }}" alt="Vehicle photo" class="w-full h-full object-cover">
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Vehicle Specifications -->
        <div class="bg-white rounded-3xl ring-1 ring-border p-6 md:p-8 shadow-sm">
          <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
              <i data-lucide="list" class="size-5 text-primary"></i>
            </div>
            <h3 class="text-xl font-bold text-foreground">Vehicle Specifications</h3>
          </div>

          <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-6">
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Stock Code</span>
            <span class="text-base font-semibold text-foreground">{{ $car->stock_code }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Brand</span>
              <span class="text-base font-semibold text-foreground">{{ $car->brand }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Model</span>
              <span class="text-base font-semibold text-foreground">{{ $car->model }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Year</span>
              <span class="text-base font-semibold text-foreground">{{ $car->year }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Price</span>
              <span class="text-base font-bold text-foreground">Rp {{ number_format($car->price, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Mileage</span>
              <span class="text-base font-semibold text-foreground">{{ number_format($car->kilometer, 0, ',', '.') }} km</span>
            </div>
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Color</span>
              <span class="text-base font-semibold text-foreground flex items-center gap-2">{{ $car->color }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Transmission</span>
              <span class="text-base font-semibold text-foreground">{{ $car->transmission }}</span>
            </div>
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Fuel Type</span>
              <span class="text-base font-semibold text-foreground flex items-center gap-2">
                <i data-lucide="{{ fuelIcon($car->fuel_type) }}" class="size-4 text-primary"></i>
                {{ $car->fuel_type }}
              </span>
            </div>
            @if ($car->engine_cc)
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Engine CC</span>
              <span class="text-base font-semibold text-foreground">{{ $car->engine_cc }} cc</span>
            </div>
            @endif
            @if ($car->plate_number)
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">License Plate</span>
              <span class="text-base font-semibold text-foreground uppercase">{{ $car->plate_number }}</span>
            </div>
            @endif
            @if ($car->vin)
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">VIN</span>
              <span class="text-base font-semibold text-foreground font-mono text-sm">{{ $car->vin }}</span>
            </div>
            @endif
            <div class="flex flex-col gap-1">
              <span class="text-sm font-medium text-secondary">Condition</span>
              <span class="px-2.5 py-1 rounded-full {{ conditionStyle($car->condition) }} text-xs font-bold uppercase tracking-wide w-fit">{{ $car->condition }}</span>
            </div>
          </div>

          @if ($car->description)
            <div class="mt-6 pt-5 border-t border-border">
              <span class="block text-sm font-medium text-secondary mb-2">Description</span>
              <p class="text-foreground leading-relaxed">{{ $car->description }}</p>
            </div>
          @endif
        </div>

        <!-- Inquiries History -->
        @if ($inquiries->count() > 0)
          <div class="bg-white rounded-3xl ring-1 ring-border overflow-hidden shadow-sm">
            <div class="flex items-center gap-3 p-6 border-b border-border">
              <div class="size-10 bg-info-light rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="history" class="size-5 text-info-dark"></i>
              </div>
              <h3 class="text-xl font-bold text-foreground">Inquiry History</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full min-w-[500px]">
                <thead class="bg-muted/50">
                  <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Buyer</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Offer Price</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-border">
                  @foreach ($inquiries as $inquiry)
                    <tr class="hover:bg-card-grey transition-colors">
                      <td class="px-6 py-4">
                        <p class="font-semibold text-foreground">{{ $inquiry->buyer_name }}</p>
                        <p class="text-xs text-secondary">{{ $inquiry->email }}</p>
                      </td>
                      <td class="px-6 py-4">
                        <p class="font-medium text-foreground">{{ $inquiry->inquiry_date->format('d M Y') }}</p>
                      </td>
                      <td class="px-6 py-4">
                        @if ($inquiry->offer_price)
                          <p class="font-semibold text-foreground">Rp {{ number_format($inquiry->offer_price, 0, ',', '.') }}</p>
                        @else
                          <p class="text-secondary">—</p>
                        @endif
                      </td>
                      <td class="px-6 py-4">
                        @php
                          $inqStyle = match ($inquiry->status) {
                            'Pending' => 'bg-warning-light text-warning-dark',
                            'Test Drive' => 'bg-info-light text-info-dark',
                            'Approved' => 'bg-success-light text-success-dark',
                            'Rejected' => 'bg-error-light text-error-dark',
                            default => 'bg-muted text-secondary',
                          };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $inqStyle }}">
                          {{ $inquiry->status }}
                        </span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif
      </div>

      <!-- Right: Inquiry Form -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl ring-1 ring-border p-6 shadow-sm sticky top-[106px]">

          @if ($car->status === 'Available')
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-border">
              <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="message-square" class="size-5 text-primary"></i>
              </div>
              <h3 class="text-lg font-bold text-foreground">Send Inquiry</h3>
            </div>

            <p class="text-sm text-secondary font-medium mb-5">Interested in this vehicle? Fill out the form below and our team will get back to you.</p>

            <form method="POST" action="{{ route('inquiries.store') }}" class="flex flex-col gap-4">
              @csrf
              <input type="hidden" name="car_id" value="{{ $car->id }}">

              <div>
                <label class="form-label">Full Name</label>
                <div class="relative">
                  <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                  <input type="text" name="buyer_name" value="{{ old('buyer_name', auth()->user()->name ?? '') }}"
                    placeholder="Your full name"
                    class="form-input @error('buyer_name') ring-error @enderror"
                    style="padding-left:44px" required>
                </div>
                @error('buyer_name')
                  <p class="text-xs text-error mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label">Phone Number</label>
                <div class="relative">
                  <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                  <input type="text" name="phone" value="{{ old('phone') }}"
                    placeholder="e.g. 0812-3456-7890"
                    class="form-input @error('phone') ring-error @enderror"
                    style="padding-left:44px" required>
                </div>
                @error('phone')
                  <p class="text-xs text-error mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label">Email Address</label>
                <div class="relative">
                  <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                  <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                    placeholder="your@email.com"
                    class="form-input @error('email') ring-error @enderror"
                    style="padding-left:44px" required>
                </div>
                @error('email')
                  <p class="text-xs text-error mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label">Offer Price (Optional)</label>
                <div class="relative">
                  <i data-lucide="dollar-sign" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
                  <input type="number" name="offer_price" value="{{ old('offer_price') }}"
                    placeholder="Negotiable"
                    class="form-input @error('offer_price') ring-error @enderror"
                    style="padding-left:44px">
                </div>
                @error('offer_price')
                  <p class="text-xs text-error mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label">Notes (Optional)</label>
                <textarea name="notes" rows="3" placeholder="Any specific questions or requests..."
                  class="form-input resize-none @error('notes') ring-error @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                  <p class="text-xs text-error mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              <button type="submit"
                class="w-full h-[52px] bg-primary text-white rounded-full font-bold text-base hover:bg-primary-hover transition-all duration-300 shadow-lg shadow-primary/20 flex items-center justify-center gap-2 cursor-pointer mt-2">
                <i data-lucide="send" class="size-5"></i>
                <span>Submit Inquiry</span>
              </button>
            </form>

          @elseif ($car->status === 'Reserved')
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-border">
              <div class="size-10 bg-warning/10 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="size-5 text-warning"></i>
              </div>
              <h3 class="text-lg font-bold text-foreground">Currently Reserved</h3>
            </div>
            <div class="flex flex-col items-center text-center gap-4 py-4">
              <div class="size-16 bg-warning/10 rounded-full flex items-center justify-center">
                <i data-lucide="car" class="size-8 text-warning"></i>
              </div>
              <div>
                <p class="font-bold text-foreground text-base mb-1">This vehicle is being processed</p>
                <p class="text-sm text-secondary font-medium leading-relaxed">
                  Another customer is currently test driving or has made an offer on this vehicle. Please check back later or browse our other available vehicles.
                </p>
              </div>
              <a href="{{ route('cars.catalog') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 shadow-sm">
                <i data-lucide="car-front" class="size-4"></i>
                <span>Browse Available Cars</span>
              </a>
            </div>

          @elseif ($car->status === 'Sold')
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-border">
              <div class="size-10 bg-secondary/10 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="check-circle" class="size-5 text-secondary"></i>
              </div>
              <h3 class="text-lg font-bold text-foreground">Vehicle Sold</h3>
            </div>
            <div class="flex flex-col items-center text-center gap-4 py-4">
              <div class="size-16 bg-muted rounded-full flex items-center justify-center">
                <i data-lucide="check-circle" class="size-8 text-secondary"></i>
              </div>
              <div>
                <p class="font-bold text-foreground text-base mb-1">This vehicle has been sold</p>
                <p class="text-sm text-secondary font-medium">Thank you for your interest. Browse our other available vehicles.</p>
              </div>
              <a href="{{ route('cars.catalog') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 shadow-sm">
                <i data-lucide="car-front" class="size-4"></i>
                <span>Browse Available Cars</span>
              </a>
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>
@endsection