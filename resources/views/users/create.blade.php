@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="page-header">
    <h2>Tambah User Baru</h2>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <form action="{{ route('users.store') }}" method="POST" class="form">
        @csrf
        
        <div class="form-group">
            <label for="username">Username *</label>
            <input type="text" name="username" id="username" 
                   class="form-control @error('username') is-invalid @enderror" 
                   value="{{ old('username') }}" required>
            @error('username')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" name="password" id="password" 
                   class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">Role *</label>
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="">Pilih Role</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
            </select>
            @error('role')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan User</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div>
@endsection