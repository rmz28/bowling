<form method="post">
<label for="roll_1">Roll 1</label>
<input type="text" name="roll_1">

<label for="roll_2">Roll 2</label>
<input type="text" name="roll_2">

<label for="roll_3">Roll 3</label>
<input type="text" name="roll_3">

<input type="submit" value="Add frame">
</form>

<br>
<br>

<a href="flush.php">Flush session</a>

<?php

var_dump($_SESSION);