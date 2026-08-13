<?php
if (!defined('ABSPATH')) {
    exit;
}

class DNNBPC_Core {
    const VERSION             = '1.0.11';
    const OPT_CORE            = 'dnnbpc_core_v1';
    const OPT_PREFIX          = 'dnnbpc_tpl_opts_v1_';
    const MENU_SLUG           = 'daily-new-nation-bangla-photocard';
    const REST_NAMESPACE      = 'daily-new-nation-bangla/v1';
    const GENERATE_CAPABILITY = 'edit_others_posts';

    private $templates = null;

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front_assets']);
        add_action('the_content', [$this, 'inject_button']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);

        add_shortcode('daily_new_nation_bangla_photocard_button', [$this, 'shortcode_button']);

        add_action('wp_ajax_dnnbpc_get_card', [$this, 'ajax_get_card_data']);
        add_action('wp_ajax_dnnbpc_get_saved_options', [$this, 'ajax_get_saved_options']);
    }

    public static function current_user_can_generate() {
        return current_user_can(self::GENERATE_CAPABILITY);
    }

    public function templates_dir() {
        return plugin_dir_path(__FILE__) . '../templates/';
    }

    public function list_templates() {
        if (is_array($this->templates)) {
            return $this->templates;
        }

        $files = glob($this->templates_dir() . '*.php');
        $out = [];

        if ($files) {
            sort($files, SORT_NATURAL | SORT_FLAG_CASE);
            foreach ($files as $file) {
                $def = include $file;
                if (
                    is_array($def)
                    && !empty($def['key'])
                    && !empty($def['name'])
                    && !empty($def['fields'])
                    && !empty($def['css'])
                    && !empty($def['html'])
                ) {
                    $out[$def['key']] = $def;
                }
            }
        }

        $this->templates = $out;
        return $this->templates;
    }

    private function core_defaults() {
        return [
            'active_template' => array_key_first($this->list_templates()),
            'show_button'     => '1',
            'button_position' => 'end',
            'date_language'   => 'bn',
            'button_text'     => "Download Luna's PhotoCard (1080x1080)",
        ];
    }

    public function get_core() {
        $core = get_option(self::OPT_CORE, []);
        if (!is_array($core)) {
            $core = [];
        }

        return wp_parse_args($core, $this->core_defaults());
    }

    public function get_active_template_key() {
        $core = $this->get_core();
        $tpls = $this->list_templates();
        $first = array_key_first($tpls);

        return (!empty($core['active_template']) && isset($tpls[$core['active_template']])) ? $core['active_template'] : $first;
    }

    public function get_template_options($tpl_key) {
        $opt_key = self::OPT_PREFIX . sanitize_key($tpl_key);
        $opts = get_option($opt_key, []);
        if (!is_array($opts)) {
            $opts = [];
        }

        $tpls = $this->list_templates();
        if (isset($tpls[$tpl_key])) {
            foreach ($tpls[$tpl_key]['fields'] as $key => $field) {
                if (!isset($opts[$key]) && isset($field['default'])) {
                    $opts[$key] = $field['default'];
                }

                if (isset($opts[$key])) {
                    $opts[$key] = $this->sanitize_template_option($opts[$key], $field);
                }
            }
        }

        return $opts;
    }

    private function sanitize_template_option($value, $field) {
        $type = isset($field['type']) ? $field['type'] : 'text';
        $value = is_scalar($value) ? (string) $value : '';

        if ($type === 'image') {
            return esc_url_raw($value);
        }

        if ($type === 'color') {
            $hex = sanitize_hex_color($value);
            if ($hex) {
                return $hex;
            }

            if (preg_match('/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}(?:\s*,\s*(?:0|1|0?\.\d+))?\s*\)$/', $value)) {
                return $value;
            }
        }

        return sanitize_text_field($value);
    }

    public function add_menu() {
        add_menu_page(
            __("Luna's PhotoCard", 'daily-new-nation-bangla-photocard'),
            __("Luna's PhotoCard", 'daily-new-nation-bangla-photocard'),
            'manage_options',
            self::MENU_SLUG,
            [$this, 'settings_page'],
            'dashicons-format-image',
            56
        );

        add_submenu_page(
            self::MENU_SLUG,
            __('Settings', 'daily-new-nation-bangla-photocard'),
            __('Settings', 'daily-new-nation-bangla-photocard'),
            'manage_options',
            self::MENU_SLUG,
            [$this, 'settings_page']
        );
    }

    public function admin_enqueue($hook) {
        if (strpos($hook, self::MENU_SLUG) === false) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('dnnbpc-admin', plugin_dir_url(__FILE__) . '../admin/admin.css', [], self::VERSION);
        wp_enqueue_script('dnnbpc-admin', plugin_dir_url(__FILE__) . '../admin/admin.js', ['jquery', 'wp-color-picker'], self::VERSION, true);

        wp_localize_script('dnnbpc-admin', 'DNNBPCAdmin', [
            'templates' => $this->list_templates(),
            'core'      => $this->get_core(),
            'opts'      => $this->get_template_options($this->get_active_template_key()),
            'nonce'     => wp_create_nonce('dnnbpc_admin'),
            'ajax'      => admin_url('admin-ajax.php'),
            'action'    => 'dnnbpc_get_saved_options',
        ]);
    }

    public function settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to manage this plugin.', 'daily-new-nation-bangla-photocard'));
        }

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nonce = isset($_POST['dnnbpc_nonce']) ? sanitize_text_field(wp_unslash($_POST['dnnbpc_nonce'])) : '';
            if ($nonce && wp_verify_nonce($nonce, 'dnnbpc_save')) {
                $tpls = $this->list_templates();
                $active_template = isset($_POST['active_template']) ? sanitize_key(wp_unslash($_POST['active_template'])) : '';
                if (!isset($tpls[$active_template])) {
                    $active_template = $this->get_active_template_key();
                }

                $show_button = isset($_POST['show_button']) ? '1' : '0';
                $button_position = isset($_POST['button_position']) ? sanitize_key(wp_unslash($_POST['button_position'])) : 'end';
                $button_position = in_array($button_position, ['start', 'end'], true) ? $button_position : 'end';
                $date_language = isset($_POST['date_language']) ? sanitize_key(wp_unslash($_POST['date_language'])) : 'bn';
                $date_language = in_array($date_language, ['bn', 'en'], true) ? $date_language : 'bn';
                $button_text = isset($_POST['button_text']) ? sanitize_text_field(wp_unslash($_POST['button_text'])) : '';

                update_option(self::OPT_CORE, [
                    'active_template' => $active_template,
                    'show_button'     => $show_button,
                    'button_position' => $button_position,
                    'date_language'   => $date_language,
                    'button_text'     => $button_text ?: $this->core_defaults()['button_text'],
                ]);

                $fields = $tpls[$active_template]['fields'];
                $saved  = [];
                foreach ($fields as $fkey => $field) {
                    $input_name = 'tpl_' . $fkey;
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized by field type immediately after wp_unslash().
                    $raw_val = isset($_POST[$input_name]) ? wp_unslash($_POST[$input_name]) : '';
                    $saved[$fkey] = $this->sanitize_template_option($raw_val, $field);
                }

                update_option(self::OPT_PREFIX . $active_template, $saved);
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved.', 'daily-new-nation-bangla-photocard') . '</p></div>';
            } else {
                echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('Security check failed. Please reload and try again.', 'daily-new-nation-bangla-photocard') . '</p></div>';
            }
        }

        $tpls = $this->list_templates();
        $core = $this->get_core();
        $active = $this->get_active_template_key();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e("Luna's PhotoCard", 'daily-new-nation-bangla-photocard'); ?></h1>
            <form method="post">
                <?php wp_nonce_field('dnnbpc_save', 'dnnbpc_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="dnnbpc-active-template"><?php esc_html_e('Design Template', 'daily-new-nation-bangla-photocard'); ?></label></th>
                        <td>
                            <select id="dnnbpc-active-template" name="active_template">
                                <?php foreach ($tpls as $k => $t): ?>
                                    <option value="<?php echo esc_attr($k); ?>" <?php selected($k, $active); ?>>
                                        <?php echo esc_html($t['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Show Auto Button on Post', 'daily-new-nation-bangla-photocard'); ?></th>
                        <td><label><input type="checkbox" name="show_button" value="1" <?php checked($core['show_button'], '1'); ?> /> <?php esc_html_e('Enable', 'daily-new-nation-bangla-photocard'); ?></label></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Button Position', 'daily-new-nation-bangla-photocard'); ?></th>
                        <td>
                            <label><input type="radio" name="button_position" value="start" <?php checked($core['button_position'], 'start'); ?> /> <?php esc_html_e('Start of post', 'daily-new-nation-bangla-photocard'); ?></label>
                            &nbsp;&nbsp;
                            <label><input type="radio" name="button_position" value="end" <?php checked($core['button_position'], 'end'); ?> /> <?php esc_html_e('End of post', 'daily-new-nation-bangla-photocard'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Date Language', 'daily-new-nation-bangla-photocard'); ?></th>
                        <td>
                            <label><input type="radio" name="date_language" value="bn" <?php checked($core['date_language'], 'bn'); ?> /> <?php esc_html_e('Bangla', 'daily-new-nation-bangla-photocard'); ?></label>
                            &nbsp;&nbsp;
                            <label><input type="radio" name="date_language" value="en" <?php checked($core['date_language'], 'en'); ?> /> <?php esc_html_e('English', 'daily-new-nation-bangla-photocard'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="dnnbpc-button-text"><?php esc_html_e('Auto Button Text', 'daily-new-nation-bangla-photocard'); ?></label></th>
                        <td><input type="text" id="dnnbpc-button-text" name="button_text" value="<?php echo esc_attr($core['button_text']); ?>" style="min-width:420px"></td>
                    </tr>
                </table>
                <h2><?php esc_html_e('Template Options', 'daily-new-nation-bangla-photocard'); ?></h2>
                <div id="dnnbpc-dynamic-fields"></div>
                <?php submit_button(__('Save Settings', 'daily-new-nation-bangla-photocard')); ?>
            </form>
            <h2><?php esc_html_e('Shortcode', 'daily-new-nation-bangla-photocard'); ?></h2>
            <p><code>[daily_new_nation_bangla_photocard_button text="Your custom text"]</code></p>
        </div>
        <?php
    }

    public function enqueue_front_assets() {
        if (!is_single()) {
            return;
        }

        if (!self::current_user_can_generate()) {
            return;
        }

        $post_id = get_queried_object_id();
        if (!$post_id || !has_post_thumbnail($post_id)) {
            return;
        }

        wp_enqueue_style('dnnbpc-fonts', plugin_dir_url(__FILE__) . '../assets/fonts.css', [], self::VERSION);
        wp_enqueue_style('dnnbpc-el-btn', plugin_dir_url(__FILE__) . '../assets/elementor-button.css', [], self::VERSION);
        wp_enqueue_script('html2canvas', plugin_dir_url(__FILE__) . '../assets/html2canvas.min.js', [], '1.4.1', true);
        wp_enqueue_script('dnnbpc-front', plugin_dir_url(__FILE__) . '../assets/front.js', ['html2canvas', 'jquery'], self::VERSION, true);
        wp_script_add_data('html2canvas', 'strategy', 'defer');
        wp_script_add_data('dnnbpc-front', 'strategy', 'defer');

        $core = $this->get_core();
        $preload = $this->prepare_card_data($post_id);

        wp_localize_script('dnnbpc-front', 'DNNBPCF', [
            'ajax'        => admin_url('admin-ajax.php'),
            'ajax_action' => 'dnnbpc_get_card',
            'nonce'       => wp_create_nonce('dnnbpc_card'),
            'rest'        => esc_url_raw(rest_url(self::REST_NAMESPACE . '/card')),
            'rest_nonce'  => wp_create_nonce('wp_rest'),
            'preload'     => is_wp_error($preload) ? null : $preload,
            'button_text' => $core['button_text'],
        ]);
    }

    public function inject_button($content) {
        if (!is_single() || !has_post_thumbnail()) {
            return $content;
        }

        if (!self::current_user_can_generate()) {
            return $content;
        }

        $core = $this->get_core();
        if ($core['show_button'] !== '1') {
            return $content;
        }

        $post_id = get_the_ID();
        $btn_html = self::button_html($post_id, $core['button_text']);

        return ($core['button_position'] === 'start') ? $btn_html . $content : $content . $btn_html;
    }

    public function shortcode_button($atts = [], $content = '') {
        if (!self::current_user_can_generate()) {
            return '';
        }

        $atts = shortcode_atts(['text' => ''], $atts, 'daily_new_nation_bangla_photocard_button');
        $post_id = get_the_ID();
        if (!$post_id || !has_post_thumbnail($post_id)) {
            return '';
        }

        $text = $atts['text'] ?: $this->get_core()['button_text'];
        return self::button_html($post_id, $text);
    }

    public static function button_html($post_id, $text, $inner_html = '') {
        if (!self::current_user_can_generate()) {
            return '';
        }

        $label = $inner_html ? $inner_html : esc_html($text);

        return sprintf(
            '<div class="dnnbpc-wrap"><button type="button" class="dnnbpc-generate dnnbpc-elementor-btn button button-primary" data-post-id="%d">%s</button></div>',
            esc_attr($post_id),
            $label
        );
    }

    public function register_rest_routes() {
        register_rest_route(self::REST_NAMESPACE, '/card', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$this, 'rest_get_card_data'],
            'permission_callback' => [$this, 'rest_can_generate_card'],
            'args'                => [
                'post_id' => [
                    'description'       => __('Post ID to render as a photo card.', 'daily-new-nation-bangla-photocard'),
                    'type'              => 'integer',
                    'required'          => true,
                    'minimum'           => 1,
                    'sanitize_callback' => 'absint',
                    'validate_callback' => function($value) {
                        return absint($value) > 0;
                    },
                ],
            ],
        ]);
    }

    public function rest_can_generate_card() {
        if (self::current_user_can_generate()) {
            return true;
        }

        return new WP_Error(
            'dnnbpc_forbidden',
            __('You do not have permission to generate photo cards.', 'daily-new-nation-bangla-photocard'),
            ['status' => 403]
        );
    }

    public function rest_get_card_data(WP_REST_Request $request) {
        $data = $this->prepare_card_data(absint($request->get_param('post_id')));
        if (is_wp_error($data)) {
            return $data;
        }

        return rest_ensure_response($data);
    }

    public function ajax_get_card_data() {
        if (!self::current_user_can_generate()) {
            wp_send_json_error(['message' => __('Forbidden.', 'daily-new-nation-bangla-photocard')], 403);
        }

        check_ajax_referer('dnnbpc_card', 'nonce');

        $post_id = isset($_POST['post_id']) ? absint(wp_unslash($_POST['post_id'])) : 0;
        $data = $this->prepare_card_data($post_id);

        if (is_wp_error($data)) {
            $error_data = $data->get_error_data();
            $status = isset($error_data['status']) ? absint($error_data['status']) : 400;
            wp_send_json_error(['message' => $data->get_error_message()], $status);
        }

        wp_send_json_success($data);
    }

    private function prepare_card_data($post_id) {
        if (!self::current_user_can_generate()) {
            return new WP_Error('dnnbpc_forbidden', __('You do not have permission to generate photo cards.', 'daily-new-nation-bangla-photocard'), ['status' => 403]);
        }

        if (!$post_id) {
            return new WP_Error('dnnbpc_invalid_post', __('Invalid post id.', 'daily-new-nation-bangla-photocard'), ['status' => 400]);
        }

        $post = get_post($post_id);
        if (!$post) {
            return new WP_Error('dnnbpc_post_not_found', __('Post not found.', 'daily-new-nation-bangla-photocard'), ['status' => 404]);
        }

        if (!is_post_publicly_viewable($post) && !current_user_can('read_post', $post_id)) {
            return new WP_Error('dnnbpc_forbidden', __('You do not have permission to render this post.', 'daily-new-nation-bangla-photocard'), ['status' => 403]);
        }

        $image = get_the_post_thumbnail_url($post_id, 'full');
        if (!$image) {
            return new WP_Error('dnnbpc_missing_image', __('No featured image on this post.', 'daily-new-nation-bangla-photocard'), ['status' => 404]);
        }

        $title = $this->decode_text(get_the_title($post_id));
        $date = $this->format_card_date($post_id, $this->get_core()['date_language']);
        $site = $this->decode_text(get_bloginfo('name'));
        $shoulder = get_post_meta($post_id, '_editorial_shoulder', true);
        $shoulder = $this->decode_text(sanitize_text_field(wp_strip_all_tags((string) $shoulder)));
        $subheading = get_post_meta($post_id, '_editorial_subheading', true);
        $subheading = $this->decode_text(sanitize_text_field(wp_strip_all_tags((string) $subheading)));

        $tpl_key = $this->get_active_template_key();
        $opts = $this->get_template_options($tpl_key);
        if (!is_array($opts)) {
            $opts = [];
        }

        $raw_title = trim(wp_strip_all_tags((string) $title));
        $title_metrics = $this->get_text_metrics($raw_title);
        $subheading_metrics = $this->get_text_metrics($subheading);
        $opts['wc'] = $title_metrics['words'];
        $opts['title_chars'] = $title_metrics['chars'];
        $opts['title_units'] = $title_metrics['units'];
        $opts['subheading_wc'] = $subheading_metrics['words'];
        $opts['subheading_chars'] = $subheading_metrics['chars'];
        $opts['subheading_units'] = $subheading_metrics['units'];
        $opts['title_bucket'] = $this->get_title_bucket($title_metrics['words'], $title_metrics['chars'], $title_metrics['units']);
        $opts['subheading_bucket'] = $this->get_subheading_bucket($subheading_metrics['words'], $subheading_metrics['units']);
        $opts['copy_density'] = $this->get_copy_density($shoulder !== '', $subheading !== '', $title_metrics, $subheading_metrics);
        $opts['domain'] = $this->get_site_domain();

        $tpls = $this->list_templates();
        $tpl_def = isset($tpls[$tpl_key]) ? [
            'key'  => $tpls[$tpl_key]['key'],
            'css'  => $tpls[$tpl_key]['css'],
            'html' => $tpls[$tpl_key]['html'],
        ] : null;

        return [
            'post_id'  => $post_id,
            'title'    => $title,
            'date'     => $date,
            'image'    => esc_url_raw($image),
            'site'     => $site,
            'shoulder' => $shoulder,
            'subheading' => $subheading,
            'tpl'      => $tpl_key,
            'opts'     => $opts,
            'tpl_def'  => $tpl_def,
        ];
    }

    private function get_site_domain() {
        $host = wp_parse_url(home_url(), PHP_URL_HOST);
        if (!$host && isset($_SERVER['SERVER_NAME'])) {
            $host = sanitize_text_field(wp_unslash($_SERVER['SERVER_NAME']));
        }

        $host = preg_replace('/^www\./', '', (string) $host);
        return sanitize_text_field($host ?: 'example.com');
    }

    private function decode_text($value) {
        return wp_specialchars_decode(html_entity_decode((string) $value, ENT_QUOTES, get_bloginfo('charset')), ENT_QUOTES);
    }

    private function get_text_metrics($text) {
        $raw_text = trim(wp_strip_all_tags((string) $text));
        $words = preg_split('/\s+/u', $raw_text, -1, PREG_SPLIT_NO_EMPTY);
        $word_count = is_array($words) ? count($words) : 0;
        $charset = get_bloginfo('charset') ?: 'UTF-8';
        $char_count = function_exists('mb_strlen') ? mb_strlen($raw_text, $charset) : strlen($raw_text);

        return [
            'words' => $word_count,
            'chars' => $char_count,
            'units' => $this->get_text_visual_units($raw_text),
        ];
    }

    private function get_text_visual_units($text) {
        $characters = preg_split('//u', trim((string) $text), -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($characters)) {
            return (float) strlen((string) $text);
        }

        $units = 0.0;
        foreach ($characters as $character) {
            if (preg_match('/\p{M}/u', $character)) {
                continue;
            }

            if (preg_match('/\s/u', $character)) {
                $units += 0.45;
                continue;
            }

            if (preg_match('/[\'"‘’“”`.,:;!?।\-\(\)\[\]{}]/u', $character)) {
                $units += 0.25;
                continue;
            }

            if (preg_match('/[০-৯0-9]/u', $character)) {
                $units += 0.72;
                continue;
            }

            if (preg_match('/[A-Za-z]/u', $character)) {
                $units += 0.65;
                continue;
            }

            $units += 1.0;
        }

        return round($units, 2);
    }

    private function get_title_bucket($word_count, $title_length, $title_visual_units) {
        if ($word_count <= 5 && $title_visual_units <= 31) {
            return 'very_short';
        }

        if ($word_count <= 8 && $title_length <= 52) {
            return 'compact';
        }

        if ($word_count <= 8 && $title_length <= 60) {
            return 'wide_short';
        }

        if ($word_count <= 8 && $title_length <= 64) {
            return 'balanced_short';
        }

        if ($word_count <= 8) {
            return 'short';
        }

        if ($word_count <= 14) {
            return 'medium';
        }

        if ($word_count <= 20) {
            return 'long';
        }

        return 'xlong';
    }

    private function get_subheading_bucket($word_count, $visual_units) {
        if ($word_count <= 0 || $visual_units <= 0) {
            return 'none';
        }

        if ($word_count <= 8 && $visual_units <= 42) {
            return 'short';
        }

        if ($word_count <= 14 && $visual_units <= 78) {
            return 'medium';
        }

        return 'long';
    }

    private function get_copy_density($has_shoulder, $has_subheading, $title_metrics, $subheading_metrics) {
        if (!$has_subheading) {
            return $has_shoulder ? 'shoulder' : 'open';
        }

        $visual_load = (float) $title_metrics['units'] + ((float) $subheading_metrics['units'] * 0.72) + ($has_shoulder ? 14 : 0);
        if ($visual_load <= 58 && (int) $title_metrics['words'] <= 8 && (int) $subheading_metrics['words'] <= 8) {
            return 'subheading_relaxed';
        }

        if ($visual_load <= 86) {
            return 'subheading';
        }

        if ($visual_load <= 112) {
            return 'subheading_dense';
        }

        return 'subheading_tight';
    }

    private function format_card_date($post_id, $language) {
        $datetime = $this->get_post_datetime_for_card($post_id);
        $day = $datetime->format('d');
        $month = (int) $datetime->format('n');
        $year = $datetime->format('Y');

        if ($language === 'en') {
            $months = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ];

            return sprintf('%s %s %s', $day, $months[$month], $year);
        }

        $months = [
            1 => 'জানুয়ারি',
            2 => 'ফেব্রুয়ারি',
            3 => 'মার্চ',
            4 => 'এপ্রিল',
            5 => 'মে',
            6 => 'জুন',
            7 => 'জুলাই',
            8 => 'আগস্ট',
            9 => 'সেপ্টেম্বর',
            10 => 'অক্টোবর',
            11 => 'নভেম্বর',
            12 => 'ডিসেম্বর',
        ];

        return $this->to_bangla_digits(sprintf('%s %s %s', $day, $months[$month], $year));
    }

    private function get_post_datetime_for_card($post_id) {
        if (function_exists('get_post_datetime')) {
            $datetime = get_post_datetime($post_id, 'date', wp_timezone());
            if ($datetime instanceof DateTimeInterface) {
                return $datetime;
            }
        }

        $timestamp = get_post_time('U', true, $post_id);
        if (!$timestamp) {
            $timestamp = time();
        }

        return (new DateTimeImmutable('@' . $timestamp))->setTimezone(wp_timezone());
    }

    private function to_bangla_digits($value) {
        return strtr((string) $value, [
            '0' => '০',
            '1' => '১',
            '2' => '২',
            '3' => '৩',
            '4' => '৪',
            '5' => '৫',
            '6' => '৬',
            '7' => '৭',
            '8' => '৮',
            '9' => '৯',
        ]);
    }

    public function ajax_get_saved_options() {
        check_ajax_referer('dnnbpc_admin', '_ajax_nonce', true);

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Forbidden.', 'daily-new-nation-bangla-photocard')], 403);
        }

        $key = isset($_POST['key']) ? sanitize_key(wp_unslash($_POST['key'])) : '';
        if (empty($key)) {
            wp_send_json_error(['message' => __('Missing key.', 'daily-new-nation-bangla-photocard')], 400);
        }

        $opt = get_option(self::OPT_PREFIX . $key, []);
        wp_send_json_success(is_array($opt) ? $opt : []);
    }
}

new DNNBPC_Core();
