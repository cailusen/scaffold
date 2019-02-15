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
			['nick', self::RULE_FUNC_CALLBACK, [self::class, "checkNick"], 'shu zu chang du bu gou', ['create']],
        ];
    }

    public static function checkNick($field, $value, array $params, array $fields)
    {
        var_dump($field, $value,$params, $fields);
        return false;

    }

}