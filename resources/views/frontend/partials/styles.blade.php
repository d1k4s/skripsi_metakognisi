<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!--custom css link-->
<link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">

<!--google font link-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!--preload images-->
<link rel="preload" as="image" href="{{ asset('frontend/assets/images/hero-banner.png') }}">
<link rel="preload" as="image" href="{{ asset('frontend/assets/images/hero-abs-1.png') }}"
    media="min-width(768px)">
<link rel="preload" as="image" href="{{ asset('frontend/assets/images/hero-abs-2.png') }}"
    media="min-width(768px)">
<style>
    /* Survey specific styles to fix navbar overlap */
    .survey-section {
        padding-top: 120px !important;
        margin-top: 0 !important;
        min-height: 100vh;
        background-color: var(--cultured-1);
    }

    @media (max-width: 768px) {
        .survey-section {
            padding-top: 150px !important;
        }
    }

    /* Survey card styling */
    .survey-section .card {
        border: none;
        border-radius: var(--radius-6);
        overflow: hidden;
    }

    .survey-section .card-header {
        padding: 15px 20px;
        font-weight: var(--fw-700);
    }

    .survey-section .question-item {
        transition: all 0.3s ease;
    }

    .survey-section .question-item:hover {
        transform: translateY(-3px);
    }

    .survey-section .btn {
        transition: var(--transition-1);
        border-radius: var(--radius-6);
    }

    .survey-section .btn:hover {
        transform: translateY(-2px);
    }

    /* Shadow utilities */
    .shadow-1 {
        box-shadow: var(--shadow-1);
    }

    .shadow-2 {
        box-shadow: var(--shadow-2);
    }

    /* Rating buttons with spaced layout and numbers on right side */
    .survey-section .rating-buttons {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 30px 0;
    }

    .survey-section .rating-button {
        position: relative;
        background-color: var(--white);
        border: 2px solid var(--cultured-2);
    }

    .survey-section .rating-input:checked+.rating-button {
        background-color: var(--ultramarine-blue);
        border-color: var(--ultramarine-blue);
    }

    /* Ensuring form labels align properly */
    .survey-section .form-label {
        display: flex;
        align-items: center;
    }

    .survey-section .required-asterisk {
        color: var(--tart-orange);
        margin-left: 4px;
        display: inline-flex;
        align-items: center;
    }

    /* style Dashboard dan logout frontend */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-toggle {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-menu .dropdown-item {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    /* end */

    /* Breadcrumbs Style */
    .breadcrumbs {
        background-color: #f9f9f9;
        padding: 15px 0;
        border-bottom: 1px solid #e1e1e1;
    }

    .breadcrumbs .container {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-start;
    }

    .breadcrumbs ol {
        padding: 0;
        margin: 0;
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .breadcrumbs ol li {
        display: inline-block;
        font-size: 14px;
        color: #333;
    }

    .breadcrumbs ol li+li:before {
        content: "/";
        padding: 0 10px;
        color: #999;
    }

    .breadcrumbs ol li a {
        text-decoration: none;
        color: #0275d8;
    }

    .breadcrumbs ol li a:hover {
        color: #01447e;
    }

    .breadcrumbs h2 {
        font-size: 24px;
        margin: 0;
        color: #333;
    }

    @media (min-width: 768px) {
        .breadcrumbs .container {
            flex-direction: row;
            align-items: center;
        }

        .breadcrumbs ol {
            margin-bottom: 0;
        }
    }

    @media (max-width: 767px) {
        .breadcrumbs ol li {
            font-size: 12px;
        }

        .breadcrumbs h2 {
            font-size: 20px;
        }
    }

    /* end breadcrumbs */

    /* Responsive styles for survey */
    @media (max-width: 768px) {
        .rating-container .form-rating {
            flex-direction: column;
            gap: 10px;
        }

        .rating-option {
            width: 100%;
        }

        .rating-text {
            display: inline-block !important;
        }
    }
</style>
