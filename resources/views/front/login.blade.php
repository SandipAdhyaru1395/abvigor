@extends('front.partials.layout')

@section('content')
    <div class="front container my-5">
        <div class="front auth-wrapper">
            <div class="auth-card">
                <div class="auth-header">
                    <h4><i class="fa fa-sign-in-alt"></i> Login</h4>
                    <p class="auth-subtitle">Enter your registered mobile number to continue</p>
                </div>
                <div class="auth-body">
                    <form id="loginForm" method="POST" action="{{ route('post.login') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <div class="auth-input-group">
                                <div class="country-code">+91</div>
                                <input id="mobile" type="text" class="form-control auth-input" maxlength="10"
                                    name="mobile" value="{{ old('mobile') }}" autocomplete="off"
                                    placeholder="Enter your 10-digit mobile number"
                                    onkeypress="return /[0-9]/i.test(event.key)">
                            </div>
                            @error('mobile')
                                <span class="text-danger error-text" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-auth-submit w-100" id="loginBtn">
                            <span class="btn-text">
                                <i class="fa fa-arrow-right"></i> Login
                            </span>
                            <span class="btn-loading" style="display: none;">
                                <i class="fa fa-spinner fa-spin"></i> Logging in...
                            </span>
                        </button>

                        <div class="auth-footer mt-4 text-center">
                            <span>Don't have an account?</span>
                            <a href="{{ route('get.register') }}" class="auth-link">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .auth-wrapper {
        min-height: calc(100vh - 250px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        max-width: 480px;
        width: 100%;
        padding: 32px 32px 28px;
        border-top: 4px solid #ed1c24;
    }

    .auth-header {
        margin-bottom: 24px;
        border-bottom: 1px solid #f1f1f1;
        padding-bottom: 16px;
    }

    .auth-header h4 {
        margin: 0 0 6px;
        font-weight: 700;
        color: #333333;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .auth-header h4 i {
        color: #ed1c24;
    }

    .auth-subtitle {
        margin: 0;
        font-size: 0.9rem;
        color: #777777;
    }

    .auth-body {
        margin-top: 8px;
    }

    .auth-input-group {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1px solid #ced4da;
        border-radius: 8px;
        overflow: hidden;
        background-color: #fdfdfd;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .auth-input-group:focus-within {
        border-color: #ed1c24;
        box-shadow: 0 0 0 0.2rem rgba(237, 28, 36, 0.15);
        background-color: #ffffff;
    }

    .country-code {
        padding: 10px 14px;
        font-size: 0.95rem;
        color: #555555;
        background: #f5f5f5;
        border-right: 1px solid #e2e2e2;
        font-weight: 600;
        white-space: nowrap;
    }

    .auth-input {
        border: none !important;
        box-shadow: none !important;
        padding: 10px 14px;
        font-size: 0.95rem;
    }

    .auth-input::placeholder {
        color: #b5b5b5;
        font-size: 0.9rem;
    }

    .auth-input:focus {
        outline: none;
    }

    .btn-auth-submit {
        background: linear-gradient(135deg, #ed1c24 0%, #c91a20 100%);
        color: #ffffff;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        padding: 10px 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 6px 16px rgba(237, 28, 36, 0.35);
        transition: all 0.25s ease;
    }

    .btn-auth-submit:hover {
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(237, 28, 36, 0.45);
    }

    .btn-auth-submit:active {
        transform: translateY(0);
        box-shadow: 0 3px 8px rgba(237, 28, 36, 0.35);
    }

    .btn-auth-submit i {
        font-size: 0.9rem;
    }

    /* Loading state animations */
    .btn-auth-submit.loading {
        pointer-events: none;
        opacity: 0.85;
        cursor: not-allowed;
    }

    .btn-auth-submit.loading .btn-text {
        display: none;
    }

    .btn-auth-submit.loading .btn-loading {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        gap: 8px;
        animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    .btn-auth-submit.loading:hover {
        transform: translateY(0);
        box-shadow: 0 6px 16px rgba(237, 28, 36, 0.35);
    }

    .auth-footer {
        font-size: 0.9rem;
        color: #777777;
    }

    .auth-link {
        color: #ed1c24;
        font-weight: 600;
        margin-left: 4px;
        text-decoration: none;
        position: relative;
    }

    .auth-link::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 0;
        height: 2px;
        background: #ed1c24;
        transition: width 0.2s ease;
    }

    .auth-link:hover::after {
        width: 100%;
    }

    .error-text {
        font-size: 0.85rem;
        margin-top: 4px;
        display: block;
    }

    @media (max-width: 576px) {
        .auth-card {
            padding: 24px 18px 22px;
            border-radius: 12px;
        }

        .auth-header h4 {
            font-size: 1.1rem;
        }

        .country-code {
            padding: 8px 10px;
            font-size: 0.85rem;
        }

        .auth-input {
            padding: 8px 10px;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            const $btn = $('#loginBtn');
            const $form = $(this);
            
            // Prevent double submission
            if ($btn.hasClass('loading')) {
                e.preventDefault();
                return false;
            }
            
            // Add loading state
            $btn.addClass('loading');
            $btn.prop('disabled', true);
            
            // Optional: Auto-reset after 10 seconds if form doesn't submit
            setTimeout(function() {
                if ($btn.hasClass('loading')) {
                    $btn.removeClass('loading');
                    $btn.prop('disabled', false);
                }
            }, 10000);
        });

        // Reset button state if form validation fails
        $('#loginForm').on('invalid', function() {
            $('#loginBtn').removeClass('loading').prop('disabled', false);
        });
    });
</script>
@endpush