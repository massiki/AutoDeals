@extends('layouts.admin', ['activeMenu' => 'users'])

@section('title', 'AutoDeals - User Management')

@section('header')
  <div>
    <h2 class="font-bold text-2xl text-foreground hidden sm:block">User Management</h2>
    <p class="text-sm text-secondary font-medium hidden sm:block">Manage system users and staff accounts</p>
  </div>
@endsection

@section('header-actions')
  @parent
  <button onclick="openPageNotFoundModal(event)"
    class="hidden sm:flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full font-bold hover:bg-primary-hover transition-all duration-300 cursor-pointer shadow-sm">
    <i data-lucide="user-plus" class="size-5"></i>
    <span>Add User</span>
  </button>
@endsection

@section('content')
  <div class="flex flex-col rounded-3xl border border-border bg-white shadow-sm overflow-hidden">
    <!-- Controls Header -->
    <div
      class="p-5 md:p-6 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
      <h3 class="font-bold text-lg text-foreground">All Users</h3>

      <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-[240px]">
          <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
          <input type="text" name="search" placeholder="Search name or email..."
            class="w-full h-12 pl-11 pr-4 rounded-2xl ring-1 ring-border focus:ring-2 focus:ring-primary outline-none font-medium text-sm transition-all duration-300 bg-muted/30">
        </div>

        <div class="relative w-full sm:w-[160px]">
          <select name="role"
            class="w-full h-12 pl-4 pr-10 rounded-2xl ring-1 ring-border focus:ring-2 focus:ring-primary outline-none font-medium text-sm transition-all duration-300 bg-muted/30 text-foreground">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
      <table class="w-full min-w-[800px] text-left border-collapse">
        <thead>
          <tr class="bg-muted/50 border-b border-border">
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[35%]">User</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[20%]">Role</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[20%]">Status</th>
            <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider w-[25%]">Joined</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border">
          @forelse ($users as $user)
            <tr class="hover:bg-muted/30 transition-colors duration-200 group">
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <div
                    class="size-11 rounded-xl overflow-hidden shrink-0 ring-1 ring-border bg-primary/10 flex items-center justify-center">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop"
                      alt="{{ $user->name }}"
                      class="w-full h-full object-cover"
                      onerror="this.style.display='none';this.nextElementSibling.classList.remove('hidden')">
                    <i data-lucide="user" class="size-5 text-primary hidden"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="font-bold text-foreground truncate text-base mb-0.5">{{ $user->name }}</p>
                    <p class="text-sm font-medium text-secondary truncate">{{ $user->email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold shadow-sm
                  {{ $user->role === 'admin' ? 'bg-accent-sky text-info-dark' : 'bg-muted text-secondary' }}">
                  <i data-lucide="shield" class="size-3.5 {{ $user->role === 'admin' ? '' : 'hidden' }}"></i>
                  {{ ucfirst($user->role) }}
                </span>
              </td>
              <td class="px-6 py-4">
                @if ($user->email_verified_at)
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-success-light text-success-dark text-xs font-bold">
                    <i data-lucide="check-circle" class="size-3.5"></i>
                    Verified
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-warning-light text-warning-dark text-xs font-bold">
                    <i data-lucide="clock" class="size-3.5"></i>
                    Unverified
                  </span>
                @endif
              </td>
              <td class="px-6 py-4">
                <div class="flex flex-col gap-0.5">
                  <p class="text-sm font-bold text-foreground">{{ $user->created_at->format('d M Y') }}</p>
                  <p class="text-xs font-medium text-secondary">{{ $user->created_at->diffForHumans() }}</p>
                </div>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="size-16 bg-muted rounded-2xl flex items-center justify-center">
                    <i data-lucide="users" class="size-8 text-secondary"></i>
                  </div>
                  <p class="font-bold text-foreground text-lg">No users found</p>
                  <p class="text-sm font-medium text-secondary">There are no user accounts yet.</p>
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
        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
      </p>
      <div class="flex items-center gap-2">
        @if ($users->onFirstPage())
          <button
            class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white text-secondary cursor-not-allowed opacity-50"
            disabled>
            <i data-lucide="chevron-left" class="size-5"></i>
          </button>
        @else
          <a href="{{ $users->previousPageUrl() }}"
            class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:ring-primary hover:text-primary text-secondary transition-all duration-200 cursor-pointer">
            <i data-lucide="chevron-left" class="size-5"></i>
          </a>
        @endif

        @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
          @if ($page == $users->currentPage())
            <span
              class="size-10 flex items-center justify-center rounded-xl bg-primary text-white font-bold shadow-sm">{{ $page }}</span>
          @else
            <a href="{{ $url }}"
              class="size-10 flex items-center justify-center rounded-xl ring-1 ring-border bg-white hover:bg-muted font-bold text-secondary transition-all duration-200 cursor-pointer">{{ $page }}</a>
          @endif
        @endforeach

        @if ($users->hasMorePages())
          <a href="{{ $users->nextPageUrl() }}"
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


