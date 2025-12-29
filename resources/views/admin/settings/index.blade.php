@extends('admin.partials.layout')
@push('styles')
    <style>
        .settings-page-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .settings-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .settings-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .page-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title-icon {
            font-size: 24px;
            color: #667eea;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .settings-card label.form-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .settings-card .form-control {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            font-family: 'Segoe UI', sans-serif;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        }

        .settings-card .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            outline: none;
            background-color: #ffffff;
        }

        .settings-card textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .settings-card .error-text {
            font-size: 0.8rem;
        }

        .form-help-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .btn-primary,
        .btn-secondary {
            border-radius: 8px;
            font-weight: 600;
            padding: 8px 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(102, 126, 234, 0.5);
        }

        .btn-secondary {
            background: #5c636a;
        }

        .btn-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(108, 117, 125, 0.6);
        }

        @media (max-width: 768px) {
            .settings-card {
                padding: 20px;
                border-radius: 12px;
            }

            .page-title {
                font-size: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="settings-page-wrapper">
        <div class="admin container-fluid py-2">
            @include('admin.partials.sidebar')
            <div class="admin main-content p-4">
                <div class="settings-card">
                    <div class="page-header">
                        <h1 class="page-title">
                            <i class="fas fa-cog page-title-icon"></i>
                            Settings
                        </h1>
                    </div>

                    <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="admin_emails" class="form-label">Admin Emails</label>
                                <textarea 
                                    class="form-control" 
                                    id="admin_emails" 
                                    name="admin_emails" 
                                    placeholder="Enter email addresses separated by commas (e.g., admin1@example.com, admin2@example.com)"
                                    autocomplete="off">{{ old('admin_emails', $adminEmails) }}</textarea>
                                <div class="form-help-text">
                                    <i class="fas fa-info-circle"></i> Enter multiple email addresses separated by commas. These emails will be used for admin notifications.
                                </div>
                                @error('admin_emails')
                                    <span class="text-danger error-text" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <input type="hidden" name="close" value="1" disabled>
                            <button type="submit" onclick="$('input[name=close]').prop('disabled', false);"
                                class="btn btn-secondary">Save &amp; Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#settingsForm').on('submit', function(e) {
                e.preventDefault();

                var adminEmails = $('#admin_emails').val().trim();

                // Validate email format if provided
                if (adminEmails) {
                    var emails = adminEmails.split(',').map(function(email) {
                        return email.trim();
                    }).filter(function(email) {
                        return email.length > 0;
                    });

                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    for (var i = 0; i < emails.length; i++) {
                        if (!emailRegex.test(emails[i])) {
                            toastr.error('Invalid email format: ' + emails[i]);
                            return false;
                        }
                    }
                }

                $(this).off('submit').submit();
            });
        });
    </script>
@endpush

