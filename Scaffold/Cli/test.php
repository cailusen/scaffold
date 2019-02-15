<?php
include "../../vendor/autoload.php";

if (!\Scaffold\Validate\post::validate(['name' => '998'], 'create')) {
	var_dump(\Scaffold\Validate\post::getFirstError());
}