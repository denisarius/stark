<div class="left_column_container">
	<?php

	$text = get_content();
	if ($text === false)
		show_content_404();
	else
	{
		set_page_title($text['title']);
		echo <<<stop
<div class="content_content_text_frame">
	<h1>{$text['title']}</h1>
	{$text['content']}
</div>
stop;
	}
	?>
</div>
<br/>
