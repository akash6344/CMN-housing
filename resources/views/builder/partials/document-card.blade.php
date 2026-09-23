<article class="card document-card" data-document-card data-category="{{ $document['category'] }}">
    <div class="document-card-head">
        <div class="document-icon">
            {!! \App\Support\Icon::svg('file') !!}
        </div>
        <div class="document-info">
            <h3 class="document-title">{{ $document['title'] }}</h3>
            <p class="document-project">{{ $document['project'] }}</p>
            <p class="document-id">{{ $document['idLabel'] }}: {{ $document['idValue'] }}</p>
        </div>
        <span class="chip {{ $document['statusClass'] }}">
            {!! \App\Support\Icon::svg($document['statusIcon']) !!}
            {{ $document['status'] }}
        </span>
    </div>

    <div class="document-card-foot">
        <span class="document-expires">Expires: {{ $document['expires'] }}</span>
        <div class="document-actions">
            <button type="button" class="doc-link" data-toast="Viewing {{ $document['title'] }}">
                {!! \App\Support\Icon::svg('eye') !!} View
            </button>
            <button type="button" class="doc-link" data-toast="Downloading {{ $document['title'] }}">
                {!! \App\Support\Icon::svg('download') !!} Download
            </button>
        </div>
    </div>
</article>
