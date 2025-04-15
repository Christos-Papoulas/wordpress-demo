@if( defined( 'ICL_SITEPRESS_VERSION' ) )
    <style>
        .wpml-ls-legacy-dropdown-click{
            width: unset!important;
        }
        .wpml-ls-legacy-dropdown-click,
        .wpml-ls-legacy-dropdown-click div,
        .wpml-ls-legacy-dropdown-click ul,
        .wpml-ls-legacy-dropdown-click li,
        .wpml-ls-legacy-dropdown-click a{
            background:inherit!important;
        }
        .wpml-ls-legacy-dropdown-click .wpml-ls-current-language:hover>a, .wpml-ls-legacy-dropdown-click a:focus, .wpml-ls-legacy-dropdown-click a:hover,.wpml-ls-display{
            color: var(--color-primary);
        }
    </style>
    <div class="bg-inherit text-inherit">
    @php
       echo do_shortcode('[wpml_language_selector_widget]');
    @endphp
    </div>
@endif
