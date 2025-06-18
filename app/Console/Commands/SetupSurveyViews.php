<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupSurveyViews extends Command
{
    protected $signature = 'survey:setup-views';

    protected $description = 'Setup the survey view files in the correct location';

    public function handle()
    {
        $this->info('Setting up survey views...');

        // Create directory if it doesn't exist
        $directory = resource_path('views/frontend/pages/survey');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
            $this->info("Created directory: {$directory}");
        }

        // Create data-diri.blade.php view
        $dataDiriView = $directory . '/data-diri.blade.php';
        if (!File::exists($dataDiriView)) {
            File::put($dataDiriView, $this->getDataDiriViewContent());
            $this->info("Created view: {$dataDiriView}");
        }

        // Create questions.blade.php view
        $questionsView = $directory . '/questions.blade.php';
        if (!File::exists($questionsView)) {
            File::put($questionsView, $this->getQuestionsViewContent());
            $this->info("Created view: {$questionsView}");
        }

        // Create thankyou.blade.php view
        $thankyouView = $directory . '/thankyou.blade.php';
        if (!File::exists($thankyouView)) {
            File::put($thankyouView, $this->getThankYouViewContent());
            $this->info("Created view: {$thankyouView}");
        }

        $this->info('Survey views setup completed!');

        return 0;
    }

    private function getDataDiriViewContent()
    {
        return <<<'BLADE'
@extends('frontend.layouts.app')

@section('title', 'Survey Metakognisi - Data Diri')

@section('content')
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Survey Metakognisi</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <p><strong>Petunjuk Pengisian:</strong></p>
                            <p>1. Isi data diri Anda dengan lengkap.</p>
                            <p>2. Setelah mengisi data diri, Anda akan diarahkan ke halaman pertanyaan.</p>
                            <p>3. Survey terdiri dari 52 pertanyaan yang dibagi menjadi beberapa halaman.</p>
                            <p>4. Tidak ada jawaban benar atau salah, jawablah sesuai dengan kondisi Anda yang sebenarnya.</p>
                        </div>

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h4><i class="bi bi-exclamation-triangle-fill"></i> Terdapat kesalahan pada data yang diinput:</h4>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('survey.save-data') }}" method="POST" id="dataDiriForm">
                            @csrf
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h4 class="mb-0">Data Diri</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="nama">Nama Lengkap<span class="text-danger">*</span></label>
                                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Maksimal 255 karakter</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="nim">NIM<span class="text-danger">*</span></label>
                                        <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
                                        @error('nim')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Contoh: 12345678</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="jurusan">Jurusan<span class="text-danger">*</span></label>
                                        <input type="text" name="jurusan" id="jurusan" class="form-control @error('jurusan') is-invalid @enderror" value="{{ old('jurusan') }}" required>
                                        @error('jurusan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Contoh: Teknik Informatika</small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4 mb-3">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">Lanjutkan ke Pertanyaan</button>
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
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 80%;
    }
    .card-header {
        font-weight: bold;
    }
</style>
@endsection
BLADE;
    }

    private function getQuestionsViewContent()
    {
        return <<<'BLADE'
@extends('frontend.layouts.app')

@section('title', 'Survey Metakognisi - Pertanyaan')

@section('content')
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Survey Metakognisi</h3>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($step / $totalSteps) * 100 }}%;"
                                aria-valuenow="{{ ($step / $totalSteps) * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                {{ round(($step / $totalSteps) * 100) }}%
                            </div>
                        </div>

                        <h4 class="mb-3">Halaman {{ $step }} dari {{ $totalSteps }}</h4>

                        <div class="alert alert-secondary mb-4">
                            <p><strong>Skala Penilaian:</strong></p>
                            <p>1 = Sangat Tidak Setuju</p>
                            <p>2 = Tidak Setuju</p>
                            <p>3 = Netral</p>
                            <p>4 = Setuju</p>
                            <p>5 = Sangat Setuju</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h4><i class="bi bi-exclamation-triangle-fill"></i> Terdapat kesalahan:</h4>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('survey.save-questions', ['step' => $step]) }}" method="POST" id="questionsForm">
                            @csrf

                            <div class="card mb-4">
                                <div class="card-body">
                                    @foreach ($questions as $questionId => $questionText)
                                        <div class="question-item mb-4 p-3 border rounded">
                                            <h5>{{ $questionId }}. {{ $questionText }}</h5>
                                            <div class="d-flex justify-content-between flex-wrap mt-3">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" name="pertanyaan{{ $questionId }}" id="pertanyaan{{ $questionId }}_{{ $i }}"
                                                            value="{{ $i }}" class="form-check-input"
                                                            {{ (isset($answers['pertanyaan'.$questionId]) && $answers['pertanyaan'.$questionId] == $i) ? 'checked' : '' }}
                                                            required>
                                                        <label for="pertanyaan{{ $questionId }}_{{ $i }}" class="form-check-label">
                                                            {{ $i }} - {{ $i==1 ? 'Sangat Tidak Setuju' : ($i==2 ? 'Tidak Setuju' : ($i==3 ? 'Netral' : ($i==4 ? 'Setuju' : 'Sangat Setuju'))) }}
                                                        </label>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4 mb-3">
                                @if ($prevStep)
                                    <a href="{{ route('survey.questions', ['step' => $prevStep]) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Sebelumnya
                                    </a>
                                @else
                                    <a href="{{ route('survey.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Kembali ke Data Diri
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    @if ($nextStep)
                                        Selanjutnya <i class="bi bi-arrow-right"></i>
                                    @else
                                        Selesai <i class="bi bi-check-circle"></i>
                                    @endif
                                </button>
                            </div>

                            <div id="loadingIndicator" style="display: none;" class="text-center mt-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Mohon tunggu...</p>
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
        const form = document.getElementById('questionsForm');
        const submitBtn = document.getElementById('submitBtn');
        const loadingIndicator = document.getElementById('loadingIndicator');

        // Handle form submission
        form.addEventListener('submit', function() {
            // Check if all questions are answered
            const unansweredQuestions = [];

            @foreach ($questions as $questionId => $questionText)
                const questionInputs{{ $questionId }} = document.querySelectorAll('input[name="pertanyaan{{ $questionId }}"]:checked');
                if (questionInputs{{ $questionId }}.length === 0) {
                    unansweredQuestions.push({{ $questionId }});
                }
            @endforeach

            if (unansweredQuestions.length > 0) {
                alert('Mohon jawab semua pertanyaan sebelum melanjutkan. Pertanyaan yang belum dijawab: ' + unansweredQuestions.join(', '));
                return false;
            }

            // Disable submit button and show loading indicator
            submitBtn.disabled = true;
            loadingIndicator.style.display = 'block';
        });

        // Auto-save answers on change
        const radioInputs = document.querySelectorAll('input[type="radio"]');
        radioInputs.forEach(input => {
            input.addEventListener('change', function() {
                localStorage.setItem(this.name, this.value);
            });

            // Load saved values if available
            const savedValue = localStorage.getItem(input.name);
            if (savedValue && input.value === savedValue) {
                input.checked = true;
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .progress {
        height: 25px;
        border-radius: 5px;
    }
    .progress-bar {
        background-color: #4361ee;
        font-weight: bold;
    }
    .question-item {
        background-color: #f8f9fa;
        transition: background-color 0.3s;
    }
    .question-item:hover {
        background-color: #e9ecef;
    }
    .form-check-inline {
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }
    @media (max-width: 768px) {
        .form-check-inline {
            flex-basis: 45%;
        }
    }
    @media (max-width: 576px) {
        .form-check-inline {
            flex-basis: 100%;
        }
    }
</style>
@endsection
BLADE;
    }

    private function getThankYouViewContent()
    {
        return <<<'BLADE'
@extends('frontend.layouts.app')

@section('title', 'Terima Kasih')

@section('content')
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="card-title mb-4">Terima Kasih!</h2>
                        <p class="card-text mb-4">Jawaban Anda telah berhasil disimpan. Terima kasih telah meluangkan waktu untuk mengisi survey ini.</p>

                        @if (session('success'))
                            <div class="alert alert-success mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
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
        // Clear local storage after successful submission
        localStorage.clear();
    });
</script>
@endsection
BLADE;
    }
}
