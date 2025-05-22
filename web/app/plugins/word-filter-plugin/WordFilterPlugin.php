<?php

class WordFilterPlugin {
	public function __construct() {
		add_action('admin_menu', [$this, 'adminMenuHtml']);

		add_filter('the_content', [$this, 'filterContent']);
	}

	public function filterContent($content) {
		$filteredContent = str_replace('word', '***', $content);
		return $filteredContent;
	}

	public function adminMenuHtml() {
		add_menu_page(
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
	}

	public function wordFilterHtml() {
		require __DIR__ . '/views/page-admin-word-filter.php';
	}

	public function wordFilterOptionsHtml() {
		require __DIR__ . '/views/page-admin-word-filter-options.php';
	}
}
