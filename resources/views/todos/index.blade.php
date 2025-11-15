@extends('layouts.app')

@section('title', 'Daftar Todo')

@section('content')
    <h2>Daftar Todo</h2>

    {{-- Tombol Add New Task & Tombol filter lainnya di sini --}}
    {{-- Anda perlu menambahkan tombol Add New Task di sini jika belum ada --}}

    {{-- Periksa apakah ada tugas yang dikelompokkan sebelum menampilkan --}}
    @if($groupedTodos->isEmpty())
        <div class="alert alert-info mt-3">Belum ada tugas yang ditambahkan atau dikategorikan.</div>
    @else
        {{-- Loop pertama: Mengelompokkan berdasarkan Hari (Senin, Selasa, dst.) --}}
        @foreach($groupedTodos as $day => $todos)

            <h4 class="mt-4 mb-2 p-2 rounded"
                style="background-color: #f0f0f0; border-left: 5px solid #007bff; color: #333;">
                {{ $day }}
                <span class="badge bg-secondary">{{ $todos->count() }} Tugas</span>
            </h4>

            <ul class="list-group shadow-sm">
                {{-- Loop kedua: Menampilkan tugas di dalam hari tersebut --}}
                @foreach($todos as $todo)
                    {{-- Menggunakan ID untuk memudahkan targeting oleh JavaScript --}}
                    <li class="list-group-item d-flex justify-content-between align-items-center" id="todo-{{ $todo->id }}">

                        {{-- Kiri: Status dan Task --}}
                        <div class="d-flex align-items-center flex-grow-1">
                            {{-- 1. Checkbox Status --}}
                            <input
                                type="checkbox"
                                class="form-check-input me-3 task-status-toggle"
                                data-todo-id="{{ $todo->id }}"
                                {{ $todo->completed ? 'checked' : '' }}
                            >

                            {{-- Judul Task dengan gaya coret jika sudah selesai --}}
                            <span class="fs-6" style="{{ $todo->completed ? 'text-decoration: line-through; color: #6c757d;' : '' }}">
                                {{ $todo->task }}
                            </span>
                        </div>

                        {{-- Kanan: Tombol Aksi --}}
                        <div class="ms-auto">
                            <a href="{{ route('todos.show', $todo->id) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ route('todos.edit', $todo->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endforeach
    @endif

    {{-- 4. Skrip AJAX untuk Menangani Status Toggle --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Event listener saat checkbox diubah
            $('.task-status-toggle').on('change', function() {
                var todoId = $(this).data('todo-id');
                var isChecked = $(this).prop('checked');
                var $taskText = $(this).closest('li').find('.flex-grow-1 span'); // Mencari span di dalam elemen li

                // Mendapatkan token CSRF
                var csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';

                // Tentukan URL route toggle (Pastikan route ini ada: /todos/{id}/toggle)
                var url = '{{ url("/todos") }}/' + todoId + '/toggle';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        completed: isChecked
                    },
                    success: function(response) {
                        // Jika berhasil, tambahkan atau hapus efek coret pada teks
                        if (response.is_completed) { // Menggunakan is_completed dari controller
                            $taskText.css({'text-decoration': 'line-through', 'color': '#6c757d'});
                        } else {
                            $taskText.css({'text-decoration': 'none', 'color': '#212529'}); // Kembalikan warna default
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal memperbarui status:', xhr.responseText);
                        alert('Gagal memperbarui status. Silakan coba lagi.');
                        // Kembalikan status checkbox ke keadaan semula jika AJAX gagal
                        $(this).prop('checked', !isChecked);
                    }
                });
            });
        });
    </script>
@endsection
