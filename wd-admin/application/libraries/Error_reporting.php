<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Error_reporting
{
    public $errors = null;

    public function set_error($message)
    {
        $this->errors[] = ['message' => $message];
    }

    public function get_errors($prefix = '<div class="alert alert-danger">', $suffix = '</div>')
    {
        $messages = '';
        if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
                if (!empty($error['message'])) {
                    $messages = $prefix . $error['message'] . $suffix;
                }
            }
        }
        return $messages;
    }

    public function has_error()
    {
        return (!empty($this->errors));
    }
}