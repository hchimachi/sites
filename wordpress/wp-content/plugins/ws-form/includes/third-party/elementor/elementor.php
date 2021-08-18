<?php

	add_action('plugins_loaded', function() {

		if(
			isset($_GET) && isset($_GET['elementor-preview'])	// phpcs:ignore
		) {

			// Disable debug
			add_filter('wsf_debug_enabled', function($debug_render) { return false; }, 10, 1);

			// Enqueue all WS Form scripts
			add_action('wp_enqueue_scripts', function() { do_action('wsf_enqueue_core'); });
		}
	});

	add_action('elementor/widgets/widgets_registered', function($widgets_manager) {

		// Unregister normal WordPress widget
		$widgets_manager->unregister_widget_type('wp-widget-ws_form_widget');

		// Register Elementor widget
		class Elementor_WS_Form_Widget extends \Elementor\Widget_Base {

			public $is_preview_mode;
			public $is_edit_mode;

			public function __construct($data = [], $args = null) {

				parent::__construct($data, $args);

				// Check if we are in preview mode
				$this->is_preview_mode = \Elementor\Plugin::$instance->preview->is_preview_mode() || is_preview();

				// Check if we are in edit mode
				$this->is_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();

				if($this->is_preview_mode || $this->is_edit_mode) {

					wp_enqueue_script('wsf-elementor', WS_FORM_PLUGIN_DIR_URL . 'includes/third-party/elementor/elementor.js', array(), WS_FORM_VERSION, true);
					wp_enqueue_style('wsf-elementor-css', WS_FORM_PLUGIN_DIR_URL . 'includes/third-party/elementor/elementor.css', array(), WS_FORM_VERSION, 'all');

				} else {

					if(!is_admin()) {

						wp_enqueue_script('wsf-elementor-public', WS_FORM_PLUGIN_DIR_URL . 'includes/third-party/elementor/elementor-public.js', array(), WS_FORM_VERSION, true);
					}
				}
			}

			public function get_script_depends() {

				if($this->is_preview_mode || $this->is_edit_mode) {

					return array('wsf-elementor');

				} else {

					if(!is_admin()) {

						return array('wsf-elementor-public');
					}
				}

				return array();
			}

			public function get_style_depends() {

				if($this->is_preview_mode || $this->is_edit_mode) {

					return array('wsf-elementor-css');

				} else {

					return array();
				}
			}

			public function get_name() {

				return 'ws-form';
			}

			public function get_title() {

				return WS_FORM_NAME_PRESENTABLE;
			}

			public function get_icon() {

				return 'eicon-form-horizontal';
			}

			public function get_categories() {

				return [ 'basic' ];
			}

			protected function _register_controls() {

				$this->start_controls_section(

					'form_section',
					[
						'label' => __( 'WS Form', 'ws-form' ),
						'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					]
				);

				$this->add_control(

					'form_id',
					[
						'label' => __( 'Form', 'ws-form' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => WS_Form_Common::get_forms_array(),
						'label_block' => true
					]
				);

				$this->end_controls_section();
			}

			protected function render() {

				$settings = $this->get_settings_for_display();

				// Get form ID
				$form_id = isset($settings['form_id']) ? intval($settings['form_id']) : 0;

				// Check if form has been selected
				if($form_id > 0) {

					if($this->is_preview_mode || $this->is_edit_mode) {

						echo sprintf('<div style="min-height:42px">%s</div>', do_shortcode(sprintf('[%s id="%u" visual_builder="true"]', WS_FORM_SHORTCODE, $form_id)));

					} else {

						echo sprintf('%s', do_shortcode(sprintf('[%s id="%u"]', WS_FORM_SHORTCODE, $form_id)));
					}

				} else {

					if($this->is_preview_mode || $this->is_edit_mode) {
?>
<div class="wsf-elementor-form-selector">
<?php
						// Output WS Form SVG Logo
						echo WS_Form_Config::get_logo_svg();	// phpcs:ignore

						// Get forms
						$forms = WS_Form_Common::get_forms_array();
?>
<select class="wsf-field">
<?php
						foreach($forms as $form_id => $form_label) {

?><option value="<?php esc_attr_e($form_id); ?>"><?php esc_html_e($form_label); ?></option>
<?php
						}
?>
</select>

</div>
<?php
					}
				}
			}
		}

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_WS_Form_Widget());
	});