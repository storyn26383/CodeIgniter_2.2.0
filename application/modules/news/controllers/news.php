<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MX_Controller {
	public function index() {
		$assign_data = array(
			'title'	=> 'Hello World',
			'body'	=> 'Hello World!!',
		);

		$this->parser->parse("hello_world.tpl", $assign_data);
	}
}