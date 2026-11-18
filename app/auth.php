<?php
// セッションが起動していなければ起動
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
  
/**
 * 利用者：ログイン済みなら利用者番号、未ログインならfalse
 */

function getMemberId()
{   
    return $_SESSION['member_id'] ?? false;
}

/**
 * 管理者：ログイン済みなら管理者番号、未ログインならfalse
 */
function getAdminId()
{
    return $_SESSION['admin_id'] ?? false;
}

?>