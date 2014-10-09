<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MX_Controller {
	public function index() {
		$this->load->helper('language');
		$this->lang->load('news');

		$assign_data = array(
			'title'	=> lang('news.title'),
			'body'	=> lang('news.body'),
		);

		$this->parser->parse("hello_world.tpl", $assign_data);
	}
}