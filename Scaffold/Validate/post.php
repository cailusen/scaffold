<?php
namespace Scaffold\Validate;

use Scaffold\Package\Validate;

class post extends Validate
{

    public static function rules()
    {
        return [
            ['name', 'required', [], '{fields} error message', ['create']]
        ];
    }

}