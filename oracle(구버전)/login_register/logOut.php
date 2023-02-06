<?php
session_start();
session_destroy();
?>
<script>
    alert("로그아웃 되었습니다.");
    localStorage.setItem('isResult', 'false');
    localStorage.setItem('loggin', 'false');
    location.replace('../main.php');
</script>
