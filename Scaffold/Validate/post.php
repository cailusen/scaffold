<?php
namespace Scaffold\Validate;

use Scaffold\Package\Validate;

class post extends Validate
{

    public static function rules()
    {
        return [
            ['name', 'lengthBetween', [2,10], 'chang du 2 - 10', ['create']],
			['nick', 'required', [], '{nick} error message', ['create']]
        ];
    }

}