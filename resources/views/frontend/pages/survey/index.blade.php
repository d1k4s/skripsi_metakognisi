@extends('frontend.layouts.app')

@section('title', 'Survey Metakognisi')

@section('content')
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Survey Metakognisi</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <p><strong>Petunjuk Pengisian:</strong></p>
                                <p>1. Isi data diri Anda dengan lengkap.</p>
                                <p>2. Bacalah setiap pernyataan dengan teliti.</p>
                                <p>3. Pilihlah salah satu jawaban yang paling sesuai dengan diri Anda.</p>
                                <p>4. Tidak ada jawaban benar atau salah, jawablah sesuai dengan kondisi Anda yang
                                    sebenarnya.</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h4><i class="bi bi-exclamation-triangle-fill"></i> Terdapat kesalahan pada data yang
                                        diinput:</h4>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('survey.store') }}" method="POST" id="surveyForm">
                                @csrf
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="mb-0">Data Diri</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="nama">Nama Lengkap<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="nama" id="nama"
                                                        class="form-control @error('nama') is-invalid @enderror"
                                                        value="{{ old('nama') }}" required>
                                                    @error('nama')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Maksimal 255 karakter</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="nim">NIM<span class="text-danger">*</span></label>
                                                    <input type="text" name="nim" id="nim"
                                                        class="form-control @error('nim') is-invalid @enderror"
                                                        value="{{ old('nim') }}" required>
                                                    @error('nim')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Contoh: 12345678</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="jurusan">Jurusan<span class="text-danger">*</span></label>
                                                    <input type="text" name="jurusan" id="jurusan"
                                                        class="form-control @error('jurusan') is-invalid @enderror"
                                                        value="{{ old('jurusan') }}" required>
                                                    @error('jurusan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">Contoh: Teknik Informatika</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="mb-0">Pertanyaan Survey</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-secondary">
                                            <p><strong>Skala Penilaian:</strong></p>
                                            <p>1 = Sangat Tidak Setuju</p>
                                            <p>2 = Tidak Setuju</p>
                                            <p>3 = Netral</p>
                                            <p>4 = Setuju</p>
                                            <p>5 = Sangat Setuju</p>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th width="5%">No</th>
                                                        <th width="65%">Pernyataan</th>
                                                        <th width="30%">Jawaban</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Pertanyaan 1 -->
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Saya menetapkan tujuan spesifik sebelum mulai mengerjakan tugas
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-check-inline">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <div class="mx-2">
                                                                        <input type="radio" name="pertanyaan1"
                                                                            id="pertanyaan1_{{ $i }}"
                                                                            value="{{ $i }}"
                                                                            class="form-check-input"
                                                                            {{ old('pertanyaan1') == $i ? 'checked' : '' }}
                                                                            required>
                                                                        <label for="pertanyaan1_{{ $i }}"
                                                                            class="form-check-label">{{ $i }}</label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Pertanyaan 2 -->
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Saya mempertimbangkan beberapa alternatif solusi ketika
                                                            menyelesaikan masalah</td>
                                                        <td>
                                                            <div class="form-check form-check-inline">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <div class="mx-2">
                                                                        <input type="radio" name="pertanyaan2"
                                                                            id="pertanyaan2_{{ $i }}"
                                                                            value="{{ $i }}"
                                                                            class="form-check-input"
                                                                            {{ old('pertanyaan2') == $i ? 'checked' : '' }}
                                                                            required>
                                                                        <label for="pertanyaan2_{{ $i }}"
                                                                            class="form-check-label">{{ $i }}</label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Pertanyaan 3-52: menggunakan format yang sama seperti di atas -->
                                                    @php
                                                        $pertanyaan = [
                                                            'Saya bertanya pada diri sendiri apakah saya sudah mempertimbangkan semua opsi ketika menyelesaikan masalah',
                                                            'Saya mencoba menggunakan strategi yang pernah berhasil di masa lalu',
                                                            'Saya mengatur langkah-langkah saya dengan hati-hati untuk menyelesaikan masalah',
                                                            'Saya membaca instruksi dengan teliti sebelum mulai mengerjakan tugas',
                                                            'Saya bertanya pada diri sendiri apakah saya memahami sesuatu dengan baik',
                                                            'Saya mengevaluasi asumsi-asumsi saya ketika mengalami kebingungan',
                                                            'Saya mengatur waktu saya agar mencapai tujuan dengan sebaik-baiknya',
                                                            'Saya tahu apa yang diharapkan guru untuk saya pelajari',
                                                            'Saya baik dalam mengingat informasi',
                                                            'Saya menggunakan strategi belajar yang berbeda tergantung pada situasi',
                                                            'Saya bertanya pada diri sendiri apakah ada cara yang lebih mudah setelah menyelesaikan tugas',
                                                            'Saya mampu memotivasi diri untuk belajar ketika dibutuhkan',
                                                            'Saya sadar strategi apa yang saya gunakan ketika belajar',
                                                            'Saya menganalisis kegunaan strategi saat belajar',
                                                            'Saya menggunakan kekuatan dan kelemahan intelektual saya saat belajar',
                                                            'Saya baik dalam mengorganisir informasi',
                                                            'Saya fokus pada makna dan signifikansi dari informasi baru',
                                                            'Saya menciptakan contoh sendiri untuk membuat informasi lebih bermakna',
                                                            'Saya menilai dengan baik seberapa baik saya memahami sesuatu',
                                                            'Saya memiliki strategi khusus yang saya gunakan saat belajar',
                                                            'Saya memikirkan apa yang sebenarnya perlu saya pelajari sebelum memulai tugas',
                                                            'Saya bertanya pada diri sendiri pertanyaan tentang materi pelajaran sebelum mulai belajar',
                                                            'Saya memikirkan beberapa cara untuk menyelesaikan masalah dan memilih yang terbaik',
                                                            'Saya merangkum apa yang telah saya pelajari setelah selesai belajar',
                                                            'Saya meminta bantuan orang lain ketika tidak memahami sesuatu',
                                                            'Saya dapat memotivasi diri untuk belajar ketika dibutuhkan',
                                                            'Saya sadar strategi apa yang saya gunakan ketika mengerjakan tugas',
                                                            'Saya secara otomatis menganalisis kegunaan strategi ketika belajar',
                                                            'Saya secara teratur berhenti sejenak untuk memeriksa pemahaman saya',
                                                            'Saya tahu kapan setiap strategi yang saya gunakan akan paling efektif',
                                                            'Saya bertanya pada diri sendiri seberapa baik saya mencapai tujuan setelah menyelesaikan tugas',
                                                            'Saya membuat gambar atau diagram untuk membantu memahami saat belajar',
                                                            'Saya mengevaluasi kembali asumsi saya ketika merasa bingung',
                                                            'Saya mengubah strategi ketika gagal memahami',
                                                            'Saya berhenti dan membaca ulang ketika menjadi bingung',
                                                            'Saya mengubah teknik atau strategi belajar untuk mencapai tujuan',
                                                            'Saya menggunakan struktur teks untuk membantu dalam belajar',
                                                            'Saya membaca instruksi dengan hati-hati sebelum mulai mengerjakan tugas',
                                                            'Saya bertanya pada diri sendiri apakah apa yang saya baca berhubungan dengan apa yang sudah saya ketahui',
                                                            'Saya mengevaluasi strategi belajar saya secara berkala',
                                                            'Saya mengecek kembali apa yang sudah saya kerjakan saat menghadapi konsep baru',
                                                            'Saya mengatur tempo belajar saya untuk memastikan saya memiliki cukup waktu',
                                                            'Saya mengatur ulang informasi untuk memudahkan saya dalam belajar',
                                                            'Saya menggunakan kemampuan verbal untuk membantu saya belajar',
                                                            'Saya menggunakan kelebihan intelektual saya untuk mengimbangi kelemahan saya',
                                                            'Saya bertanya pada diri saya apakah saya telah mempertimbangkan semua pilihan saat menyelesaikan masalah',
                                                            'Saya mencari tahu makna dari informasi yang tidak familier',
                                                            'Saya berhenti dan membaca ulang saat informasi tidak jelas',
                                                            'Saya mencari tahu makna dari informasi yang tidak familier',
                                                            'Saya berhenti dan membaca ulang saat informasi tidak jelas',
                                                        ];
                                                    @endphp

                                                    @foreach ($pertanyaan as $index => $isi)
                                                        <tr>
                                                            <td>{{ $index + 3 }}</td>
                                                            <td>{{ $isi }}</td>
                                                            <td>
                                                                <div class="form-check form-check-inline">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <div class="mx-2">
                                                                            <input type="radio"
                                                                                name="pertanyaan{{ $index + 3 }}"
                                                                                id="pertanyaan{{ $index + 3 }}_{{ $i }}"
                                                                                value="{{ $i }}"
                                                                                class="form-check-input"
                                                                                {{ old('pertanyaan' . ($index + 3)) == $i ? 'checked' : '' }}
                                                                                required>
                                                                            <label
                                                                                for="pertanyaan{{ $index + 3 }}_{{ $i }}"
                                                                                class="form-check-label">{{ $i }}</label>
                                                                        </div>
                                                                    @endfor
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4 mb-5">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">Kirim
                                        Jawaban</button>
                                    <div id="loadingIndicator" style="display: none;" class="mt-3">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Mohon tunggu, jawaban Anda sedang diproses...</p>
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
            // Form validation feedback
            const form = document.getElementById('surveyForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingIndicator = document.getElementById('loadingIndicator');

            // Client-side validation
            const nimInput = document.getElementById('nim');
            nimInput.addEventListener('input', function() {
                if (this.value.length > 50) {
                    this.setCustomValidity('NIM tidak boleh lebih dari 50 karakter');
                } else {
                    this.setCustomValidity('');
                }
            });

            form.addEventListener('submit', function(e) {
                // Disable submit button and show loading indicator
                submitBtn.disabled = true;
                loadingIndicator.style.display = 'block';

                // Form validation sudah ditangani oleh HTML5 validation dan Laravel validation
            });

            // Tampilkan error validation langsung di form saat halaman dimuat
            @if ($errors->any())
                // Scroll ke error pertama
                window.scrollTo({
                    top: document.querySelector('.alert-danger').offsetTop - 100,
                    behavior: 'smooth'
                });
            @endif
        });
    </script>
@endsection

@section('styles')
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 80%;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-danger h4 {
            margin-bottom: 0.5rem;
        }

        .alert-danger ul {
            padding-left: 1.5rem;
        }

        .form-check-inline {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        @media (max-width: 768px) {
            .form-check-inline {
                flex-wrap: wrap;
            }
        }
    </style>
    <div class="text-center mt-4 mb-5">
        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">Kirim
            Jawaban</button>
        <div id="loadingIndicator" style="display: none;" class="mt-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Mohon tunggu, jawaban Anda sedang diproses...</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation feedback
            const form = document.getElementById('surveyForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingIndicator = document.getElementById('loadingIndicator');

            // Client-side validation untuk usia
            const usiaInput = document.getElementById('usia');
            usiaInput.addEventListener('input', function() {
                const usia = parseInt(this.value);
                if (usia < 10) {
                    this.setCustomValidity('Usia minimal 10 tahun');
                } else if (usia > 100) {
                    this.setCustomValidity('Usia maksimal 100 tahun');
                } else {
                    this.setCustomValidity('');
                }
            });

            // Client-side validation untuk kelas
            const kelasInput = document.getElementById('kelas');
            kelasInput.addEventListener('input', function() {
                if (this.value.length > 20) {
                    this.setCustomValidity('Kelas tidak boleh lebih dari 20 karakter');
                } else {
                    this.setCustomValidity('');
                }
            });

            form.addEventListener('submit', function(e) {
                // Disable submit button and show loading indicator
                submitBtn.disabled = true;
                loadingIndicator.style.display = 'block';

                // Form validation sudah ditangani oleh HTML5 validation dan Laravel validation
            });

            // Tampilkan error validation langsung di form saat halaman dimuat
            @if ($errors->any())
                // Scroll ke error pertama
                window.scrollTo({
                    top: document.querySelector('.alert-danger').offsetTop - 100,
                    behavior: 'smooth'
                });
            @endif
        });
    </script>
@endsection

@section('styles')
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 80%;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-danger h4 {
            margin-bottom: 0.5rem;
        }

        .alert-danger ul {
            padding-left: 1.5rem;
        }
    </style>
@endsection
