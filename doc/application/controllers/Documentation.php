<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Documentation extends MY_Controller {

    public function index() {
        $this->load->model('crud_model');
        $slug = $this->uri->segment(1);
        $slug_topic = $this->uri->segment(2);
        $topic = $this->crud_model->read('doc_topics', null, array('slug' => $slug))->row_array();
        $subtopics = $this->crud_model->read(
                        'doc_subtopics', 'doc_subtopics.*', array('doc_topics.slug' => $slug), 'doc_subtopics.id', array('doc_topics' => 'doc_topics.id=doc_subtopics.topic'))->result_array();
        if (empty($slug_topic)) {
            $current_topic = $subtopics[0];
        } else {
            $search = search($subtopics, 'slug', $slug_topic);
            if (!$search) {
                redirect($slug);
            } else {
                $current_topic = array_shift($search);
            }
        }
        add_css(array(
            'plugins/codesnippet/css/monokai_sublime.css'
        ));
        add_js(array(
            'plugins/codesnippet/js/highlight.pack.js',
            'view/documentation/js/scripts.js'
        ));
        $vars = array(
            'title' => $topic['name'],
            'topic' => $topic,
            'subtopics' => $subtopics,
            'current_topic' => $current_topic
        );
        $this->load->template('documentation/index', $vars);
    }

}
