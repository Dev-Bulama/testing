<?php
/**
 * Elementor widget for the Gaeinity Community blocks.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! class_exists( 'Gaeinity_Community_Elementor_Widget' ) ) :

class Gaeinity_Community_Elementor_Widget extends Widget_Base {

    /**
     * Plugin instance reference.
     *
     * @var Gaeinity_Community_Plugin
     */
    protected $plugin;

    /**
     * Constructor.
     */
    public function __construct( $plugin, $data = array(), $args = null ) {
        $this->plugin = $plugin;
        parent::__construct( $data, $args );
    }

    public function get_name() {
        return 'gaenity-community-block';
    }

    public function get_title() {
        return __( 'Gaeinity Community Block', 'gaenity-community' );
    }

    public function get_icon() {
        return 'eicon-users';
    }

    public function get_categories() {
        return array( 'gaenity-community' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            array(
                'label' => __( 'Content', 'gaenity-community' ),
            )
        );

        $this->add_control(
            'block_type',
            array(
                'label'   => __( 'Block', 'gaenity-community' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'community_home',
                'options' => array(
                    'community_home' => __( 'Community Home', 'gaenity-community' ),
                    'resources'      => __( 'Resources', 'gaenity-community' ),
                    'register'       => __( 'Registration form', 'gaenity-community' ),
                    'login'          => __( 'Login form', 'gaenity-community' ),
                    'discussion_form'=> __( 'Discussion submission form', 'gaenity-community' ),
                    'discussion_board'=> __( 'Discussion board', 'gaenity-community' ),
                    'polls'          => __( 'Polls', 'gaenity-community' ),
                    'expert_request' => __( 'Ask an Expert form', 'gaenity-community' ),
                    'expert_register'=> __( 'Register as an Expert form', 'gaenity-community' ),
                    'contact'        => __( 'Contact form', 'gaenity-community' ),
                    'chat'           => __( 'Community chat', 'gaenity-community' ),
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $block    = isset( $settings['block_type'] ) ? $settings['block_type'] : 'community_home';

        echo $this->plugin->render_block( $block );
    }
}

endif;
