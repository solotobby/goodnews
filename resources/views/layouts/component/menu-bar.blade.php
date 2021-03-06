<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ms-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="{{ url('home') }}" class="logo-link">
                    <img class="logo-light logo-img" src="{{asset('res/images/goodnews_logo.png')}}" srcset="{{ asset('res/images/logo2x.png 2x') }}" alt="logo">
                    <img class="logo-dark logo-img" src="{{ asset('res/images/goodnews_logo.png') }}" srcset="{{ asset('res/images/logo-dark2x.png 2x') }}" alt="logo-dark">
                    {{-- <img class="logo-dark logo-img" src="{{ asset('res/images/logo-dark.png') }}" srcset="{{ asset('res/images/logo-dark2x.png 2x') }}" alt="logo-dark"> --}}
                </a>
            </div><!-- .nk-header-brand -->
            
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info d-none d-md-block">
                                    <div class="user-status">

                                        @if(auth()->user()->hasRole('admin'))
                                        Administrator
                                        @elseif(auth()->user()->hasRole('user'))
                                        User
                                        @else
                                        Reseller
                                        @endif
                                    </div>
                                    <div class="user-name dropdown-indicator">{{auth()->user()->name}}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end dropdown-menu-s1">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <?php 
                                            // $str = auth()->user()->name;
                                            // $words = explode(' ', $str);
                                            // $result = $words[0][0]. $words[1][0];
                                        ?>
                                        {{-- <span>{{ strtoupper($result) }}</span> --}}
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{auth()->user()->name}}</span>
                                        <span class="sub-text">{{auth()->user()->email}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    {{-- <li><a href="html/user-profile-regular.html"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li> --}}
                                    {{-- <li><a href="html/user-profile-setting.html"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li> --}}
                                    {{-- <li><a href="html/user-profile-activity.html"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li> --}}
                                    <li><a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a 
                                        href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <em class="icon ni ni-signout"></em><span>Sign out</span>
                                        </a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                   
                </ul><!-- .nk-quick-nav -->
            </div><!-- .nk-header-tools -->
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>