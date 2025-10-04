<?php
/**
 * Main plugin class for the Gaeinity Community Suite.
 *
 * The rewritten version focuses on stable shortcode rendering so every front
 * end block can be embedded with Elementor, the Block Editor, or classic
 * shortcodes without producing server errors. The markup intentionally keeps
 * styles light so the active theme dictates typography and colours.
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
     */
    const VERSION = '2.1.0';

    /**
     * Run on plugin activation.
     */
    public static function activate() {
        $plugin = new self();
        $plugin->define_constants();
        $plugin->register_post_types();
        $plugin->maybe_seed_content();
        flush_rewrite_rules();
    }

    /**
     * Run on plugin deactivation.
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Bootstraps plugin hooks.
     */
    public function init() {
        $this->define_constants();

        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_shortcodes' ) );
        add_action( 'init', array( $this, 'seed_content_on_init' ), 20 );

        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

        add_action( 'admin_post_gaenity_form_submit', array( $this, 'handle_form_submission' ) );
        add_action( 'admin_post_nopriv_gaenity_form_submit', array( $this, 'handle_form_submission' ) );

        add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
        add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
    }

    /**
     * Define path related constants.
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
     * Register post types used to persist community information.
     */
    public function register_post_types() {
        register_post_type(
            'gaenity_resource',
            array(
                'labels' => array(
                    'name'          => __( 'Resources', 'gaenity-community' ),
                    'singular_name' => __( 'Resource', 'gaenity-community' ),
                ),
                'public'       => true,
                'has_archive'  => false,
                'show_in_rest' => true,
                'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
                'rewrite'      => array( 'slug' => 'gaenity-resource' ),
            )
        );

        register_post_type(
            'gaenity_discussion',
            array(
                'labels' => array(
                    'name'          => __( 'Community Discussions', 'gaenity-community' ),
                    'singular_name' => __( 'Discussion', 'gaenity-community' ),
                ),
                'public'       => true,
                'has_archive'  => false,
                'show_in_rest' => true,
                'supports'     => array( 'title', 'editor', 'author', 'comments' ),
                'rewrite'      => array( 'slug' => 'gaenity-discussion' ),
            )
        );

        register_post_type(
            'gaenity_submission',
            array(
                'labels' => array(
                    'name'          => __( 'Community Entries', 'gaenity-community' ),
                    'singular_name' => __( 'Community Entry', 'gaenity-community' ),
                ),
                'public'       => false,
                'show_ui'      => current_user_can( 'manage_options' ),
                'show_in_menu' => true,
                'supports'     => array( 'title', 'custom-fields' ),
                'menu_icon'    => 'dashicons-groups',
            )
        );
    }

    /**
     * Register front-end assets. They only set spacing/layout so themes handle
     * typography.
     */
    public function register_assets() {
        wp_register_style(
            'gaenity-community-frontend',
            GAENITY_COMMUNITY_ASSETS . 'css/frontend.css',
            array(),
            self::VERSION
        );

        wp_register_script(
            'gaenity-community-frontend',
            GAENITY_COMMUNITY_ASSETS . 'js/frontend.js',
            array(),
            self::VERSION,
            true
        );

        wp_localize_script(
            'gaenity-community-frontend',
            'gaenityCommunity',
            array(
                'autoHideDelay' => 6000,
            )
        );
    }

    /**
     * Enqueue assets when a shortcode renders.
     */
    protected function enqueue_frontend_assets() {
        wp_enqueue_style( 'gaenity-community-frontend' );
        wp_enqueue_script( 'gaenity-community-frontend' );
    }

    /**
     * Register Elementor widgets safely.
     */
    public function register_elementor_widgets( $widgets_manager ) {
        if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\\Elementor\\Widget_Base' ) ) {
            return;
        }

        require_once GAENITY_COMMUNITY_PATH . 'includes/class-gaenity-elementor-widget.php';

        $widget = new Gaeinity_Community_Elementor_Widget( $this );

        if ( method_exists( $widgets_manager, 'register' ) ) {
            $widgets_manager->register( $widget );
        } elseif ( method_exists( $widgets_manager, 'register_widget_type' ) ) {
            $widgets_manager->register_widget_type( $widget );
        }
    }

    /**
     * Register Elementor category when Elementor is ready.
     */
    public function register_elementor_category( $elements_manager ) {
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        $elements_manager->add_category(
            'gaenity-community',
            array(
                'title' => __( 'Gaeinity Community', 'gaenity-community' ),
            )
        );
    }

    /**
     * Register shortcode handlers.
     */
    public function register_shortcodes() {
        add_shortcode( 'gaenity_community', array( $this, 'shortcode_router' ) );

        $blocks = array(
            'community_home',
            'resources',
            'register',
            'login',
            'discussion_form',
            'discussion_board',
            'polls',
            'expert_request',
            'expert_register',
            'contact',
            'chat',
        );

        foreach ( $blocks as $block ) {
            add_shortcode(
                'gaenity_' . $block,
                function ( $atts = array() ) use ( $block ) {
                    return $this->render_block( $block, $atts );
                }
            );
        }
    }

    /**
     * Seed content on init for upgrades where activation does not re-run.
     */
    public function seed_content_on_init() {
        if ( get_option( 'gaenity_community_seeded', false ) ) {
            return;
        }

        $this->maybe_seed_content();
    }

    /**
     * Seed example content on activation so site owners can customise quickly.
     */
    protected function maybe_seed_content() {
        if ( get_option( 'gaenity_community_seeded', false ) ) {
            return;
        }

        $author = get_current_user_id();

        if ( ! $author ) {
            $admins = get_users(
                array(
                    'role'   => 'administrator',
                    'fields' => 'ID',
                    'number' => 1,
                )
            );

            if ( ! empty( $admins ) ) {
                $author = (int) $admins[0];
            }
        }

        if ( ! $author ) {
            $author = 1;
        }

        $resources = array(
            array(
                'title'   => __( 'Risk Register Template', 'gaenity-community' ),
                'excerpt' => __( 'Track likelihood, impact, and owners to stay ahead of operational and financial risks.', 'gaenity-community' ),
                'content' => __( 'Use this spreadsheet as a starting point for capturing risks across your business. Update the status and mitigation actions regularly, and assign owners so nothing falls through the cracks.', 'gaenity-community' ),
            ),
            array(
                'title'   => __( 'Supplier Vetting Checklist', 'gaenity-community' ),
                'excerpt' => __( 'Assess new suppliers across compliance, reliability, and financial stability before onboarding.', 'gaenity-community' ),
                'content' => __( 'The checklist guides you through reference checks, documentation reviews, and contingency planning so you can confidently select and manage suppliers.', 'gaenity-community' ),
            ),
            array(
                'title'   => __( '30-Day Cash Flow Tracker', 'gaenity-community' ),
                'excerpt' => __( 'Stay close to daily cash inflows and outflows and identify gaps before they become urgent.', 'gaenity-community' ),
                'content' => __( 'Populate the tracker with your recurring revenue and expense streams. Use the projection tab to compare planned vs. actual results and identify corrective actions.', 'gaenity-community' ),
            ),
        );

        foreach ( $resources as $resource ) {
            if ( get_page_by_title( $resource['title'], OBJECT, 'gaenity_resource' ) ) {
                continue;
            }

            wp_insert_post(
                array(
                    'post_type'    => 'gaenity_resource',
                    'post_status'  => 'publish',
                    'post_title'   => $resource['title'],
                    'post_excerpt' => $resource['excerpt'],
                    'post_content' => $resource['content'],
                    'post_author'  => $author,
                )
            );
        }

        $discussions = array(
            array(
                'title'   => __( 'How are you mitigating supplier delays this quarter?', 'gaenity-community' ),
                'content' => __( 'We have experienced repeat delays from a logistics partner. Looking for practical approaches to diversify without increasing costs dramatically. Any experiences from retail or manufacturing teams?', 'gaenity-community' ),
            ),
            array(
                'title'   => __( 'What KPIs are you using to monitor cash burn?', 'gaenity-community' ),
                'content' => __( 'Our services firm is scaling quickly and I want to track burn rate in a way that aligns finance and operations. Curious which dashboards or templates have worked for you.', 'gaenity-community' ),
            ),
        );

        foreach ( $discussions as $discussion ) {
            if ( get_page_by_title( $discussion['title'], OBJECT, 'gaenity_discussion' ) ) {
                continue;
            }

            wp_insert_post(
                array(
                    'post_type'    => 'gaenity_discussion',
                    'post_status'  => 'publish',
                    'post_title'   => $discussion['title'],
                    'post_content' => $discussion['content'],
                    'post_author'  => $author,
                )
            );
        }

        $page = get_page_by_path( 'gaenity-community' );

        if ( ! $page ) {
            $sections = array(
                '[gaenity_community block="community_home"]',
                '[gaenity_community block="resources"]',
                '[gaenity_community block="discussion_board"]',
                '[gaenity_community block="polls"]',
                '[gaenity_community block="expert_request"]',
                '[gaenity_community block="expert_register"]',
                '[gaenity_community block="contact"]',
            );

            wp_insert_post(
                array(
                    'post_type'    => 'page',
                    'post_status'  => 'publish',
                    'post_title'   => __( 'Gaeinity Community', 'gaenity-community' ),
                    'post_name'    => 'gaenity-community',
                    'post_content' => implode( "\n\n", $sections ),
                    'post_author'  => $author,
                )
            );
        }

        update_option( 'gaenity_community_seeded', 1 );
    }

    /**
     * Router for [gaenity_community block="..."]
     */
    public function shortcode_router( $atts = array() ) {
        $atts = shortcode_atts(
            array( 'block' => 'community_home' ),
            $atts,
            'gaenity_community'
        );

        return $this->render_block( $atts['block'], $atts );
    }

    /**
     * Render a community block by key.
     */
    public function render_block( $block, $atts = array() ) {
        $this->enqueue_frontend_assets();

        ob_start();

        if ( $message = $this->current_message() ) {
            printf( '<div class="gaenity-notice" data-gaenity-auto-hide="true">%s</div>', esc_html( $message ) );
        }

        switch ( $block ) {
            case 'resources':
                $this->render_resources_block();
                break;
            case 'register':
                $this->render_registration_form();
                break;
            case 'login':
                $this->render_login_form();
                break;
            case 'discussion_form':
                $this->render_discussion_form();
                break;
            case 'discussion_board':
                $this->render_discussion_board();
                break;
            case 'polls':
                $this->render_polls_block();
                break;
            case 'expert_request':
                $this->render_expert_request_form();
                break;
            case 'expert_register':
                $this->render_expert_register_form();
                break;
            case 'contact':
                $this->render_contact_form();
                break;
            case 'chat':
                $this->render_chat_block();
                break;
            case 'community_home':
            default:
                $this->render_home_block();
                break;
        }

        return ob_get_clean();
    }

    /**
     * Render the community hero/home block.
     */
    protected function render_home_block() {
        ?>
        <section class="gaenity-section">
            <header class="gaenity-section__header">
                <p class="gaenity-eyebrow"><?php esc_html_e( 'Community Home', 'gaenity-community' ); ?></p>
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Dedicated space for entrepreneurs to connect and grow.', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'The Gaenity community connects business owners, entrepreneurs, and professionals who want to share practical solutions. Join to ask questions, post challenges, and learn from peers and professionals.', 'gaenity-community' ); ?></p>
                <div class="gaenity-actions">
                    <a class="gaenity-button" href="#gaenity-register"><?php esc_html_e( 'Create your account', 'gaenity-community' ); ?></a>
                    <a class="gaenity-button gaenity-button--outline" href="#gaenity-expert"><?php esc_html_e( 'Ask an Expert', 'gaenity-community' ); ?></a>
                    <a class="gaenity-button gaenity-button--outline" href="#gaenity-expert-register"><?php esc_html_e( 'Register as an Expert', 'gaenity-community' ); ?></a>
                </div>
            </header>
            <div class="gaenity-grid">
                <?php foreach ( $this->home_highlights() as $highlight ) : ?>
                    <article class="gaenity-card">
                        <h3 class="gaenity-card__title"><?php echo esc_html( $highlight['title'] ); ?></h3>
                        <p><?php echo esc_html( $highlight['description'] ); ?></p>
                        <a class="gaenity-card__link" href="<?php echo esc_url( $highlight['url'] ); ?>"><?php echo esc_html( $highlight['cta'] ); ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }

    /**
     * Highlights for the home overview grid.
     */
    protected function home_highlights() {
        return array(
            array(
                'title'       => __( 'Resources Library', 'gaenity-community' ),
                'description' => __( 'Guides, templates, and case studies to support smarter business decisions.', 'gaenity-community' ),
                'cta'         => __( 'Browse resources', 'gaenity-community' ),
                'url'         => '#gaenity-resources',
            ),
            array(
                'title'       => __( 'Community Forums', 'gaenity-community' ),
                'description' => __( 'Join industry and regional discussions to learn from peers.', 'gaenity-community' ),
                'cta'         => __( 'View discussions', 'gaenity-community' ),
                'url'         => '#gaenity-discussions',
            ),
            array(
                'title'       => __( 'Monthly Polls', 'gaenity-community' ),
                'description' => __( 'Vote to provide insights across risk, finance, and operations.', 'gaenity-community' ),
                'cta'         => __( 'See latest poll', 'gaenity-community' ),
                'url'         => '#gaenity-polls',
            ),
        );
    }

    /**
     * Render the resources block.
     */
    protected function render_resources_block() {
        $resources = $this->get_resources();
        ?>
        <section id="gaenity-resources" class="gaenity-section">
            <header class="gaenity-section__header">
                <p class="gaenity-eyebrow"><?php esc_html_e( 'Resources', 'gaenity-community' ); ?></p>
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Practical tools that turn ideas into action.', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'From risk management checklists to finance enablement guides and operational templates, each resource is designed to help businesses build resilience, prepare for growth, and make measurable progress.', 'gaenity-community' ); ?></p>
            </header>
            <div class="gaenity-filter-bar" role="toolbar" aria-label="<?php esc_attr_e( 'Resource filters', 'gaenity-community' ); ?>">
                <button class="gaenity-button" data-gaenity-filter="free"><?php esc_html_e( 'Free resources', 'gaenity-community' ); ?></button>
                <button class="gaenity-button gaenity-button--outline" disabled><?php esc_html_e( 'Paid resources (coming soon)', 'gaenity-community' ); ?></button>
            </div>
            <div class="gaenity-grid">
                <?php foreach ( $resources as $resource ) : ?>
                    <article class="gaenity-card">
                        <?php if ( ! empty( $resource['image'] ) ) : ?>
                            <img class="gaenity-card__image" src="<?php echo esc_url( $resource['image'] ); ?>" alt="" loading="lazy" />
                        <?php endif; ?>
                        <h3 class="gaenity-card__title"><?php echo esc_html( $resource['title'] ); ?></h3>
                        <p><?php echo esc_html( $resource['description'] ); ?></p>
                        <button class="gaenity-button gaenity-button--ghost" data-gaenity-toggle="<?php echo esc_attr( $resource['id'] ); ?>"><?php esc_html_e( 'Download', 'gaenity-community' ); ?></button>
                        <?php $this->render_download_form( $resource ); ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }

    /**
     * Retrieve resource data from custom posts or provide defaults.
     */
    protected function get_resources() {
        $items = array();

        $query = new WP_Query(
            array(
                'post_type'      => 'gaenity_resource',
                'posts_per_page' => 12,
                'orderby'        => 'menu_order date',
                'order'          => 'ASC',
            )
        );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $items[] = array(
                    'id'          => 'resource-' . get_the_ID(),
                    'title'       => get_the_title(),
                    'description' => wp_strip_all_tags( get_the_excerpt() ? get_the_excerpt() : get_the_content() ),
                    'image'       => get_the_post_thumbnail_url( get_the_ID(), 'medium' ),
                );
            }
            wp_reset_postdata();
        }

        if ( empty( $items ) ) {
            $items = array(
                array(
                    'id'          => 'resource-risk-register',
                    'title'       => __( 'Risk register template', 'gaenity-community' ),
                    'description' => __( 'Track and prioritise risks with a ready-to-use spreadsheet.', 'gaenity-community' ),
                    'image'       => '',
                ),
                array(
                    'id'          => 'resource-supplier-checklist',
                    'title'       => __( 'Supplier checklist', 'gaenity-community' ),
                    'description' => __( 'Evaluate supplier resilience and performance with an actionable checklist.', 'gaenity-community' ),
                    'image'       => '',
                ),
                array(
                    'id'          => 'resource-cashflow-tracker',
                    'title'       => __( 'Cash flow tracker', 'gaenity-community' ),
                    'description' => __( 'Monitor inflows and outflows to keep finances on track each month.', 'gaenity-community' ),
                    'image'       => '',
                ),
            );
        }

        return $items;
    }

    /**
     * Output the modal download form used in resource cards.
     */
    protected function render_download_form( $resource ) {
        ?>
        <div class="gaenity-modal" id="<?php echo esc_attr( $resource['id'] ); ?>" hidden>
            <div class="gaenity-modal__dialog" role="dialog" aria-modal="true">
                <button type="button" class="gaenity-modal__close" data-gaenity-close="<?php echo esc_attr( $resource['id'] ); ?>" aria-label="<?php esc_attr_e( 'Close', 'gaenity-community' ); ?>">&times;</button>
                <h3><?php echo esc_html( $resource['title'] ); ?></h3>
                <form class="gaenity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <?php wp_nonce_field( 'gaenity_form_nonce', 'gaenity_form_nonce' ); ?>
                    <input type="hidden" name="action" value="gaenity_form_submit" />
                    <input type="hidden" name="gaenity_form_type" value="resource_download" />
                    <input type="hidden" name="gaenity_resource_name" value="<?php echo esc_attr( $resource['title'] ); ?>" />
                    <input type="hidden" name="gaenity_redirect" value="<?php echo esc_url( $this->current_url() ); ?>" />

                    <?php $this->render_input( 'gaenity_email', __( 'Email', 'gaenity-community' ), 'email', true ); ?>

                    <label>
                        <span><?php esc_html_e( 'Role', 'gaenity-community' ); ?></span>
                        <select name="gaenity_role" required>
                            <option value="">--</option>
                            <option value="business-owner"><?php esc_html_e( 'Business owner', 'gaenity-community' ); ?></option>
                            <option value="professional"><?php esc_html_e( 'Professional', 'gaenity-community' ); ?></option>
                        </select>
                    </label>

                    <label>
                        <span><?php esc_html_e( 'Industry', 'gaenity-community' ); ?></span>
                        <select name="gaenity_industry" required>
                            <?php foreach ( $this->industries() as $value => $label ) : ?>
                                <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label class="gaenity-checkbox">
                        <input type="checkbox" name="gaenity_consent" value="1" required />
                        <span><?php esc_html_e( 'By accessing this resource, you consent to Gaenity storing your details securely to provide the download and send relevant updates. We never sell or share your data.', 'gaenity-community' ); ?></span>
                    </label>

                    <button type="submit" class="gaenity-button"><?php esc_html_e( 'Email me the download', 'gaenity-community' ); ?></button>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Registration form markup.
     */
    protected function render_registration_form() {
        ?>
        <section id="gaenity-register" class="gaenity-section">
            <header class="gaenity-section__header">
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Join the community', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'Complete the form to create your community profile and access private discussions.', 'gaenity-community' ); ?></p>
            </header>
            <form class="gaenity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'gaenity_form_nonce', 'gaenity_form_nonce' ); ?>
                <input type="hidden" name="action" value="gaenity_form_submit" />
                <input type="hidden" name="gaenity_form_type" value="community_register" />
                <input type="hidden" name="gaenity_redirect" value="<?php echo esc_url( $this->current_url() ); ?>" />

                <?php $this->render_input( 'gaenity_full_name', __( 'Full name (not shown publicly)', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_input( 'gaenity_display_name', __( 'Display name', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_input( 'gaenity_email', __( 'Email', 'gaenity-community' ), 'email', true ); ?>
                <?php $this->render_input( 'gaenity_role', __( 'Role / title', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_input( 'gaenity_region', __( 'Region', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_input( 'gaenity_country', __( 'Country', 'gaenity-community' ), 'text', true ); ?>

                <label>
                    <span><?php esc_html_e( 'Industry', 'gaenity-community' ); ?></span>
                    <select name="gaenity_industry" required>
                        <?php foreach ( $this->industries() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <?php $this->render_input( 'gaenity_challenge', __( 'Primary challenge right now', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_textarea( 'gaenity_goals', __( 'Goals for joining', 'gaenity-community' ), true ); ?>

                <label class="gaenity-checkbox">
                    <input type="checkbox" name="gaenity_guidelines" value="1" required />
                    <span><?php esc_html_e( 'I agree to the community guidelines', 'gaenity-community' ); ?></span>
                </label>

                <label class="gaenity-checkbox">
                    <input type="checkbox" name="gaenity_updates" value="1" />
                    <span><?php esc_html_e( 'I agree to receive updates from Gaenity', 'gaenity-community' ); ?></span>
                </label>

                <button type="submit" class="gaenity-button"><?php esc_html_e( 'Submit registration', 'gaenity-community' ); ?></button>
            </form>
        </section>
        <?php
    }

    /**
     * Login section relies on WordPress core form.
     */
    protected function render_login_form() {
        ?>
        <section class="gaenity-section">
            <header class="gaenity-section__header">
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Already a member?', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'Log in with your WordPress account to access the community areas.', 'gaenity-community' ); ?></p>
            </header>
            <?php wp_login_form(); ?>
        </section>
        <?php
    }

    /**
     * Render form for new discussions.
     */
    protected function render_discussion_form() {
        ?>
        <section class="gaenity-section" id="gaenity-discussions">
            <header class="gaenity-section__header">
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Start a new discussion', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'Introduce yourself or raise a challenge to gather insights from peers.', 'gaenity-community' ); ?></p>
            </header>
            <form class="gaenity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'gaenity_form_nonce', 'gaenity_form_nonce' ); ?>
                <input type="hidden" name="action" value="gaenity_form_submit" />
                <input type="hidden" name="gaenity_form_type" value="discussion" />
                <input type="hidden" name="gaenity_redirect" value="<?php echo esc_url( $this->current_url() ); ?>" />

                <?php $this->render_input( 'gaenity_topic', __( 'Discussion title', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_textarea( 'gaenity_message', __( 'Describe your challenge or question', 'gaenity-community' ), true ); ?>

                <label>
                    <span><?php esc_html_e( 'Industry', 'gaenity-community' ); ?></span>
                    <select name="gaenity_industry" required>
                        <?php foreach ( $this->industries() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>
                    <span><?php esc_html_e( 'Region', 'gaenity-community' ); ?></span>
                    <select name="gaenity_region" required>
                        <?php foreach ( $this->regions() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>
                    <span><?php esc_html_e( 'Primary challenge', 'gaenity-community' ); ?></span>
                    <select name="gaenity_challenge" required>
                        <?php foreach ( $this->challenges() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label class="gaenity-checkbox">
                    <input type="checkbox" name="gaenity_anonymous" value="1" />
                    <span><?php esc_html_e( 'Post anonymously', 'gaenity-community' ); ?></span>
                </label>

                <button type="submit" class="gaenity-button"><?php esc_html_e( 'Submit discussion', 'gaenity-community' ); ?></button>
            </form>
        </section>
        <?php
    }

    /**
     * Render list of recent discussions.
     */
    protected function render_discussion_board() {
        $posts = new WP_Query(
            array(
                'post_type'      => 'gaenity_discussion',
                'post_status'    => 'publish',
                'posts_per_page' => 6,
            )
        );
        ?>
        <section class="gaenity-section">
            <header class="gaenity-section__header">
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Recent discussions', 'gaenity-community' ); ?></h2>
            </header>
            <div class="gaenity-list">
                <?php if ( $posts->have_posts() ) : ?>
                    <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
                        <article class="gaenity-list__item">
                            <h3 class="gaenity-list__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="gaenity-list__meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( get_the_author() ); ?></p>
                            <p><?php echo esc_html( wp_trim_words( get_the_content(), 30 ) ); ?></p>
                        </article>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p><?php esc_html_e( 'No discussions yet. Be the first to start one!', 'gaenity-community' ); ?></p>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }

    /**
     * Polls block – lightweight static poll to avoid heavy integrations.
     */
    protected function render_polls_block() {
        ?>
        <section id="gaenity-polls" class="gaenity-section">
            <header class="gaenity-section__header">
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Community polls', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'Collect quick signals from members across regions and industries.', 'gaenity-community' ); ?></p>
            </header>
            <div class="gaenity-card">
                <h3 class="gaenity-card__title"><?php esc_html_e( 'Monthly benchmark poll', 'gaenity-community' ); ?></h3>
                <p><?php esc_html_e( 'Voting is available to logged-in members. Submit your focus for the month.', 'gaenity-community' ); ?></p>
                <?php if ( is_user_logged_in() ) : ?>
                    <form class="gaenity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <?php wp_nonce_field( 'gaenity_form_nonce', 'gaenity_form_nonce' ); ?>
                        <input type="hidden" name="action" value="gaenity_form_submit" />
                        <input type="hidden" name="gaenity_form_type" value="poll" />
                        <input type="hidden" name="gaenity_redirect" value="<?php echo esc_url( $this->current_url() ); ?>" />
                        <fieldset>
                            <legend><?php esc_html_e( 'Which area is your top priority this month?', 'gaenity-community' ); ?></legend>
                            <label class="gaenity-radio">
                                <input type="radio" name="gaenity_choice" value="risk" required />
                                <span><?php esc_html_e( 'Risk management', 'gaenity-community' ); ?></span>
                            </label>
                            <label class="gaenity-radio">
                                <input type="radio" name="gaenity_choice" value="finance" required />
                                <span><?php esc_html_e( 'Finance enablement', 'gaenity-community' ); ?></span>
                            </label>
                            <label class="gaenity-radio">
                                <input type="radio" name="gaenity_choice" value="operations" required />
                                <span><?php esc_html_e( 'Operations', 'gaenity-community' ); ?></span>
                            </label>
                        </fieldset>
                        <button type="submit" class="gaenity-button"><?php esc_html_e( 'Submit vote', 'gaenity-community' ); ?></button>
                    </form>
                <?php else : ?>
                    <p><?php esc_html_e( 'Please log in to participate in polls.', 'gaenity-community' ); ?></p>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }

    /**
     * Ask an Expert form.
     */
    protected function render_expert_request_form() {
        ?>
        <section id="gaenity-expert" class="gaenity-section">
            <header class="gaenity-section__header">
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Ask an Expert', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'Get tailored advice from vetted professionals in risk, finance, and operations.', 'gaenity-community' ); ?></p>
            </header>
            <ol class="gaenity-steps">
                <li><?php esc_html_e( 'Post your request – share your challenge in risk, finance, or operations.', 'gaenity-community' ); ?></li>
                <li><?php esc_html_e( 'Connect with an expert – we will match you with the right advisor.', 'gaenity-community' ); ?></li>
                <li><?php esc_html_e( 'Pay securely – experts are compensated fairly for their insights.', 'gaenity-community' ); ?></li>
            </ol>
            <form class="gaenity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'gaenity_form_nonce', 'gaenity_form_nonce' ); ?>
                <input type="hidden" name="action" value="gaenity_form_submit" />
                <input type="hidden" name="gaenity_form_type" value="expert_request" />
                <input type="hidden" name="gaenity_redirect" value="<?php echo esc_url( $this->current_url() ); ?>" />

                <?php $this->render_input( 'gaenity_full_name', __( 'Your name', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_input( 'gaenity_email', __( 'Email', 'gaenity-community' ), 'email', true ); ?>
                <?php $this->render_input( 'gaenity_budget', __( 'Budget (optional)', 'gaenity-community' ), 'text', false ); ?>
                <?php $this->render_textarea( 'gaenity_message', __( 'Describe your challenge or question', 'gaenity-community' ), true ); ?>

                <label>
                    <span><?php esc_html_e( 'Preferred format', 'gaenity-community' ); ?></span>
                    <select name="gaenity_preference" required>
                        <option value="email"><?php esc_html_e( 'Email summary', 'gaenity-community' ); ?></option>
                        <option value="virtual-meeting"><?php esc_html_e( '30 minute virtual meeting', 'gaenity-community' ); ?></option>
                    </select>
                </label>

                <button type="submit" class="gaenity-button"><?php esc_html_e( 'Submit request', 'gaenity-community' ); ?></button>
            </form>
        </section>
        <?php
    }

    /**
     * Expert registration form.
     */
    protected function render_expert_register_form() {
        ?>
        <section id="gaenity-expert-register" class="gaenity-section">
            <header class="gaenity-section__header">
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Register as an Expert', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'Share your expertise with the community. Provide focus areas and we will review your application.', 'gaenity-community' ); ?></p>
            </header>
            <form class="gaenity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'gaenity_form_nonce', 'gaenity_form_nonce' ); ?>
                <input type="hidden" name="action" value="gaenity_form_submit" />
                <input type="hidden" name="gaenity_form_type" value="expert_register" />
                <input type="hidden" name="gaenity_redirect" value="<?php echo esc_url( $this->current_url() ); ?>" />

                <?php $this->render_input( 'gaenity_full_name', __( 'Full name', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_input( 'gaenity_email', __( 'Email', 'gaenity-community' ), 'email', true ); ?>
                <?php $this->render_input( 'gaenity_role', __( 'Role / headline', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_textarea( 'gaenity_expertise', __( 'Areas of expertise', 'gaenity-community' ), true ); ?>

                <button type="submit" class="gaenity-button"><?php esc_html_e( 'Apply as expert', 'gaenity-community' ); ?></button>
            </form>
        </section>
        <?php
    }

    /**
     * Contact form block.
     */
    protected function render_contact_form() {
        ?>
        <section class="gaenity-section">
            <header class="gaenity-section__header">
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Contact Gaenity', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'We welcome questions, ideas, and collaboration. Send a message below.', 'gaenity-community' ); ?></p>
            </header>
            <form class="gaenity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'gaenity_form_nonce', 'gaenity_form_nonce' ); ?>
                <input type="hidden" name="action" value="gaenity_form_submit" />
                <input type="hidden" name="gaenity_form_type" value="contact" />
                <input type="hidden" name="gaenity_redirect" value="<?php echo esc_url( $this->current_url() ); ?>" />

                <?php $this->render_input( 'gaenity_full_name', __( 'Name', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_input( 'gaenity_email', __( 'Email', 'gaenity-community' ), 'email', true ); ?>
                <?php $this->render_input( 'gaenity_subject', __( 'Subject', 'gaenity-community' ), 'text', true ); ?>
                <?php $this->render_textarea( 'gaenity_message', __( 'Message', 'gaenity-community' ), true ); ?>

                <label class="gaenity-checkbox">
                    <input type="checkbox" name="gaenity_updates" value="1" />
                    <span><?php esc_html_e( 'I agree to receive updates from Gaenity', 'gaenity-community' ); ?></span>
                </label>

                <button type="submit" class="gaenity-button"><?php esc_html_e( 'Send message', 'gaenity-community' ); ?></button>
            </form>
            <footer class="gaenity-contact-footer">
                <p><?php esc_html_e( 'Follow us on:', 'gaenity-community' ); ?></p>
                <ul class="gaenity-inline-list">
                    <li><a href="https://www.instagram.com" target="_blank" rel="noopener">Instagram</a></li>
                    <li><a href="https://www.facebook.com" target="_blank" rel="noopener">Facebook</a></li>
                    <li><a href="https://www.linkedin.com" target="_blank" rel="noopener">LinkedIn</a></li>
                </ul>
            </footer>
        </section>
        <?php
    }

    /**
     * Chat block lists recent short messages.
     */
    protected function render_chat_block() {
        $messages = $this->get_chat_messages();
        ?>
        <section class="gaenity-section">
            <header class="gaenity-section__header">
                <h2 class="gaenity-section__title"><?php esc_html_e( 'Community chat', 'gaenity-community' ); ?></h2>
                <p><?php esc_html_e( 'Share quick wins or ask for immediate tips. Messages are visible to all members.', 'gaenity-community' ); ?></p>
            </header>
            <div class="gaenity-chat">
                <?php if ( ! empty( $messages ) ) : ?>
                    <ul class="gaenity-chat__list">
                        <?php foreach ( $messages as $entry ) : ?>
                            <li class="gaenity-chat__item">
                                <div class="gaenity-chat__meta">
                                    <strong><?php echo esc_html( $entry['name'] ); ?></strong>
                                    <span><?php echo esc_html( $entry['time'] ); ?></span>
                                </div>
                                <p><?php echo esc_html( $entry['message'] ); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p><?php esc_html_e( 'No messages yet. Start the conversation below!', 'gaenity-community' ); ?></p>
                <?php endif; ?>
            </div>
            <form class="gaenity-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'gaenity_form_nonce', 'gaenity_form_nonce' ); ?>
                <input type="hidden" name="action" value="gaenity_form_submit" />
                <input type="hidden" name="gaenity_form_type" value="chat" />
                <input type="hidden" name="gaenity_redirect" value="<?php echo esc_url( $this->current_url() ); ?>" />

                <?php $this->render_input( 'gaenity_display_name', __( 'Display name', 'gaenity-community' ), 'text', false ); ?>
                <?php $this->render_textarea( 'gaenity_message', __( 'Message', 'gaenity-community' ), true ); ?>

                <label class="gaenity-checkbox">
                    <input type="checkbox" name="gaenity_anonymous" value="1" />
                    <span><?php esc_html_e( 'Post anonymously', 'gaenity-community' ); ?></span>
                </label>

                <button type="submit" class="gaenity-button"><?php esc_html_e( 'Send message', 'gaenity-community' ); ?></button>
            </form>
        </section>
        <?php
    }

    /**
     * Render simple text input.
     */
    protected function render_input( $name, $label, $type = 'text', $required = false ) {
        ?>
        <label>
            <span><?php echo esc_html( $label ); ?></span>
            <input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php echo $required ? 'required' : ''; ?> />
        </label>
        <?php
    }

    /**
     * Render textarea.
     */
    protected function render_textarea( $name, $label, $required = false ) {
        ?>
        <label>
            <span><?php echo esc_html( $label ); ?></span>
            <textarea name="<?php echo esc_attr( $name ); ?>" rows="4" <?php echo $required ? 'required' : ''; ?>></textarea>
        </label>
        <?php
    }

    /**
     * Industry dropdown options.
     */
    protected function industries() {
        return array(
            'retail'        => __( 'Retail & e-commerce', 'gaenity-community' ),
            'manufacturing' => __( 'Manufacturing', 'gaenity-community' ),
            'services'      => __( 'Services', 'gaenity-community' ),
            'health'        => __( 'Health & wellness', 'gaenity-community' ),
            'food'          => __( 'Food & hospitality', 'gaenity-community' ),
            'technology'    => __( 'Technology & startups', 'gaenity-community' ),
            'nonprofit'     => __( 'Nonprofits & education', 'gaenity-community' ),
            'finance'       => __( 'Finance / Financial Services', 'gaenity-community' ),
            'agriculture'   => __( 'Agriculture', 'gaenity-community' ),
            'other'         => __( 'Other (describe)', 'gaenity-community' ),
        );
    }

    /**
     * Region options.
     */
    protected function regions() {
        return array(
            'africa'        => __( 'Africa', 'gaenity-community' ),
            'north-america' => __( 'North America', 'gaenity-community' ),
            'europe'        => __( 'Europe', 'gaenity-community' ),
            'middle-east'   => __( 'Middle East', 'gaenity-community' ),
            'asia-pacific'  => __( 'Asia Pacific', 'gaenity-community' ),
            'latin-america' => __( 'Latin America', 'gaenity-community' ),
        );
    }

    /**
     * Challenge taxonomy options.
     */
    protected function challenges() {
        return array(
            'cash-flow'         => __( 'Cash flow', 'gaenity-community' ),
            'supplier-risk'     => __( 'Supplier / customer risk', 'gaenity-community' ),
            'compliance'        => __( 'Compliance', 'gaenity-community' ),
            'operations'        => __( 'Operations', 'gaenity-community' ),
            'people'            => __( 'People', 'gaenity-community' ),
            'sales'             => __( 'Sales / marketing', 'gaenity-community' ),
            'technology'        => __( 'Technology & data', 'gaenity-community' ),
            'financial-controls'=> __( 'Financial controls', 'gaenity-community' ),
            'credit'            => __( 'Credit', 'gaenity-community' ),
            'fraud'             => __( 'Fraud', 'gaenity-community' ),
        );
    }

    /**
     * Handle all front-end form submissions routed through admin-post.php.
     */
    public function handle_form_submission() {
        if ( empty( $_POST['gaenity_form_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['gaenity_form_nonce'] ), 'gaenity_form_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'gaenity-community' ) );
        }

        $type = isset( $_POST['gaenity_form_type'] ) ? sanitize_key( wp_unslash( $_POST['gaenity_form_type'] ) ) : '';
        $redirect = ! empty( $_POST['gaenity_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['gaenity_redirect'] ) ) : home_url();

        $message = __( 'Thanks! Your submission has been received.', 'gaenity-community' );

        switch ( $type ) {
            case 'resource_download':
                $message = __( 'Resource download link will arrive in your inbox.', 'gaenity-community' );
                $this->store_submission( 'resource_download', array(
                    'resource' => sanitize_text_field( wp_unslash( $_POST['gaenity_resource_name'] ?? '' ) ),
                    'email'    => sanitize_email( wp_unslash( $_POST['gaenity_email'] ?? '' ) ),
                    'role'     => sanitize_text_field( wp_unslash( $_POST['gaenity_role'] ?? '' ) ),
                    'industry' => sanitize_text_field( wp_unslash( $_POST['gaenity_industry'] ?? '' ) ),
                    'consent'  => ! empty( $_POST['gaenity_consent'] ),
                ) );
                break;
            case 'community_register':
                $message = __( 'Thanks for registering. Our team will review and follow up shortly.', 'gaenity-community' );
                $this->store_submission( 'registration', array(
                    'full_name'    => sanitize_text_field( wp_unslash( $_POST['gaenity_full_name'] ?? '' ) ),
                    'display_name' => sanitize_text_field( wp_unslash( $_POST['gaenity_display_name'] ?? '' ) ),
                    'email'        => sanitize_email( wp_unslash( $_POST['gaenity_email'] ?? '' ) ),
                    'role'         => sanitize_text_field( wp_unslash( $_POST['gaenity_role'] ?? '' ) ),
                    'region'       => sanitize_text_field( wp_unslash( $_POST['gaenity_region'] ?? '' ) ),
                    'country'      => sanitize_text_field( wp_unslash( $_POST['gaenity_country'] ?? '' ) ),
                    'industry'     => sanitize_text_field( wp_unslash( $_POST['gaenity_industry'] ?? '' ) ),
                    'challenge'    => sanitize_text_field( wp_unslash( $_POST['gaenity_challenge'] ?? '' ) ),
                    'goals'        => sanitize_textarea_field( wp_unslash( $_POST['gaenity_goals'] ?? '' ) ),
                    'guidelines'   => ! empty( $_POST['gaenity_guidelines'] ),
                    'updates'      => ! empty( $_POST['gaenity_updates'] ),
                ) );
                break;
            case 'discussion':
                $message = __( 'Discussion submitted for review.', 'gaenity-community' );
                $this->create_discussion_post();
                break;
            case 'poll':
                $message = __( 'Thanks for voting!', 'gaenity-community' );
                $this->store_submission( 'poll_vote', array(
                    'choice' => sanitize_text_field( wp_unslash( $_POST['gaenity_choice'] ?? '' ) ),
                    'user'   => get_current_user_id(),
                ) );
                break;
            case 'expert_request':
                $message = __( 'Expert request received. Expect a follow-up soon.', 'gaenity-community' );
                $this->store_submission( 'expert_request', array(
                    'name'        => sanitize_text_field( wp_unslash( $_POST['gaenity_full_name'] ?? '' ) ),
                    'email'       => sanitize_email( wp_unslash( $_POST['gaenity_email'] ?? '' ) ),
                    'budget'      => sanitize_text_field( wp_unslash( $_POST['gaenity_budget'] ?? '' ) ),
                    'message'     => sanitize_textarea_field( wp_unslash( $_POST['gaenity_message'] ?? '' ) ),
                    'preference'  => sanitize_text_field( wp_unslash( $_POST['gaenity_preference'] ?? '' ) ),
                ) );
                break;
            case 'expert_register':
                $message = __( 'Expert application submitted.', 'gaenity-community' );
                $this->store_submission( 'expert_register', array(
                    'name'      => sanitize_text_field( wp_unslash( $_POST['gaenity_full_name'] ?? '' ) ),
                    'email'     => sanitize_email( wp_unslash( $_POST['gaenity_email'] ?? '' ) ),
                    'role'      => sanitize_text_field( wp_unslash( $_POST['gaenity_role'] ?? '' ) ),
                    'expertise' => sanitize_textarea_field( wp_unslash( $_POST['gaenity_expertise'] ?? '' ) ),
                ) );
                break;
            case 'contact':
                $message = __( 'Thanks for reaching out. We will reply soon.', 'gaenity-community' );
                $this->store_submission( 'contact', array(
                    'name'    => sanitize_text_field( wp_unslash( $_POST['gaenity_full_name'] ?? '' ) ),
                    'email'   => sanitize_email( wp_unslash( $_POST['gaenity_email'] ?? '' ) ),
                    'subject' => sanitize_text_field( wp_unslash( $_POST['gaenity_subject'] ?? '' ) ),
                    'message' => sanitize_textarea_field( wp_unslash( $_POST['gaenity_message'] ?? '' ) ),
                    'updates' => ! empty( $_POST['gaenity_updates'] ),
                ) );
                break;
            case 'chat':
                $message = __( 'Message shared with the community.', 'gaenity-community' );
                $this->store_submission( 'chat', array(
                    'display_name' => sanitize_text_field( wp_unslash( $_POST['gaenity_display_name'] ?? '' ) ),
                    'message'      => sanitize_textarea_field( wp_unslash( $_POST['gaenity_message'] ?? '' ) ),
                    'anonymous'    => ! empty( $_POST['gaenity_anonymous'] ),
                ) );
                break;
            default:
                do_action( 'gaenity_community_form_' . $type, $_POST );
                break;
        }

        $redirect = add_query_arg( array( 'gaenity_message' => rawurlencode( $message ) ), $redirect );
        wp_safe_redirect( $redirect );
        exit;
    }

    /**
     * Persist submissions as a private custom post type entry.
     */
    protected function store_submission( $type, $data ) {
        $title = sprintf( '%s – %s', ucfirst( str_replace( '_', ' ', $type ) ), $this->format_datetime() );

        wp_insert_post(
            array(
                'post_type'   => 'gaenity_submission',
                'post_status' => 'private',
                'post_title'  => $title,
                'post_author' => get_current_user_id(),
                'meta_input'  => array(
                    'gaenity_submission_type' => $type,
                    'gaenity_submission_data' => $data,
                ),
            )
        );
    }

    /**
     * Format a timestamp respecting site settings and backwards compatibility.
     */
    protected function format_datetime( $timestamp = null ) {
        $timestamp = $timestamp ? (int) $timestamp : current_time( 'timestamp' );
        $format    = trim( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );

        if ( function_exists( 'wp_date' ) ) {
            return wp_date( $format, $timestamp );
        }

        return date_i18n( $format, $timestamp );
    }

    /**
     * Create a discussion post.
     */
    protected function create_discussion_post() {
        $title   = sanitize_text_field( wp_unslash( $_POST['gaenity_topic'] ?? '' ) );
        $content = sanitize_textarea_field( wp_unslash( $_POST['gaenity_message'] ?? '' ) );
        $user_id = get_current_user_id();

        $post_id = wp_insert_post(
            array(
                'post_type'    => 'gaenity_discussion',
                'post_status'  => 'pending',
                'post_title'   => $title,
                'post_content' => $content,
                'post_author'  => $user_id,
                'meta_input'   => array(
                    'gaenity_industry'  => sanitize_text_field( wp_unslash( $_POST['gaenity_industry'] ?? '' ) ),
                    'gaenity_region'    => sanitize_text_field( wp_unslash( $_POST['gaenity_region'] ?? '' ) ),
                    'gaenity_challenge' => sanitize_text_field( wp_unslash( $_POST['gaenity_challenge'] ?? '' ) ),
                    'gaenity_anonymous' => ! empty( $_POST['gaenity_anonymous'] ),
                ),
            )
        );

        if ( ! empty( $_POST['gaenity_anonymous'] ) && $post_id ) {
            update_post_meta( $post_id, 'gaenity_display_name', __( 'Anonymous member', 'gaenity-community' ) );
        }
    }

    /**
     * Fetch latest chat messages stored as submissions.
     */
    protected function get_chat_messages() {
        $query = new WP_Query(
            array(
                'post_type'      => 'gaenity_submission',
                'posts_per_page' => 10,
                'post_status'    => array( 'private', 'publish' ),
                'meta_key'       => 'gaenity_submission_type',
                'meta_value'     => 'chat',
                'orderby'        => 'date',
                'order'          => 'DESC',
            )
        );

        $messages = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $data = get_post_meta( get_the_ID(), 'gaenity_submission_data', true );
                $author_id = (int) get_post_field( 'post_author', get_the_ID() );
                $author_name = $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';
                $messages[] = array(
                    'name'    => ! empty( $data['anonymous'] ) ? __( 'Anonymous', 'gaenity-community' ) : ( $data['display_name'] ?? $author_name ),
                    'message' => $data['message'] ?? '',
                    'time'    => get_the_date() . ' ' . get_the_time(),
                );
            }
            wp_reset_postdata();
        }

        return array_reverse( $messages );
    }

    /**
     * Current URL helper to redirect back to shortcode location.
     */
    protected function current_url() {
        $scheme = is_ssl() ? 'https://' : 'http://';
        $host   = $_SERVER['HTTP_HOST'] ?? parse_url( home_url(), PHP_URL_HOST );
        $uri    = $_SERVER['REQUEST_URI'] ?? '/';

        return esc_url_raw( $scheme . $host . $uri );
    }

    /**
     * Read the current notice message from the query string.
     */
    protected function current_message() {
        if ( empty( $_GET['gaenity_message'] ) ) {
            return '';
        }

        return sanitize_text_field( wp_unslash( $_GET['gaenity_message'] ) );
    }
}

endif;
