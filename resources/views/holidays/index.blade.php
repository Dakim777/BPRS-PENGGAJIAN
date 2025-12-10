@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Manajemen Hari Libur Nasional</h2>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Hari Libur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('holidays.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tanggal Libur</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: HUT RI ke-79" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            + Simpan Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Daftar Hari Libur</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holidays as $holiday)
                            <tr>
                                <td>{{ $holiday->tanggal->format('d-m-Y') }}</td>
                                <td>{{ $holiday->tanggal->translatedFormat('l') }}</td>
                                <td>{{ $holiday->keterangan }}</td>
                                <td>
                                    <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus hari libur ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data hari libur.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $holidays->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection