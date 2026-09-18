<article class="card deal-card">
    <div class="deal-head">
        <span class="deal-id">Deal #{{ $deal['id'] }}</span>
        <span class="chip {{ $deal['statusClass'] }}">{{ $deal['status'] }}</span>
    </div>
    <h3 class="deal-title">{{ $deal['title'] }}</h3>
    <div class="deal-unit">{{ $deal['unit'] }}</div>

    @if ($deal['counter'])
        <div class="offer-row">
            <div class="offer-box">
                <span>Buyer Offer</span>
                <strong>{{ $deal['buyer'] }}</strong>
            </div>
            <div class="offer-box accent">
                <span>Your Counter</span>
                <strong>{{ $deal['counter'] }}</strong>
            </div>
        </div>
    @else
        <div class="offer-row single">
            <div class="offer-box">
                <span>Buyer Offer</span>
                <strong>{{ $deal['buyer'] }}</strong>
            </div>
        </div>
        @if ($deal['expires'])
            <div class="deal-note">
                {!! \App\Support\Icon::svg('clock') !!} {{ $deal['expires'] }}
            </div>
        @endif
        <div class="deal-actions">
            <button class="btn btn-success btn-sm" type="button" data-toast="Accepted deal #{{ $deal['id'] }}">Accept</button>
            <button class="btn btn-outline btn-sm" type="button" data-open-counter="{{ $deal['id'] }}">Counter</button>
            <button class="btn btn-outline btn-sm" type="button" data-toast="Rejected deal #{{ $deal['id'] }}">Reject</button>
        </div>
    @endif
</article>
