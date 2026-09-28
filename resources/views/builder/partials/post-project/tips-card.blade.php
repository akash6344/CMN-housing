<article class="card wizard-tips">
    <div class="wizard-tips-head">
        {!! \App\Support\Icon::svg('lightbulb') !!}
        <h3>Tips for a Great Listing</h3>
    </div>
    <ul>
        @foreach ($wizardTips as $tip)
            <li>
                {!! \App\Support\Icon::svg('checkSimple') !!}
                <span>{{ $tip }}</span>
            </li>
        @endforeach
    </ul>
</article>
