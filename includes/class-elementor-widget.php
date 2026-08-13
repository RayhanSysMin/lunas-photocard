<?php
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class DNNBPC_Elementor_Widget extends Widget_Base {
    public function get_name() {
        return 'daily_new_nation_bangla_photocard_button';
    }

    public function get_title() {
        return __("Luna's PhotoCard Button", 'daily-new-nation-bangla-photocard');
    }

    public function get_icon() {
        return 'eicon-camera';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Button Content', 'daily-new-nation-bangla-photocard'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('button_text', [
            'label'   => __('Button Text', 'daily-new-nation-bangla-photocard'),
            'type'    => Controls_Manager::TEXT,
            'default' => __("Download Luna's PhotoCard (1080x1080)", 'daily-new-nation-bangla-photocard'),
        ]);

        $this->add_control('button_icon', [
            'label'   => __('Button Icon', 'daily-new-nation-bangla-photocard'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-camera', 'library' => 'fa-solid'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style', [
            'label' => __('Style', 'daily-new-nation-bangla-photocard'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('text_color', [
            'label'     => __('Text Color', 'daily-new-nation-bangla-photocard'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .dnnbpc-elementor-btn' => 'color: {{VALUE}} !important;'],
        ]);

        $this->add_control('background_color', [
            'label'     => __('Background Color', 'daily-new-nation-bangla-photocard'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .dnnbpc-elementor-btn' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;'],
        ]);

        $this->add_control('hover_background_color', [
            'label'     => __('Hover Background Color', 'daily-new-nation-bangla-photocard'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .dnnbpc-elementor-btn:hover' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'typography',
            'selector' => '{{WRAPPER}} .dnnbpc-elementor-btn',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        if (!DNNBPC_Core::current_user_can_generate()) {
            return;
        }

        $settings = $this->get_settings_for_display();
        $text = !empty($settings['button_text']) ? $settings['button_text'] : __("Download Luna's PhotoCard (1080x1080)", 'daily-new-nation-bangla-photocard');
        $post_id = get_the_ID();

        if (!$post_id || !has_post_thumbnail($post_id)) {
            echo '<p style="color:#777;">' . esc_html__('No featured image found for this post.', 'daily-new-nation-bangla-photocard') . '</p>';
            return;
        }

        ob_start();
        if (!empty($settings['button_icon']['value'])) {
            \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true']);
            echo ' ';
        }
        echo esc_html($text);

        echo DNNBPC_Core::button_html($post_id, $text, ob_get_clean());
    }
}
