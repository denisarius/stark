<?php
// -----------------------------------------------------------------------------
function is_active_session()
{
	if (isset($_COOKIE['stored_user_id']) && $_COOKIE['stored_user_id']!='' && isset($_COOKIE['stored_user_hash']) && $_COOKIE['stored_user_hash']!='')
	{
		$_SESSION['user_id']=$_COOKIE['stored_user_id'];
		$_SESSION['user_hash']=$_COOKIE['stored_user_hash'];
	}
	if (!isset($_SESSION['user_id']) || $_SESSION['user_id']=='' || !isset($_SESSION['user_hash']) || $_SESSION['user_hash']=='') return false;
	$user=get_data_array('id, password', 'users', "id='{$_SESSION['user_id']}'");
	if ($user===false)  return false;
	if (md5($user['id'].$user['password'])!=$_SESSION['user_hash']) return false;
	return true;
}
// -----------------------------------------------------------------------------
?>