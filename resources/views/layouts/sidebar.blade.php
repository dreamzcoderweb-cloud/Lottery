<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.banners.index') }}" class="app-brand-link">
            <img src="{{ asset('assets/img/logo.png') }}" alt="logo" height="28">
            <span class="app-brand-text demo menu-text fw-bold ms-2">Super Admin</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        @canany(['banners.view'])
        <li
            class="menu-item {{ request()->is('admin/banners') || request()->is('admin/add_banner') || request()->is('admin/edit_banner/*') || request()->is('admin/banners') || request()->is('admin/add_banner') || request()->is('admin/edit_banner/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div class="text-truncate" data-i18n="banners">Banners</div>
            </a>

            <ul class="menu-sub">

                @can('banners.view')
                <li
                    class="menu-item {{ request()->is('admin/banners') || request()->is('admin/add_banner') || request()->is('admin/edit_banner/*') ? 'active' : '' }}">
                    <a href="{{ route('admin.banners.index') }}" class="menu-link">
                        <div class="text-truncate">With Filter</div>
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endcanany

        @canany(['roles.view', 'staff.view'])
            <li
                class="menu-item {{ request()->is('admin/roles_with_filter') || request()->is('admin/add_role') || request()->is('admin/edit_role/*') || request()->is('admin/staff') || request()->is('admin/add_staff') || request()->is('admin/edit_staff/*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-lock-alt"></i>
                    <div class="text-truncate">Access Control</div>
                </a>

                <ul class="menu-sub">
                    @can('roles.view')
                        <li
                            class="menu-item {{ request()->is('admin/roles_with_filter') || request()->is('admin/add_role') || request()->is('admin/edit_role/*') ? 'active' : '' }}">
                            <a href="{{ route('admin.roles.index') }}" class="menu-link">
                                <div class="text-truncate">Role Master</div>
                            </a>
                        </li>
                    @endcan
                    @can('staff.view')
                        <li
                            class="menu-item {{ request()->is('admin/staff') || request()->is('admin/add_staff') || request()->is('admin/edit_staff/*') ? 'active' : '' }}">
                            <a href="{{ route('admin.staff.index') }}" class="menu-link">
                                <div class="text-truncate">Staff</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['customers.view','customers.show'])
            <li
                class="menu-item {{ request()->is('admin/customers') || request()->is('admin/customers/*') ? 'active' : '' }}">
                <a href="{{ route('admin.customers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div class="text-truncate" data-i18n="services">Customers</div>
                </a>
            </li>
        @endcanany

        @canany(['slots.view'])
            <li
                class="menu-item {{ request()->is('admin/slots') ||  request()->is('admin/add_slot') || request()->is('admin/edit_slot/*')? 'active' : '' }}">
                <a href="{{ route('admin.slots.index') }}" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-purchase-tag-alt"></i>
                    <div class="text-truncate" data-i18n="services">Slots</div>
                </a>
            </li>
        @endcanany

        @can('withdrawals.view')
            <li class="menu-item {{ request()->is('admin/wallet-withdrawals') || request()->is('admin/wallet-withdrawals/*') ? 'active' : '' }}">
                <a href="{{ route('admin.withdrawals.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-credit-card"></i>
                    <div class="text-truncate">Withdrawals</div>
                </a>
            </li>
        @endcan

        @can('recharges.view')
            <li class="menu-item {{ request()->is('admin/wallet-recharges') || request()->is('admin/wallet-recharges/*') ? 'active' : '' }}">
                <a href="{{ route('admin.recharges.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-wallet"></i>
                    <div class="text-truncate">Wallet Recharges</div>
                </a>
            </li>
        @endcan

         @canany(['reports.winningsslots'])
            <li
                class="menu-item {{ request()->is('admin/reports/winnings-slots') || request()->is('admin/reports/winnings-slots/*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                    <div class="text-truncate">Report</div>
                </a>

                <ul class="menu-sub">

                    @can('reports.winningsslots')
                        <li
                            class="menu-item {{ request()->is('admin/reports/winnings-slots') || request()->is('admin/reports/winnings-slots/*') ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.winningsslots') }}" class="menu-link">
                                <div class="text-truncate">Winnings Slots</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
    </ul>
</aside>
