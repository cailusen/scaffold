<?php

namespace Scaffold\Package;

use Valitron\Validator;

abstract class Validate
{

	private static $validateHandler = [];

	private static $groupRules = [];

	private static $errorMsg = '';
    /**
     * @return array
     *
     * @example [
     * ['name', 'required', [], ' error message',[scenes]]
     * ['field', 'valition rule', [args], 'message', [scenes]]
     * ]
     */
    abstract static protected function rules();


    private function __construct()
	{
	}

	private static function groupRules($scene) {
    	$rules = static::rules();
    	$singleKey = md5(static::class . $scene);
    	if (!isset(self::$groupRules[$singleKey])) {
			foreach ($rules as $rule) {
				$scenes = array_pop($rule);
				if (in_array($scene, $scenes)) {
					self::$groupRules[$singleKey][] = $rule;
				}
			}
		}
    	return self::$groupRules[$singleKey];
	}

	private static function getValitorHandler(array $data) {
		$singleKey = md5(json_encode($data, JSON_UNESCAPED_UNICODE) . static::class);
		if (!isset(self::$validateHandler[$singleKey])) {
			self::$validateHandler[$singleKey] = new Validator($data);
		}

		return self::$validateHandler[$singleKey];
	}

	public static function validate(array $data, $scene)
	{
		$rules = self::groupRules($scene);
		$v = self::getValitorHandler($data);
		foreach ($rules as $rule) {
			$field= $rule[0];
			$ruleFun = $rule[1];
			$args = $rule[2] ? : [];
			$errorMsg = $rule[3];
			if ($ruleFun == "callBack" && is_callable($args)) {
				call_user_func_array($v, [$args]);
			} else {
				array_unshift($args, $field);
				call_user_func_array($v, [[
					$ruleFun => $args
				]]);
			}
			if (!$v->validate()) {
				self::$errorMsg = $errorMsg;
				return false;
			}
		}
		return true;
	}

	public static function getFirstError()
	{
		return self::$errorMsg;
	}


}