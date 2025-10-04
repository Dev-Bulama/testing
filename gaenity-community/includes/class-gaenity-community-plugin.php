<?php
/**
 * Main plugin class for Gaeinity Community Suite.
 *
 * @package GaeinityCommunity
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Gaeinity_Community_Plugin' ) ) :

class Gaeinity_Community_Plugin {

    /**
     * Plugin version.
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Plugin slug.
     *
     * @var string
     */
    protected $slug = 'gaenity-community';

    /**
     * Initialise the plugin.
     */
    public function init() {
        $this->define_constants();

        register_activation_hook( GAENITY_COMMUNITY_PLUGIN_FILE, array( __CLASS__, 'activate' ) );
        register_deactivation_hook( GAENITY_COMMUNITY_PLUGIN_FILE, array( __CLASS__, 'deactivate' ) );

        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_action( 'init', array( $this, 'register_shortcodes' ) );
        add_action( 'init', array( $this, 'register_roles' ) );
        add_action( 'init', array( $this, 'load_textdomain' ) );

        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
        add_action( 'save_post_gaenity_resource', array( $this, 'save_resource_meta' ) );
        add_action( 'save_post_gaenity_poll', array( $this, 'save_poll_meta' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

        $this->register_ajax_actions();

        add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
        add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
    }

    /**
     * Define core plugin constants.
     */
    protected function define_constants() {
        if ( ! defined( 'GAENITY_COMMUNITY_PATH' ) ) {
            define( 'GAENITY_COMMUNITY_PATH', plugin_dir_path( GAENITY_COMMUNITY_PLUGIN_FILE ) );
        }
        if ( ! defined( 'GAENITY_COMMUNITY_URL' ) ) {
            define( 'GAENITY_COMMUNITY_URL', plugin_dir_url( GAENITY_COMMUNITY_PLUGIN_FILE ) );
        }
        if ( ! defined( 'GAENITY_COMMUNITY_ASSETS' ) ) {
            define( 'GAENITY_COMMUNITY_ASSETS', trailingslashit( GAENITY_COMMUNITY_URL . 'assets' ) );
        }
    }

    /**
     * Load plugin text domain.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'gaenity-community', false, dirname( plugin_basename( GAENITY_COMMUNITY_PLUGIN_FILE ) ) . '/languages' );
    }

    /**
     * Activation hook callback.
     */
    public static function activate() {
        self::create_tables();
        self::add_roles();
        flush_rewrite_rules();
    }

    /**
     * Deactivation hook callback.
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Create required database tables.
     */
    protected static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $downloads_table = $wpdb->prefix . 'gaenity_resource_downloads';
        $sql_downloads   = "CREATE TABLE $downloads_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            resource_id BIGINT(20) UNSIGNED NOT NULL,
            email VARCHAR(255) NOT NULL,
            role VARCHAR(100) DEFAULT '' NOT NULL,
            industry VARCHAR(191) DEFAULT '' NOT NULL,
            consent TINYINT(1) DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY resource_id (resource_id)
        ) $charset_collate;";

        $experts_table = $wpdb->prefix . 'gaenity_expert_requests';
        $sql_experts   = "CREATE TABLE $experts_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NULL,
            name VARCHAR(191) NOT NULL,
            email VARCHAR(255) NOT NULL,
            role VARCHAR(100) DEFAULT '' NOT NULL,
            region VARCHAR(100) DEFAULT '' NOT NULL,
            country VARCHAR(100) DEFAULT '' NOT NULL,
            industry VARCHAR(191) DEFAULT '' NOT NULL,
            challenge VARCHAR(191) DEFAULT '' NOT NULL,
            description TEXT NULL,
            budget VARCHAR(100) DEFAULT '' NOT NULL,
            preference VARCHAR(50) DEFAULT '' NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY email (email)
        ) $charset_collate;";

        $contacts_table = $wpdb->prefix . 'gaenity_contact_messages';
        $sql_contacts   = "CREATE TABLE $contacts_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            email VARCHAR(255) NOT NULL,
            subject VARCHAR(191) NOT NULL,
            message TEXT NOT NULL,
            updates TINYINT(1) DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        $chat_table = $wpdb->prefix . 'gaenity_chat_messages';
        $sql_chat   = "CREATE TABLE $chat_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NULL,
            display_name VARCHAR(191) DEFAULT '' NOT NULL,
            role VARCHAR(100) DEFAULT '' NOT NULL,
            region VARCHAR(100) DEFAULT '' NOT NULL,
            industry VARCHAR(191) DEFAULT '' NOT NULL,
            challenge VARCHAR(191) DEFAULT '' NOT NULL,
            message TEXT NOT NULL,
            is_anonymous TINYINT(1) DEFAULT 0 NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY created_at (created_at)
        ) $charset_collate;";

        $votes_table = $wpdb->prefix . 'gaenity_poll_votes';
        $sql_votes   = "CREATE TABLE $votes_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            poll_id BIGINT(20) UNSIGNED NOT NULL,
            user_id BIGINT(20) UNSIGNED NULL,
            option_key VARCHAR(100) NOT NULL,
            region VARCHAR(100) DEFAULT '' NOT NULL,
            industry VARCHAR(191) DEFAULT '' NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY poll_id (poll_id)
        ) $charset_collate;";

        dbDelta( $sql_downloads );
        dbDelta( $sql_experts );
        dbDelta( $sql_contacts );
        dbDelta( $sql_chat );
        dbDelta( $sql_votes );
    }

    /**
     * Register community specific roles.
     */
    public function register_roles() {
        if ( ! get_role( 'gaenity_expert' ) ) {
            add_role( 'gaenity_expert', __( 'Gaeinity Expert', 'gaenity-community' ), array( 'read' => true ) );
        }
    }

    /**
     * Add roles during activation.
     */
    protected static function add_roles() {
        if ( ! get_role( 'gaenity_expert' ) ) {
            add_role( 'gaenity_expert', __( 'Gaeinity Expert', 'gaenity-community' ), array( 'read' => true ) );
        }
    }

    /**
     * Register post types.
     */
    public function register_post_types() {
        register_post_type(
            'gaenity_resource',
            array(
                'labels'      => array(
                    'name'          => __( 'Resources', 'gaenity-community' ),
                    'singular_name' => __( 'Resource', 'gaenity-community' ),
                ),
                'public'      => true,
                'has_archive' => true,
                'menu_icon'   => 'dashicons-media-document',
                'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
                'show_in_rest'=> true,
            )
        );

        register_post_type(
            'gaenity_discussion',
            array(
                'labels'      => array(
                    'name'          => __( 'Community Discussions', 'gaenity-community' ),
                    'singular_name' => __( 'Discussion', 'gaenity-community' ),
                ),
                'public'      => true,
                'has_archive' => true,
                'supports'    => array( 'title', 'editor', 'author', 'comments' ),
                'menu_icon'   => 'dashicons-format-chat',
                'show_in_rest'=> true,
            )
        );

        register_post_type(
            'gaenity_poll',
            array(
                'labels'      => array(
                    'name'          => __( 'Community Polls', 'gaenity-community' ),
                    'singular_name' => __( 'Poll', 'gaenity-community' ),
                ),
                'public'      => false,
                'show_ui'     => true,
                'supports'    => array( 'title' ),
                'menu_icon'   => 'dashicons-chart-bar',
                'show_in_rest'=> false,
            )
        );
    }

    /**
     * Register taxonomies.
     */
    public function register_taxonomies() {
        register_taxonomy(
            'gaenity_resource_type',
            'gaenity_resource',
            array(
                'labels'            => array(
                    'name'          => __( 'Resource Types', 'gaenity-community' ),
                    'singular_name' => __( 'Resource Type', 'gaenity-community' ),
                ),
                'public'            => true,
                'hierarchical'      => false,
                'show_in_rest'      => true,
            )
        );

        register_taxonomy(
            'gaenity_region',
            'gaenity_discussion',
            array(
                'labels'       => array(
                    'name'          => __( 'Regions', 'gaenity-community' ),
                    'singular_name' => __( 'Region', 'gaenity-community' ),
                ),
                'public'       => true,
                'hierarchical' => false,
                'show_in_rest' => true,
            )
        );

        register_taxonomy(
            'gaenity_industry',
            'gaenity_discussion',
            array(
                'labels'       => array(
                    'name'          => __( 'Industries', 'gaenity-community' ),
                    'singular_name' => __( 'Industry', 'gaenity-community' ),
                ),
                'public'       => true,
                'hierarchical' => false,
                'show_in_rest' => true,
            )
        );

        register_taxonomy(
            'gaenity_challenge',
            'gaenity_discussion',
            array(
                'labels'       => array(
                    'name'          => __( 'Challenges', 'gaenity-community' ),
                    'singular_name' => __( 'Challenge', 'gaenity-community' ),
                ),
                'public'       => true,
                'hierarchical' => false,
                'show_in_rest' => true,
            )
        );
    }

    /**
     * Register Elementor widget category.
     */
    public function register_elementor_category( $elements_manager ) {
        $elements_manager->add_category(
            'gaenity-community',
            array(
                'title' => __( 'Gaeinity Community', 'gaenity-community' ),
                'icon'  => 'fa fa-users',
            )
        );
    }

    /**
     * Register Elementor widgets.
     */
    public function register_elementor_widgets( $widgets_manager ) {
        if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
            return;
        }

        require_once GAENITY_COMMUNITY_PATH . 'includes/class-gaenity-elementor-widget.php';

        $widgets_manager->register( new Gaeinity_Community_Elementor_Widget( $this ) );
    }

    /**
     * Register meta boxes.
     */
    public function register_meta_boxes() {
        add_meta_box(
            'gaenity_resource_details',
            __( 'Resource Details', 'gaenity-community' ),
            array( $this, 'render_resource_meta_box' ),
            'gaenity_resource',
            'normal',
            'high'
        );

        add_meta_box(
            'gaenity_poll_details',
            __( 'Poll Options', 'gaenity-community' ),
            array( $this, 'render_poll_meta_box' ),
            'gaenity_poll',
            'normal',
            'high'
        );
    }

    /**
     * Render resource meta box.
     */
    public function render_resource_meta_box( $post ) {
        wp_nonce_field( 'gaenity_resource_meta', 'gaenity_resource_meta_nonce' );

        $download_url = get_post_meta( $post->ID, '_gaenity_resource_file', true );
        $is_premium   = has_term( 'paid', 'gaenity_resource_type', $post->ID );

        echo '<p>' . esc_html__( 'Provide a public URL to the resource file (PDF, DOCX, etc).', 'gaenity-community' ) . '</p>';
        echo '<label for="gaenity_resource_file">' . esc_html__( 'Download URL', 'gaenity-community' ) . '</label>';
        echo '<input type="url" class="widefat" id="gaenity_resource_file" name="gaenity_resource_file" value="' . esc_attr( $download_url ) . '" />';
        echo '<p>' . esc_html__( 'Assign the resource type taxonomy with either Free or Paid to control front-end availability.', 'gaenity-community' ) . '</p>';
        echo '<p>' . ( $is_premium ? esc_html__( 'Current resource type includes Paid.', 'gaenity-community' ) : esc_html__( 'Current resource type is Free unless changed.', 'gaenity-community' ) ) . '</p>';
    }

    /**
     * Render poll meta box.
     */
    public function render_poll_meta_box( $post ) {
        wp_nonce_field( 'gaenity_poll_meta', 'gaenity_poll_meta_nonce' );
        $question = get_post_meta( $post->ID, '_gaenity_poll_question', true );
        $options  = get_post_meta( $post->ID, '_gaenity_poll_options', true );
        if ( empty( $options ) ) {
            $options = array(
                'option_one'   => __( 'Option one', 'gaenity-community' ),
                'option_two'   => __( 'Option two', 'gaenity-community' ),
                'option_three' => __( 'Option three', 'gaenity-community' ),
            );
        }

        echo '<p>' . esc_html__( 'Poll title appears on the front end. Use this box for an optional expanded question.', 'gaenity-community' ) . '</p>';
        echo '<label for="gaenity_poll_question">' . esc_html__( 'Expanded question (optional)', 'gaenity-community' ) . '</label>';
        echo '<textarea class="widefat" id="gaenity_poll_question" name="gaenity_poll_question" rows="3">' . esc_textarea( $question ) . '</textarea>';

        echo '<p>' . esc_html__( 'Provide up to five answer choices. Leave labels blank to hide unused options.', 'gaenity-community' ) . '</p>';

        for ( $i = 1; $i <= 5; $i++ ) {
            $key   = 'option_' . $i;
            $value = isset( $options[ $key ] ) ? $options[ $key ] : '';
            echo '<p>';
            echo '<label for="gaenity_poll_' . esc_attr( $key ) . '">' . sprintf( esc_html__( 'Option %d label', 'gaenity-community' ), $i ) . '</label>';
            echo '<input type="text" class="widefat" id="gaenity_poll_' . esc_attr( $key ) . '" name="gaenity_poll_options[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '" />';
            echo '</p>';
        }
    }

    /**
     * Save resource meta box data.
     */
    public function save_resource_meta( $post_id ) {
        if ( ! isset( $_POST['gaenity_resource_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gaenity_resource_meta_nonce'] ) ), 'gaenity_resource_meta' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( isset( $_POST['gaenity_resource_file'] ) ) {
            $url = esc_url_raw( wp_unslash( $_POST['gaenity_resource_file'] ) );
            update_post_meta( $post_id, '_gaenity_resource_file', $url );
        }
    }

    /**
     * Save poll meta box data.
     */
    public function save_poll_meta( $post_id ) {
        if ( ! isset( $_POST['gaenity_poll_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gaenity_poll_meta_nonce'] ) ), 'gaenity_poll_meta' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        $question = isset( $_POST['gaenity_poll_question'] ) ? wp_kses_post( wp_unslash( $_POST['gaenity_poll_question'] ) ) : '';
        $options  = isset( $_POST['gaenity_poll_options'] ) ? (array) $_POST['gaenity_poll_options'] : array();

        $clean_options = array();
        $count         = 0;
        foreach ( $options as $key => $label ) {
            $label = sanitize_text_field( $label );
            if ( ! empty( $label ) ) {
                $count++;
                $clean_options[ $key ] = $label;
            }
        }

        if ( $count < 2 ) {
            return;
        }

        update_post_meta( $post_id, '_gaenity_poll_question', $question );
        update_post_meta( $post_id, '_gaenity_poll_options', $clean_options );
    }

    /**
     * Enqueue frontend assets.
     */
    public function enqueue_assets() {
        wp_register_style( 'gaenity-community', GAENITY_COMMUNITY_ASSETS . 'css/frontend.css', array(), $this->version );
        wp_register_script( 'gaenity-community', GAENITY_COMMUNITY_ASSETS . 'js/frontend.js', array( 'jquery' ), $this->version, true );

        wp_enqueue_style( 'gaenity-community' );
        wp_enqueue_script( 'gaenity-community' );

        wp_localize_script(
            'gaenity-community',
            'GaeinityCommunity',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'gaenity-community' ),
                'chat'    => array(
                    'pollInterval' => 10000,
                    'maxMessages'  => 30,
                ),
            )
        );
    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_admin_assets() {
        wp_register_style( 'gaenity-community-admin', GAENITY_COMMUNITY_ASSETS . 'css/frontend.css', array(), $this->version );
        wp_enqueue_style( 'gaenity-community-admin' );
    }

    /**
     * Register shortcodes.
     */
    public function register_shortcodes() {
        add_shortcode( 'gaenity_resources', array( $this, 'render_resources_shortcode' ) );
        add_shortcode( 'gaenity_community_home', array( $this, 'render_community_home_shortcode' ) );
        add_shortcode( 'gaenity_community_register', array( $this, 'render_registration_form' ) );
        add_shortcode( 'gaenity_community_login', array( $this, 'render_login_form' ) );
        add_shortcode( 'gaenity_discussion_form', array( $this, 'render_discussion_form' ) );
        add_shortcode( 'gaenity_discussion_board', array( $this, 'render_discussion_board' ) );
        add_shortcode( 'gaenity_polls', array( $this, 'render_polls' ) );
        add_shortcode( 'gaenity_expert_request', array( $this, 'render_expert_request_form' ) );
        add_shortcode( 'gaenity_expert_register', array( $this, 'render_expert_register_form' ) );
        add_shortcode( 'gaenity_contact', array( $this, 'render_contact_form' ) );
        add_shortcode( 'gaenity_community_chat', array( $this, 'render_chat_interface' ) );
    }

    /**
     * Register Ajax handlers.
     */
    protected function register_ajax_actions() {
        $actions = array(
            'gaenity_resource_download' => 'handle_resource_download',
            'gaenity_user_register'     => 'handle_user_registration',
            'gaenity_user_login'        => 'handle_user_login',
            'gaenity_discussion_submit' => 'handle_discussion_submit',
            'gaenity_poll_vote'         => 'handle_poll_vote',
            'gaenity_expert_request'    => 'handle_expert_request',
            'gaenity_expert_register'   => 'handle_expert_registration',
            'gaenity_contact_submit'    => 'handle_contact_submission',
            'gaenity_chat_send'         => 'handle_chat_message',
            'gaenity_chat_fetch'        => 'handle_chat_fetch',
        );

        foreach ( $actions as $action => $method ) {
            add_action( 'wp_ajax_' . $action, array( $this, $method ) );
            add_action( 'wp_ajax_nopriv_' . $action, array( $this, $method ) );
        }
    }

    /**
     * Handle resource download submission.
     */
    public function handle_resource_download() {
        $this->verify_nonce();

        $resource_id = isset( $_POST['resource_id'] ) ? absint( $_POST['resource_id'] ) : 0;
        $email       = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        $role        = isset( $_POST['role'] ) ? sanitize_text_field( wp_unslash( $_POST['role'] ) ) : '';
        $industry    = isset( $_POST['industry'] ) ? sanitize_text_field( wp_unslash( $_POST['industry'] ) ) : '';
        $other       = isset( $_POST['industry_other'] ) ? sanitize_text_field( wp_unslash( $_POST['industry_other'] ) ) : '';
        $consent     = isset( $_POST['consent'] ) ? 1 : 0;
        $download    = isset( $_POST['download_url'] ) ? esc_url_raw( wp_unslash( $_POST['download_url'] ) ) : '';

        if ( empty( $resource_id ) || empty( $email ) || empty( $role ) || empty( $industry ) ) {
            wp_send_json_error( array( 'message' => __( 'Please complete all required fields.', 'gaenity-community' ) ) );
        }

        if ( 'other' === strtolower( $industry ) && ! empty( $other ) ) {
            $industry = $other;
        }

        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'gaenity_resource_downloads',
            array(
                'resource_id' => $resource_id,
                'email'       => $email,
                'role'        => $role,
                'industry'    => $industry,
                'consent'     => $consent,
            ),
            array( '%d', '%s', '%s', '%s', '%d' )
        );

        if ( empty( $download ) ) {
            $download = get_post_meta( $resource_id, '_gaenity_resource_file', true );
        }

        wp_send_json_success(
            array(
                'message'      => __( 'Thanks! Your download will begin shortly.', 'gaenity-community' ),
                'download_url' => $download,
            )
        );
    }

    /**
     * Handle community registration.
     */
    public function handle_user_registration() {
        $this->verify_nonce();

        $required = array( 'full_name', 'display_name', 'email', 'password', 'role', 'region', 'country', 'industry', 'challenge', 'goals' );
        foreach ( $required as $field ) {
            if ( empty( $_POST[ $field ] ) ) {
                wp_send_json_error( array( 'message' => __( 'Please complete all required fields.', 'gaenity-community' ) ) );
            }
        }

        if ( empty( $_POST['guidelines'] ) ) {
            wp_send_json_error( array( 'message' => __( 'You must agree to the community guidelines.', 'gaenity-community' ) ) );
        }

        $email        = sanitize_email( wp_unslash( $_POST['email'] ) );
        $display_name = sanitize_text_field( wp_unslash( $_POST['display_name'] ) );
        $full_name    = sanitize_text_field( wp_unslash( $_POST['full_name'] ) );
        $password     = wp_unslash( $_POST['password'] );
        $role         = sanitize_text_field( wp_unslash( $_POST['role'] ) );
        $region       = sanitize_text_field( wp_unslash( $_POST['region'] ) );
        $country      = sanitize_text_field( wp_unslash( $_POST['country'] ) );
        $industry     = sanitize_text_field( wp_unslash( $_POST['industry'] ) );
        $challenge    = sanitize_text_field( wp_unslash( $_POST['challenge'] ) );
        $goals        = wp_kses_post( wp_unslash( $_POST['goals'] ) );
        $updates      = ! empty( $_POST['updates'] ) ? 1 : 0;

        if ( email_exists( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'This email is already registered.', 'gaenity-community' ) ) );
        }

        $username = sanitize_user( current( explode( '@', $email ) ) );
        $username = apply_filters( 'gaenity_generate_username', $username, $email );

        if ( username_exists( $username ) ) {
            $username .= '_' . wp_generate_password( 4, false );
        }

        $user_id = wp_create_user( $username, $password, $email );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
        }

        $user_role = 'subscriber';
        if ( 'Forum Expert' === $role ) {
            $user_role = 'gaenity_expert';
        }

        wp_update_user(
            array(
                'ID'           => $user_id,
                'display_name' => $display_name,
                'nickname'     => $display_name,
                'role'         => $user_role,
            )
        );

        update_user_meta( $user_id, 'gaenity_full_name', $full_name );
        update_user_meta( $user_id, 'gaenity_region', $region );
        update_user_meta( $user_id, 'gaenity_country', $country );
        update_user_meta( $user_id, 'gaenity_industry', $industry );
        update_user_meta( $user_id, 'gaenity_challenge', $challenge );
        update_user_meta( $user_id, 'gaenity_goals', $goals );
        update_user_meta( $user_id, 'gaenity_updates_opt_in', $updates );
        update_user_meta( $user_id, 'gaenity_role_title', $role );

        wp_signon(
            array(
                'user_login'    => $username,
                'user_password' => $password,
                'remember'      => true,
            ),
            false
        );

        wp_send_json_success(
            array(
                'message'  => __( 'Welcome to the Gaenity community!', 'gaenity-community' ),
                'redirect' => apply_filters( 'gaenity_registration_redirect', home_url() ),
            )
        );
    }

    /**
     * Handle login request.
     */
    public function handle_user_login() {
        $this->verify_nonce();

        $credentials = array(
            'user_login'    => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
            'user_password' => isset( $_POST['password'] ) ? wp_unslash( $_POST['password'] ) : '',
            'remember'      => ! empty( $_POST['remember'] ),
        );

        if ( empty( $credentials['user_login'] ) || empty( $credentials['user_password'] ) ) {
            wp_send_json_error( array( 'message' => __( 'Email and password are required.', 'gaenity-community' ) ) );
        }

        $user = wp_signon( $credentials, false );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( array( 'message' => $user->get_error_message() ) );
        }

        wp_send_json_success(
            array(
                'message'  => __( 'Login successful.', 'gaenity-community' ),
                'redirect' => apply_filters( 'gaenity_login_redirect', home_url() ),
            )
        );
    }

    /**
     * Handle discussion submission.
     */
    public function handle_discussion_submit() {
        $this->verify_nonce();

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'You must be logged in to post.', 'gaenity-community' ) ) );
        }

        $user_id = get_current_user_id();

        $title     = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
        $content   = isset( $_POST['content'] ) ? wp_kses_post( wp_unslash( $_POST['content'] ) ) : '';
        $region    = isset( $_POST['region'] ) ? sanitize_text_field( wp_unslash( $_POST['region'] ) ) : '';
        $industry  = isset( $_POST['industry'] ) ? sanitize_text_field( wp_unslash( $_POST['industry'] ) ) : '';
        $challenge = isset( $_POST['challenge'] ) ? sanitize_text_field( wp_unslash( $_POST['challenge'] ) ) : '';
        $country   = isset( $_POST['country'] ) ? sanitize_text_field( wp_unslash( $_POST['country'] ) ) : '';
        $anonymous = ! empty( $_POST['anonymous'] );

        if ( empty( $title ) || empty( $content ) || empty( $region ) || empty( $industry ) || empty( $challenge ) ) {
            wp_send_json_error( array( 'message' => __( 'Please complete all required fields.', 'gaenity-community' ) ) );
        }

        $post_id = wp_insert_post(
            array(
                'post_type'    => 'gaenity_discussion',
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_author'  => $user_id,
            ),
            true
        );

        if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( array( 'message' => $post_id->get_error_message() ) );
        }

        wp_set_object_terms( $post_id, $region, 'gaenity_region' );
        wp_set_object_terms( $post_id, $industry, 'gaenity_industry' );
        wp_set_object_terms( $post_id, $challenge, 'gaenity_challenge' );

        update_post_meta( $post_id, '_gaenity_country', $country );
        update_post_meta( $post_id, '_gaenity_anonymous', $anonymous ? 1 : 0 );

        wp_send_json_success(
            array(
                'message' => __( 'Discussion published successfully.', 'gaenity-community' ),
            )
        );
    }

    /**
     * Handle poll votes.
     */
    public function handle_poll_vote() {
        $this->verify_nonce();

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Login is required to vote.', 'gaenity-community' ) ) );
        }

        $poll_id = isset( $_POST['poll_id'] ) ? absint( $_POST['poll_id'] ) : 0;
        $option  = isset( $_POST['option'] ) ? sanitize_text_field( wp_unslash( $_POST['option'] ) ) : '';
        $region  = isset( $_POST['region'] ) ? sanitize_text_field( wp_unslash( $_POST['region'] ) ) : '';
        $industry= isset( $_POST['industry'] ) ? sanitize_text_field( wp_unslash( $_POST['industry'] ) ) : '';

        if ( empty( $poll_id ) || empty( $option ) || empty( $region ) || empty( $industry ) ) {
            wp_send_json_error( array( 'message' => __( 'Please select an option and provide your profile filters.', 'gaenity-community' ) ) );
        }

        $options = get_post_meta( $poll_id, '_gaenity_poll_options', true );
        if ( empty( $options ) || ! isset( $options[ $option ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid poll option selected.', 'gaenity-community' ) ) );
        }

        $user_id = get_current_user_id();

        global $wpdb;
        $exists = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'gaenity_poll_votes WHERE poll_id = %d AND user_id = %d LIMIT 1', $poll_id, $user_id ) );

        if ( $exists ) {
            wp_send_json_error( array( 'message' => __( 'You already voted in this poll.', 'gaenity-community' ) ) );
        }

        $wpdb->insert(
            $wpdb->prefix . 'gaenity_poll_votes',
            array(
                'poll_id'    => $poll_id,
                'user_id'    => $user_id,
                'option_key' => $option,
                'region'     => $region,
                'industry'   => $industry,
            ),
            array( '%d', '%d', '%s', '%s', '%s' )
        );

        $results = $this->get_poll_results_markup( $poll_id, $options );

        wp_send_json_success(
            array(
                'message' => __( 'Thanks for sharing your vote.', 'gaenity-community' ),
                'results' => $results,
            )
        );
    }

    /**
     * Handle expert request submissions.
     */
    public function handle_expert_request() {
        $this->verify_nonce();

        $fields = array( 'name', 'email', 'role', 'region', 'country', 'industry', 'challenge', 'description', 'budget', 'preference' );
        foreach ( $fields as $field ) {
            if ( empty( $_POST[ $field ] ) ) {
                wp_send_json_error( array( 'message' => __( 'Please complete all required fields.', 'gaenity-community' ) ) );
            }
        }

        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'gaenity_expert_requests',
            array(
                'user_id'    => get_current_user_id(),
                'name'       => sanitize_text_field( wp_unslash( $_POST['name'] ) ),
                'email'      => sanitize_email( wp_unslash( $_POST['email'] ) ),
                'role'       => sanitize_text_field( wp_unslash( $_POST['role'] ) ),
                'region'     => sanitize_text_field( wp_unslash( $_POST['region'] ) ),
                'country'    => sanitize_text_field( wp_unslash( $_POST['country'] ) ),
                'industry'   => sanitize_text_field( wp_unslash( $_POST['industry'] ) ),
                'challenge'  => sanitize_text_field( wp_unslash( $_POST['challenge'] ) ),
                'description'=> wp_kses_post( wp_unslash( $_POST['description'] ) ),
                'budget'     => sanitize_text_field( wp_unslash( $_POST['budget'] ) ),
                'preference' => sanitize_text_field( wp_unslash( $_POST['preference'] ) ),
            ),
            array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );

        wp_send_json_success( array( 'message' => __( 'Your request has been submitted. We will be in touch soon.', 'gaenity-community' ) ) );
    }

    /**
     * Handle expert registration submissions.
     */
    public function handle_expert_registration() {
        $this->verify_nonce();

        $fields = array( 'name', 'email', 'expertise', 'profile_url' );
        foreach ( $fields as $field ) {
            if ( empty( $_POST[ $field ] ) ) {
                wp_send_json_error( array( 'message' => __( 'Please complete all required fields.', 'gaenity-community' ) ) );
            }
        }

        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'gaenity_expert_requests',
            array(
                'user_id'    => get_current_user_id(),
                'name'       => sanitize_text_field( wp_unslash( $_POST['name'] ) ),
                'email'      => sanitize_email( wp_unslash( $_POST['email'] ) ),
                'role'       => 'Expert Applicant',
                'region'     => '',
                'country'    => '',
                'industry'   => '',
                'challenge'  => 'expert_registration',
                'description'=> wp_kses_post( wp_unslash( $_POST['expertise'] ) ),
                'budget'     => sanitize_text_field( wp_unslash( $_POST['profile_url'] ) ),
                'preference' => 'expert_registration',
            ),
            array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );

        wp_send_json_success( array( 'message' => __( 'Thanks! Our team will review your expert application.', 'gaenity-community' ) ) );
    }

    /**
     * Handle contact form submissions.
     */
    public function handle_contact_submission() {
        $this->verify_nonce();

        $fields = array( 'name', 'email', 'subject', 'message' );
        foreach ( $fields as $field ) {
            if ( empty( $_POST[ $field ] ) ) {
                wp_send_json_error( array( 'message' => __( 'Please complete all required fields.', 'gaenity-community' ) ) );
            }
        }

        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'gaenity_contact_messages',
            array(
                'name'    => sanitize_text_field( wp_unslash( $_POST['name'] ) ),
                'email'   => sanitize_email( wp_unslash( $_POST['email'] ) ),
                'subject' => sanitize_text_field( wp_unslash( $_POST['subject'] ) ),
                'message' => wp_kses_post( wp_unslash( $_POST['message'] ) ),
                'updates' => ! empty( $_POST['updates'] ) ? 1 : 0,
            ),
            array( '%s', '%s', '%s', '%s', '%d' )
        );

        wp_send_json_success( array( 'message' => __( 'Thanks for reaching out. We will reply soon.', 'gaenity-community' ) ) );
    }

    /**
     * Handle chat messages.
     */
    public function handle_chat_message() {
        $this->verify_nonce();

        $message   = isset( $_POST['message'] ) ? wp_kses_post( wp_unslash( $_POST['message'] ) ) : '';
        $role      = isset( $_POST['role'] ) ? sanitize_text_field( wp_unslash( $_POST['role'] ) ) : '';
        $region    = isset( $_POST['region'] ) ? sanitize_text_field( wp_unslash( $_POST['region'] ) ) : '';
        $industry  = isset( $_POST['industry'] ) ? sanitize_text_field( wp_unslash( $_POST['industry'] ) ) : '';
        $challenge = isset( $_POST['challenge'] ) ? sanitize_text_field( wp_unslash( $_POST['challenge'] ) ) : '';
        $anonymous = ! empty( $_POST['anonymous'] );
        $display   = isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ) : '';

        if ( empty( $message ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter a message.', 'gaenity-community' ) ) );
        }

        $user_id = get_current_user_id();
        if ( $user_id && empty( $display ) ) {
            $user    = get_userdata( $user_id );
            $display = $user ? $user->display_name : __( 'Member', 'gaenity-community' );
        }

        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'gaenity_chat_messages',
            array(
                'user_id'      => $user_id ? $user_id : null,
                'display_name' => $anonymous ? __( 'Anonymous', 'gaenity-community' ) : $display,
                'role'         => $role,
                'region'       => $region,
                'industry'     => $industry,
                'challenge'    => $challenge,
                'message'      => $message,
                'is_anonymous' => $anonymous ? 1 : 0,
            ),
            array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d' )
        );

        wp_send_json_success( array( 'message' => __( 'Message posted.', 'gaenity-community' ) ) );
    }

    /**
     * Handle chat fetch.
     */
    public function handle_chat_fetch() {
        $this->verify_nonce();

        $messages = $this->get_chat_messages();
        wp_send_json_success( array( 'messages' => $messages ) );
    }

    /**
     * Verify AJAX nonce.
     */
    protected function verify_nonce() {
        if ( empty( $_POST['gaenity_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gaenity_nonce'] ) ), 'gaenity-community' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed. Please refresh and try again.', 'gaenity-community' ) ) );
        }
    }

    /**
     * Render resources grid.
     */
    public function render_resources_shortcode( $atts ) {
        $atts = shortcode_atts(
            array(
                'type' => 'all',
            ),
            $atts,
            'gaenity_resources'
        );

        $resource_types = array( 'free', 'paid' );
        if ( 'all' !== $atts['type'] && in_array( $atts['type'], $resource_types, true ) ) {
            $resource_types = array( $atts['type'] );
        }

        $output  = '<div class="gaenity-resources-section">';
        $output .= '<div class="gaenity-section-header">';
        $output .= '<h2>' . esc_html__( 'Practical tools that turn ideas into action.', 'gaenity-community' ) . '</h2>';
        $output .= '<p>' . esc_html__( 'From risk management checklists to finance enablement guides and operational templates, each resource is designed to help businesses build resilience, prepare for growth, and make measurable progress.', 'gaenity-community' ) . '</p>';
        $output .= '<div class="gaenity-resource-tabs">';
        foreach ( array( 'free' => __( 'Free Resources', 'gaenity-community' ), 'paid' => __( 'Paid Resources', 'gaenity-community' ) ) as $key => $label ) {
            $output .= '<button class="gaenity-resource-tab" data-target="gaenity-resources-' . esc_attr( $key ) . '">' . esc_html( $label ) . '</button>';
        }
        $output .= '</div>';
        $output .= '</div>';

        foreach ( $resource_types as $type ) {
            $output .= '<div class="gaenity-resource-grid" id="gaenity-resources-' . esc_attr( $type ) . '">';

            $query = new WP_Query(
                array(
                    'post_type'      => 'gaenity_resource',
                    'posts_per_page' => -1,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'gaenity_resource_type',
                            'field'    => 'slug',
                            'terms'    => $type,
                        ),
                    ),
                )
            );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $resource_id  = get_the_ID();
                    $download_url = get_post_meta( $resource_id, '_gaenity_resource_file', true );
                    $description  = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 25 );
                    $image        = get_the_post_thumbnail( $resource_id, 'medium', array( 'class' => 'gaenity-resource-image' ) );

                    $output .= '<article class="gaenity-resource-card">';
                    if ( $image ) {
                        $output .= $image;
                    }
                    $output .= '<div class="gaenity-resource-body">';
                    $output .= '<h3>' . esc_html( get_the_title() ) . '</h3>';
                    $output .= '<p>' . esc_html( $description ) . '</p>';
                    if ( 'free' === $type ) {
                        $output .= '<button class="gaenity-button" data-resource="' . esc_attr( $resource_id ) . '">' . esc_html__( 'Download', 'gaenity-community' ) . '</button>';
                    } else {
                        $output .= '<span class="gaenity-coming-soon">' . esc_html__( 'Coming soon', 'gaenity-community' ) . '</span>';
                    }
                    $output .= '</div>';
                    $output .= '</article>';

                    if ( 'free' === $type && ! empty( $download_url ) ) {
                        $output .= $this->get_resource_form_markup( $resource_id, $download_url );
                    }
                }
            } else {
                $output .= '<p class="gaenity-empty-state">' . esc_html__( 'No resources available yet. Check back soon!', 'gaenity-community' ) . '</p>';
            }

            wp_reset_postdata();
            $output .= '</div>';
        }

        $output .= '</div>';

        return $output;
    }

    /**
     * Resource download form markup.
     */
    protected function get_resource_form_markup( $resource_id, $download_url ) {
        $industries = $this->get_industry_options();

        ob_start();
        ?>
        <div class="gaenity-modal" id="gaenity-resource-modal-<?php echo esc_attr( $resource_id ); ?>" hidden>
            <div class="gaenity-modal-content">
                <button class="gaenity-modal-close" aria-label="<?php esc_attr_e( 'Close', 'gaenity-community' ); ?>">&times;</button>
                <h3><?php esc_html_e( 'Access this resource', 'gaenity-community' ); ?></h3>
                <form class="gaenity-form gaenity-ajax-form" data-success-message="<?php esc_attr_e( 'Thanks! Your download will start automatically.', 'gaenity-community' ); ?>">
                    <input type="hidden" name="action" value="gaenity_resource_download" />
                    <input type="hidden" name="resource_id" value="<?php echo esc_attr( $resource_id ); ?>" />
                    <input type="hidden" name="download_url" value="<?php echo esc_url( $download_url ); ?>" />
                    <?php wp_nonce_field( 'gaenity-community', 'gaenity_nonce' ); ?>
                    <p>
                        <label for="gaenity_email_<?php echo esc_attr( $resource_id ); ?>"><?php esc_html_e( 'Email', 'gaenity-community' ); ?></label>
                        <input type="email" id="gaenity_email_<?php echo esc_attr( $resource_id ); ?>" name="email" required />
                    </p>
                    <p>
                        <label for="gaenity_role_<?php echo esc_attr( $resource_id ); ?>"><?php esc_html_e( 'Role', 'gaenity-community' ); ?></label>
                        <select id="gaenity_role_<?php echo esc_attr( $resource_id ); ?>" name="role" required>
                            <option value=""><?php esc_html_e( 'Select role', 'gaenity-community' ); ?></option>
                            <option value="Business owner"><?php esc_html_e( 'Business owner', 'gaenity-community' ); ?></option>
                            <option value="Professional"><?php esc_html_e( 'Professional', 'gaenity-community' ); ?></option>
                        </select>
                    </p>
                    <p>
                        <label for="gaenity_industry_<?php echo esc_attr( $resource_id ); ?>"><?php esc_html_e( 'Industry', 'gaenity-community' ); ?></label>
                        <select id="gaenity_industry_<?php echo esc_attr( $resource_id ); ?>" name="industry" required>
                            <option value=""><?php esc_html_e( 'Select industry', 'gaenity-community' ); ?></option>
                            <?php foreach ( $industries as $label ) : ?>
                                <option value="<?php echo esc_attr( $label ); ?>"><?php echo esc_html( $label ); ?></option>
                            <?php endforeach; ?>
                            <option value="other"><?php esc_html_e( 'Other', 'gaenity-community' ); ?></option>
                        </select>
                    </p>
                    <p>
                        <label for="gaenity_industry_other_<?php echo esc_attr( $resource_id ); ?>" class="gaenity-hidden">&nbsp;</label>
                        <input type="text" id="gaenity_industry_other_<?php echo esc_attr( $resource_id ); ?>" name="industry_other" placeholder="<?php esc_attr_e( 'If other, please specify', 'gaenity-community' ); ?>" />
                    </p>
                    <p class="gaenity-checkbox">
                        <label>
                            <input type="checkbox" name="consent" value="1" required />
                            <?php esc_html_e( 'By accessing this resource, you consent to Gaenity storing your details securely to provide the download and send relevant updates. We never sell or share your data with third parties. You can manage your preferences or unsubscribe at any time.', 'gaenity-community' ); ?>
                        </label>
                    </p>
                    <p>
                        <button type="submit" class="gaenity-button"><?php esc_html_e( 'Download', 'gaenity-community' ); ?></button>
                    </p>
                    <div class="gaenity-form-feedback" role="alert" aria-live="polite"></div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render community home.
     */
    public function render_community_home_shortcode() {
        ob_start();
        ?>
        <section class="gaenity-community-home">
            <header>
                <h2><?php esc_html_e( 'Community Home', 'gaenity-community' ); ?></h2>
                <p class="gaenity-intro"><?php esc_html_e( 'The Gaenity community connects business owners, entrepreneurs, and professionals who want to share practical solutions. Join to ask questions, post challenges, and learn from peers and professionals.', 'gaenity-community' ); ?></p>
                <div class="gaenity-cta-group">
                    <a class="gaenity-button" href="#gaenity-register"><?php esc_html_e( 'Create your account', 'gaenity-community' ); ?></a>
                    <a class="gaenity-button ghost" href="#gaenity-ask-expert"><?php esc_html_e( 'Ask an Expert', 'gaenity-community' ); ?></a>
                    <a class="gaenity-button ghost" href="#gaenity-register-expert"><?php esc_html_e( 'Register as an Expert', 'gaenity-community' ); ?></a>
                </div>
            </header>
            <div class="gaenity-forum-structure">
                <div class="gaenity-columns">
                    <div>
                        <h3><?php esc_html_e( 'Getting started', 'gaenity-community' ); ?></h3>
                        <ul>
                            <li><?php esc_html_e( 'Introductions', 'gaenity-community' ); ?></li>
                            <li><?php esc_html_e( 'Community updates', 'gaenity-community' ); ?></li>
                        </ul>
                    </div>
                    <div>
                        <h3><?php esc_html_e( 'Regions', 'gaenity-community' ); ?></h3>
                        <ul>
                            <?php foreach ( $this->get_region_options() as $region ) : ?>
                                <li><a href="<?php echo esc_url( add_query_arg( 'region', rawurlencode( $region ), get_post_type_archive_link( 'gaenity_discussion' ) ) ); ?>"><?php echo esc_html( $region ); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div>
                        <h3><?php esc_html_e( 'Industries', 'gaenity-community' ); ?></h3>
                        <ul>
                            <?php foreach ( $this->get_industry_options() as $industry ) : ?>
                                <li><a href="<?php echo esc_url( add_query_arg( 'industry', rawurlencode( $industry ), get_post_type_archive_link( 'gaenity_discussion' ) ) ); ?>"><?php echo esc_html( $industry ); ?></a></li>
                            <?php endforeach; ?>
                            <li><?php esc_html_e( 'Other', 'gaenity-community' ); ?></li>
                        </ul>
                    </div>
                    <div>
                        <h3><?php esc_html_e( 'Common challenges', 'gaenity-community' ); ?></h3>
                        <ul>
                            <?php foreach ( $this->get_challenge_options() as $challenge ) : ?>
                                <li><?php echo esc_html( $challenge ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render registration form.
     */
    public function render_registration_form() {
        if ( is_user_logged_in() ) {
            return '<p class="gaenity-notice">' . esc_html__( 'You are already part of the community.', 'gaenity-community' ) . '</p>';
        }

        ob_start();
        ?>
        <form id="gaenity-register" class="gaenity-form gaenity-ajax-form" data-success-redirect="<?php echo esc_url( home_url() ); ?>">
            <h3><?php esc_html_e( 'Join the Gaenity Community', 'gaenity-community' ); ?></h3>
            <input type="hidden" name="action" value="gaenity_user_register" />
            <?php wp_nonce_field( 'gaenity-community', 'gaenity_nonce' ); ?>
            <p>
                <label for="gaenity_full_name"><?php esc_html_e( 'Full Name', 'gaenity-community' ); ?></label>
                <input type="text" id="gaenity_full_name" name="full_name" required />
            </p>
            <p>
                <label for="gaenity_display_name"><?php esc_html_e( 'Display Name', 'gaenity-community' ); ?></label>
                <input type="text" id="gaenity_display_name" name="display_name" required />
            </p>
            <p>
                <label for="gaenity_email_register"><?php esc_html_e( 'Email', 'gaenity-community' ); ?></label>
                <input type="email" id="gaenity_email_register" name="email" required />
            </p>
            <p>
                <label for="gaenity_password"><?php esc_html_e( 'Password', 'gaenity-community' ); ?></label>
                <input type="password" id="gaenity_password" name="password" required />
            </p>
            <p>
                <label for="gaenity_role_title"><?php esc_html_e( 'Role / Title', 'gaenity-community' ); ?></label>
                <select id="gaenity_role_title" name="role" required>
                    <option value=""><?php esc_html_e( 'Select role', 'gaenity-community' ); ?></option>
                    <option value="Business Owner"><?php esc_html_e( 'Business Owner', 'gaenity-community' ); ?></option>
                    <option value="Employed Professional"><?php esc_html_e( 'Employed Professional', 'gaenity-community' ); ?></option>
                    <option value="Forum Expert"><?php esc_html_e( 'Forum Expert', 'gaenity-community' ); ?></option>
                </select>
            </p>
            <p>
                <label for="gaenity_region"><?php esc_html_e( 'Region', 'gaenity-community' ); ?></label>
                <select id="gaenity_region" name="region" required>
                    <option value=""><?php esc_html_e( 'Select region', 'gaenity-community' ); ?></option>
                    <?php foreach ( $this->get_region_options() as $region ) : ?>
                        <option value="<?php echo esc_attr( $region ); ?>"><?php echo esc_html( $region ); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="gaenity_country"><?php esc_html_e( 'Country', 'gaenity-community' ); ?></label>
                <input type="text" id="gaenity_country" name="country" required />
            </p>
            <p>
                <label for="gaenity_industry"><?php esc_html_e( 'Industry', 'gaenity-community' ); ?></label>
                <select id="gaenity_industry" name="industry" required>
                    <option value=""><?php esc_html_e( 'Select industry', 'gaenity-community' ); ?></option>
                    <?php foreach ( $this->get_industry_options() as $industry ) : ?>
                        <option value="<?php echo esc_attr( $industry ); ?>"><?php echo esc_html( $industry ); ?></option>
                    <?php endforeach; ?>
                    <option value="Other"><?php esc_html_e( 'Other', 'gaenity-community' ); ?></option>
                </select>
            </p>
            <p>
                <label for="gaenity_primary_challenge"><?php esc_html_e( 'Primary challenge right now', 'gaenity-community' ); ?></label>
                <select id="gaenity_primary_challenge" name="challenge" required>
                    <option value=""><?php esc_html_e( 'Select challenge', 'gaenity-community' ); ?></option>
                    <?php foreach ( $this->get_challenge_options() as $challenge ) : ?>
                        <option value="<?php echo esc_attr( $challenge ); ?>"><?php echo esc_html( $challenge ); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="gaenity_goals"><?php esc_html_e( 'Goals for joining', 'gaenity-community' ); ?></label>
                <textarea id="gaenity_goals" name="goals" rows="3" required></textarea>
            </p>
            <p class="gaenity-checkbox">
                <label>
                    <input type="checkbox" name="guidelines" value="1" required />
                    <?php esc_html_e( 'I agree to the community guidelines', 'gaenity-community' ); ?>
                </label>
            </p>
            <p class="gaenity-checkbox">
                <label>
                    <input type="checkbox" name="updates" value="1" />
                    <?php esc_html_e( 'I agree to receive updates from Gaenity', 'gaenity-community' ); ?>
                </label>
            </p>
            <div class="gaenity-community-guidelines">
                <h4><?php esc_html_e( 'Community guidelines', 'gaenity-community' ); ?></h4>
                <ul>
                    <li><?php esc_html_e( 'Be respectful and constructive', 'gaenity-community' ); ?></li>
                    <li><?php esc_html_e( 'Share real experiences', 'gaenity-community' ); ?></li>
                    <li><?php esc_html_e( 'No spam or selling', 'gaenity-community' ); ?></li>
                    <li><?php esc_html_e( 'Protect privacy', 'gaenity-community' ); ?></li>
                    <li><?php esc_html_e( 'Repeated violations may result in removal', 'gaenity-community' ); ?></li>
                </ul>
            </div>
            <p>
                <button type="submit" class="gaenity-button"><?php esc_html_e( 'Join now', 'gaenity-community' ); ?></button>
            </p>
            <div class="gaenity-form-feedback" role="alert" aria-live="polite"></div>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Render login form.
     */
    public function render_login_form() {
        if ( is_user_logged_in() ) {
            return '<p class="gaenity-notice">' . esc_html__( 'You are already logged in.', 'gaenity-community' ) . '</p>';
        }

        ob_start();
        ?>
        <form class="gaenity-form gaenity-ajax-form">
            <h3><?php esc_html_e( 'Member Login', 'gaenity-community' ); ?></h3>
            <input type="hidden" name="action" value="gaenity_user_login" />
            <?php wp_nonce_field( 'gaenity-community', 'gaenity_nonce' ); ?>
            <p>
                <label for="gaenity_login_email"><?php esc_html_e( 'Email', 'gaenity-community' ); ?></label>
                <input type="email" id="gaenity_login_email" name="email" required />
            </p>
            <p>
                <label for="gaenity_login_password"><?php esc_html_e( 'Password', 'gaenity-community' ); ?></label>
                <input type="password" id="gaenity_login_password" name="password" required />
            </p>
            <p>
                <label>
                    <input type="checkbox" name="remember" value="1" />
                    <?php esc_html_e( 'Remember me', 'gaenity-community' ); ?>
                </label>
            </p>
            <p>
                <button type="submit" class="gaenity-button"><?php esc_html_e( 'Login', 'gaenity-community' ); ?></button>
            </p>
            <div class="gaenity-form-feedback" role="alert" aria-live="polite"></div>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Render discussion submission form.
     */
    public function render_discussion_form() {
        if ( ! is_user_logged_in() ) {
            return '<p class="gaenity-notice">' . esc_html__( 'Please log in to post a discussion.', 'gaenity-community' ) . '</p>';
        }

        ob_start();
        ?>
        <form class="gaenity-form gaenity-ajax-form">
            <h3><?php esc_html_e( 'Share your challenge', 'gaenity-community' ); ?></h3>
            <input type="hidden" name="action" value="gaenity_discussion_submit" />
            <?php wp_nonce_field( 'gaenity-community', 'gaenity_nonce' ); ?>
            <p>
                <label for="gaenity_discussion_title"><?php esc_html_e( 'Title', 'gaenity-community' ); ?></label>
                <input type="text" id="gaenity_discussion_title" name="title" required />
            </p>
            <p>
                <label for="gaenity_discussion_content"><?php esc_html_e( 'Describe your challenge', 'gaenity-community' ); ?></label>
                <textarea id="gaenity_discussion_content" name="content" rows="4" required></textarea>
            </p>
            <p>
                <label for="gaenity_discussion_region"><?php esc_html_e( 'Region', 'gaenity-community' ); ?></label>
                <select id="gaenity_discussion_region" name="region" required>
                    <option value=""><?php esc_html_e( 'Select region', 'gaenity-community' ); ?></option>
                    <?php foreach ( $this->get_region_options() as $region ) : ?>
                        <option value="<?php echo esc_attr( $region ); ?>"><?php echo esc_html( $region ); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="gaenity_discussion_country"><?php esc_html_e( 'Country', 'gaenity-community' ); ?></label>
                <input type="text" id="gaenity_discussion_country" name="country" required />
            </p>
            <p>
                <label for="gaenity_discussion_industry"><?php esc_html_e( 'Industry', 'gaenity-community' ); ?></label>
                <select id="gaenity_discussion_industry" name="industry" required>
                    <option value=""><?php esc_html_e( 'Select industry', 'gaenity-community' ); ?></option>
                    <?php foreach ( $this->get_industry_options() as $industry ) : ?>
                        <option value="<?php echo esc_attr( $industry ); ?>"><?php echo esc_html( $industry ); ?></option>
                    <?php endforeach; ?>
                    <option value="Other"><?php esc_html_e( 'Other', 'gaenity-community' ); ?></option>
                </select>
            </p>
            <p>
                <label for="gaenity_discussion_challenge"><?php esc_html_e( 'Challenge', 'gaenity-community' ); ?></label>
                <select id="gaenity_discussion_challenge" name="challenge" required>
                    <option value=""><?php esc_html_e( 'Select challenge', 'gaenity-community' ); ?></option>
                    <?php foreach ( $this->get_challenge_options() as $challenge ) : ?>
                        <option value="<?php echo esc_attr( $challenge ); ?>"><?php echo esc_html( $challenge ); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p class="gaenity-checkbox">
                <label>
                    <input type="checkbox" name="anonymous" value="1" />
                    <?php esc_html_e( 'Post anonymously', 'gaenity-community' ); ?>
                </label>
            </p>
            <p>
                <button type="submit" class="gaenity-button"><?php esc_html_e( 'Publish discussion', 'gaenity-community' ); ?></button>
            </p>
            <div class="gaenity-form-feedback" role="alert" aria-live="polite"></div>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Render discussion board with filters.
     */
    public function render_discussion_board( $atts ) {
        $atts = shortcode_atts(
            array(
                'per_page' => 10,
            ),
            $atts,
            'gaenity_discussion_board'
        );

        $paged = max( 1, get_query_var( 'paged' ) ? get_query_var( 'paged' ) : ( isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1 ) );

        $tax_query = array();
        $filters   = array( 'region' => 'gaenity_region', 'industry' => 'gaenity_industry', 'challenge' => 'gaenity_challenge' );
        foreach ( $filters as $query_var => $taxonomy ) {
            if ( ! empty( $_GET[ $query_var ] ) ) {
                $tax_query[] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'name',
                    'terms'    => sanitize_text_field( wp_unslash( $_GET[ $query_var ] ) ),
                );
            }
        }

        $query_args = array(
            'post_type'      => 'gaenity_discussion',
            'posts_per_page' => intval( $atts['per_page'] ),
            'paged'          => $paged,
        );

        if ( ! empty( $tax_query ) ) {
            $query_args['tax_query'] = $tax_query;
        }

        $query = new WP_Query( $query_args );

        ob_start();
        ?>
        <div class="gaenity-discussion-board">
            <form class="gaenity-filters" method="get">
                <label>
                    <?php esc_html_e( 'Region', 'gaenity-community' ); ?>
                    <select name="region">
                        <option value=""><?php esc_html_e( 'All regions', 'gaenity-community' ); ?></option>
                        <?php $this->render_filter_options( 'region' ); ?>
                    </select>
                </label>
                <label>
                    <?php esc_html_e( 'Industry', 'gaenity-community' ); ?>
                    <select name="industry">
                        <option value=""><?php esc_html_e( 'All industries', 'gaenity-community' ); ?></option>
                        <?php $this->render_filter_options( 'industry' ); ?>
                    </select>
                </label>
                <label>
                    <?php esc_html_e( 'Challenge', 'gaenity-community' ); ?>
                    <select name="challenge">
                        <option value=""><?php esc_html_e( 'All challenges', 'gaenity-community' ); ?></option>
                        <?php $this->render_filter_options( 'challenge' ); ?>
                    </select>
                </label>
                <button type="submit" class="gaenity-button ghost"><?php esc_html_e( 'Filter', 'gaenity-community' ); ?></button>
            </form>
            <?php if ( $query->have_posts() ) : ?>
                <ul class="gaenity-discussion-list">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <li class="gaenity-discussion-item">
                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <p class="gaenity-discussion-meta"><?php echo esc_html( $this->get_discussion_meta_summary( get_the_ID() ) ); ?></p>
                            <p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 25 ) ); ?></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php $this->render_pagination( $query ); ?>
            <?php else : ?>
                <p class="gaenity-empty-state"><?php esc_html_e( 'No discussions available yet. Start the conversation by posting the first question!', 'gaenity-community' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }

    /**
     * Render polls for logged-in users.
     */
    public function render_polls() {
        if ( ! is_user_logged_in() ) {
            return '<p class="gaenity-notice">' . esc_html__( 'Please sign in to take part in community polls.', 'gaenity-community' ) . '</p>';
        }

        $polls = get_posts(
            array(
                'post_type'      => 'gaenity_poll',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            )
        );

        if ( empty( $polls ) ) {
            return '<p class="gaenity-empty-state">' . esc_html__( 'Polls will appear here soon. Check back for new questions!', 'gaenity-community' ) . '</p>';
        }

        ob_start();
        ?>
        <div class="gaenity-polls" id="gaenity-polls">
            <?php foreach ( $polls as $poll ) :
                $question = get_post_meta( $poll->ID, '_gaenity_poll_question', true );
                $options  = get_post_meta( $poll->ID, '_gaenity_poll_options', true );
                if ( empty( $options ) || count( $options ) < 2 ) {
                    continue;
                }
                ?>
                <div class="gaenity-poll" data-poll="<?php echo esc_attr( $poll->ID ); ?>">
                    <h4><?php echo esc_html( get_the_title( $poll->ID ) ); ?>
                        <?php if ( ! empty( $question ) ) : ?>
                            <span class="gaenity-poll-question"><?php echo esc_html( $question ); ?></span>
                        <?php endif; ?>
                    </h4>
                    <form class="gaenity-form gaenity-ajax-form" data-refresh="gaenity-polls">
                        <input type="hidden" name="action" value="gaenity_poll_vote" />
                        <input type="hidden" name="poll_id" value="<?php echo esc_attr( $poll->ID ); ?>" />
                        <?php wp_nonce_field( 'gaenity-community', 'gaenity_nonce' ); ?>
                        <div class="gaenity-poll-options">
                            <?php foreach ( $options as $key => $label ) : ?>
                                <label class="gaenity-radio">
                                    <input type="radio" name="option" value="<?php echo esc_attr( $key ); ?>" required /> <?php echo esc_html( $label ); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p>
                            <label for="gaenity_poll_region_<?php echo esc_attr( $poll->ID ); ?>"><?php esc_html_e( 'Region', 'gaenity-community' ); ?></label>
                            <select id="gaenity_poll_region_<?php echo esc_attr( $poll->ID ); ?>" name="region" required>
                                <option value=""><?php esc_html_e( 'Select region', 'gaenity-community' ); ?></option>
                                <?php foreach ( $this->get_region_options() as $region ) : ?>
                                    <option value="<?php echo esc_attr( $region ); ?>"><?php echo esc_html( $region ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <p>
                            <label for="gaenity_poll_industry_<?php echo esc_attr( $poll->ID ); ?>"><?php esc_html_e( 'Industry', 'gaenity-community' ); ?></label>
                            <select id="gaenity_poll_industry_<?php echo esc_attr( $poll->ID ); ?>" name="industry" required>
                                <option value=""><?php esc_html_e( 'Select industry', 'gaenity-community' ); ?></option>
                                <?php foreach ( $this->get_industry_options() as $industry ) : ?>
                                    <option value="<?php echo esc_attr( $industry ); ?>"><?php echo esc_html( $industry ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <p><button type="submit" class="gaenity-button"><?php esc_html_e( 'Submit vote', 'gaenity-community' ); ?></button></p>
                        <div class="gaenity-form-feedback" role="alert" aria-live="polite"></div>
                    </form>
                    <?php echo $this->get_poll_results_markup( $poll->ID, $options ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render expert request form.
     */
    public function render_expert_request_form() {
        ob_start();
        ?>
        <section id="gaenity-ask-expert" class="gaenity-expert-request">
            <h3><?php esc_html_e( 'Ask an Expert', 'gaenity-community' ); ?></h3>
            <p class="gaenity-intro"><?php esc_html_e( 'Need guidance beyond the community? Our vetted experts are here to help. Post your question, set your budget, and get actionable advice. Experts are rated by members and paid fairly for their insights.', 'gaenity-community' ); ?></p>
            <ol class="gaenity-mini-process">
                <li><strong><?php esc_html_e( 'Post your request', 'gaenity-community' ); ?></strong> – <?php esc_html_e( 'Share your challenge in Risk, Finance, or Operations.', 'gaenity-community' ); ?></li>
                <li><strong><?php esc_html_e( 'Connect with an expert', 'gaenity-community' ); ?></strong> – <?php esc_html_e( 'We’ll match you with the right advisor.', 'gaenity-community' ); ?></li>
                <li><strong><?php esc_html_e( 'Pay securely', 'gaenity-community' ); ?></strong> – <?php esc_html_e( 'Experts are compensated, and you get clear answers.', 'gaenity-community' ); ?></li>
            </ol>
            <form class="gaenity-form gaenity-ajax-form">
                <input type="hidden" name="action" value="gaenity_expert_request" />
                <?php wp_nonce_field( 'gaenity-community', 'gaenity_nonce' ); ?>
                <p>
                    <label for="gaenity_request_name"><?php esc_html_e( 'Your name', 'gaenity-community' ); ?></label>
                    <input type="text" id="gaenity_request_name" name="name" required />
                </p>
                <p>
                    <label for="gaenity_request_email"><?php esc_html_e( 'Email', 'gaenity-community' ); ?></label>
                    <input type="email" id="gaenity_request_email" name="email" required />
                </p>
                <p>
                    <label for="gaenity_request_role"><?php esc_html_e( 'Role', 'gaenity-community' ); ?></label>
                    <select id="gaenity_request_role" name="role" required>
                        <option value=""><?php esc_html_e( 'Select role', 'gaenity-community' ); ?></option>
                        <option value="Business Owner"><?php esc_html_e( 'Business Owner', 'gaenity-community' ); ?></option>
                        <option value="Professional"><?php esc_html_e( 'Professional', 'gaenity-community' ); ?></option>
                    </select>
                </p>
                <p>
                    <label for="gaenity_request_region"><?php esc_html_e( 'Region', 'gaenity-community' ); ?></label>
                    <select id="gaenity_request_region" name="region" required>
                        <option value=""><?php esc_html_e( 'Select region', 'gaenity-community' ); ?></option>
                        <?php foreach ( $this->get_region_options() as $region ) : ?>
                            <option value="<?php echo esc_attr( $region ); ?>"><?php echo esc_html( $region ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="gaenity_request_country"><?php esc_html_e( 'Country', 'gaenity-community' ); ?></label>
                    <input type="text" id="gaenity_request_country" name="country" required />
                </p>
                <p>
                    <label for="gaenity_request_industry"><?php esc_html_e( 'Industry', 'gaenity-community' ); ?></label>
                    <select id="gaenity_request_industry" name="industry" required>
                        <option value=""><?php esc_html_e( 'Select industry', 'gaenity-community' ); ?></option>
                        <?php foreach ( $this->get_industry_options() as $industry ) : ?>
                            <option value="<?php echo esc_attr( $industry ); ?>"><?php echo esc_html( $industry ); ?></option>
                        <?php endforeach; ?>
                        <option value="Other"><?php esc_html_e( 'Other', 'gaenity-community' ); ?></option>
                    </select>
                </p>
                <p>
                    <label for="gaenity_request_challenge"><?php esc_html_e( 'Challenge / Question', 'gaenity-community' ); ?></label>
                    <select id="gaenity_request_challenge" name="challenge" required>
                        <option value=""><?php esc_html_e( 'Select challenge area', 'gaenity-community' ); ?></option>
                        <?php foreach ( $this->get_challenge_options() as $challenge ) : ?>
                            <option value="<?php echo esc_attr( $challenge ); ?>"><?php echo esc_html( $challenge ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="gaenity_request_description"><?php esc_html_e( 'Describe your challenge', 'gaenity-community' ); ?></label>
                    <textarea id="gaenity_request_description" name="description" rows="4" required></textarea>
                </p>
                <p>
                    <label for="gaenity_request_budget"><?php esc_html_e( 'Preferred budget', 'gaenity-community' ); ?></label>
                    <input type="text" id="gaenity_request_budget" name="budget" placeholder="<?php esc_attr_e( 'e.g. $150 for email advice', 'gaenity-community' ); ?>" required />
                </p>
                <p>
                    <label for="gaenity_request_preference"><?php esc_html_e( 'Preferred engagement', 'gaenity-community' ); ?></label>
                    <select id="gaenity_request_preference" name="preference" required>
                        <option value=""><?php esc_html_e( 'Select option', 'gaenity-community' ); ?></option>
                        <option value="email"><?php esc_html_e( 'Email consultation', 'gaenity-community' ); ?></option>
                        <option value="virtual_meeting"><?php esc_html_e( '30 minute virtual meeting', 'gaenity-community' ); ?></option>
                    </select>
                </p>
                <p>
                    <button type="submit" class="gaenity-button"><?php esc_html_e( 'Submit request', 'gaenity-community' ); ?></button>
                </p>
                <div class="gaenity-form-feedback" role="alert" aria-live="polite"></div>
            </form>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render expert registration form.
     */
    public function render_expert_register_form() {
        ob_start();
        ?>
        <section id="gaenity-register-expert" class="gaenity-expert-register">
            <h3><?php esc_html_e( 'Register as an Expert', 'gaenity-community' ); ?></h3>
            <p class="gaenity-intro"><?php esc_html_e( 'Share your experience with entrepreneurs who need practical advice in risk, finance, and operations. Approved experts receive tailored requests and fair compensation.', 'gaenity-community' ); ?></p>
            <form class="gaenity-form gaenity-ajax-form">
                <input type="hidden" name="action" value="gaenity_expert_register" />
                <?php wp_nonce_field( 'gaenity-community', 'gaenity_nonce' ); ?>
                <p>
                    <label for="gaenity_expert_name"><?php esc_html_e( 'Full name', 'gaenity-community' ); ?></label>
                    <input type="text" id="gaenity_expert_name" name="name" required />
                </p>
                <p>
                    <label for="gaenity_expert_email"><?php esc_html_e( 'Email', 'gaenity-community' ); ?></label>
                    <input type="email" id="gaenity_expert_email" name="email" required />
                </p>
                <p>
                    <label for="gaenity_expert_expertise"><?php esc_html_e( 'Areas of expertise', 'gaenity-community' ); ?></label>
                    <textarea id="gaenity_expert_expertise" name="expertise" rows="3" required></textarea>
                </p>
                <p>
                    <label for="gaenity_expert_linkedin"><?php esc_html_e( 'LinkedIn or portfolio URL', 'gaenity-community' ); ?></label>
                    <input type="url" id="gaenity_expert_linkedin" name="profile_url" required />
                </p>
                <p>
                    <button type="submit" class="gaenity-button"><?php esc_html_e( 'Submit application', 'gaenity-community' ); ?></button>
                </p>
                <div class="gaenity-form-feedback" role="alert" aria-live="polite"></div>
            </form>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render contact form.
     */
    public function render_contact_form() {
        ob_start();
        ?>
        <section class="gaenity-contact">
            <h3><?php esc_html_e( 'We welcome questions, ideas, and collaboration. Send a message', 'gaenity-community' ); ?></h3>
            <form class="gaenity-form gaenity-ajax-form">
                <input type="hidden" name="action" value="gaenity_contact_submit" />
                <?php wp_nonce_field( 'gaenity-community', 'gaenity_nonce' ); ?>
                <p>
                    <label for="gaenity_contact_name"><?php esc_html_e( 'Name', 'gaenity-community' ); ?></label>
                    <input type="text" id="gaenity_contact_name" name="name" required />
                </p>
                <p>
                    <label for="gaenity_contact_email"><?php esc_html_e( 'Email', 'gaenity-community' ); ?></label>
                    <input type="email" id="gaenity_contact_email" name="email" required />
                </p>
                <p>
                    <label for="gaenity_contact_subject"><?php esc_html_e( 'Subject', 'gaenity-community' ); ?></label>
                    <input type="text" id="gaenity_contact_subject" name="subject" required />
                </p>
                <p>
                    <label for="gaenity_contact_message"><?php esc_html_e( 'Message', 'gaenity-community' ); ?></label>
                    <textarea id="gaenity_contact_message" name="message" rows="4" required></textarea>
                </p>
                <p class="gaenity-checkbox">
                    <label>
                        <input type="checkbox" name="updates" value="1" />
                        <?php esc_html_e( 'I agree to receive updates from Gaenity', 'gaenity-community' ); ?>
                    </label>
                </p>
                <p>
                    <button type="submit" class="gaenity-button"><?php esc_html_e( 'Send message', 'gaenity-community' ); ?></button>
                </p>
                <div class="gaenity-form-feedback" role="alert" aria-live="polite"></div>
            </form>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render community chat interface.
     */
    public function render_chat_interface() {
        $messages = $this->get_chat_messages();
        $max_messages = apply_filters( 'gaenity_chat_max_messages', 30 );
        ob_start();
        ?>
        <section class="gaenity-chat">
            <h3><?php esc_html_e( 'Community Chat', 'gaenity-community' ); ?></h3>
            <div class="gaenity-chat-window" data-max-messages="<?php echo esc_attr( $max_messages ); ?>">
                <ul class="gaenity-chat-messages">
                    <?php foreach ( $messages as $message ) : ?>
                        <li>
                            <div class="gaenity-chat-meta">
                                <strong><?php echo esc_html( $message['display_name'] ); ?></strong>
                                <?php if ( ! empty( $message['role'] ) ) : ?>
                                    <span class="gaenity-badge"><?php echo esc_html( $message['role'] ); ?></span>
                                <?php endif; ?>
                                <span class="gaenity-chat-timestamp"><?php echo esc_html( $message['time'] ); ?></span>
                            </div>
                            <div class="gaenity-chat-body"><?php echo wp_kses_post( wpautop( $message['message'] ) ); ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <form class="gaenity-form gaenity-chat-form gaenity-ajax-form" data-refresh="gaenity-chat">
                <input type="hidden" name="action" value="gaenity_chat_send" />
                <?php wp_nonce_field( 'gaenity-community', 'gaenity_nonce' ); ?>
                <p>
                    <label for="gaenity_chat_display"><?php esc_html_e( 'Display name', 'gaenity-community' ); ?></label>
                    <input type="text" id="gaenity_chat_display" name="display_name" placeholder="<?php esc_attr_e( 'Optional if logged in', 'gaenity-community' ); ?>" />
                </p>
                <p>
                    <label for="gaenity_chat_role"><?php esc_html_e( 'Role', 'gaenity-community' ); ?></label>
                    <select id="gaenity_chat_role" name="role">
                        <option value=""><?php esc_html_e( 'Select role', 'gaenity-community' ); ?></option>
                        <option value="Business Owner"><?php esc_html_e( 'Business Owner', 'gaenity-community' ); ?></option>
                        <option value="Professional"><?php esc_html_e( 'Professional', 'gaenity-community' ); ?></option>
                        <option value="Forum Expert"><?php esc_html_e( 'Forum Expert', 'gaenity-community' ); ?></option>
                    </select>
                </p>
                <p>
                    <label for="gaenity_chat_region"><?php esc_html_e( 'Region', 'gaenity-community' ); ?></label>
                    <select id="gaenity_chat_region" name="region">
                        <option value=""><?php esc_html_e( 'Select region', 'gaenity-community' ); ?></option>
                        <?php foreach ( $this->get_region_options() as $region ) : ?>
                            <option value="<?php echo esc_attr( $region ); ?>"><?php echo esc_html( $region ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="gaenity_chat_industry"><?php esc_html_e( 'Industry', 'gaenity-community' ); ?></label>
                    <select id="gaenity_chat_industry" name="industry">
                        <option value=""><?php esc_html_e( 'Select industry', 'gaenity-community' ); ?></option>
                        <?php foreach ( $this->get_industry_options() as $industry ) : ?>
                            <option value="<?php echo esc_attr( $industry ); ?>"><?php echo esc_html( $industry ); ?></option>
                        <?php endforeach; ?>
                        <option value="Other"><?php esc_html_e( 'Other', 'gaenity-community' ); ?></option>
                    </select>
                </p>
                <p>
                    <label for="gaenity_chat_challenge"><?php esc_html_e( 'Challenge', 'gaenity-community' ); ?></label>
                    <select id="gaenity_chat_challenge" name="challenge">
                        <option value=""><?php esc_html_e( 'Select challenge', 'gaenity-community' ); ?></option>
                        <?php foreach ( $this->get_challenge_options() as $challenge ) : ?>
                            <option value="<?php echo esc_attr( $challenge ); ?>"><?php echo esc_html( $challenge ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="gaenity-checkbox">
                    <label>
                        <input type="checkbox" name="anonymous" value="1" />
                        <?php esc_html_e( 'Post anonymously', 'gaenity-community' ); ?>
                    </label>
                </p>
                <p>
                    <label for="gaenity_chat_message"><?php esc_html_e( 'Message', 'gaenity-community' ); ?></label>
                    <textarea id="gaenity_chat_message" name="message" rows="3" required></textarea>
                </p>
                <p>
                    <button type="submit" class="gaenity-button"><?php esc_html_e( 'Send', 'gaenity-community' ); ?></button>
                </p>
                <div class="gaenity-form-feedback" role="alert" aria-live="polite"></div>
            </form>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Return predefined industry options.
     */
    protected function get_industry_options() {
        return array(
            'Retail & e-commerce',
            'Manufacturing',
            'Services',
            'Health & wellness',
            'Food & hospitality',
            'Technology & startups',
            'Agriculture',
            'Finance/Financial Services',
            'Nonprofits & education',
        );
    }

    /**
     * Return region options.
     */
    protected function get_region_options() {
        return array(
            'Africa',
            'North America',
            'Europe',
            'Middle East',
            'Asia Pacific',
            'Latin America',
        );
    }

    /**
     * Return challenge options.
     */
    protected function get_challenge_options() {
        return array(
            'Cash flow',
            'Supplier/customer risk',
            'Compliance',
            'Operations',
            'People',
            'Sales/marketing',
            'Technology & data',
            'Financial Controls',
            'Credit',
            'Fraud',
        );
    }

    /**
     * Render filter options for discussion board.
     */
    protected function render_filter_options( $type ) {
        $taxonomy = 'gaenity_' . $type;
        $terms    = get_terms(
            array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
            )
        );

        $selected = isset( $_GET[ $type ] ) ? sanitize_text_field( wp_unslash( $_GET[ $type ] ) ) : '';

        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %3$s>%2$s</option>',
                    esc_attr( $term->name ),
                    esc_html( $term->name ),
                    selected( $selected, $term->name, false )
                );
            }
        }
    }

    /**
     * Build discussion meta summary text.
     */
    protected function get_discussion_meta_summary( $post_id ) {
        $parts = array();
        $region = wp_get_post_terms( $post_id, 'gaenity_region', array( 'fields' => 'names' ) );
        if ( ! empty( $region ) ) {
            $parts[] = sprintf( __( 'Region: %s', 'gaenity-community' ), implode( ', ', $region ) );
        }
        $industry = wp_get_post_terms( $post_id, 'gaenity_industry', array( 'fields' => 'names' ) );
        if ( ! empty( $industry ) ) {
            $parts[] = sprintf( __( 'Industry: %s', 'gaenity-community' ), implode( ', ', $industry ) );
        }
        $challenge = wp_get_post_terms( $post_id, 'gaenity_challenge', array( 'fields' => 'names' ) );
        if ( ! empty( $challenge ) ) {
            $parts[] = sprintf( __( 'Challenge: %s', 'gaenity-community' ), implode( ', ', $challenge ) );
        }
        $country = get_post_meta( $post_id, '_gaenity_country', true );
        if ( $country ) {
            $parts[] = sprintf( __( 'Country: %s', 'gaenity-community' ), $country );
        }

        return implode( ' | ', $parts );
    }

    /**
     * Render pagination links.
     */
    protected function render_pagination( WP_Query $query ) {
        $links = paginate_links(
            array(
                'total'   => $query->max_num_pages,
                'current' => max( 1, get_query_var( 'paged' ) ),
                'type'    => 'list',
                'prev_text' => __( 'Previous', 'gaenity-community' ),
                'next_text' => __( 'Next', 'gaenity-community' ),
            )
        );

        if ( $links ) {
            echo '<nav class="gaenity-pagination">' . wp_kses_post( $links ) . '</nav>';
        }
    }

    /**
     * Generate poll results markup.
     */
    protected function get_poll_results_markup( $poll_id, $options ) {
        $counts = $this->get_poll_vote_counts( $poll_id );
        $total  = array_sum( $counts );

        ob_start();
        ?>
        <div class="gaenity-poll-results">
            <h5><?php esc_html_e( 'Current results', 'gaenity-community' ); ?></h5>
            <ul>
                <?php foreach ( $options as $key => $label ) :
                    $count = isset( $counts[ $key ] ) ? (int) $counts[ $key ] : 0;
                    $percentage = $total ? round( ( $count / $total ) * 100 ) : 0;
                    ?>
                    <li>
                        <span class="gaenity-result-label"><?php echo esc_html( $label ); ?></span>
                        <span class="gaenity-result-value"><?php echo esc_html( sprintf( '%d%% (%d)', $percentage, $count ) ); ?></span>
                        <span class="gaenity-result-bar" style="width: <?php echo esc_attr( $percentage ); ?>%"></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Count votes per option.
     */
    protected function get_poll_vote_counts( $poll_id ) {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( 'SELECT option_key, COUNT(*) as votes FROM ' . $wpdb->prefix . 'gaenity_poll_votes WHERE poll_id = %d GROUP BY option_key', $poll_id ), ARRAY_A );
        $counts  = array();
        if ( $results ) {
            foreach ( $results as $row ) {
                $counts[ $row['option_key'] ] = (int) $row['votes'];
            }
        }
        return $counts;
    }

    /**
     * Fetch latest chat messages.
     */
    protected function get_chat_messages() {
        global $wpdb;
        $rows = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'gaenity_chat_messages ORDER BY id DESC LIMIT 30', ARRAY_A );
        $messages = array();
        if ( $rows ) {
            foreach ( array_reverse( $rows ) as $row ) {
                $messages[] = array(
                    'display_name' => $row['display_name'],
                    'role'         => $row['role'],
                    'message'      => wp_kses_post( $row['message'] ),
                    'time'         => mysql2date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $row['created_at'] ),
                );
            }
        }
        return $messages;
    }
}

endif;

