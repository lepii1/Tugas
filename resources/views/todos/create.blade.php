@extends('layouts.app')

@section('title', "Buat Task Baru")

@section('content')
    <h2>Buat Task Baru</h2>

    <form action="{{ route('todos.store') }}" method="POST" class="mt-3">
        @csrf

        <div class="mb-3">
            <label for="task" class="form-label">Nama Task</label>
            <input type="text" name="task" id="task" class="form-control" required>
        </div>

        {{-- FIELD BARU: Pilihan Hari --}}
        <div class="mb-3">
            <label for="day" class="form-label">Jadwal Hari</label>
            <select name="day" id="day" class="form-select">
                <option value="">-- Pilih Hari (Opsional) --</option>
                {{-- $days berasal dari TodoController::create() --}}
                @foreach($days as $day)
                    <option value="{{ $day }}">{{ $day }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Tambah Task</button>
        <a href="{{ route('todos.index') }}" class="btn btn-secondary">Kembali Ke Daftar</a>
    </form>

@endsection
