@extends('layouts.app')

@push('styles')
<style>
    .admin-content { min-width: 0; }

    main .dt-container .dt-layout-row:first-child {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: .75rem;
        margin: 0;
        padding: 1rem 1.25rem .75rem;
    }

    main .dt-container .dt-search {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: 0 !important;
    }

    main .dt-container .dt-search input {
        min-width: 220px;
        max-width: 100%;
        margin-left: 0 !important;
        padding: .5rem .7rem;
        border: 1px solid #d8cba9;
        border-radius: .65rem;
        background: #fffdf8;
    }

    main .dt-container .dt-search input:focus {
        border-color: var(--ec);
        outline: 0;
        box-shadow: 0 0 0 .2rem #3158b826;
    }

    @media (min-width: 992px) {
        .admin-sidebar {
            position: sticky;
            top: 57px;
            z-index: 1010;
            align-self: flex-start;
            height: calc(100vh - 57px);
            overflow-y: auto;
        }
    }

    @media (max-width: 575.98px) {
        main .dt-container .dt-layout-row:first-child {
            justify-content: stretch;
            padding-inline: 1rem;
        }

        main .dt-container .dt-search {
            width: 100%;
        }

        main .dt-container .dt-search input {
            min-width: 0;
            flex: 1;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('main table').forEach(table => new DataTable(table, {
        paging: false,
        info: false,
        searching: true,
        order: []
    }));
</script>
@endpush

@section('content')
<div class="container-fluid admin-shell">
    <div class="row">
        <aside class="col-lg-2 bg-ec p-3 admin-sidebar">
            <h5 class="text-white mb-4"><i class="bi bi-shield-check"></i> Admin Panel</h5>
            @foreach([['admin.dashboard','bi-grid','Dashboard'],['admin.vehicles.index','bi-car-front','Kendaraan'],['admin.manufacturers.index','bi-buildings','Manufacturer'],['admin.users.index','bi-people','Pengguna'],['admin.notifications.index','bi-bell','Notifikasi']] as [$route,$icon,$label])
                <a href="{{ route($route) }}" class="sidebar-link {{ request()->routeIs(str_replace('.index','.*',$route))?'active':'' }}">
                    <i class="bi {{ $icon }} me-2"></i>{{ $label }}
                </a>
            @endforeach
        </aside>
        <main class="col-lg-10 p-4 p-lg-5 admin-content">@yield('admin-content')</main>
    </div>
</div>
@endsection
