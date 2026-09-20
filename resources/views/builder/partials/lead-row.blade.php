<tr>
    <td>
        <div class="lead-cell">
            <div class="avatar" style="background:{{ $lead['color'] }};color:{{ $lead['ink'] }}">
                {{ $lead['initials'] }}
            </div>
            <div>
                <div class="lead-cell-name">{{ $lead['name'] }}</div>
                <div class="lead-cell-meta">{{ $lead['email'] }}</div>
                <div class="lead-cell-meta">{{ $lead['phone'] }}</div>
            </div>
        </div>
    </td>
    <td>
        <div class="lead-cell-name">{{ $lead['project'] }}</div>
        <div class="lead-cell-meta">{{ $lead['unit'] }}</div>
    </td>
    <td>
        <span class="chip {{ $lead['statusClass'] }}">{{ $lead['status'] }}</span>
    </td>
    <td>{{ $lead['source'] }}</td>
    <td>{{ $lead['date'] }}</td>
    <td>
        <div class="lead-row-actions">
            @if (!empty($lead['showActions']))
                <button type="button" class="icon-ghost" data-toast="Calling {{ $lead['name'] }}…" aria-label="Call">
                    {!! \App\Support\Icon::svg('phone') !!}
                </button>
                <button type="button" class="icon-ghost" data-toast="Email drafted for {{ $lead['name'] }}" aria-label="Email">
                    {!! \App\Support\Icon::svg('mail') !!}
                </button>
                <button type="button" class="icon-ghost" data-toast="Schedule visit" aria-label="Schedule">
                    {!! \App\Support\Icon::svg('calendar') !!}
                </button>
            @endif
            <button type="button" class="icon-ghost" data-toast="More actions" aria-label="More">
                {!! \App\Support\Icon::svg('more') !!}
            </button>
        </div>
    </td>
</tr>
