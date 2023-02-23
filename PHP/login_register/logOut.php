<?php
session_start();
session_destroy();
?>
<script>
    localStorage.setItem('isResult', 'false');
    location.replace('../main.php');
</script>
