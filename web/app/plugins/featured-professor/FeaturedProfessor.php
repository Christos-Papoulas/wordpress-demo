<?php

class FeaturedProfessor
{
	function __construct()
	{
		add_action('init', [$this, 'onInit']);
		add_action('rest_api_init', [$this, 'onRestApiInit']);
	}

	function onInit()
	{
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

		while($professor->have_posts()) {
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
}
