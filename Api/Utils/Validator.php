<?php

namespace api\utils;

class Validator
{
    public static function required($data, $fields)
    {
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                return "$field is required";
            }
        }
        return null;
    }

    public static function maxLength($field, $value, $maxLength)
    {
        if (strlen($value) > $maxLength) {
            return "$field exceeds maximum length of $maxLength characters";
        }
        return null;
    }

    public static function boolean($field, $value)
    {
        if (!is_bool($value)) {
            return "$field must be a boolean value";
        }
        return null;
    }

    public static function integer($field, $value)
    {
        if (!is_int($value)) {
            return "$field must be an integer";
        }
        return null;
    }
}