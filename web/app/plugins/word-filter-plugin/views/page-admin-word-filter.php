<div class="wrap">
	<h1>Words to Filter</h1>
	<form method="post" action="options.php">
		<?php
		settings_fields('word-filter-options');
		do_settings_sections('word-filter-options');
		submit_button();
		?>
	</form>
</div>
