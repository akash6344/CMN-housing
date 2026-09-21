@extends('layouts.builder')

@section('content')
    @include('builder.partials.settings-tabs')

    <div class="settings-panels">
        <section class="settings-panel is-active" data-settings-panel="profile">
            <div class="card settings-card">
                <div class="settings-card-head">
                    <h2>Profile Information</h2>
                    <p>Update your personal information and contact details.</p>
                </div>

                <div class="profile-photo">
                    <div class="profile-avatar">{{ $profile['initials'] }}</div>
                    <div>
                        <button type="button" class="btn btn-outline" data-toast="Photo upload coming soon">Change Photo</button>
                        <p class="field-hint">JPG, PNG or GIF. Max 2MB.</p>
                    </div>
                </div>

                <div class="form-grid form-grid-2">
                    @include('builder.partials.form-field', [
                        'id' => 'first-name',
                        'label' => 'First Name',
                        'value' => $profile['firstName'],
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'last-name',
                        'label' => 'Last Name',
                        'value' => $profile['lastName'],
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'email',
                        'label' => 'Email',
                        'type' => 'email',
                        'value' => $profile['email'],
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'phone',
                        'label' => 'Phone',
                        'type' => 'tel',
                        'value' => $profile['phone'],
                    ])
                </div>

                <div class="settings-card-actions">
                    <button type="button" class="btn btn-primary" data-toast="Profile saved">Save Changes</button>
                </div>
            </div>
        </section>

        <section class="settings-panel" data-settings-panel="company" hidden>
            <div class="card settings-card">
                <div class="settings-card-head">
                    <h2>Company Information</h2>
                    <p>Manage your builder company details and branding.</p>
                </div>

                <div class="form-grid">
                    @include('builder.partials.form-field', [
                        'id' => 'company-name',
                        'label' => 'Company Name',
                        'value' => $company['name'],
                        'class' => 'span-2',
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'gst',
                        'label' => 'GST Number',
                        'value' => $company['gst'],
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'pan',
                        'label' => 'PAN Number',
                        'value' => $company['pan'],
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'address',
                        'label' => 'Registered Address',
                        'value' => $company['address'],
                        'class' => 'span-2',
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'website',
                        'label' => 'Website',
                        'value' => $company['website'],
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'year',
                        'label' => 'Year Established',
                        'value' => $company['year'],
                    ])
                </div>

                <div class="settings-card-actions">
                    <button type="button" class="btn btn-primary" data-toast="Company details saved">Save Changes</button>
                </div>
            </div>
        </section>

        <section class="settings-panel" data-settings-panel="notifications" hidden>
            <div class="card settings-card">
                <div class="settings-card-head">
                    <h2>Notification Preferences</h2>
                    <p>Choose what notifications you want to receive.</p>
                </div>

                <div class="toggle-list">
                    @foreach ($notificationPrefs as $pref)
                        @include('builder.partials.toggle-row', ['pref' => $pref])
                    @endforeach
                </div>
            </div>
        </section>

        <section class="settings-panel" data-settings-panel="security" hidden>
            <div class="card settings-card">
                <div class="settings-card-head">
                    <h2>Password</h2>
                    <p>Change your password to keep your account secure.</p>
                </div>

                <div class="form-grid form-grid-1">
                    @include('builder.partials.form-field', [
                        'id' => 'current-password',
                        'label' => 'Current Password',
                        'type' => 'password',
                        'placeholder' => '••••••••',
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'new-password',
                        'label' => 'New Password',
                        'type' => 'password',
                        'placeholder' => '••••••••',
                    ])
                    @include('builder.partials.form-field', [
                        'id' => 'confirm-password',
                        'label' => 'Confirm New Password',
                        'type' => 'password',
                        'placeholder' => '••••••••',
                    ])
                </div>

                <div class="settings-card-actions settings-card-actions-start">
                    <button type="button" class="btn btn-primary" data-toast="Password updated">Update Password</button>
                </div>
            </div>

            <div class="card settings-card">
                <div class="settings-card-head">
                    <h2>Two-Factor Authentication</h2>
                    <p>Add an extra layer of security to your account.</p>
                </div>

                <div class="security-2fa">
                    <div class="security-2fa-icon">
                        {!! \App\Support\Icon::svg('smartphone') !!}
                    </div>
                    <div class="security-2fa-meta">
                        <div class="toggle-title">Authenticator App</div>
                        <div class="toggle-desc">Use an authenticator app for 2FA</div>
                    </div>
                    <button type="button" class="btn btn-outline" data-toast="2FA setup coming soon">Enable</button>
                </div>
            </div>
        </section>
    </div>
@endsection
