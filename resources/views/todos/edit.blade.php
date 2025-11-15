@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <h2>Edit Task</h2>

    <form action="{{ route('todos.update', $todo->id) }}" method="POST" class="mt-3">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="task" class="form-label">Nama Task</label>
            <input type="text" name="task" id="task" class="form-control" value="{{ $todo->task }}" required>
        </div>

        {{-- FIELD BARU: Pilihan Hari --}}
        <div class="mb-3">
            <label for="day" class="form-label">Jadwal Hari</label>
            <select name="day" id="day" class="form-select">
                <option value="">-- Pilih Hari (Opsional) --</option>
                {{-- $days berasal dari TodoController::edit() --}}
                @foreach($days as $day)
                    {{-- Otomatis pilih hari yang sudah tersimpan --}}
                    <option value="{{ $day }}" {{ $todo->day == $day ? 'selected' : '' }}>
                        {{ $day }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-warning">Update Task</button>
        <a href="{{ route('todos.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
    </form>
@endsection
