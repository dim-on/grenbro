<?php
/**
 * Class for the building ui slider elements .
 *
 * @package    Cherry_Framework
 * @subpackage Class
 * @author     Cherry Team <support@cherryframework.com>
 * @copyright  Copyright (c) 2012 - 2015, Cherry Team
 * @link       http://www.cherryframework.com/
 * @license    http://www.gnu.org/licenses/gpl-3.0.en.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'UI_Slider' ) ) {

	/**
	 * Class for the building UI_Slider elements.
	 */
	class UI_Slider extends UI_Element implements I_UI {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		private $defaults_settings = array(
			'id'         => 'cherry-ui-slider-id',
			'name'       => 'cherry-ui-slider-name',
			'max_value'  => 100,
			'min_value'  => 0,
			'value'      => 50,
			'step_value' => 1,
			'label'      => '',
			'class'      => '',
			'master'     => '',
			'lock'       => false,
		);

		/**
		 * Instance of this Cherry5_Lock_Element class.
		 *
		 * @since 1.0.0
		 * @var object
		 * @access private
		 */
		private $lock_element = null;

		/**
		 * Constructor method for the UI_Slider class.
		 *
		 * @since 1.0.0
		 */
		function __construct( $args = array() ) {
			$this->defaults_settings['id'] = 'cherry-ui-slider-' . uniqid();
			$this->settings = wp_parse_args( $args, $this->defaults_settings );
			$this->lock_element = new Cherry5_Lock_Element( $this->settings );

			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		}

		/**
		 * Render html UI_Slider.
		 *
		 * @since 1.0.0
		 */
		public function render() {
			$html = '';
			$class = implode( ' ',
				array(
					$this->settings['class'],
					$this->settings['master'],
					$this->lock_element->get_class(),
				)
			);

			$html .= '<div class="cherry-ui-container ' . esc_attr( $class ) . '">';

			$ui_stepper = new UI_Stepper(
				array(
					'id'         => $this->settings['id'] . '-stepper',
					'name'       => $this->settings['name'],
					'max_value'  => $this->settings['max_value'],
					'min_value'  => $this->settings['min_value'],
					'value'      => $this->settings['value'],
					'step_value' => $this->settings['step_value'],
				)
			);
			$ui_stepper_html = $ui_stepper->render();

				if ( '' !== $this->settings['label'] ) {
					$html .= '<label class="cherry-label" for="' . esc_attr( $this->settings['id'] ) . '">' . esc_html( $this->settings['label'] ) . '</label> ';
				}
				$html .= '<div class="cherry-slider-wrap">';
					$html .= '<div class="cherry-slider-holder">';
						$html .= '<input type="range" class="cherry-slider-unit" step="' . esc_attr( $this->settings['step_value'] ) . '" min="' . esc_attr( $this->settings['min_value'] ) . '" max="' . esc_attr( $this->settings['max_value'] ) . '" value="' . esc_attr( $this->settings['value'] ) . '"' . $this->lock_element->get_disabled_attr() . '>';
					$html .= '</div>';
					$html .= '<div class="cherry-slider-input">';
						$html .= $ui_stepper_html;
					$html .= '</div>';
				$html .= '</div>';
				$html .= $this->lock_element->get_html();
			$html .= '</div>';

			return $html;
		}

		/**
		 * Enqueue javascript and stylesheet UI_Slider.
		 *
		 * @since 1.0.0
		 */
		public static function enqueue_assets() {
			wp_enqueue_script(
				'ui-slider',
				esc_url( Cherry_Core::base_url( 'inc/ui-elements/ui-slider/assets/min/ui-slider.min.js', Cherry_UI_Elements::$module_path ) ),
				array( 'jquery' ),
				Cherry_UI_Elements::$core_version,
				true
			);

			wp_enqueue_style(
				'ui-slider',
				esc_url( Cherry_Core::base_url( 'inc/ui-elements/ui-slider/assets/min/ui-slider.min.css', Cherry_UI_Elements::$module_path ) ),
				array(),
				Cherry_UI_Elements::$core_version,
				'all'
			);
		}
	}
}
