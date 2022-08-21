<?php
function value_sanitize($val) {
	
	if(isset($val)) {

		$val = htmlspecialchars($val);
		$val = stripcslashes($val);
		$val = addslashes($val);

		return $val;
	} else {
		return false;
	}
}
?>