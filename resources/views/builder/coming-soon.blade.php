@extends('layouts.builder')

@section('content')
    <section class="card coming-soon">
        <div class="coming-soon-icon">{!! \App\Support\Icon::svg('clock') !!}</div>
        <h2>{{ $sectionName }}</h2>
        <p>Coming soon. This screen is not part of the current dashboard design.</p>
        <a class="btn btn-primary" href="{{ route('dashboard') }}">Back to Overview</a>
    </section>
@endsection
