@extends('frontend.layouts.app')

@section('title', 'Survey Metakognisi - Data Diri')

@section('content')
    <section class="section survey-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-1">
                        <div class="card-header" style="background-color: var(--ultramarine-blue); color: var(--white);">
                            <h3 class="card-title mb-0 fw-bold">Survey Metakognisi</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert"
                                style="background-color: var(--ultramarine-blue_10); border: 1px solid var(--ultramarine-blue);">
                                <h5 class="fw-bold mb-3"><i class="bi bi-info-circle-fill me-2"></i>Petunjuk Pengisian:</h5>
                                <ol class="mb-0 ps-3">
                                    <li class="mb-2">Isi data diri Anda dengan lengkap.</li>
                                    <li class="mb-2">Setelah mengisi data diri, Anda akan diarahkan ke halaman pertanyaan.
                                    </li>
                                    <li class="mb-2">Survey terdiri dari 52 pertanyaan yang dibagi menjadi beberapa
                                        halaman.</li>
                                    <li>Tidak ada jawaban benar atau salah, jawablah sesuai dengan kondisi Anda yang
                                        sebenarnya.</li>
                                </ol>
                            </div>

                            @if (session('error'))
                                <div class="alert mt-4"
                                    style="background-color: var(--coral_10); border: 1px solid var(--tart-orange);">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert mt-4"
                                    style="background-color: var(--coral_10); border: 1px solid var(--tart-orange);">
                                    <h5 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat
                                        kesalahan pada data yang diinput:</h5>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('survey.save-data') }}" method="POST" id="dataDiriForm" class="mt-4">
                                @csrf
                                <div class="card" style="background-color: var(--cultured-1); border: none;">
                                    <div class="card-header" style="background-color: var(--cultured-2);">
                                        <h4 class="mb-0 fw-bold"><i class="bi bi-person-fill me-2"></i>Data Diri</h4>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-md-12 mb-4">
                                                <div class="form-group">
                                                    <label for="nama"
                                                        class="form-label fw-bold d-flex align-items-center"
                                                        style="color: var(--space-cadet-2);">
                                                        Nama Lengkap
                                                        <span class="required-asterisk ms-1"
                                                            style="color: var(--tart-orange);">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"
                                                            style="background-color: var(--cultured-2);"><i
                                                                class="bi bi-person"></i></span>
                                                        <input type="text" name="nama" id="nama"
                                                            class="form-control form-control-lg @error('nama') is-invalid @enderror"
                                                            value="{{ old('nama') }}" required
                                                            placeholder="Masukkan nama lengkap">
                                                    </div>
                                                    @error('nama')
                                                        <div class="invalid-feedback d-block mt-1"
                                                            style="color: var(--tart-orange);">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text mt-1"
                                                        style="color: var(--old-lavender);">Maksimal 255 karakter</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label for="nim"
                                                        class="form-label fw-bold d-flex align-items-center"
                                                        style="color: var(--space-cadet-2);">
                                                        NIM
                                                        <span class="required-asterisk ms-1"
                                                            style="color: var(--tart-orange);">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"
                                                            style="background-color: var(--cultured-2);"><i
                                                                class="bi bi-credit-card"></i></span>
                                                        <input type="text" name="nim" id="nim"
                                                            class="form-control form-control-lg @error('nim') is-invalid @enderror"
                                                            value="{{ old('nim') }}" required placeholder="Masukkan NIM">
                                                    </div>
                                                    @error('nim')
                                                        <div class="invalid-feedback d-block mt-1"
                                                            style="color: var(--tart-orange);">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text mt-1"
                                                        style="color: var(--old-lavender);">Contoh: 12345678</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label for="jurusan"
                                                        class="form-label fw-bold d-flex align-items-center"
                                                        style="color: var(--space-cadet-2);">
                                                        Jurusan
                                                        <span class="required-asterisk ms-1"
                                                            style="color: var(--tart-orange);">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"
                                                            style="background-color: var(--cultured-2);"><i
                                                                class="bi bi-book"></i></span>
                                                        <input type="text" name="jurusan" id="jurusan"
                                                            class="form-control form-control-lg @error('jurusan') is-invalid @enderror"
                                                            value="{{ old('jurusan') }}" required
                                                            placeholder="Masukkan jurusan">
                                                    </div>
                                                    @error('jurusan')
                                                        <div class="invalid-feedback d-block mt-1"
                                                            style="color: var(--tart-orange);">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text mt-1"
                                                        style="color: var(--old-lavender);">Contoh: Teknik
                                                        Informatika</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4 mb-3">
                                    <button type="submit" class="btn"
                                        style="background-color: var(--ultramarine-blue); color: var(--white); font-size: var(--fs-5); font-weight: var(--fw-700); min-height: 60px; padding: 10px 30px;"
                                        id="submitBtn">
                                        <i class="bi bi-arrow-right-circle-fill me-2"></i>Lanjutkan ke Pertanyaan
                                    </button>
                                    <div id="loadingIndicator" style="display: none;" class="mt-3">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Mohon tunggu...</p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('dataDiriForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingIndicator = document.getElementById('loadingIndicator');

            // Handle form submission
            form.addEventListener('submit', function() {
                // Disable submit button and show loading indicator
                submitBtn.disabled = true;
                loadingIndicator.style.display = 'block';
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        /* Fix for required asterisk alignment */
        .required-asterisk {
            display: inline-flex;
            align-items: center;
            line-height: 1;
        }

        /* Form styling */
        .form-control {
            border: 1px solid var(--cultured-2);
        }

        .form-control:focus {
            border-color: var(--ultramarine-blue);
            box-shadow: 0 0 0 0.2rem var(--ultramarine-blue_10);
        }

        .form-control.is-invalid {
            border-color: var(--tart-orange);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('dataDiriForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingIndicator = document.getElementById('loadingIndicator');

            // Handle form submission
            form.addEventListener('submit', function() {
                // Disable submit button and show loading indicator
                submitBtn.disabled = true;
                loadingIndicator.style.display = 'block';
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        .survey-section {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .survey-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }

        .survey-inner-card {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-weight: bold;
        }

        .form-control {
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 80%;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 8px;
        }

        /* Bootstrap 5 utility classes */
        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.15);
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.15);
        }

        .border-info {
            border: 1px solid rgba(13, 202, 240, 0.5) !important;
        }

        .border-danger {
            border: 1px solid rgba(220, 53, 69, 0.5) !important;
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }
    </style>
@endsection
