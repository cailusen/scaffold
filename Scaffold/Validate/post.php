<?php
namespace Scaffold\Validate;

use Scaffold\Package\Validate;

class post extends Validate
{

    public function rules()
    {
        return [
            ['name', 'required', [], '{fields} error message', 'label']
        ];
    }

}