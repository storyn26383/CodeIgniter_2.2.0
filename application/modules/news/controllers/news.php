<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MX_Controller {
	public function __construct() {
		parent::__construct();
		$this->lang->load('news');
	}

	// public function index_get() {
	// 	$assign_data = array(
	// 		'title'		=> lang('news.title'),
	// 		'body'		=> lang('news.body'),
	// 	);

	// 	$this->parser->parse('hello_world.tpl', $assign_data);
	// }

	public function index_get() {
		$this->load->helper('url');

		$data = array(1 => array(
			'id' => 1,
			'title' => 'Ebola screening starts at New York\'s JFK airport',
			'author' => 'Sebastien Malo',
			'date' => 'October 11, 2014',
			'url' => site_url(uri_string() . '/1'),
		), 2 => array(
			'id' => 2,
			'title' => 'Seven NJ high school football players charged in connection with hazing',
			'author' => 'Associated Press',
			'date' => 'October 11, 2014',
			'url' => site_url(uri_string() . '/2'),
		), 3 => array(
			'id' => 3,
			'title' => 'Georgia could sue dealer for making Todd Gurley break rules',
			'author' => 'Keith Whitney',
			'date' => 'Sat Oct 11, 2014 2:06pm',
			'url' => site_url(uri_string() . '/3'),
		));

		$id = $this->get('id');

		$this->response->tpl = 'news.tpl';
		$this->response->data = array(
			's_show'	=> !!$id,
			'data'		=> !$id ? $data : @$data[$id],
		);
	}
}