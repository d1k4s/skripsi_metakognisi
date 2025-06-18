@extends('frontend.layouts.app')

@section('title', 'Survey Metakognisi - Pertanyaan')

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
                            <div class="progress-container mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold" style="color: var(--space-cadet-2);">Progress</span>
                                    <span class="fw-bold"
                                        style="color: var(--ultramarine-blue);">{{ round(($step / $totalSteps) * 100) }}%</span>
                                </div>
                                <div class="progress" style="height: 25px; background-color: var(--cultured-2);">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                        style="width: {{ ($step / $totalSteps) * 100 }}%; background-color: var(--ultramarine-blue);"
                                        aria-valuenow="{{ ($step / $totalSteps) * 100 }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>

                            <div class="step-indicator d-flex justify-content-between align-items-center mb-4">
                                <h4 class="step-text mb-0 fw-bold" style="color: var(--space-cadet-2);">
                                    <i class="bi bi-list-ol me-2"></i>Halaman {{ $step }} dari {{ $totalSteps }}
                                </h4>
                                <span class="badge px-3 py-2"
                                    style="background-color: var(--ultramarine-blue); color: var(--white); border-radius: 50px;">
                                    Pertanyaan {{ $startQuestion }}-{{ $endQuestion }}
                                </span>
                            </div>

                            <div class="alert"
                                style="background-color: var(--ultramarine-blue_10); border: 1px solid var(--ultramarine-blue);">
                                <h5 class="fw-bold mb-3"><i class="bi bi-info-circle-fill me-2"></i>Skala Penilaian:</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">1 = Sangat Tidak Setuju</li>
                                            <li class="mb-2">2 = Tidak Setuju</li>
                                            <li class="mb-2">3 = Netral</li>
                                            <li class="mb-2">4 = Setuju</li>
                                            <li>5 = Sangat Setuju</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="alert"
                                    style="background-color: var(--coral_10); border: 1px solid var(--tart-orange);">
                                    <h5 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat
                                        kesalahan:</h5>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (count($questions) > 0)
                                <form action="{{ route('survey.save-questions', ['step' => $step]) }}" method="POST"
                                    id="questionsForm">
                                    @csrf

                                    <div class="questions-container">
                                        @foreach ($questions as $questionId => $questionText)
                                            <div class="question-item mb-4 p-4 shadow-2 border rounded-3"
                                                style="background-color: var(--cultured-1); border-color: var(--cultured-2) !important;">
                                                <h5 class="mb-3 fw-bold question-text" style="color: var(--space-cadet-2);">
                                                    <span
                                                        class="question-number rounded-circle me-2 d-inline-flex align-items-center justify-content-center"
                                                        style="background-color: var(--ultramarine-blue); color: var(--white); width: 30px; height: 30px;">{{ $questionId }}</span>
                                                    {{ $questionText }}
                                                </h5>

                                                <div class="rating-container mt-4">
                                                    <div class="rating-buttons-wrapper">
                                                        <div class="rating-scale-labels">
                                                            <span>Sangat Tidak Setuju</span>
                                                            <span>Sangat Setuju</span>
                                                        </div>
                                                        <div class="rating-buttons">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <div class="rating-button-item">
                                                                    <input type="radio"
                                                                        name="pertanyaan{{ $questionId }}"
                                                                        id="pertanyaan{{ $questionId }}_{{ $i }}"
                                                                        value="{{ $i }}" class="rating-input"
                                                                        {{ isset($answers['pertanyaan' . $questionId]) && $answers['pertanyaan' . $questionId] == $i ? 'checked' : '' }}
                                                                        required>
                                                                    <label
                                                                        for="pertanyaan{{ $questionId }}_{{ $i }}"
                                                                        class="rating-button">
                                                                        <span
                                                                            class="rating-number">{{ $i }}</span>
                                                                    </label>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="d-flex justify-content-between mt-4 mb-3">
                                        @if ($prevStep)
                                            <a href="{{ route('survey.questions', ['step' => $prevStep]) }}" class="btn"
                                                style="border: 1px solid var(--ultramarine-blue); color: var(--ultramarine-blue); font-size: var(--fs-5); min-height: 60px; padding: 10px 30px;">
                                                <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                                            </a>
                                        @else
                                            <a href="{{ route('survey.index') }}" class="btn"
                                                style="border: 1px solid var(--ultramarine-blue); color: var(--ultramarine-blue); font-size: var(--fs-5); min-height: 60px; padding: 10px 30px;">
                                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Data Diri
                                            </a>
                                        @endif

                                        <button type="submit" class="btn"
                                            style="background-color: var(--ultramarine-blue); color: var(--white); font-size: var(--fs-5); font-weight: var(--fw-700); min-height: 60px; padding: 10px 30px;"
                                            id="submitBtn">
                                            @if ($nextStep)
                                                Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
                                            @else
                                                Selesai <i class="bi bi-check-circle ms-2"></i>
                                            @endif
                                        </button>
                                    </div>

                                    <div id="loadingIndicator" style="display: none;" class="text-center mt-3">
                                        <div class="spinner-border" style="color: var(--ultramarine-blue);" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Mohon tunggu...</p>
                                    </div>
                                </form>
                            @else
                                <div class="alert"
                                    style="background-color: var(--orange-peel_10); border: 1px solid var(--orange-peel);">
                                    <p>Tidak ada pertanyaan untuk halaman ini. <a href="{{ route('survey.index') }}"
                                            style="color: var(--ultramarine-blue); font-weight: var(--fw-700);">Kembali ke
                                            awal</a>.</p>
                                </div>
                            @endif
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
            if (!form) return; // Skip if form doesn't exist

            const submitBtn = document.getElementById('submitBtn');
            const loadingIndicator = document.getElementById('loadingIndicator');

            // Handle form submission
            form.addEventListener('submit', function(e) {
                // Check if all questions are answered
                const unansweredQuestions = [];

                @foreach ($questions as $questionId => $questionText)
                    const questionInputs{{ $questionId }} = document.querySelectorAll(
                        'input[name="pertanyaan{{ $questionId }}"]:checked');
                    if (questionInputs{{ $questionId }}.length === 0) {
                        unansweredQuestions.push({{ $questionId }});
                    }
                @endforeach

                if (unansweredQuestions.length > 0) {
                    e.preventDefault();
                    // Highlight unanswered questions
                    unansweredQuestions.forEach(questionId => {
                        const questionElement = document.querySelector(
                            `[name="pertanyaan${questionId}"]`).closest('.question-item');
                        if (questionElement) {
                            questionElement.classList.add('unanswered');

                            // Scroll to the first unanswered question
                            if (questionId === unansweredQuestions[0]) {
                                questionElement.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }
                        }
                    });

                    alert('Mohon jawab semua pertanyaan sebelum melanjutkan. Pertanyaan yang belum dijawab: ' +
                        unansweredQuestions.join(', '));
                    return false;
                }

                // Disable submit button and show loading indicator
                submitBtn.disabled = true;
                loadingIndicator.style.display = 'block';
            });

            // Auto-save answers on change and highlight selected option
            const radioInputs = document.querySelectorAll('.rating-input');
            radioInputs.forEach(input => {
                input.addEventListener('change', function() {
                    localStorage.setItem(this.name, this.value);

                    // Remove unanswered highlight when answered
                    const questionItem = this.closest('.question-item');
                    if (questionItem) {
                        questionItem.classList.remove('unanswered');
                    }

                    // Update selected styles in this question group
                    const questionName = this.name;
                    const allOptionsInQuestion = document.querySelectorAll(
                        `input[name="${questionName}"]`);

                    allOptionsInQuestion.forEach(option => {
                        const label = option.nextElementSibling;
                        if (option.checked) {
                            label.classList.add('selected');
                        } else {
                            label.classList.remove('selected');
                        }
                    });
                });

                // Load saved values if available
                const savedValue = localStorage.getItem(input.name);
                if (savedValue && input.value === savedValue) {
                    input.checked = true;
                    // Trigger change event to apply styling
                    const event = new Event('change');
                    input.dispatchEvent(event);
                }
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        /* Rating buttons styles */
        .rating-buttons-wrapper {
            margin-top: 20px;
        }

        .rating-scale-labels {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: var(--old-lavender);
            margin-bottom: 10px;
        }

        .rating-buttons {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            position: relative;
        }

        .rating-buttons:before {
            content: '';
            position: absolute;
            height: 2px;
            background-color: var(--cultured-2);
            width: calc(100% - 60px);
            top: 50%;
            left: 30px;
            z-index: 0;
        }

        .rating-button-item {
            position: relative;
            z-index: 1;
        }

        .rating-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .rating-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background-color: var(--white);
            border: 2px solid var(--cultured-2);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .rating-number {
            font-size: 24px;
            font-weight: var(--fw-700);
            color: var(--space-cadet-2);
            position: absolute;
            right: -8px;
            top: -8px;
            background-color: var(--white);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--cultured-2);
            transition: all 0.3s ease;
        }

        .rating-button:hover {
            background-color: var(--ultramarine-blue_10);
            border-color: var(--ultramarine-blue);
        }

        .rating-button:hover .rating-number {
            border-color: var(--ultramarine-blue);
        }

        .rating-input:checked+.rating-button {
            background-color: var(--ultramarine-blue);
            border-color: var(--ultramarine-blue);
        }

        .rating-input:checked+.rating-button .rating-number {
            background-color: var(--ultramarine-blue);
            color: var(--white);
            border-color: var(--white);
        }

        /* Unanswered question highlight */
        .question-item.unanswered {
            border: 2px solid var(--tart-orange) !important;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .rating-buttons {
                flex-direction: column;
                gap: 20px;
            }

            .rating-buttons:before {
                width: 2px;
                height: calc(100% - 60px);
                top: 30px;
                left: 30px;
            }

            .rating-scale-labels {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
@endsection
