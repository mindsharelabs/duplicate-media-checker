<?php

if(!class_exists('Duplicate_Media_Checker_Config')) {
	class Duplicate_Media_Checker_Config extends Duplicate_Media_Checker_Options {

		public $setup, $sections, $settings, $subpages;

		public function __construct() {
			$this->do_setup();
			$this->create_settings();

			add_action('enqueue_scripts_'.DMC_PLUGIN_SLUG, array($this, 'enqueue_scripts'));

			add_action('init', array($this, 'initialize'));
		}

		public function enqueue_scripts() {

		}

		private function do_setup() {
			$this->setup = array(
				 'project_name' => DMC_PLUGIN_NAME,
				 'project_slug' => DMC_PLUGIN_SLUG,
				 'menu'         => 'settings',
				 'page_title'   => sprintf(__('%s Settings', 'sb'), DMC_PLUGIN_NAME),
				 'menu_title'   => DMC_PLUGIN_NAME,
				 'capability'   => 'manage_options',
				 'option_group' => 'DMC_options',
				 'slug'         => DMC_PLUGIN_SLUG.'-settings',
			);
		}

		private function create_settings() {

			$this->settings['text_field_one'] = array(
					'title'   => __('Text field one'),
					'desc'    => __('Text field description.'),
					'std'     => 'Text field default',
					'type'    => 'text',
			);

		}

		public function initialize() {
			parent::__construct($this->setup, $this->settings);
		}

	}
}
if(class_exists('Duplicate_Media_Checker_Config')) {
	$dmc_options = new Duplicate_Media_Checker_Config();
}
