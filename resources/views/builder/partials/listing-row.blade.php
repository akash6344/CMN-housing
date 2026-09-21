<tr data-listing-row data-status="{{ strtolower($listing['status']) }}" data-project="{{ $listing['project'] }}">
    <td>
        <div class="listing-unit-id">{{ $listing['unitId'] }}</div>
    </td>
    <td>{{ $listing['project'] }}</td>
    <td>{{ $listing['type'] }}</td>
    <td>{{ $listing['size'] }}</td>
    <td>
        <div class="listing-price">{{ $listing['price'] }}</div>
    </td>
    <td>
        <span class="chip {{ $listing['statusClass'] }}">{{ $listing['status'] }}</span>
    </td>
    <td>{{ $listing['leads'] }}</td>
    <td>{{ $listing['views'] }}</td>
    <td>
        <div class="listing-row-actions">
            <button type="button" class="icon-ghost" data-toast="Viewing {{ $listing['unitId'] }}" aria-label="View">
                {!! \App\Support\Icon::svg('eye') !!}
            </button>
            <button type="button" class="icon-ghost" data-toast="Edit {{ $listing['unitId'] }}" aria-label="Edit">
                {!! \App\Support\Icon::svg('edit') !!}
            </button>
            <button type="button" class="icon-ghost" data-toast="Paused {{ $listing['unitId'] }}" aria-label="Pause">
                {!! \App\Support\Icon::svg('pause') !!}
            </button>
            <button type="button" class="icon-ghost" data-toast="More actions" aria-label="More">
                {!! \App\Support\Icon::svg('more') !!}
            </button>
        </div>
    </td>
</tr>
