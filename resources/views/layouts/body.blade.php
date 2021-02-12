@extends('layouts.app')
@section('body')
<body>
    <div class="loader">Loading&#8230;</div>
    <header>
        <nav class="navbar fixed-top navbar-expand-lg py-1 navbar-dark blue scrolling-navbar">
            <a class="navbar-brand" href="./"><strong>SISTEM INFORMASI IMPORTIR</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-flex-icons ml-auto">
                    <li class="nav-item dropdown px-2">
                        <a class="nav-link dropdown" title="Akun Anda" data-toggle="dropdown"><i class="fa fa-user-circle fa-2x"></i></a>
                        <div class="dropdown-menu dropdown-primary dropdown-menu-right">
                            <div id="userprofile">{{ Auth::user()->name }}</div>
                            @can('users')
                            <a class="dropdown-item" href="{{ url('/admin/users') }}">Users</a>
                            @endcan
                            @can('roles')
                            <a class="dropdown-item" href="{{ url('/admin/roles') }}">Roles</a>
                            @endcan
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container-fluid">
        @yield('content')
    </div>
    <div class="footer">
        <hr/>
        <div class="container text-center">
            <p class="text-muted small">&copy;&nbsp;2019 All rights reserved.<br>Versi 2018.1.1
            </p>
        </div>
    </div>
</body>
@endsection
