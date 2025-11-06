<div class="sidebar shadow" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="clinicdropdown">
                    <a href="{{ route('dashboard', $module_id) }}" class="logo">
                        <img src="{{ asset('assets/img/user.jpg') }}" class="img-fluid" alt="Profile" />
                        <div class="user-names">
                            <h5>{{ Auth::user()->nom ?? 'Utilisateur' }}</h5>
                            <h6>{{ Auth::user()->fonction ?? 'fonction' }}</h6>
                        </div>
                    </a>
                </li>
            </ul>
            <ul>
                <li>
                    <h6 class="submenu-hdr">Menu Ned&Core Audit</h6>
                    <ul>
                        <li>
                            <a href="{{ route('dashboard', $module_id) }}"
                                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fa fa-home"></i><span>Audit de conformité </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
