<?php

class Users_widget
{

    public function dropdown()
    {
        return array(
            'name' => 'Criar usuário',
            'url' => base_url('apps/users/create')
        );
    }
}