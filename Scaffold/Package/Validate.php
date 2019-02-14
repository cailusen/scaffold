<?php

namespace Scaffold\Package;


abstract class Validate
{


    /**
     * @return array
     *
     * @example [
     * ['name', 'required', [], '{fields} error message', 'label']
     * ['field', 'valition rule', [args], 'message', 'Lable']
     * ]
     */
    abstract protected function rules();



}