<?php
$connect=mysqli_connect("localhost", "test", "t3st3r123", "test");
if (empty($_GET['q'])) $_GET['q']="";

echo mysqli_real_escape_string($connect, $_GET['q']);

?>
<form action="?" method="GET">
<textarea name="q" placeholder="mida escapida?"></textarea><br/>
<input type="submit"/>
</form>
