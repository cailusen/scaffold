<?php

namespace Scaffold\Package;


abstract class Validate
{


    /**
     * @return array
     *
     * @example [
     * ['name', 'required', [], '{fields} error message', 'label', [scenes]]
     * ['field', 'valition rule', [args], 'message', 'Lable', [scenes]]
     * ]
     */
    abstract protected function rules();



}