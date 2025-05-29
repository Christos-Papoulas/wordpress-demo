<div class="wrap">
	<h1>Word Filter</h1>

	<?php if (isset($_POST['justsubmitted']) && $_POST['justsubmitted'] === 'true') : ?>
		<?php if($this->handleWordFilterForm()): ?>
		<div class="notice notice-success">
			<p>Settings saved.</p>
		</div>
		<?php endif; ?>
	<?php endif; ?>

	<form method="post">
		<input type="hidden" name="justsubmitted" value="true">
		<?php wp_nonce_field('saveFilterWords', 'words-nonce'); ?>

		<label for="plugin_words_to_filter"><p>Enter a <strong>comma-separated</strong> list of words to filter</p></label>
		<div class="word-filter__flex-container">
			<textarea name="plugin_words_to_filter" id="word-filter-textarea" placeholder="bad, horrible, awful, terrible"><?= esc_textarea(get_option('plugin_words_to_filter')) ?></textarea>
		</div>

		<input type="submit" class="button button-primary" value="Save Changes">
	</form>
</div>
