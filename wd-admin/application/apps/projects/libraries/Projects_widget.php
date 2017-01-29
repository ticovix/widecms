<?php

class Projects_widget
{

    public function dropdown()
    {
        $CI = &get_instance();
        $user = $CI->user_data;
        $dev_mode = $user['dev_mode'];
        $dropdown = array();
        if (!$dev_mode) {
            $CI->load->app('projects')->model('projects_model');
            $projects = $CI->projects_model->search($dev_mode, null);
            if ($projects) {
                foreach ($projects as $project) {
                    $dropdown[] = array(
                        'name' => $project['name'],
                        'url' => base_url('apps/projects/project/' . $project['directory'])
                    );
                }
            }
        }

        return $dropdown;
    }
}