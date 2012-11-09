<?php
  session_start();
  session_destroy();
  require_once 'common/functions.php';
  redirect("./");
?>