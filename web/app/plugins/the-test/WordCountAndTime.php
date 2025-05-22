<?php

class WordCountAndTime {
	public int $characterCount;
	public int $readTime;
	public int $wordCount;

	public function __construct() {
		add_action('admin_menu', [$this, 'adminPage']);

		add_action('admin_init', [$this, 'registerSettings']);

		add_filter('the_content', [$this, 'wrapContent']);

		add_action('init', [$this, 'languageSupport']);
    }

    function adminPage()
    {
        add_options_page(
            'Word Count Settings',
            esc_html__('Word Count', 'the-test-domain'),
            'manage_options',
            'word-count',
            [$this, 'adminMenuHtml']
        );
    }

    function adminMenuHtml()
    {
        // add error/update messages

        // check if the user have submitted the settings
        // WordPress will add the "settings-updated" $_GET parameter to the url
        if (isset($_GET['settings-updated'])) {
            // add settings saved message with the class of "updated"
            add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', 'wporg'), 'updated');
        }

        // show error/update messages
        settings_errors('wporg_messages');

		include_once __DIR__ . '/views/admin-settings.php';
    }

	function checkboxHTML($args) {
		require __DIR__ . '/views/admin-checkbox.php';
	}

	function displayLocationHTML() {
		include __DIR__ . '/views/admin-display-location.php';
	}

	function headlineHTML() {
		require __DIR__ . '/views/admin-headline.php';
	}

	function languageSupport() {
		load_plugin_textdomain('the-test-domain', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}

	function registerSettings() {
		add_settings_section('wcp_section', null, null, 'word-count');

		add_settings_field('wcp_location', __('Display Location'), [$this, 'displayLocationHTML'], 'word-count', 'wcp_section');
		register_setting('word-count-plugin', 'wcp_location', [
			'sanitize_callback' => [$this, 'sanitizeLocation'],
			'default' => '0'
		]);

		add_settings_field('wcp_headline', __('Headline'), [$this, 'headlineHTML'], 'word-count', 'wcp_section');
		register_setting('word-count-plugin', 'wcp_headline', [
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'Words Statistics'
		]);

		add_settings_field('wcp_word_count', __('Word Count'), [$this, 'checkboxHTML'], 'word-count', 'wcp_section', ['name' => 'wcp_word_count']);
		register_setting('word-count-plugin', 'wcp_word_count', [
			'sanitize_callback' => 'sanitize_text_field',
			'default' => true
		]);

		add_settings_field('wcp_character_count', __('Character Count'), [$this, 'checkboxHTML'], 'word-count', 'wcp_section', ['name' => 'wcp_character_count']);
		register_setting('word-count-plugin', 'wcp_character_count', [
			'sanitize_callback' => 'sanitize_text_field',
			'default' => true
		]);

		add_settings_field('wcp_readtime', __('Read time'), [$this, 'checkboxHTML'], 'word-count', 'wcp_section', ['name' => 'wcp_readtime']);
		register_setting('word-count-plugin', 'wcp_readtime', [
			'sanitize_callback' => 'sanitize_text_field',
			'default' => true
		]);
	}

	function sanitizeLocation($input) {
		if (! in_array($input, ['0', '1'])) {
			add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be before content or after content');

			return get_option('wcp_location');
		}

		return $input;
	}

	function wrapContent($content) {
		if (! is_main_query() || ! is_single() || get_post_type() !== 'post') {
			return $content;
		}

		$this->wordCount = get_option('wcp_word_count', 0);
		$this->characterCount = get_option('wcp_character_count', 0);
		$this->readTime = get_option('wcp_readtime', 0);

		if ($this->wordCount == 0 && $this->characterCount == 0 && $this->readTime == 0) {
			return $content;
		}

		$statistics = $this->calculateStatistics($content);

		return $this->placeStatistics($content, $statistics);
	}

	protected function calculateStatistics($content) {
		$statistics = '<div><h4>' . esc_html(get_option('wcp_headline', 'Words Statistics')) . '</h4>';
		$words = str_word_count($content);

		if ($this->wordCount == '1') {
			$statistics .= '<span class="word-count">' . $words . ' words</span> <br>';
		}

		if ($this->characterCount == '1') {
			$statistics .= '<span class="character-count">' . strlen($content) . ' characters</span> <br>';
		}

		if ($this->readTime == '1') {
			$statistics .= '<span class="read-time">' . round($words / 255) . ' minutes</span> <br>';
		}

		$statistics .= '</div>';

		return $statistics;
	}

	protected function placeStatistics($content, $statistics) {
		if (get_option('wcp_location') === '0') {
			return $statistics . '<hr>' . $content;
		}

		return $content . '<hr>' . $statistics;
	}
}
