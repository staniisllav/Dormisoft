<x-dashboardheader />
<x-dashboardnavbar />
<x-dashboardsidebar :active="__('store_settings')" />
{{-- Page content start --}}
<section class="content">

    {{-- Tabel by Livewire start --}}
    <livewire:storesettingstable />
    {{-- Tabel by Livewire end --}}

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
