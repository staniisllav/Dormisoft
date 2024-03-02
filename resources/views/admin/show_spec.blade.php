<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('spec')" />

{{-- Page content start --}}
<section class="content">
    {{-- livewire tabs --}}
    <livewire:show-spec itemId="{{ $data->id }}" />

    {{-- end livewire tabs --}}
    <a href="#" class="top-up-btn" id="topUp">

        <svg>
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </a>

</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardfooter />