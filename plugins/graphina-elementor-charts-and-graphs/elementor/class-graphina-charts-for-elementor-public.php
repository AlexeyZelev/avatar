<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://iqonic.design
 * @since      1.4.0
 *
 * @package    Graphina_Charts_For_Elementor
 * @subpackage Graphina_Charts_For_Elementor/public
 */

use Elementor\Plugin;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Graphina_Charts_For_Elementor
 * @subpackage Graphina_Charts_For_Elementor/public
 * @author     Iqonic Design < hello@iqonic.design>
 */
class Graphina_Charts_For_Elementor_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.4.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.4.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $helper;
    private $all_chart = ['area_chart', 'bubble_chart', 'candle_chart', 'column_chart', 'donut_chart', 'heatmap_chart', 'line_chart', 'pie_chart', 'polar_chart', 'radar_chart', 'radial_chart', 'timeline_chart'];

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.4.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin-facing side of the site.
     *
     * @since    1.4.0
     */
    public function admin_enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Graphina_Charts_For_Elementor_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Graphina_Charts_For_Elementor_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (!isGraphinaPro()) {
            wp_enqueue_style('graphina-charts-for-elementor-public', plugin_dir_url(__FILE__) . 'css/graphina-charts-for-elementor-public.css', array(), $this->version, 'all');
        }

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.4.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Graphina_Charts_For_Elementor_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Graphina_Charts_For_Elementor_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (!isGraphinaPro()) {
            wp_enqueue_style('graphina-charts-for-elementor-public', plugin_dir_url(__FILE__) . 'css/graphina-charts-for-elementor-public.css', array(), $this->version, 'all');
        }

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.4.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Graphina_Charts_For_Elementor_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Graphina_Charts_For_Elementor_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ($this->is_preview_mode()) {
            $elements = Plugin::$instance->documents->get(get_queried_object_id());
            if ($elements != '') {
                $get_graphina_widget = $this->filter_widgets($elements->get_elements_data());

                if (count(array_intersect($get_graphina_widget, $this->all_chart)) > 0) {
                    wp_enqueue_script('apexcharts-min', plugin_dir_url(__FILE__) . 'js/apexcharts.min.js', array('jquery'), $this->version, false);
                }
                if (in_array('animated_radial', $get_graphina_widget)) {
                    wp_enqueue_script('graphina-charts-perlin', plugin_dir_url(__FILE__) . 'js/perlin.js', array(), $this->version, false);
                    wp_enqueue_script('graphina-charts-d3', plugin_dir_url(__FILE__) . 'js/d3.min.js', array(), $this->version, false);
                }

                wp_enqueue_script('graphina-charts-for-elementor-public', plugin_dir_url(__FILE__) . 'js/graphina-charts-for-elementor-public.js', array('jquery'), $this->version, false);
                wp_localize_script('graphina-charts-for-elementor-public', 'localize', array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('get_graphina_chart_settings'),
                    'graphinaAllGraphs' => [],
                    'graphinaAllGraphsOptions' => [],
                    'graphinaBlockCharts' => []
                ));
            }
        } else {
            wp_enqueue_script('apexcharts-min', plugin_dir_url(__FILE__) . 'js/apexcharts.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script('graphina-charts-perlin', plugin_dir_url(__FILE__) . 'js/perlin.js', array(), $this->version, false);
            wp_enqueue_script('graphina-charts-d3', plugin_dir_url(__FILE__) . 'js/d3.min.js', array(), $this->version, false);
            wp_enqueue_script('graphina-charts-for-elementor-public', plugin_dir_url(__FILE__) . 'js/graphina-charts-for-elementor-public.js', array('jquery'), $this->version, false);
            wp_localize_script('graphina-charts-for-elementor-public', 'localize', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('get_graphina_chart_settings'),
                'graphinaAllGraphs' => [],
                'graphinaAllGraphsOptions' => [],
                'graphinaBlockCharts' => []
            ));
        }
    }

    public function elementor_init()
    {
        Plugin::$instance->elements_manager->add_category(
            'iq-graphina-charts',
            [
                'title' => esc_html__('Graphina Chart', 'iq-graphina-charts'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    public function include_widgets()
    {
        if (defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base')) {

            /***********************
             *  Charts
             */
            require plugin_dir_path(__FILE__) . '/charts/line/widget/line_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/area/widget/area_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/column/widget/column_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/donut/widget/donut_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/pie/widget/pie_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/radar/widget/radar_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/bubble/widget/bubble_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/candle/widget/candle_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/heatmap/widget/heatmap_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/radial/widget/radial_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/timeline/widget/timeline_chart.php';
            require plugin_dir_path(__FILE__) . '/charts/animated-radial/widget/animated-radial.php';
            require plugin_dir_path(__FILE__) . '/charts/polar/widget/polar_chart.php';
        }
    }

    public function is_preview_mode()
    {
        if (isset($_REQUEST['elementor-preview'])) {
            return false;
        }

        if (isset($_REQUEST['ver'])) {
            return false;
        }

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'elementor') {
            return false;
        }
        return true;
    }

    public function filter_widgets($elements = null)
    {
        $widgets = [];
        if ($elements !== null) {
            foreach ($elements as $element) {
                if (isset($element['elType']) && $element['elType'] == 'widget') {
                    if ($element['widgetType'] === 'global') {
                        $document = Plugin::$instance->documents->get($element['templateID']);
                        $widgets = array_merge($widgets, $this->filter_widgets($document->get_elements_data()));
                    } else {
                        $widgets[] = $element['widgetType'];
                    }
                }

                if (!empty($element['elements'])) {
                    $widgets = array_merge($widgets, $this->filter_widgets($element['elements']));
                }
            }
        }
        return $widgets;
    }

    public function promote_pro_elements($config)
    {
        if (isGraphinaPro()) {
            return $config;
        }

        $promotion_widgets = [];

        if (isset($config['promotionWidgets'])) {
            $promotion_widgets = $config['promotionWidgets'];
        }

        $combine_array = array_merge($promotion_widgets, [
            [
                'name' => 'dynamic_column_chart',
                'title' => esc_html__('Nested Column', 'graphina-lang'),
                'icon' => 'fas fa-wave-square',
                'categories' => '["iq-graphina-charts"]',
            ],
            [
                'name' => 'mixed_chart',
                'title' => esc_html__('Mixed', 'graphina-lang'),
                'icon' => 'fas fa-water',
                'categories' => '["iq-graphina-charts"]',
            ],
            [
                'name' => 'graphina_counter',
                'title' => esc_html__('Counter', 'graphina-lang'),
                'icon' => 'fas fa-sort-numeric-up-alt',
                'categories' => '["iq-graphina-charts"]',
            ],
            [
                'name' => 'advance_datatable',
                'title' => esc_html__('Advance DataTable', 'graphina-lang'),
                'icon' => 'fas fa-table',
                'categories' => '["iq-graphina-charts"]',
            ],
        ]);
        $config['promotionWidgets'] = $combine_array;

        return $config;
    }

    public function action_get_graphina_chart_settings()
    {
        $default = ['chart' => ['dropShadow' => ['enabledOnSeries' => []]]];
        $data = ['series' => [], 'category' => [], 'fail_message' => '', 'fail' => false];
        $instantInit = false;
        if (isset($_POST['action'])
            && isset($_POST['_ajax_nonce'])
            && 'get_graphina_chart_settings' === $_POST['action']
            && wp_verify_nonce($_POST['_ajax_nonce'], 'get_graphina_chart_settings')) {

            try {

                $settings = $_POST['fields'];
                $type = $_POST['chart_type'];
                $id = $_POST['chart_id'];
                $gradient = [];
                $second_gradient = [];
                $dropshadowSeries = [];
                $stockWidth = [];
                $stockDashArray = [];
                $fill_pattern = [];

                switch ($type) {
                    case 'line':
                    case 'area':
                    case 'column':
                    case 'heatmap':
                    case 'radar':
                        $dataType = 'area';
                        break;
                    case 'donut':
                    case 'polar':
                    case 'pie':
                    case 'radial':
                        $dataType = 'circle';
                        break;
                    case 'timeline':
                        $dataType = 'timeline';
                        break;
                    default:
                        $dataType = $type;
                        break;
                }
                $seriesCount = isset($settings['iq_' . $type . '_chart_data_series_count']) ? $settings['iq_' . $type . '_chart_data_series_count'] : 0;
                for ($i = 0; $i < $seriesCount; $i++) {
                    $dropShadowSeries[] = $i;
                    $gradient[] = strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]);
                    $second_gradient[] = strval($settings['iq_' . $type . '_chart_gradient_2_' . $i]) === '' ? strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]) : strval($settings['iq_' . $type . '_chart_gradient_2_' . $i]);
                    $stockWidth[] = $settings['iq_' . $type . '_chart_width_3_' . $i] !== '' ? $settings['iq_' . $type . '_chart_width_3_' . $i] : 0;
                    $stockDashArray[] = $settings['iq_' . $type . '_chart_dash_3_' . $i] !== '' ? $settings['iq_' . $type . '_chart_dash_3_' . $i] : 0;
                    $fill_pattern[] = isset($settings['iq_' . $type . '_chart_bg_pattern_' . $i]) ? $settings['iq_' . $type . '_chart_bg_pattern_' . $i] : 'verticalLines';
                }
                if (isGraphinaPro() && $settings['iq_' . $type . '_chart_data_option'] === 'dynamic') {
                    $data = graphina_pro_chart_content($settings, $id, $type, $dataType);
                    if (isset($data['fail']) && $data['fail'] === 'permission') {
                        wp_send_json(['status' => true, 'instant_init' => $instantInit, 'fail' => true, 'fail_message' => $data['fail_message'], 'chart_id' => $id, 'chart_option' => []]);
                    }
                }
                $gradient_new = $second_gradient_new = $stock_width_new = $stock_dash_array_new = $fill_pattern_array_new = [];
                $desiredLength = count($data['series']);
                while (count($gradient_new) < $desiredLength) {
                    $gradient_new = array_merge($gradient_new, $gradient);
                    $second_gradient_new = array_merge($second_gradient_new, $second_gradient);
                    $stock_width_new = array_merge($stock_width_new, $stockWidth);
                    $stock_dash_array_new = array_merge($stock_dash_array_new, $stockDashArray);
                    $fill_pattern_array_new = array_merge($fill_pattern_array_new, $fill_pattern);
                }

                $xaxis_font_color_new = [];
                while (count($xaxis_font_color_new) <= count($data['category'])) {
                    $xaxis_font_color_new = array_merge($xaxis_font_color_new, [strval($settings['iq_' . $type . '_chart_font_color'])]);
                }
                $xaxisFontColor = array_slice($xaxis_font_color_new, 0, count($data['category']));

                $gradient = array_slice($gradient_new, 0, $desiredLength);
                $second_gradient = array_slice($second_gradient_new, 0, $desiredLength);
                $stockWidth = array_slice($stock_width_new, 0, $desiredLength);
                $stockDashArray = array_slice($stock_dash_array_new, 0, $desiredLength);
                $fill_pattern = array_slice($fill_pattern_array_new, 0, $desiredLength);

                $data['category'] = array_map(function ($v) {
                    return strval($v);
                }, $data['category']);

                $optionSetting = [
                    'series' => $data['series'],
                    'chart' => [
                        'animations' => [
                            'enabled' => $settings['iq_' . $type . '_chart_animation'] === "yes"
                        ]
                    ],
                    'noData' => [
                        'text' => (isset($settings['iq_' . $type . '_chart_no_data_text']) ? $settings['iq_' . $type . '_chart_no_data_text'] : '')
                    ],
                    'stroke' => [
                        'width' => $stockWidth,
                        'dashArray' => $stockDashArray
                    ],
                    'colors' => count($gradient) === 0 ? ['#ffffff'] : $gradient,
                    'fill' => [
                        'colors' => count($gradient) === 0 ? ['#ffffff'] : $gradient,
                        'gradient' => [
                            'gradientToColors' => $second_gradient
                        ]
                    ]
                ];
                if ($type === 'radar') {
                    $optionSetting['xaxis']['labels']['style']['colors'] = $xaxisFontColor;
                }
                if (!in_array($dataType, ['bubble'])) {
                    $optionSetting['chart']['dropShadow'] = [
                        'enabledOnSeries' => $dropshadowSeries
                    ];
                }
                if (!in_array($dataType, ['candle', 'bubble', 'circle'])) {
                    $optionSetting['xaxis']['categories'] = (count($data['category']) > 0 ? $data['category'] : []);
                }
                if (in_array($dataType, ['circle'])) {
                    $optionSetting['fill']['pattern'] = [
                        'style' => $fill_pattern,
                        'width' => 6,
                        'height' => 6,
                        'strokeWidth' => 2
                    ];
                    $optionSetting['stroke'] = ['width' => (int)$settings['iq_' . $type . '_chart_stroke_width']];
                    $optionSetting['labels'] = (count($data['category']) > 0 ? $data['category'] : []);
                    $optionSetting['legend'] = ['show' => $settings['iq_' . $type . '_chart_legend_show'] === "yes" && count($data['series']) > 0 && count($data['category']) > 0];
                }
                if (count($data['series']) > 0 && isset($data['series'][0]['data']) && count($data['series'][0]['data']) > 100) {
                    $optionSetting['chart']['animations'] = [
                        'enabled' => false,
                        'dynamicAnimation' => ['enabled' => false]
                    ];
                    $optionSetting['chart']['toolbar'] = ['show' => true];
                    $optionSetting['dataLabels'] = ['enabled' => false];
                    $optionSetting['xaxis']['tickAmount'] = 20;
                    $instantInit = true;
                }
                wp_send_json(['status' => true, 'instant_init' => $instantInit, 'fail' => false, 'fail_message' => $data['fail_message'], 'chart_id' => $id, 'chart_option' => $optionSetting]);

            } catch (Exception $exception) {

                wp_send_json(['status' => false, 'instant_init' => $instantInit, 'fail' => false, 'fail_message' => $data['fail_message'], 'chart_id' => -1, 'chart_option' => $default]);
            }
        }

        wp_send_json(['status' => false, 'instant_init' => $instantInit, 'fail' => false, 'fail_message' => $data['fail_message'], 'chart_id' => -1, 'chart_option' => $default]);
    }
}
