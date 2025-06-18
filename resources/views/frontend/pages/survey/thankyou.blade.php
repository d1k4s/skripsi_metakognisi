@extends('frontend.layouts.app')

@section('title', 'Terima Kasih')

@section('content')
    <section class="section survey-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card text-center shadow-1"
                        style="border: none; border-radius: var(--radius-6); overflow: hidden;">
                        <div class="card-body p-5">
                            <div class="success-icon mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" style="color: var(--ultramarine-blue);">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>

                            <h2 class="card-title mb-4 fw-bold" style="color: var(--space-cadet-2);">Terima Kasih!</h2>
                            <p class="card-text mb-4 lead" style="color: var(--old-lavender);">Jawaban Anda telah berhasil
                                disimpan. Terima kasih telah meluangkan waktu untuk mengisi survey ini.</p>

                            @if (session('success'))
                                <div class="alert mb-4"
                                    style="background-color: var(--ultramarine-blue_10); border: 1px solid var(--ultramarine-blue);">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="mt-5">
                                <a href="{{ url('/') }}" class="btn"
                                    style="background-color: var(--ultramarine-blue); color: var(--white); font-size: var(--fs-5); font-weight: var(--fw-700); min-height: 60px; padding: 10px 30px;">
                                    <i class="bi bi-house-fill me-2"></i>Kembali ke Beranda
                                </a>
                            </div>

                            <div class="mt-4">
                                <p style="color: var(--old-lavender);">Jika Anda memiliki pertanyaan, silakan hubungi kami.
                                </p>
                            </div>
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

            // Simple animation for the checkmark
            const checkmark = document.querySelector('.success-icon svg');
            checkmark.style.transform = 'scale(0)';

            setTimeout(() => {
                checkmark.style.transition = 'transform 0.5s ease-out';
                checkmark.style.transform = 'scale(1)';
            }, 300);
        });
    </script>
@endsection

@section('styles')
    <style>
        .survey-section {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .survey-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .thank-you-card {
            background-color: #ffffff;
            transition: all 0.3s ease;
        }

        .thank-you-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }

        /* Checkmark animation */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }

        .success-checkmark .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #4CAF50;
        }

        .success-checkmark .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }

        .success-checkmark .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
        }

        .success-checkmark .check-icon::before,
        .success-checkmark .check-icon::after {
            content: '';
            height: 100px;
            position: absolute;
            background: #f8f9fa;
            transform: rotate(-45deg);
        }

        .success-checkmark .check-icon .icon-line {
            height: 5px;
            background-color: #4CAF50;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }

        .success-checkmark .check-icon .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
        }

        .success-checkmark .check-icon .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
        }

        .success-checkmark .check-icon .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 4px solid rgba(76, 175, 80, 0.5);
        }

        .success-checkmark .check-icon .icon-fix {
            top: 8px;
            width: 5px;
            left: 26px;
            z-index: 1;
            height: 85px;
            position: absolute;
            transform: rotate(-45deg);
            background-color: #f8f9fa;
        }

        @keyframes rotate-circle {
            0% {
                transform: rotate(-45deg);
            }

            5% {
                transform: rotate(-45deg);
            }

            12% {
                transform: rotate(-405deg);
            }

            100% {
                transform: rotate(-405deg);
            }
        }

        @keyframes icon-line-tip {
            0% {
                width: 0;
                left: 1px;
                top: 19px;
            }

            54% {
                width: 0;
                left: 1px;
                top: 19px;
            }

            70% {
                width: 50px;
                left: -8px;
                top: 37px;
            }

            84% {
                width: 17px;
                left: 21px;
                top: 48px;
            }

            100% {
                width: 25px;
                left: 14px;
                top: 46px;
            }
        }

        @keyframes icon-line-long {
            0% {
                width: 0;
                right: 46px;
                top: 54px;
            }

            65% {
                width: 0;
                right: 46px;
                top: 54px;
            }

            84% {
                width: 55px;
                right: 0px;
                top: 35px;
            }

            100% {
                width: 47px;
                right: 8px;
                top: 38px;
            }
        }
    </style>
@endsection
