<?php

class WordCountAndTime {
    public function __construct() {
        add_action('admin_menu', [$this, 'adminPage']);


		add_action('admin_init', [$this, 'registerSettings']);
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

    function adminPage()
    {
        add_options_page(
            'Word Count Settings',
            'Word Count',
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

	function sanitizeLocation($input) {
		if (! in_array($input, ['0', '1'])) {
			add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be before content or after content');

			return get_option('wcp_location');
		}

		return $input;
	}

}
