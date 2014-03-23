<?php
	if (isset($_REQUEST['_SESSION'])) die('Global error.');
	session_start();
	require_once "{$_SERVER['DOCUMENT_ROOT']}/_config.php";
	require_once "$_base_site_proc_path/variables.php";

	importVars('section', false);
	if(!isset($section) || $section=='') exit;

	require_once "$_base_site_proc_path/cms.php";
	require_once "$_base_site_proc_path/phpMailer/class.phpmailer.php";
	require_once "$_base_site_proc_path/db.php";
	require_once "$_base_site_proc_path/user.php";
	require_once "$_base_site_proc_path/main.php";
	require_once "_strings.php";
    require_once "$pmPath/pmConfig.php";
    require_once "$pmPath/pmAPI.php";
	require_once pmIncludePath('design.php');

	header("Content-Type: text/html; charset={$html_charset}");

	$link=connect_db();
	$language=$_languages[0];
	switch ($section)
	{
		// сохранение рейтинга и генерация нового блока рейтинга
		case 'reciepRatingSave':
			pmImportVars('reciep_id|rating', true);
			if (!isset($reciep_id) || $reciep_id=='' || !isset($rating) || $rating=='') return;
			$ip=cms_get_objects_details(1, $reciep_id, 'rating_ip');
			if ($ip==$_SERVER['REMOTE_ADDR']) return;
			$exists=true;
			$old_count=cms_get_objects_details(1, $reciep_id, 'rating_count');
			$old_rating=get_data('value', $_cms_objects_details, "node='$reciep_id' and typeId='rating'");
			if ($old_rating===false) {$old_rating=0; $old_count=0; $exists=false; }
			$rating=((float)$old_rating*$old_count+$rating)/($old_count+1);
			cms_set_objects_details(1, $reciep_id, 'rating_count', $old_count+1);
			cms_set_objects_details(1, $reciep_id, 'rating', $rating);
			cms_set_objects_details(1, $reciep_id, 'rating_ip', $_SERVER['REMOTE_ADDR']);
			echo content_get_reciep_rating_stars_block($reciep_id, $old_count+1, $rating);
			break;
	}
	mysql_close($link);
// -----------------------------------------------------------------------------
?>