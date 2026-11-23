<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="#" class="brand-link">
            <span class="brand-text fw-light">Job Hunter</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon bi bi-palette"></i>
                        <p>Home</p>
                    </a>
                </li>
                <hr>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-table"></i><p>
                            Vagas
                        <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('job_parameters') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Parametros</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('appliedJobs') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Inscritas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-download"></i>
                        <p>Logs</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
