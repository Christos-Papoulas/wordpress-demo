<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())

    @vite(['resources/css/app.css', 'resources/js/app.js'])

  </head>

  <body @php(body_class())>
    @php(wp_body_open())

    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      @include('sections.header.template')

      <main id="main" class="main">
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar">
          @yield('sidebar')
        </aside>
      @endif

      @include('sections.footer.template')
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())

    <div id="backdrop" class="hidden bg-[rgba(0,0,0,.5)] fixed inset-0 z-[1000]"></div>
    @include('components.wishlist-notification')
    @include('components.compare-list-notification')
  </body>
  <script>
    document.getElementById('backdrop').addEventListener('click',function(){
      document.dispatchEvent(new CustomEvent('closeAllModals', {detail: {}}));
    })
  </script>
  <?php

    $user = wp_get_current_user();
    $user = [
      'email' => $user->user_email,
      'display_name' => $user->display_name,
      'first_name' => $user->first_name,
      'last_name' => $user->last_name,
    ];

    $ajax_callback_settings = [
      'ajax_url' => admin_url('admin-ajax.php'),
      'ajax_nonce' => wp_create_nonce('security_nonce'),
    ];
    $global_app_data = [
      'home_url' => home_url(),
      'app_name' => get_bloginfo('name'),
      'logo' => [
          'url' => ht_get_field('header_logo','options')['url'] ?? '',
          'width' => ht_get_field('header_logo','options')['width'] ?? 0,
          'height' => ht_get_field('header_logo','options')['height'] ?? 0,
      ],
      'user' => $user,
    ];
  ?>
  <script>
    var ajax_callback_settings = {{ Illuminate\Support\Js::from($ajax_callback_settings) }};
    var global_app_data = {{ Illuminate\Support\Js::from($global_app_data) }};
  </script>
</html>
