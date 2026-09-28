<div class="wizard-footer" data-wizard-footer>
    <button type="button" class="btn btn-outline" data-wizard-prev hidden>
        {!! \App\Support\Icon::svg('arrowLeft') !!} Previous
    </button>
    <button type="button" class="btn btn-outline" data-toast="Draft saved">
        {!! \App\Support\Icon::svg('save') !!} Save as Draft
    </button>
    <div class="wizard-footer-right">
        <button type="button" class="btn btn-ghost" data-toast="Cancelled" onclick="window.location='{{ route('builder.projects') }}'">Cancel</button>
        <button type="button" class="btn btn-primary" data-wizard-next>
            Next: Units & Pricing {!! \App\Support\Icon::svg('arrowRight') !!}
        </button>
        <button type="button" class="btn btn-primary" data-wizard-submit hidden data-toast="Project submitted for review">
            Submit Project for Review {!! \App\Support\Icon::svg('send') !!}
        </button>
    </div>
</div>
