<?php
	$pmf_self_path=$_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']);
	$pmf_self_url=dirname($_SERVER['PHP_SELF']);

	$pmf_root_path="{$_SERVER['DOCUMENT_ROOT']}/data/content/images";
//	$pmf_root_path="{$_SERVER['DOCUMENT_ROOT']}/data";
	if (isset($_SESSION['pmf_root_path']) && $_SESSION['pmf_root_path']!='') $pmf_root_path=$_SESSION['pmf_root_path'];

	$pmf_root_url="/data";
	if (isset($_SESSION['pmf_root_url']) && $_SESSION['pmf_root_url']!='') $pmf_root_url=$_SESSION['pmf_root_url'];

	$pmf_allowed_extension='jpg|png|gif';
	if (isset($_SESSION['pmf_allowed_extension']) && $_SESSION['pmf_allowed_extension']!='') $pmf_allowed_extension=$_SESSION['pmf_allowed_extension'];

	$pmf_thumb_size=185;
	if (isset($_SESSION['pmf_thumb_size']) && $_SESSION['pmf_thumb_size']!='') $pmf_thumb_size=$_SESSION['pmf_thumb_size'];

?>