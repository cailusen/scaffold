<?php
include "../../vendor/autoload.php";

if (!\Scaffold\validate\post::validate(['name' => '998', 'nick' => '12'], 'create')) {
	var_dump(\Scaffold\validate\post::getFirstError());
}