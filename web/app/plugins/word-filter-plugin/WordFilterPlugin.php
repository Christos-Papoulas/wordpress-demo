<?php

class WordFilterPlugin {
	public function __construct() {
		add_action('admin_menu', [$this, 'adminMenuHtml']);

		add_action('admin_init', [$this, 'settings']);

		add_filter('the_content', [$this, 'filterContent']);
	}

	public function adminMenuHtml() {
		$mainPageHook = add_menu_page(
			'Words to Filter',
			__('Word Filter'),
			'manage_options',
			'word-filter',
			[$this, 'wordFilterHtml'],
			'dashicons-smiley',
			100
		);

		// Override default first menu element
		add_submenu_page(
			'word-filter',
			__('Word Filter'),
			__('Word List'),
			'manage_options',
			'word-filter',
			[$this, 'wordFilterHtml']
		);

		add_submenu_page(
			'word-filter',
			__('Word Filter Options'),
			__('Options'),
			'manage_options',
			'word-filter-options',
			[$this, 'wordFilterOptionsHtml']
		);

		add_action("load-{$mainPageHook}", [$this, 'mainPageAssets']);
	}

	public function handleWordFilterForm() {
		if(!wp_verify_nonce($_POST['words-nonce'], 'saveFilterWords') || !current_user_can('manage_options')) {
			abort(401);
		}
		update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter']));

		return true;
	}

	public function filterContent($content) {
		if (empty(get_option('plugin_words_to_filter'))) {
			return $content;
		}
		$wordsToFilter = array_map('trim', explode(',', get_option('plugin_words_to_filter')));

		return str_ireplace($wordsToFilter, esc_html(get_option('replacementText')), $content);
	}

	public function settings() {
		add_settings_section(
			'replacement-text-section', null, null, 'word-filter-options'
		);

		register_setting(
			'replacementFields', 'replacementText'
		);

		add_settings_field(
			'replacement-text', 'Replacement Text', [$this, 'replacementTextFieldHtml'], 'word-filter-options', 'replacement-text-section'
		);
	}

	public function replacementTextFieldHtml() {
		echo '<input type="text" name="replacementText" value="' . esc_attr(get_option('replacementText')) . '" placeholder="The text that replace the filtered words">';
		echo '<p class="description">Let empty to remove the filtered words</p>';
	}

	public function mainPageAssets() {
		wp_enqueue_style(
			'word-filter-style',
			plugins_url('styles.css', __FILE__)
		);
	}

	public function wordFilterHtml() {
		require __DIR__ . '/views/page-admin-word-filter.php';
	}

	public function wordFilterOptionsHtml() {
		require __DIR__ . '/views/page-admin-word-filter-options.php';
	}
}
