<div class="text-truncate take-tool-headelemnet">
    @if (isset($tool_id))
    <span class="badge badge-pill badge-soft-primary font-size-11">{{ $tool_id }}</span>
    @endif
    <div class="row page_headingapp">
        <div class="col-1" id="normal-back">
            <a href="{{ url()->previous() }}" class="trgr_ovrly">
            <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </a>
        </div>
        <div class="col-1" id="steps-back" style="display: none;">
            <a id="previous-steps" role="menuitem">
            <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 17L1 9L9 1" stroke="#667685" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </a>
        </div>
        <div class="col-10">
            <h4 class="mb-0 submisn_heading step_heading">{{ $text }}</h4>
        </div>
        <div class="col-1"></div>
    </div>
    
</div>
