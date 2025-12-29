@extends('admin.partials.layout')

@push('styles')
    <style>
        .login-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 12px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 32px;
            width: 100%;
            max-width: 520px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-title i {
            color: #667eea;
            font-size: 24px;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #dfe6e9;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            outline: none;
        }

        /* Validation error styles */
        .form-control.error {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
        }

        .form-control.valid {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.15);
        }

        label.error {
            color: #dc3545;
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 5px;
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-primary-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.35);
        }

        .btn-primary-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-primary-login i {
            font-size: 0.9rem;
        }

        /* Loading state animations */
        .btn-primary-login.loading {
            pointer-events: none;
            opacity: 0.85;
            cursor: not-allowed;
        }

        .btn-primary-login.loading .btn-text {
            display: none;
        }

        .btn-primary-login.loading .btn-loading {
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

        .btn-primary-login.loading:hover {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.35);
        }

        .login-footer a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-title">
                <i class="fas fa-lock"></i>
                Admin Login
            </div>

            <form id="loginForm" method="POST" action="{{ route('admin.post.login') }}">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input id="username" type="text" class="form-control" maxlength="10"
                        name="username" value="{{ old('username') }}" autocomplete="off"
                        placeholder="Enter username">
                    @error('username')
                        <span class="text-danger error-text" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input id="password" type="password" class="form-control" maxlength="10"
                        name="password" value="{{ old('password') }}" autocomplete="off"
                        placeholder="Enter password">
                    @error('password')
                        <span class="text-danger error-text" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary-login" id="loginBtn">
                    <span class="btn-text">
                        <i class="fa fa-arrow-right"></i> Login
                    </span>
                    <span class="btn-loading text-white" style="display: none;">
                        <i class="fa fa-spinner fa-spin "></i> Logging in...
                    </span>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<!-- jQuery Validation Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js"></script>

<script>
    $(document).ready(function() {
        // Add custom alphanumeric validation method (must be defined before validate())
        // $.validator.addMethod("alphanumeric", function(value, element) {
        //     // If field is optional and empty, skip validation
        //     if (this.optional(element)) {
        //         return true;
        //     }
        //     // Check if value contains only letters and numbers
        //     return /^[a-zA-Z0-9]+$/.test(value);
        // }, "Username can only contain letters and numbers");

        // Initialize jQuery Validation
        $('#loginForm').validate({
            rules: {
                username: {
                    required: true,
                    // minlength: 3,
                    maxlength: 10,
                    // alphanumeric: true
                },
                password: {
                    required: true,
                    // minlength: 3,
                    // maxlength: 10
                }
            },
            messages: {
                username: {
                    required: "Please enter your username",
                    minlength: "Username must be at least 3 characters",
                    maxlength: "Username cannot exceed 10 characters",
                    alphanumeric: "Username can only contain letters and numbers"
                },
                password: {
                    required: "Please enter your password",
                    minlength: "Password must be at least 3 characters",
                    maxlength: "Password cannot exceed 10 characters"
                }
            },
            errorElement: 'label',
            errorClass: 'error',
            errorPlacement: function(error, element) {
                // Remove any existing error messages
                element.siblings('label.error').remove();
                // Place error message after the input field
                error.insertAfter(element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('error').removeClass('valid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('error').addClass('valid');
            },
            submitHandler: function(form) {
                const $btn = $('#loginBtn');
                const $form = $(form);
                
                // Prevent double submission
                if ($btn.hasClass('loading')) {
                    return false;
                }
                
                // Add loading state
                $btn.addClass('loading');
                $btn.prop('disabled', true);
                
                // Submit the form
                form.submit();
                
                // Optional: Auto-reset after 10 seconds if form doesn't submit
                setTimeout(function() {
                    if ($btn.hasClass('loading')) {
                        $btn.removeClass('loading');
                        $btn.prop('disabled', false);
                    }
                }, 10000);
            },
            invalidHandler: function(event, validator) {
                // Reset button state if validation fails
                $('#loginBtn').removeClass('loading').prop('disabled', false);
            }
        });

        // Real-time validation on blur
        $('#username, #password').on('blur', function() {
            $(this).valid();
        });

        // Clear validation errors on input
        $('#username, #password').on('input', function() {
            if ($(this).hasClass('error')) {
                $(this).valid();
            }
        });
    });
</script>
@endpush