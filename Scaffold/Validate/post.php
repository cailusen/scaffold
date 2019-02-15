<?php
namespace Scaffold\Validate;

use Scaffold\Package\Validate;

class post extends Validate
{

    public static function rules()
    {
        return [
            ['name', 'lengthBetween', [2,10], 'chang du 2 - 10', ['create']],
			['nick', 'required', [], 'bi xu cun zai', ['create']],
			['nick', self::RULE_FUNC_CALLBACK, function ($field, $value, array $params, array $fields) {
        	return false;
			}, 'shu zu chang du bu gou', ['create']],
        ];
    }

}