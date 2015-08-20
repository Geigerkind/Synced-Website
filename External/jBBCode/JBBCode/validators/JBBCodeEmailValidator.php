<?php

class JBBCodeEmailValidator implements \JBBCode\InputValidator {

    public function validate($input)
    {
        $valid = filter_var($input, FILTER_VALIDATE_EMAIL);
        return !!$valid;
    }
}