<!--
<footer class="content-info">
    @php(dynamic_sidebar('sidebar-footer'))
</footer>
-->
<footer class="site-footer">
    <div class="site-footer__inner container container--narrow">
        <div class="group">
            <div class="site-footer__col-one">
                <h1 class="school-logo-text school-logo-text--alt-color">
                    <a href="{{ home_url('/') }}"><strong>Fictional</strong> University</a>
                </h1>
                <p><a class="site-footer__link" href="#">555.555.5555</a></p>
            </div>

            <div class="site-footer__col-two-three-group">
                <div class="site-footer__col-two">
                    <h3 class="headline headline--small">Explore</h3>
                    @if (has_nav_menu('footerLocationOne'))
                      <nav class="nav-list" aria-label="{{ wp_get_nav_menu_name('footerLocationOne') }}">
                        {!!
                          wp_nav_menu([
                            'theme_location' => 'footerLocationOne',
                            'menu_class' => '', 'echo' => false
                          ])
                        !!}
                      </nav>
                    @endif
                </div>

                <div class="site-footer__col-three">
                    <h3 class="headline headline--small">Learn</h3>
                    @if (has_nav_menu('footer_location_two'))
                      <nav class="nav-list" aria-label="{{ wp_get_nav_menu_name('footer_location_two') }}">
                        {!!
                          wp_nav_menu([
                            'theme_location' => 'footer_location_two',
                            'menu_class' => '', 'echo' => false
                          ])
                        !!}
                      </nav>
                    @endif
                </div>
            </div>

            <div class="site-footer__col-four">
                <h3 class="headline headline--small">Connect With Us</h3>
                <nav>
                    <ul class="min-list social-icons-list group">
                        <li>
                            <a href="#" class="social-color-facebook"><i class="fa fa-facebook"
                                    aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#" class="social-color-twitter"><i class="fa fa-twitter"
                                    aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#" class="social-color-youtube"><i class="fa fa-youtube"
                                    aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#" class="social-color-linkedin"><i class="fa fa-linkedin"
                                    aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#" class="social-color-instagram"><i class="fa fa-instagram"
                                    aria-hidden="true"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</footer>
