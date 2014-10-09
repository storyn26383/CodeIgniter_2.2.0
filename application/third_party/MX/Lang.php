<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter CI_Language class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Lang.php
 *
 * @copyright	Copyright (c) 2011 Wiredesignz
 * @version 	5.4
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Lang extends CI_Lang
{
	/*
	 * default language list form config file
	 */
	private $_language_list;
	/*
	 * default language field form $_POST or $_GET or $_SESSION
	 */
	private $_language_field;
	/*
	 * default language
	 */
	private $_language_key;
	/*
	 * default language folder
	 */
	private $_language_folder;
	/*
	 * default prefix on language array key
	 */
	private $_language_prefix;

	public function __construct()
	{
		parent::__construct();
	}

	public function load($langfile = array(), $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '', $_module = '') {
		$this->_set_language();
		$CI =& get_instance();

		$langfile = str_replace('.php', '', $langfile);
		// add prefix on language key
		$this->_language_prefix = $langfile;

		if ($add_suffix == TRUE) {
			$langfile = str_replace('_lang.', '', $langfile) . '_lang';
		}

		$langfile .= '.php';

		if (in_array($langfile, $this->is_loaded, TRUE)) {
			return;
		}

		$config =& get_config();

		if ($idiom == '') {
			$deft_lang = $this->_language_folder;
			$idiom = ($deft_lang == '') ? 'english' : $deft_lang;
		}

		$_module OR $_module = CI::$APP->router->fetch_module();
		list($path, $langfile) = Modules::find($langfile, $_module, 'language/' . $idiom . '/');

		// Determine where the language file is and load it
		if ($path === FALSE) {
			if ($alt_path != '' && file_exists($alt_path . 'language/' . $idiom . '/' . $langfile)) {
				include($alt_path . 'language/' . $idiom . '/' . $langfile);
			} else {
				$found = FALSE;

				foreach (get_instance()->load->get_package_paths(TRUE) as $package_path) {
					if (file_exists($package_path . 'language/' . $idiom . '/' . $langfile)) {
						include($package_path . 'language/' . $idiom . '/' . $langfile);
						$found = TRUE;
						break;
					}
				}

				if ($found !== TRUE) {
					show_error('Unable to load the requested language file: language/' . $idiom . '/' . $langfile);
				}
			}
		} else {
			$lang = Modules::load_file($langfile, $path, 'lang');
		}

		if (!isset($lang)) {
			log_message('error', 'Language file contains no data: language/' . $idiom . '/' . $langfile);

			return;
		}

		if ($return) return $lang;

		$this->is_loaded[] = $langfile;

		// add prefix value of array key
		$lang = $this->_set_prefix($lang);
		$this->language = array_merge($this->language, $lang);

		unset($lang);

		log_message('debug', 'Language file loaded: language/' . $idiom . '/' . $langfile);

		return $this->language;
	}

	private function _set_prefix($lang = array()) {
		$output = array();
		foreach ($lang as $key => $val) {
			// legacy support
			$output[$key] = $val;
			// add prefix key
			$key = $this->_language_prefix . "." . $key;
			$output[$key] = $val;
		}

		return $output;
	}

	private function _set_language() {
		$CI =& get_instance();
		$CI->load->library('session');
		$CI->config->load('language');

		$this->_language_list = $CI->config->item('language_list');
		$this->_language_field = $CI->config->item('language_field');
		$this->_language_key = $CI->config->item('language_key');

		if ($CI->input->get_post($this->_language_field) != FALSE) {
			$lang = strtolower($CI->input->get_post($this->_language_field));

			// check lang is exist in group
			if (array_key_exists($lang, $this->_language_list)) {
				$CI->session->set_userdata($this->_language_field, $lang);
			}
		}

		// set default browser language
		if (!$CI->session->userdata($this->_language_field)) {
			$CI->session->set_userdata($this->_language_field, $this->_default_lang());
		}

		$this->_language_folder = $this->_language_list[$CI->session->userdata($this->_language_field)];

		return $this;
	}

	private function _default_lang() {
		$browser_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtolower(strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ',')) : '';

		return (!empty($browser_lang) and array_key_exists($browser_lang, $this->_language_list)) ? strtolower($browser_lang) : $this->_language_key;
	}
}