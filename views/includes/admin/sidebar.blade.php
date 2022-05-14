<section class="sidebar">
    <div class="sidebar-brand">
        <i class='bx bx-home-alt-2'></i>
        <span class="sidebar-brand-name">MyDashboard</span>
    </div>

    <ul class="sidebar-navigation">
        <li class="sidebar-navigation-item sidebar-navigation-item--active">
            <a href="{{ url('/admin') }}" class="sidebar-navigation-link">
                <i class='bx bx-grid-alt'></i>
                <span class="sidebar-navigation-name">Dashboard</span>
            </a>
        </li>

        <li class="sidebar-navigation-item">
            <a href="{{ url('/admin/accounts') }}" class="sidebar-navigation-link">
                <i class='bx bx-user'></i>
                <span class="sidebar-navigation-name">Accounts</span>
            </a>
        </li>

        <li class="sidebar-navigation-item">
            <a href="{{ url('/admin/examinations') }}" class="sidebar-navigation-link">
                <i class='bx bx-cube-alt'></i>
                <span class="sidebar-navigation-name">Examination</span>
            </a>
        </li>

        <li class="sidebar-navigation-item">
            <a href="{{ url('/admin/subjects') }}" class="sidebar-navigation-link">
                <i class='bx bx-cube-alt'></i>
                <span class="sidebar-navigation-name">Subjects</span>
            </a>
        </li>
    </ul>
</section>
