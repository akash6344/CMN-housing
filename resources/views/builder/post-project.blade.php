@extends('layouts.builder')

@section('content')
    <div class="wizard-page" data-project-wizard data-current-step="1">
        <div class="wizard-top">
            <a class="wizard-back" href="{{ route('dashboard') }}">
                {!! \App\Support\Icon::svg('arrowLeft') !!} Back to Dashboard
            </a>
            @include('builder.partials.post-project.stepper')
        </div>

        <div class="wizard-layout">
            <div class="wizard-main">
                @include('builder.partials.post-project.step-basic')
                @include('builder.partials.post-project.step-units')
                @include('builder.partials.post-project.step-amenities')
                @include('builder.partials.post-project.step-media')
                @include('builder.partials.post-project.step-review')
                @include('builder.partials.post-project.wizard-footer')
            </div>

            <aside class="wizard-aside">
                <div class="wizard-aside-ready card" data-ready-card hidden>
                    <div class="banner-icon" style="background:#d1fae5;color:#047857;">{!! \App\Support\Icon::svg('checkSimple') !!}</div>
                    <h3>Your project is almost ready!</h3>
                    <p>Submit for review and our team will verify details before going live.</p>
                    <button type="button" class="btn btn-primary" data-wizard-submit data-toast="Project submitted for review">
                        Submit Project for Review {!! \App\Support\Icon::svg('arrowRight') !!}
                    </button>
                </div>
                @include('builder.partials.post-project.tips-card')
                @include('builder.partials.post-project.preview-card')
                @include('builder.partials.post-project.bargain-aside')
                <article class="card wizard-promo">
                    <strong>List Smarter. Sell Faster.</strong>
                    <span>Only on CMNHousing</span>
                </article>
            </aside>
        </div>
    </div>
@endsection
