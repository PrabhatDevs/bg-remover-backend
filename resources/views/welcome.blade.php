<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Background Remover | HD Quality Instantly</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --neon-blue: #00d2ff;
            --neon-purple: #7000ff;
            --cyber-cyan: #0891b2;
            --glass-border: rgba(0, 0, 0, 0.06);
            --primary-gradient: linear-gradient(135deg, var(--neon-purple) 0%, var(--neon-blue) 100%);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            --btn-radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-page);
            background-image:
                radial-gradient(at 0% 0%, rgba(112, 0, 255, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(0, 210, 255, 0.05) 0px, transparent 50%);
            color: var(--text-main);
            line-height: 1.6;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(rgba(0, 0, 0, 0.01) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 0, 0, 0.01) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -1;
        }

        /* --- Header --- */
        header {
            height: 75px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.3rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
            text-transform: uppercase;
            text-decoration: none !important;
        }

        .credits-badge {
            background: #fff;
            padding: 0.5rem 1.2rem;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--neon-purple);
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 15px rgba(112, 0, 255, 0.05);
        }

        /* --- Hero --- */
        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            letter-spacing: -2px;
            line-height: 1.1;
        }

        .trust-line {
            font-size: 0.9rem;
            font-weight: 600;
            color: #000;
            background: var(--glass-border);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            display: inline-block;
        }

        /* --- Main Tool Workspace --- */
        .custom-card {
            background: var(--bg-card);
            border-radius: 32px;
            padding: 2.5rem;
            border: 1px solid var(--glass-border);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        }

        .upload-area {
            border: 2px dashed var(--neon-purple);
            border-radius: 24px;
            padding: 5rem 2rem;
            background: #fdfdfe;
            transition: var(--transition);
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: var(--neon-blue);
            background: rgba(0, 210, 255, 0.03);
            transform: scale(0.995);
        }

        @keyframes shine {
            0% {
                transform: translateX(-150%) rotate(45deg);
            }

            20% {
                transform: translateX(150%) rotate(45deg);
            }

            100% {
                transform: translateX(150%) rotate(45deg);
            }
        }

        /* --- Improved Value Prop Cards --- */
        .feature-card {
            background: var(--bg-card);
            padding: 2.2rem;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            position: relative;
            transition: var(--transition);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: var(--neon-blue);
        }

        .feature-icon {
            font-size: 2.2rem;
            margin-bottom: 1.2rem;
            display: inline-block;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* --- Quality Section --- */
        .preview-card {
            border-radius: 24px;
            overflow: hidden;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
            transition: 0.3s;
        }

        .preview-card:hover {
            transform: translateY(-8px);
        }

        .hover-preview {
            position: relative;
            width: 100%;
            aspect-ratio: 4/3;
            overflow: hidden;
            background-image: conic-gradient(#ffffff 0.25turn, #f1f5f9 0.25turn 0.5turn, #ffffff 0.5turn 0.75turn, #f1f5f9 0.75turn);
            background-size: 20px 20px;
        }

        .hover-preview img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .after-img {
            z-index: 1;
        }

        .before-img {
            z-index: 2;
            transition: opacity 0.6s ease;
        }

        .hover-preview:hover .before-img {
            opacity: 0;
        }

        /* --- Slider --- */
        .comparison-slider {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            cursor: ew-resize;
            background: #f1f5f9;
            border-radius: 24px;
            overflow: hidden;
            user-select: none;
        }

        .slider-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .after-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            overflow: hidden;
            z-index: 2;
            border-right: 2px solid var(--neon-blue);
            background-image: conic-gradient(#ffffff 0.25turn, #e2e8f0 0.25turn 0.5turn, #ffffff 0.5turn 0.75turn, #e2e8f0 0.75turn);
            background-size: 20px 20px;
        }

        .slider-handle {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 44px;
            margin-left: -22px;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .handle-circle {
            width: 46px;
            height: 46px;
            background: #fff;
            border: 3px solid var(--neon-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            font-size: 1.3rem;
        }

        /* --- Pricing --- */
        .pricing-box {
            background: #fff;
            border-radius: 32px;
            padding: 3rem;
            border: 2px solid rgba(112, 0, 255, 0.1);
            box-shadow: 0 10px 30px rgba(112, 0, 255, 0.05);
        }

        /* --- History --- */
        .history-item {
            aspect-ratio: 1;
            background: #f1f5f9;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            transition: var(--transition);
        }

        .history-item:hover {
            transform: scale(1.05);
            border-color: var(--neon-blue);
        }

        .history-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- Footer --- */
        footer {
            background: #fff;
            padding: 5rem 0 2rem;
            border-top: 1px solid var(--glass-border);
            position: relative;
            overflow: hidden;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
            opacity: 0.3;
        }

        .footer-link {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .footer-link:hover {
            color: var(--neon-blue);
        }

        .social-link {
            width: 36px;
            height: 36px;
            background: #f8fafc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: 0.3s;
        }

        .social-link:hover {
            background: var(--primary-gradient);
            color: #fff;
        }

        /* --- Global Helpers --- */
        .section-title {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -1px;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            width: 50%;
            height: 4px;
            background: var(--primary-gradient);
            display: block;
            margin: 10px auto 0;
            border-radius: 10px;
        }

        .spinner-custom {
            width: 64px;
            height: 64px;
            border: 6px solid #f1f5f9;
            border-top: 6px solid var(--neon-blue);
            border-right: 6px solid var(--neon-purple);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .hidden {
            display: none !important;
        }

        /* Navbar */
        .navbar {
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .nav-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Logo */
        .logo {
            font-weight: 800;
            font-size: 1.2rem;
            text-decoration: none;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Right */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }


        .login-btn {
            border: 1px solid rgba(0, 0, 0, 0.1);
            color: #0f172a;
        }

        .signup-btn {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
        }

        /* Menu icon */
        .menu-toggle {
            font-size: 1.4rem;
            cursor: pointer;
            display: none;
        }

        /* Dropdown */
        .mobile-menu {
            display: none;
            flex-direction: column;
            padding: 1rem;
            background: white;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .mobile-menu a {
            padding: 0.7rem 0;
            text-decoration: none;
            color: #0f172a;
        }

        .mobile-menu .credits-badge {
            margin-bottom: 0.5rem;
        }

        /* Active */
        .mobile-menu.active {
            display: flex;
        }

        /* Mobile */
        @media (max-width: 768px) {

            .menu-toggle {
                display: block;
            }
        }

        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;

            transition: var(--transition);
            text-decoration: none;
            border-radius: var(--btn-radius) !important;
        }

        .btn-primary {
            color: #fff;
            background: var(--primary-gradient);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(112, 0, 255, 0.25);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-outline {
            color: var(--text-main);
            border: 1px solid var(--glass-border);
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--bg-card);
            border-color: var(--neon-blue);
            color: var(--neon-blue);
        }

        .btn-ghost {
            color: var(--text-muted);
            background: transparent;
        }

        .btn-ghost:hover {
            color: var(--neon-purple);
        }

        .btn-soft {
            background: rgba(112, 0, 255, 0.08);
            color: var(--neon-purple);
            border: 1px solid rgba(112, 0, 255, 0.15);
        }

        .btn-soft:hover {
            background: rgba(112, 0, 255, 0.15);
        }

        .btn-cyber {
            background: rgba(0, 210, 255, 0.08);
            color: var(--neon-blue);
            border: 1px solid rgba(0, 210, 255, 0.2);
        }

        .btn-cyber:hover {
            background: rgba(0, 210, 255, 0.15);
        }

        .btn-success {
            color: #fff;
            background-color: #198754;
            border-color: #198754;
        }

        .btn-success:hover {
            background-color: #157347;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #bb2d3b;
        }

        .btn-warning {
            color: #000;
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #ffca2c;
        }

        .btn-info {
            color: #000;
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .btn-dark {
            color: #fff;
            background-color: #212529;
            border-color: #212529;
        }

        .btn-light {
            color: #000;
            background-color: #f8f9fa;
            border-color: #f8f9fa;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .btn-gradient {
            color: #fff;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
        }

        .btn-gradient:hover {
            opacity: 0.9;
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(45deg);
            opacity: 0;
        }

        .btn-primary:hover::after {
            animation: shine 1s ease;
            opacity: 1;
        }

        @keyframes shine {
            0% {
                transform: translateX(-150%) rotate(45deg);
            }

            100% {
                transform: translateX(150%) rotate(45deg);
            }
        }

        .bg-container {
            position: absolute;
            /* Crucial: This makes the container the "anchor" for the background layer */
            min-height: 100vh;
            width: 100%;
            /* Flexbox properties to center your "Try It Now" button */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            /* Recommended: Keeps the edge clean */
        }

        /* This is the transparent background layer */
        .bg-container::before {
            content: "";
            /* Required to render the pseudo-element */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            /* Pushes the layer behind your actual content */

            /* Your original background properties */
            background: #f8f9fa url("{{ asset('main-bg.png') }}") center center / cover no-repeat;

            /* ADD OPACITY HERE (0.0 to 1.0) */
            opacity: 0.3;
            /* This is 50% opacity */
        }

        .hover-preview {
            position: relative;
            overflow: hidden;
            height: 250px;
            /* Adjust based on your design */
        }

        .after-img,
        .before-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.4s ease;
            opacity: 0;
            pointer-events: none;
            /* Prevents images from interfering with card clicks */
        }

        .show {
            opacity: 1 !important;
        }
    </style>
</head>

<body>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2 justify-content-center fw-bold text-primary"
                href="#">
                <img src="{{ asset('mrprabhat-logo.png') }}" height="40" alt="">
                <span class="d-none d-md-flex">Toolsbyprabhat</span>
            </a>




            <!-- Right Side -->
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex gap-3 me-3 d-none  d-lg-flex">
                    <a href="#" class="text-muted text-decoration-none">Features</a>
                    <a href="#" class="text-muted text-decoration-none">Pricing</a>
                    <a href="#" class="text-muted text-decoration-none">Gallery</a>

                </div>
                <!-- Login -->
                <a href="#" class="btn btn-ghost">
                    Login
                </a>

                <!-- Signup -->
                <a href="#" class="btn btn-primary">
                    Sign Up
                </a>

                <!-- Toggle -->
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

            </div>

        </div>
    </nav>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">

        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <!-- Credits -->
            <div class="mb-3">
                <span class="badge bg-light text-primary fs-6 px-3 py-2">
                    Credits: 20
                </span>
            </div>

            <!-- Links -->
            <div class="d-flex flex-column gap-3 fs-5">
                <a href="#" class="text-dark text-decoration-none">Features</a>
                <a href="#" class="text-dark text-decoration-none">Pricing</a>
                <a href="#" class="text-dark text-decoration-none">Gallery</a>
                <a href="#" class="text-dark text-decoration-none">API</a>
            </div>

        </div>

    </div>


    <style>
        .hero-scan-container {
            position: relative;
            width: 100%;
            max-width: 450px;
            aspect-ratio: 3 / 4;
            margin: 0 auto;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
            background: #fff;
        }

        .layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Ensure images NEVER change size */
        .layer img {
            width: 100% !important;
            /* height: 450px !important; Matches container scale or use object-fit */
            aspect-ratio: 3 / 4;
            object-fit: cover;
            display: block;
        }

        /* The Result Layer (Background Pattern) */
        .result-layer {
            z-index: 1;
            background-image: radial-gradient(#d1d1d1 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* The Original Layer (Animate Height) */
        .original-layer {
            z-index: 2;
            overflow: hidden;
            /* This creates the "crop" effect */
            border-bottom: 2px solid var(--neon-blue);
            animation: verticalScan 4s cubic-bezier(0.45, 0, 0.55, 1) infinite alternate;
        }

        .original-layer img {
            filter: brightness(0.9);
        }

        /* The Scanning Bar */
        .scan-line-horizontal {
            position: absolute;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--neon-blue);
            z-index: 3;
            box-shadow: 0 0 20px var(--neon-blue);
            animation: barMove 4s cubic-bezier(0.45, 0, 0.55, 1) infinite alternate;
        }

        /* Keyframes: Top to Bottom */
        @keyframes verticalScan {
            0% {
                height: 100%;
            }

            /* Fully Original */
            10% {
                height: 100%;
            }

            /* Pause */
            90% {
                height: 0%;
            }

            /* Fully Removed */
            100% {
                height: 0%;
            }

            /* Pause */
        }

        @keyframes barMove {
            0% {
                top: 100%;
            }

            10% {
                top: 100%;
            }

            90% {
                top: 0%;
            }

            100% {
                top: 0%;
            }
        }
    </style>

    <div class="bg-container">
    </div>
    <main class="container py-5">
        <!-- Hero Section -->
        <div class="row align-items-center justify-content-center g-0 g-lg-5 mb-5">
            <div class="col-lg-7 text-lg-start text-center order-2">
                <h1 class="mb-3 mt-3 mt-lg-0 display-5 fw-bold">
                    Remove Image Background <br>in HD —
                    <span style="color: var(--neon-blue);">Instantly</span>
                </h1>

                <p class="text-dark fs-5 mb-2 fw-semibold">
                    Try it yourself — I guarantee you won't be disappointed.
                </p>
                <p class="text-muted fs-6 mb-4 mx-auto mx-lg-0" style="max-width: 600px;">
                    Stop settling for low-quality previews. Experience pixel-perfect HD background removal
                    that actually works every single time.
                </p>

                <div class="trust-line mb-5 fw-medium opacity-75">
                    <span class="me-2">✨</span> Join thousands of creators & sellers across India 🇮🇳
                </div>

                <div class="mt-2">
                    <button class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg fw-bold border-0"
                        style="" onclick="scrollToWorkspace()">
                        Try It Now — 100% Free
                    </button>
                    <div
                        class="small text-muted mt-3 d-flex align-items-center justify-content-center justify-content-lg-start gap-3">
                        <span><i class="bi bi-check-circle-fill text-success"></i> 20 Free HD Downloads</span>
                        <span class="opacity-50">|</span>
                        <span><i class="bi bi-stars text-warning"></i> Peak Detailing</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 p-0 order-1">
                <div class="hero-scan-container">
                    <div class="layer result-layer">
                        <img src="{{ asset('images/landing-image-no-bg.png') }}" alt="Result">
                    </div>

                    <div class="layer original-layer">
                        <img src="{{ asset('images/landing-image.jpg') }}" alt="Original">
                    </div>

                    <div class="scan-line-horizontal"></div>
                </div>
            </div>
        </div>

        <!-- Main Tool Workspace -->
        <div id="main-workspace" class="mb-5">
            <div id="upload-card" class="custom-card">
                <div class="upload-area text-center" id="drop-zone"
                    onclick="document.getElementById('file-input').click()">
                    <input type="file" id="file-input" accept="image/*" class="d-none">
                    <div class="mb-4 display-4">📸</div>
                    <h3 class="fw-bold mb-2">Click or Drag & Drop</h3>
                    <p class="text-muted mb-4">Upload your image to get started</p>
                    <p class="text-muted small mb-4">👉 Signup required to try</p>
                    {{-- <button class="btn btn-primary">Upload Image</button> --}}
                </div>

                <!-- Preview State -->
                <div id="image-preview" class="hidden">
                    <div class="position-relative overflow-hidden border rounded-4 bg-light">
                        <img id="img-source" src="" alt="Preview" class="w-100 d-block"
                            style="max-height: 500px; object-fit: contain;">
                        <button class="btn btn-light position-absolute top-0 end-0 m-3 shadow-sm rounded-3"
                            style="width: 40px; height: 40px;" onclick="resetUI()">✕</button>
                    </div>
                    <div class="mt-4 text-center">
                        <button class="btn btn-custom-primary btn-lg px-5" onclick="processImage()">
                            Remove Background Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading UI -->
            <div id="loading-state" class="hidden text-center py-5">
                <div class="spinner-custom mx-auto mb-4"></div>
                <h3 class="fw-extrabold">GENERATING HD OUTPUT...</h3>
                <p class="text-muted">Isolating subject from background (2-3 seconds)</p>
            </div>

            <!-- Results Section -->
            <div id="result-card" class="custom-card hidden">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold m-0">Real HD Result</h3>
                    <button class="btn btn-outline-secondary btn-sm rounded-3" onclick="resetUI()">Try New</button>
                </div>

                <div class="comparison-slider" id="slider-box">
                    <img src="" alt="Original" class="slider-image" id="res-original">
                    <div class="after-container" id="after-container">
                        <img src="" alt="Result" class="slider-image" id="res-result">
                    </div>
                    <div class="slider-handle" id="slider-handle">
                        <div class="handle-circle">↔️</div>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <button class="btn btn-primary btn-lg px-5" onclick="handleDownload()">
                        Download HD Result
                    </button>
                </div>
            </div>

            <!-- Exhausted State -->
            <div id="exhausted-card" class="custom-card hidden text-center">
                <div class="display-4 mb-3">🎉</div>
                <h2 class="fw-bold mb-3">Daily Limit Reached</h2>
                <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">You've used all 20 of your free daily
                    credits. Upgrade to keep removing backgrounds instantly.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <button class="btn btn-custom-primary">Upgrade Plan (₹49)</button>
                    <button class="btn btn-outline-dark rounded-4 px-4" onclick="resetUI()">Back to Start</button>
                </div>
            </div>
        </div>

        <!-- Quality Section -->
        <div class="text-center  mb-5 mt-5 pt-4">
            <h2 class="section-title">Built for Pixel Perfection</h2>
            <p class="text-muted">Click Image to see the perfection and detailing</p>
        </div>

        <div class="row g-4 mb-5" id="previewRow">
            <div class="col-md-4">
                <div class="preview-card" onclick="toggleComparison(this)">
                    <div class="hover-preview">
                        <img src="{{ asset('images/girl-no-bg.png') }}" alt="Result" class="after-img">
                        <img src="{{ asset('images/girl-img.jpg') }}" alt="Original" class="before-img show"
                            style="filter: brightness(0.8)">
                    </div>
                    <div class="preview-label text-center p-3 fw-semibold">Portrait HD Quality</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="preview-card" onclick="toggleComparison(this)">
                    <div class="hover-preview">
                        <img src="{{ asset('images/e-commerce-no-bg.png') }}" alt="Result" class="after-img">
                        <img src="{{ asset('images/e-commerce.jpg') }}" alt="Original" class="before-img show"
                            style="filter: brightness(0.8)">
                    </div>
                    <div class="preview-label text-center p-3 fw-semibold">E-commerce Products</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="preview-card" onclick="toggleComparison(this)">
                    <div class="hover-preview">
                        <img src="{{ asset('images/peak-detailing-no-bg.png') }}" alt="Result" class="after-img">
                        <img src="{{ asset('images/peak-detailing.jpg') }}" alt="Original" class="before-img show"
                            style="filter: brightness(0.8)">
                    </div>
                    <div class="preview-label text-center p-3 fw-semibold">Peak Detailing for Hair</div>
                </div>
            </div>
        </div>

        <!-- Value Proposition -->
        <div class="text-center mb-5 mt-5">
            <h2 class="section-title">Try Once. You’ll Use It Forever.</h2>
            <p class="text-muted">Built for real results — not just demos.</p>
        </div>

        <div class="row g-4 mb-5 justify-content-center">

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <span class="feature-icon">💎</span>
                    <h3 class="fs-5 fw-bold">Real HD Quality</h3>
                    <p class="text-muted small m-0">
                        No blurry previews. You get clean, full-resolution images ready to use anywhere.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <span class="feature-icon">⚡</span>
                    <h3 class="fs-5 fw-bold">Instant Results</h3>
                    <p class="text-muted small m-0">
                        Upload → process → download in seconds. No waiting, no complicated steps.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <span class="feature-icon">🎁</span>
                    <h3 class="fs-5 fw-bold">Free to Start</h3>
                    <p class="text-muted small m-0">
                        Get 20 real HD removals. No tricks, no fake previews — just real usage.
                    </p>
                </div>
            </div>

        </div>

        <div class="pricing-section text-center py-5 mx-auto" style="max-width: 1000px;">
            <div class="mb-5">
                <h2 class="fw-bold mb-3 section-title">Simple Pricing. <span class="text-primary">No Strings
                        Attached.</span></h2>
                <p class="fs-5 text-muted">
                    Start for free, upgrade when you're ready. <br>
                    <strong>Zero Subscriptions. Zero Watermarks. Pure HD Quality.</strong>
                </p>
            </div>

            <div class="row g-4 justify-content-center align-items-stretch">
                <div class="col-md-5">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 hover-lift">
                        <div class="card-body d-flex flex-column">
                            <span class="text-uppercase small fw-bold text-muted mb-2">The Starter</span>
                            <h3 class="fw-bold text-dark">Welcome Gift</h3>
                            <div class="display-6 fw-bold my-3">₹0</div>
                            <ul class="list-unstyled mb-4 flex-grow-1">
                                <li class="mb-2">✅ <strong>20 Full HD</strong> Credits</li>
                                <li class="mb-2">✅ No Expiry Date</li>
                                <li class="mb-2 text-muted">✅ Standard Processing</li>
                            </ul>
                            <button class="btn btn-outline-dark rounded-pill py-2 fw-bold"
                                onclick="scrollToWorkspace()">Get Started Free</button>
                            <small class="mt-3 text-muted">Limited time for early users</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div
                        class="card h-100 border-primary shadow-lg rounded-4 p-4 position-relative overflow-hidden premium-card">
                        <div class="popular-badge">BEST VALUE</div>

                        <div class="card-body d-flex flex-column">
                            <span class="text-uppercase small fw-bold text-primary mb-2">Pro Detailing</span>
                            <h3 class="fw-bold text-dark">Creator Pack</h3>
                            <div class="display-6 fw-bold my-3 text-primary">₹49</div>

                            <ul class="list-unstyled mb-4 flex-grow-1 text-start px-3">
                                <li class="mb-2">🔥 <strong>200 HD Credits</strong> <span
                                        class="badge bg-success-subtle text-success ms-1">Save 90%</span></li>
                                <li class="mb-2">⚡ Priority Ultra-Fast Removal</li>
                                <li class="mb-2">💎 Peak Detail Enhancement</li>
                                <li class="mb-2">🛡️ Lifetime Validity</li>
                            </ul>

                            <button class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm shine-effect"
                                onclick="startPayment()">
                                Unlock 200 Images — ₹49
                            </button>
                            <small class="mt-3 text-primary fw-bold">Less than ₹0.25 per image!</small>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-5 text-muted small">
                <i class="bi bi-shield-lock"></i> Secure one-time payment. No recurring charges, ever.
            </p>
        </div>

        <div class="col">
            <div class="card border-0 bg-light rounded-4 h-100 overflow-hidden shadow-sm hover-shadow transition">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/200x200" class="card-img-top p-2 rounded-5"
                        alt="Processed Image">
                    <span class="badge bg-success position-absolute top-0 end-0 m-2 rounded-pill">HD</span>
                </div>
                <div class="card-body p-2 text-center">
                    <p class="small text-truncate mb-1 fw-medium">portrait_01.png</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-outline-dark border-0 p-1"><i
                                class="bi bi-download"></i></button>
                        <button class="btn btn-sm btn-outline-danger border-0 p-1"><i
                                class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 bg-light rounded-4 h-100 overflow-hidden shadow-sm opacity-75">
                <div class="d-flex align-items-center justify-content-center bg-white m-2 rounded-4"
                    style="height: 150px;">
                    <div class="spinner-border text-primary spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="card-body p-2 text-center">
                    <p class="small text-truncate mb-1 text-muted">car_photo.jpg</p>
                    <span class="badge bg-warning text-dark rounded-pill"
                        style="font-size: 0.7rem;">Processing...</span>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 bg-light rounded-4 h-100 overflow-hidden shadow-sm">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/200x200" class="card-img-top p-2 rounded-5"
                        alt="Processed Image">
                </div>
                <div class="card-body p-2 text-center">
                    <p class="small text-truncate mb-1 fw-medium">product_v2.png</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-outline-dark border-0 p-1"><i
                                class="bi bi-download"></i></button>
                        <button class="btn btn-sm btn-outline-danger border-0 p-1"><i
                                class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Final CTA -->
        <section class="text-center py-5 mt-5 rounded-5 shadow-lg text-white"
            style="background: var(--primary-gradient);">
            <h2 class="fw-bold display-5 mb-3">Try It Now — See the Result</h2>
            <p class="opacity-75 mb-5 fs-5">Upload any image and see the background removed instantly.</p>
            <button class="btn btn-light px-4 py-2 fw-bold rounded-5" style="color: var(--neon-purple);"
                onclick="scrollToWorkspace()">Upload Your Image</button>
        </section>
    </main>

    <footer class="mt-5">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-12">
                    <a href="#" class="logo">ToolsByPrabhat</a>
                    <p class="text-muted mt-3 small">Building high-quality AI tools accessible for students and
                        creators across India.</p>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <h6 class="fw-bold mb-3">Product</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link">BG Remover</a></li>
                        <li><a href="#" class="footer-link">Image Upscaler</a></li>
                        <li><a href="#" class="footer-link">Pricing</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link">Help Center</a></li>
                        <li><a href="#" class="footer-link">Contact Us</a></li>
                        <li><a href="#" class="footer-link">Report Bug</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h6 class="fw-bold mb-3">Connect</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link">Privacy Policy</a></li>
                        <li><a href="#" class="footer-link">Terms of Service</a></li>
                        <li><a href="#" class="footer-link">Refund Policy</a></li>
                        <li><a href="#" class="footer-link">About ToolsByPrabhat</a></li>
                    </ul>
                </div>
            </div>

            <div
                class="pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-2 text-muted small">
                <p class="mb-0">© 2024 ToolsByPrabhat. Made with ❤️ for creators.</p>
                <div class="d-flex gap-3">

                    <a href="#" class="social-link">𝕏</a>
                    <a href="#" class="social-link">In</a>
                    <a href="#" class="social-link">Ig</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleComparison(card) {
            // 1. Get images ONLY within the clicked card
            const afterImg = card.querySelector('.after-img');
            const beforeImg = card.querySelector('.before-img');

            // 2. We also need to find the button text (if it exists globally)
            const btnText = document.querySelector('#imageToggler .view-text');

            // 3. Toggle the 'show' class locally
            const isShowingOriginal = beforeImg.classList.contains('show');

            if (isShowingOriginal) {
                // Switch this card to "After" view
                beforeImg.classList.remove('show');
                afterImg.classList.add('show');

                // Optional: Update global button text if needed
                if (btnText) btnText.innerText = "Show Original Images";
            } else {
                // Switch this card back to "Before" view
                afterImg.classList.remove('show');
                beforeImg.classList.add('show');

                // Optional: Update global button text if needed
                if (btnText) btnText.innerText = "Show Background Removed";
            }
        }
    </script>
    <script>
        let credits = 0;
        let isProcessing = false;

        const fileInput = document.getElementById('file-input');
        const imgSource = document.getElementById('img-source');
        const uploadCard = document.getElementById('upload-card');
        const dropZone = document.getElementById('drop-zone');
        const imagePreview = document.getElementById('image-preview');
        const loadingState = document.getElementById('loading-state');
        const resultCard = document.getElementById('result-card');
        const resOriginal = document.getElementById('res-original');
        const resResult = document.getElementById('res-result');
        const afterContainer = document.getElementById('after-container');
        const sliderHandle = document.getElementById('slider-handle');
        const sliderBox = document.getElementById('slider-box');
        const creditsCount = document.getElementById('credits-count');
        const historyGrid = document.getElementById('history-grid');
        const exhaustedCard = document.getElementById('exhausted-card');

        function scrollToWorkspace() {
            const workspace = document.getElementById('main-workspace');
            window.scrollTo({
                top: workspace.offsetTop - 100,
                behavior: 'smooth'
            });
        }

        fileInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) handleFile(e.target.files[0]);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--neon-blue)';
        });
        dropZone.addEventListener('dragleave', () => dropZone.style.borderColor = '#cbd5e1');
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#cbd5e1';
            if (e.dataTransfer.files && e.dataTransfer.files[0]) handleFile(e.dataTransfer.files[0]);
        });

        function handleFile(file) {
            if (!file.type.startsWith('image/')) {
                alert("Please upload a valid image file.");
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                const data = e.target.result;
                imgSource.src = data;
                resOriginal.src = data;
                resResult.src = data;

                dropZone.classList.add('hidden');
                exhaustedCard.classList.add('hidden');
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function processImage() {
            if (credits <= 0) {
                uploadCard.classList.add('hidden');
                exhaustedCard.classList.remove('hidden');
                return;
            }
            if (isProcessing) return;
            isProcessing = true;
            uploadCard.classList.add('hidden');
            loadingState.classList.remove('hidden');
            setTimeout(() => {
                loadingState.classList.add('hidden');
                resultCard.classList.remove('hidden');

                credits--;
                creditsCount.innerText = `Credits: ${credits}`;

                resResult.style.filter =
                    "contrast(1.1) brightness(1.1) drop-shadow(0 0 15px rgba(255,255,255,0.8))";
                resResult.style.opacity = "0.95";

                isProcessing = false;
                addToHistory(imgSource.src);
                initSlider();
            }, 2500);
        }

        function resetUI() {
            imagePreview.classList.add('hidden');
            resultCard.classList.add('hidden');
            loadingState.classList.add('hidden');
            exhaustedCard.classList.add('hidden');
            uploadCard.classList.remove('hidden');
            dropZone.classList.remove('hidden');
            fileInput.value = '';
            isProcessing = false;
        }

        function addToHistory(src) {
            if (historyGrid.innerHTML.includes('No history')) historyGrid.innerHTML = '';
            const col = document.createElement('div');
            col.className = 'col';
            col.innerHTML = `<div class="history-item"><img src="${src}"></div>`;
            historyGrid.prepend(col);
        }

        function initSlider() {
            let active = false;
            const startMove = () => active = true;
            const endMove = () => active = false;

            const adjust = (e) => {
                if (!active) return;
                let x = e.pageX || (e.touches && e.touches[0].pageX);
                if (!x) return;
                let rect = sliderBox.getBoundingClientRect();
                let pos = ((x - rect.left) / rect.width) * 100;
                pos = Math.max(0, Math.min(100, pos));
                afterContainer.style.width = pos + '%';
                sliderHandle.style.left = pos + '%';
            };

            sliderBox.onmousedown = startMove;
            window.onmouseup = endMove;
            window.onmousemove = adjust;
            sliderBox.ontouchstart = startMove;
            window.ontouchend = endMove;
            window.ontouchmove = adjust;

            afterContainer.style.width = '50%';
            sliderHandle.style.left = '50%';
        }

        function handleDownload() {
            const link = document.createElement('a');
            link.href = resResult.src;
            link.download = 'hd_result_toolsbyprabhat.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

</body>

</html>
