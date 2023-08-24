<aside id="sidebar-left" class="sidebar-left">

    <div class="sidebar-header">
        <div class="sidebar-title">
            Navigation
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano has-scrollbar">
        <div class="nano-content" tabindex="0" style="right: -15px;">
            <nav id="menu" class="nav-main" role="navigation">

                <ul class="nav nav-main">
                    <li>
                        <a class="nav-link" href="{{route('cms.dashboard.index')}}">
                            <i class="fas fa-home" aria-hidden="true"></i>
                            <span>{{trans('label.dashboard')}}</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link" href="{{route('race.index')}}">
                            <i class="fas fa-people-carry" aria-hidden="true"></i>
                            <span>{{trans('Registration')}}</span>
                        </a>
                    </li>









                </ul>
            </nav>
        </div>
    </div>

    <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
        <div class="nano-pane" style="opacity: 1; visibility: visible;"><div class="nano-slider" style="height: 142px; transform: translate(0px, 0px);"></div></div>

</aside>
