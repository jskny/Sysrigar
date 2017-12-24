<?php
// ウクライナ等からのログインページへの攻撃が続いているので、先人様を参考にしてセキュリティ対策を施す。
// このファイルだけ、wordpress フォルダの直下に置く必要があります。
// 2017/12/24
// https://gray-code.com/blog/wordpress/change-the-url-of-admin-page/
define('SYSRIGAR_LOGIN_KEY_TEXT', "welcome2admin");
define('SYSRIGAR_LOGIN_KEY', password_hash(SYSRIGAR_LOGIN_KEY_TEXT, PASSWORD_BCRYPT, array('cost'=>12)));
require_once './wp-login.php';
?>
