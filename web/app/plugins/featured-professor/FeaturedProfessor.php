<?php

class FeaturedProfessor
{
	function __construct()
	{
		add_action('init', [$this, 'onInit']);
		add_action('rest_api_init', [$this, 'onRestApiInit']);
		add_action('the_content', [$this, 'addRelatedPosts']);
	}

	function onInit()
	{
		load_plugin_textdomain(
			domain: 'featured-professor',
			plugin_rel_path: dirname(plugin_basename(__FILE__)) . '/languages'
		);

		register_meta(
			'post',
			'featuredProfessor',
			array(
				'show_in_rest' => true,
				'type' => 'number',
				'single' => false,
			)
		);

		wp_register_script('featuredProfessorScript', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-i18n', 'wp-editor'));
		wp_register_style('featuredProfessorStyle', plugin_dir_url(__FILE__) . 'build/index.css');

		wp_set_script_translations('featuredProfessorScript', 'featured-professor', plugin_dir_path(__FILE__) . '/languages');

		register_block_type('ourplugin/featured-professor', array(
			'render_callback' => [$this, 'renderCallback'],
			'editor_script' => 'featuredProfessorScript',
			'editor_style' => 'featuredProfessorStyle'
		));
	}

	function onRestApiInit()
	{
		register_rest_route(
			'featuredprofessor/v1',
			'/getHTML',
			array(
				'methods' => WP_REST_SERVER::READABLE,
				'callback' => [$this, 'getHtmlApi'],
				'permission_callback' => '__return_true'
			),
		);
	}

	function addRelatedPosts($content)
	{
		if (is_singular('professor') && in_the_loop() && is_main_query()) {
			return $content .= $this->relatedPostsHtml(get_the_ID());
		}
		return $content;
	}

	function getHtmlApi($request)
	{
		return $this->getHTML($request['professorId']);
	}

	function getHTML($professorId)
	{
		$professor = new WP_Query([
			'p' => $professorId,
			'post_type' => 'professor'
		]);

		while ($professor->have_posts()) {
			$professor->the_post();
			$relatedPrograms = get_field('related-programs');
			ob_start(); ?>

			<div class="professor-callout">
				<div class="professor-callout__photo" style="background-image: url('<?= the_post_thumbnail_url('professorPortrait'); ?>')"> </div>
				<div class="professor-callout__text">
					<h3><?php the_title(); ?></h3>
					<p><?= wp_trim_words(get_the_content(), 30); ?></p>
					<?php if ($relatedPrograms) : ?>
						<div>
							<?php foreach ($relatedPrograms as $program) : ?>
								<p>
									<a href="<?= get_the_permalink($program); ?>">
										<?= get_the_title($program); ?>
									</a>
								</p>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<p>
						<a href="<?= get_the_permalink(); ?>">
							Learn more about <?= the_title(); ?> &raquo;
						</a>
					</p>
				</div>
			</div>
		<?php
			wp_reset_postdata();
			return ob_get_clean();
		}

		return '';
	}

	function renderCallback(array  $attributes)
	{
		if (empty($attributes['profId'])) {
			return;
		}

		wp_enqueue_style('featuredProfessorStyle');

		return $this->getHTML($attributes['profId']);
	}

	function relatedPostsHtml($id)
	{
		// 1) Get the raw meta value from wp_postmeta

		$args = array(
			'post_type'      => 'post',            // or whatever post type you’re targeting
			'posts_per_page' => -1,                // get all matches
			'meta_key'       => 'featuredProfessor',
			'meta_value'     => $id,
		);

		// Run the query
		$query = new WP_Query( $args );
		$posts = $query->posts; // Array of WP_Post objects

		ob_start();

		if ( ! empty( $posts ) ) { ?>
			<h4 class="headline headline--medium">Related posts:</h4>
			<ul>
			<?php foreach( $posts as $p ) { ?>
				<li><a href="<?= get_the_permalink($p); ?>"><?= get_the_title($p); ?></a></li>
			<?php } ?>
			</ul>
		<?php }
		wp_reset_postdata();
		return ob_get_clean();
	}
}
