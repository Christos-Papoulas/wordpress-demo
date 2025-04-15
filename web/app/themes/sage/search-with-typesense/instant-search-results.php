<?php
/*
 * tmpl-cmswt-Result-itemTemplate--[post-type-slug]
 * for different templates for different post types add the post type slug instead of [post-type-slug] as the id
 * example tmpl-cm-typesense-shortcode-page-search-results or tmpl-cm-typesense-shortcode-book-search-results
 */
?>
<script type="text/html" id="tmpl-cmswt-Result-itemTemplate--default">
    <# if( data.post_type === 'recipe' ) { #>

        <a href="{{{data._highlightResult.permalink.value}}}" class="group flex flex-col justify-between " rel="nofollow noopener">
            <div class="hit-header flex aspect-square rounded-t-3xl overflow-hidden">
                <# var imageHTML = '';

                console.log(data.post_type)

                if(data.post_thumbnail_html !== undefined && data.post_thumbnail_html !== ''){
                imageHTML = data.post_thumbnail_html
                }else if(data.post_thumbnail !== undefined && data.post_thumbnail !== ''){
                imageHTML = `<img src="${data.post_thumbnail}"
                                alt="${data.post_title}"
                                class="ais-Hit-itemImage object-cover !w-full !h-full md:group-hover:brightness-125 md:group-hover:scale-105 md:group-hover:rotate-3 md:transition md:duration-300"
                />`
                }
                else{
                imageHTML = `<img src="<?php echo esc_url(CODEMANAS_TYPESENSE_THUMBNAIL_IMAGE_URL) ?>"
                                alt="${data.post_title}"
                                class="ais-Hit-itemImage object-cover !w-full !h-full md:group-hover:brightness-125 md:group-hover:scale-105 md:group-hover:rotate-3 md:transition md:duration-300"
                />`
                }
                #>
                <# if(imageHTML !== ''){ #>
                    {{{imageHTML}}}
                <# } #>
            </div>

            <div class="flex flex-col">
                <div class="bg-[#F5F5F5] p-5 text-body flex flex-col pb-6">
                    <h5 class="title text-lg font-semibold mb-3 line-clamp-2 min-h-14">{{{data.formatted.post_title}}}</h5>
                    <div class="text-base text-black font-bold flex gap-2 border-b-[1.5px] border-black py-2">
                        <span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.71875 4.5V19.5" stroke="#707072" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.46875 4.875V8.625C5.46875 10.5 7.71875 10.5 7.71875 10.5C7.71875 10.5 9.96875 10.5 9.96875 8.625V4.875" stroke="#707072" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.2812 10.5V19.5" stroke="#707072" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.5312 7.5C18.5312 9.15686 17.5239 10.5 16.2812 10.5C15.0386 10.5 14.0312 9.15686 14.0312 7.5C14.0312 5.84314 15.0386 4.5 16.2812 4.5C17.5239 4.5 18.5312 5.84314 18.5312 7.5Z" stroke="#707072" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                        </span>
                        {{{ data?.serves }}} <?php echo ' '.__('Serves', 'sage'); ?>
                    </div>
                    <div class="text-base text-black font-bold flex gap-2 border-b-[1.5px] border-black py-2">
                        <span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.9453 12L17.9453 18.7179" stroke="#707072" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.05469 7.94727L6.05469 18.718" stroke="#707072" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 5.28125L12 18.7177" stroke="#707072" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                        </span>    
                        {{{ data?.calories_per_serve }}} <?php echo ' '.__('Calories / Serving', 'sage'); ?>
                    </div>
                </div>
                <div class="bg-primary text-white font-semibold text-base rounded-b-3xl text-center px-7 py-2.5">
                    {{{ data.difficulty_term?.name }}}
                </div>
            </div>
        </a>
       

    <# } else { #>
        <# if(data.taxonomy === undefined){ #>
        <div class="hit-header">
            <# var imageHTML = '';

            console.log(data.post_type)

            if(data.post_thumbnail_html !== undefined && data.post_thumbnail_html !== ''){
            imageHTML = data.post_thumbnail_html
            }else if(data.post_thumbnail !== undefined && data.post_thumbnail !== ''){
            imageHTML = `<img src="${data.post_thumbnail}"
                            alt="${data.post_title}"
                            class="ais-Hit-itemImage"
            />`
            }
            else{
            imageHTML = `<img src="<?php echo esc_url(CODEMANAS_TYPESENSE_THUMBNAIL_IMAGE_URL) ?>"
                            alt="${data.post_title}"
                            class="ais-Hit-itemImage"
            />`
            }
            #>
            <# if(imageHTML !== ''){ #>
            <a href="{{{data._highlightResult.permalink.value}}}" class="hit-header--link" rel="nofollow noopener">{{{imageHTML}}}</a>
            <# } #>
        </div>
        <# } #>
        <div class="hit-content">
            <# if(data._highlightResult.permalink !== undefined ) { #>
            <a href="{{{data._highlightResult.permalink.value}}}" class="hit-contentLink" rel="nofollow noopener"><h5 class="title">
                    {{{data.formatted.post_title}}}</h5></a>
            <# } #>
            <# if( data.post_type === 'post' ) { #>
            <div class="hit-meta">
                <span class="posted-by">
                    By {{data.post_author}}
                </span>
                <span class="posted-on">
                    <time datetime="">{{data.formatted.postedDate}}</time>
                </span>
                <# if ( Object.keys(data.formatted.cats).length > 0 ) { #>
                <div class="hit-cats">
                    <# for ( let key in data.formatted.cats ) { #>
                    <div class="hit-cat"><a href="{{{data.formatted.cats[key]}}}">{{{key}}}</a>,</div>
                    <# } #>
                </div>
                <# } #>
            </div>
            <# } #>
            <div class="hit-description">{{{data.formatted.post_content}}}</div>
            <div class="hit-link">
                <a href="{{data.permalink}}"><?php _e('Read More...', 'search-with-typesense'); ?></a>
            </div>
        </div>
    <# } #>
</script>
