<article class="card lead-card">
    <div class="lead-top">
        <div class="avatar" style="background:{{ $lead['color'] }};color:{{ $lead['ink'] }}">
            {{ $lead['initials'] }}
        </div>
        <div>
            <div class="lead-name">{{ $lead['name'] }}</div>
            <div class="lead-sub">{{ $lead['property'] }}</div>
        </div>
        <span class="chip {{ $lead['statusClass'] }}">{{ $lead['status'] }}</span>
    </div>
    <ul class="lead-meta">
        <li>{!! \App\Support\Icon::svg('mail') !!}{{ $lead['email'] }}</li>
        <li>{!! \App\Support\Icon::svg('phone') !!}{{ $lead['phone'] }}</li>
        <li>{!! \App\Support\Icon::svg('map') !!}{{ $lead['place'] }}</li>
        <li>{!! \App\Support\Icon::svg('clock') !!}{{ $lead['when'] }}</li>
    </ul>
    <div class="lead-actions">
        <button class="btn btn-primary btn-sm" type="button" data-toast="Calling {{ $lead['name'] }}…">
            {!! \App\Support\Icon::svg('phone') !!} Call
        </button>
        <button class="btn btn-outline btn-sm" type="button" data-toast="Email drafted for {{ $lead['name'] }}">
            {!! \App\Support\Icon::svg('mail') !!} Email
        </button>
    </div>
</article>
