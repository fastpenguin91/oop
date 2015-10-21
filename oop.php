<?php
/*
  Plugin Name: oop add menus and settings
  Plugin URI: http://startupinacar.com
  Description: add menus for plugin using Object Oriented Programming techniques
  Author: Me
  Version: 1.0
  Author URI: http://startupinacar.com
*/


Class Jc_General_Settings_Admin {

  public function __construct(){
    //don't think I need anything in here
    add_action( 'admin_menu', array( $this, 'jc_example_add_menu_page' ) );

    add_action( 'admin_init', array( $this, 'jc_settings_init' ) );

    add_action( 'the_post', array( $this, 'apply_changes_to_the_content' ) );

  }



  public function jc_example_add_menu_page(){

    add_menu_page( 'General Settings', 'toplvlmenu', 'edit_pages', 'jc-settings', array( $this, 'jc_add_menu_render_admin'), 'dashicons-admin-customizer' );

  }

  public function jc_add_menu_render_admin(){
    echo "Hello from inside the class!!!"; ?>

    <form method="post" action="options.php">
    <?php do_settings_sections( 'jc-settings' ); ?>
    <?php settings_fields( 'jc-settings' ); ?>
    <?php submit_button(); ?>
    <?php
  }

  public function jc_settings_init(){
    add_settings_section(
      'general_settings_section',
      'jc plugin settings',
      array( $this, 'jc_section_callback' ),
      'jc-settings'
    );

    add_settings_field(
      'filter_explicit_stuff',
      'Censor explicit content',
      array( $this, 'jc_filter_callback' ),
      'jc-settings',
      'general_settings_section',
      array(
        'filter explicit content? If yes, then check the box!'
       )
    );

    register_setting( 'jc-settings', 'jc-settings' );
  
  }

  public function jc_section_callback(){
    
    echo "jc_section_callback test";
  }

  public function jc_filter_callback($args){

    $options = get_option( 'jc-settings' );
    $html = '<input type="checkbox" id="filter_explicit_stuff" name="jc-settings[filter_explicit_stuff]" value="1" ' . checked( 1, isset( $options[ 'filter_explicit_stuff' ] ), false ) . '/>';
    $html .= '<label for="filter_explicit_stuff"> '  . $args[0] . '</label>';
    echo $html;

  }

  public function jc_filter_explicit_content( $content ) {

    $content = str_replace( 'badword', 'b*****d', $content );
    return $content;
    exit;

  }

  public function apply_changes_to_the_content(){
    $options = get_option( 'jc-settings' );
    if( isset( $options[ 'filter_explicit_stuff' ] ) ){
      add_filter( 'the_content', array( $this, 'jc_filter_explicit_content' ) );
    }
  }


}


$jc_settings_plugin = new Jc_General_Settings_Admin;
