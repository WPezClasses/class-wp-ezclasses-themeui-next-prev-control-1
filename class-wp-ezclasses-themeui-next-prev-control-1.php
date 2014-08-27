<?
/** 
 * For rendering (blog post) simple next / previous contols
 *
 * This is NOT paging in a here's a list of pages sense. This is simply Next and Previous.
 *
 * PHP version 5.3
 *
 * LICENSE: TODO
 *
 * @package WP ezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.0
 * @license TODO
 */
 
 /*
 * == Change Log == 
 *
 * --- 
*/

if ( !defined('ABSPATH') ) {
	header('HTTP/1.0 403 Forbidden');
    die();
}
?>

<?php
if (! class_exists('Class_WP_ezClasses_ThemeUI_Next_Prev_Control_1') ) {
  class Class_WP_ezClasses_ThemeUI_Next_Prev_Control_1 extends Class_WP_ezClasses_Master_Singleton {
  
  	protected $_arr_init;
		
		public function __construct() {
			parent::__construct();
		}
		
		public function ezc_init($arr_args = NULL){
		
		  $arr_init_defaults = $this->init_defaults();
		  $this->_arr_init = WP_ezMethods::ez_array_merge(array($arr_init_defaults, $arr_args));
		}
		
		protected function init_defaults(){
		
		  $arr_defaults = array(
		    'echo' => false,
		    'filters' => false,
			'validation' => false,
			); 
		  return $arr_defaults;
		}
		
		/**
		 * Simple "paging": Older / Newer pages of posts
		 */
		public function next_prev( $arr_args = '' ) {
		
		  // are we going to echo or return the str_return
		  $bool_echo = $this->_arr_init['echo'];
		  if ( isset($arr_args['echo']) && is_bool($arr_args['echo']) ){
		    $bool_echo = $arr_args['echo'];
		  }
		  
		  // TODO validation
		  
		  if ( WP_ezMethods::array_pass($arr_args) ){
		    $arr_args = array_merge($this->next_prev_defaults(), $arr_args);
		  } else {
		    $arr_args = $this->next_prev_defaults();	
		  }
		  
		  global $wp_query;
		
			$str_to_return = '';

			if ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) { // navigation links for home, archive, and search pages 
			
				$str_to_return .= '<ul class="' . sanitize_text_field($arr_args['ul_class']) . '">';
				if ( get_next_posts_link() ) { 
					$str_to_return .= '<li class="' . sanitize_text_field($arr_args['li_class_next']) .'">';
					$str_to_return .= get_next_posts_link( '<span class="'. sanitize_text_field($arr_args['older_class']) .'"></span>' . sanitize_text_field($arr_args['older']) );
					$str_to_return .= '</li>';
				} 

				if ( get_previous_posts_link() ) {
					$str_to_return .= '<li class="' . sanitize_text_field($arr_args['li_class_prev']) . '">';
					$str_to_return .= get_previous_posts_link( sanitize_text_field($arr_args['newer']) . '<span class="'. sanitize_text_field($arr_args['newer_class']) .'"></span>' );
					$str_to_return .= '</li>';
				} 
				$str_to_return .= '</ul>';
			} 
			
			if ( $bool_echo ) {
				echo $str_to_return;
			}
			return array('status' => true, 'msg' => 'success', 'source' => get_class(), 'str_to_return' => $str_to_return);
			
		}

		
		/**
		 * Next / Previous posts
		 */
		public function next_prev_single( $arr_args = NULL ) {
			
		  // are we going to echo or return the str_return
		  $bool_echo = $this->_arr_init['echo'];
		  if ( isset($arr_args['echo']) && is_bool($arr_args['echo']) ){
		    $bool_echo = $arr_args['echo'];
		  }
			
		  // TODO validation
		  
		  if ( WP_ezMethods::array_pass($arr_args) ){
		    $arr_args = array_merge($this->next_prev_defaults(), $arr_args);
		  } else {
		    $arr_args = $this->next_prev_defaults();	
		  }
			
			global $wp_query;
		
			$str_to_return = '';
			if ( is_single() ) { // navigation links for single posts 
			
				$str_to_return .= '<ul class="' .  sanitize_text_field($arr_args['ul_class']) . '">';
				
				$str_get_previous_post = get_previous_post();
				if ( ! empty( $str_get_previous_post )){
					$str_to_return .= '<li class="' . sanitize_text_field($arr_args['li_class_prev']) . '"><a href="' . get_permalink( $str_get_previous_post->ID ) . '">' . sanitize_text_field($str_get_previous_post->post_title) . '<span class="' . sanitize_text_field($arr_args['previous_class']) . '"></span>' . sanitize_text_field($arr_args['previous']) . '</a></li>';
				}
				
				$str_get_next_post = get_next_post();
				if ( ! empty( $str_get_next_post )){
					$str_to_return .= '<li class="' . sanitize_text_field($arr_args['li_class_prev']) . '"><a href="' . get_permalink( $str_get_next_post->ID ) . '">'. sanitize_text_field($arr_args['next']) .  '<span class="' . sanitize_text_field($arr_args['next_class']) . '"></span>' . sanitize_text_field($str_get_next_post->post_title) .'</a></li>';
				}
				
				$str_to_return .= '</ul>';
			} 
			
			if ( $bool_echo ) {
				echo $str_to_return;
			}
			return array('status' => true, 'msg' => 'success', 'source' => get_class(), 'str_to_return' => $str_to_return);
		} 
		
		/**
		 *
		 */
		public function next_prev_defaults(){
		
			$arr_defaults = array(
			  'ul_class' 			=> 'ezbs-pager',
			  'li_class_next'		=> 'ezbs-next',
			  'li_class_prev'		=> 'ezbs-previous',
			  'next'				=> 'Next ',
			  'next_class'			=> 'icon-chevron-right',  
			  'newer'				=> ' Newer',
			  'newer_class'			=> 'meta-nav ' . 'icon-chevron-right',
			  'previous'			=> 'Prev',
			  'previous_class'		=> 'icon-chevron-left',
			  'older'				=> 'Older ',
			  'older_class'			=> 'meta-nav ' . 'icon-chevron-left',
			);
			
			/*
			 * Allow filters?
			 */			
			if ( $this->_arr_init['filters'] ){
				$arr_defaults_via_filter = apply_filters('filter_ezc_themeui_next_prev_control_1_defaults', $arr_defaults);
				$arr_defaults = WP_ezMethods::_ez_array_merge($arr_defaults, $arr_defaults_via_filter);
			}
			return $arr_defaults;
		}
		
	} // close class
} // close if class_exists()
?>