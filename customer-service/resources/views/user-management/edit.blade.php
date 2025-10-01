@extends('layouts.sidebar')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="text-gray-600 mt-1">Edit informasi user</p>
        </div>
        <a href="{{ auth()->user()->role === 'manager' ? route('manager.users.index') : route('users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                           placeholder="Masukkan nama lengkap...">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                           placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru (Opsional)</label>
                    <input type="password" name="password" id="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror"
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                @if(auth()->user()->role === 'admin')
                    <select name="role" id="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-300 @enderror">
                        <option value="">Pilih Role</option>
                        <option value="cs" {{ old('role', $user->role) === 'cs' ? 'selected' : '' }}>Customer Service</option>
                        <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                @else
                    <!-- Manager can only see CS role, cannot change it -->
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <input type="text" value="Customer Service" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                    <p class="mt-1 text-sm text-gray-600">Manager tidak dapat mengubah role user</p>
                @endif
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">User aktif</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ auth()->user()->role === 'manager' ? route('manager.users.index') : route('users.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 transform hover:scale-105">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
