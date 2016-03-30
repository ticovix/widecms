<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Error_reporting {

    public $errors = array();

    public function setError($lang, $message) {
        $this->errors[] = ['lang' => $lang, 'message' => $message];
    }

    public function getErrors($prefix = '<div class="alert alert-danger">', $suffix = '</div>') {
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

}
