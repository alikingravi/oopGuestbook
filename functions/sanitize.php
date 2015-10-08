<!-- This will only have the escape function for security -->
<?php
function escape($string){
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}