<?php
session_start();
session_destroy();
?>
<html>
    <p>Vuoi disconnetterti?</p>
    <button onclick="window.location.href='login.php'" role="button">OK</button>
    <button onclick="window.location.href='index.php'" role="button">Cancel</button>
</html>