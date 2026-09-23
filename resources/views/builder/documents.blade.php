@extends('layouts.builder')

@section('content')
    <div class="banner documents-banner">
        <div class="banner-icon">{!! \App\Support\Icon::svg('alert') !!}</div>
        <div class="banner-copy">
            <strong>{{ $documentBanner['title'] }}</strong>
            <span>{{ $documentBanner['text'] }}</span>
        </div>
        <button type="button" class="btn btn-warning btn-sm" data-toast="Opening pending document">
            View Details
        </button>
    </div>

    <div class="page-toolbar documents-toolbar">
        <div class="status-tabs" data-filter-group="documents">
            @foreach ($documentTabs as $index => $tab)
                <button
                    type="button"
                    class="status-tab {{ $index === 0 ? 'is-active' : '' }}"
                    data-filter-tab
                    data-document-filter="{{ $tab['id'] }}"
                >{{ $tab['label'] }}</button>
            @endforeach
        </div>
    </div>

    <div class="document-grid" data-document-grid>
        @foreach ($documents as $document)
            @include('builder.partials.document-card', ['document' => $document])
        @endforeach
    </div>
@endsection
