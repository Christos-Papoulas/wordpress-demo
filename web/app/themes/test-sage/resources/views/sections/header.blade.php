<header class="site-header">
  <div class="container">
    <h1 class="school-logo-text float-left">
      <a href="{{ home_url('/') }}">{!! $siteName !!}</a>
    </h1>
    <span class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
    <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
    <div class="site-header__menu group">
      @if (has_nav_menu('primary_navigation'))
        <nav class="main-navigation" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
          {!!
            wp_nav_menu([
              'theme_location' => 'primary_navigation',
              'menu_class' => '', 'echo' => false
            ])
          !!}
        </nav>
      @endif
      <div class="site-header__util">
      @if (is_user_logged_in())
        <a href="{{ site_url('/my-notes') }}" class="btn btn--small btn--orange float-left push-right">My Notes</a>

        <a href="{{ wp_logout_url() }}" class="btn btn--small btn--dark-orange float-left btn--with-photo">
          <span class="site-header__avatar">
            {!! get_avatar(get_current_user_id(), 60) !!}
          </span>

          <span class="btn__text">
            Log out
          </span>
        </a>
      @else
        <a href="{{ wp_login_url() }}" class="btn btn--small btn--orange float-left push-right">Login</a>
        <a href="{{ wp_registration_url() }}" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
        <span class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
        @endif
      </div>
    </div>
  </div>
</header>
