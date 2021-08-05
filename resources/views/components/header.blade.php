<nav class="navbar navbar-expand-sm bg-light">
    <a class="navbar-brand" href="">{{ucfirst($role)}}</a>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">About</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route($role.'.logout') }}">Logout</a>        
        </li>
    </ul>
</nav>