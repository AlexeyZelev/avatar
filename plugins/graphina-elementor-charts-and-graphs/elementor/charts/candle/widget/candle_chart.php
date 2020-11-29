<?php

namespace Elementor;
if (!defined('ABSPATH')) exit;

/**
 * Elementor Blog widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.4.0
 */
class Candle_chart extends Widget_Base
{
    private $upwardColor = '#008B36';
    private $downwardColor = '#C70000';

    /**
     * Get widget name.
     *
     * Retrieve heading widget name.
     *
     * @return string Widget name.
     * @since 1.4.0
     * @access public
     *
     */

    public function get_name()
    {
        return 'candle_chart';
    }

    /**
     * Get widget Title.
     *
     * Retrieve heading widget Title.
     *
     * @return string Widget Title.
     * @since 1.4.0
     * @access public
     *
     */

    public function get_title()
    {
        return 'Candle';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the heading widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @return array Widget categories.
     * @since 1.4.0
     * @access public
     *
     */

    public function get_categories()
    {
        return ['iq-graphina-charts'];
    }

    /**
     * Get widget icon.
     *
     * Retrieve heading widget icon.
     *
     * @return string Widget icon.
     * @since 1.4.0
     * @access public
     *
     */

    public function get_icon()
    {
        return 'fab fa-gitter';
    }

    /***************************************************
     * @param string $type
     * @param int $i
     * @param int $min
     * @param int $max
     * @param int $range
     * @param int $len
     * @return array
     */

    /*
     * ----------------  Sample Object Of Array  ------------------
     * [
     *   'iq_type_chart_value_open_3_' => 10.02,
     *   'iq_type_chart_value_high_3_' => 10.02,
     *   'iq_type_chart_value_low_3_' => 10.02,
     *   'iq_type_chart_value_close_3_' => 10.02,
     *   'iq_type_chart_value_date_3_' => '2020-08-05 16:05'
     * ]
     */

    protected function candleDataGenerator($type = '', $i = 0, $min = 0, $max = 100, $range = 5, $len = 25)
    {
        $result = [];
        for ($j = 0; $j < $len; $j++) {
            $default = rand($min, $max);
            $result[] =
                [
                    'iq_' . $type . '_chart_value_open_3_' . $i => round((rand($default + $range, $default - $range) * 1.00002), 2),
                    'iq_' . $type . '_chart_value_high_3_' . $i => round((rand($default + $range, $default - $range) * 1.00002), 2),
                    'iq_' . $type . '_chart_value_low_3_' . $i => round((rand($default + $range, $default - $range) * 1.00002), 2),
                    'iq_' . $type . '_chart_value_close_3_' . $i => round((rand($default + $range, $default - $range) * 1.00002), 2),
                    'iq_' . $type . '_chart_value_date_3_' . $i => graphina_getRandomDate(date('Y-m-d H:i'), 'Y-m-d H:i', ['hour' => rand(0, 6), 'minute' => rand(0, 50)])
                ];
        }
        return $result;
    }

    public function get_chart_type()
    {
        return 'candle';
    }

    protected function _register_controls()
    {
        $type = $this->get_chart_type();

        graphina_basic_setting($this, $type);

        graphina_chart_data_option_setting($this, $type);

        $this->start_controls_section(
            'iq_' . $type . '_section_2',
            [
                'label' => esc_html__('Chart Setting', 'graphina-lang'),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'iq_' . $type . '_chart_is_pro',
                                    'operator' => '==',
                                    'value' => 'false'
                                ],
                                [
                                    'name' => 'iq_' . $type . '_chart_data_option',
                                    'operator' => '==',
                                    'value' => 'manual'
                                ]
                            ]
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'iq_' . $type . '_chart_is_pro',
                                    'operator' => '==',
                                    'value' => 'true'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_height',
            [
                'label' => esc_html__('Height (px)', 'graphina-lang'),
                'type' => Controls_Manager::NUMBER,
                'default' => 350,
                'step' => 5,
                'min' => 100
            ]
        );

        $this->add_control(
            'iq_' . $type . '_can_chart_show_toolbar',
            [
                'label' => esc_html__('Toolbar', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => false
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_no_data_text',
            [
                'label' => esc_html__('No Data Text', 'graphina-lang'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Loading...', 'graphina-lang'),
                'default' => 'No Data Available',
            ]
        );

        graphina_tooltip($this, $type);

        $this->add_control(
            'iq_' . $type . '_can_chart_yaxis_show',
            [
                'label' => esc_html__('Y-axis Tooltip', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => false
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_hr_fill_setting',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_fill_setting_title',
            [
                'label' => esc_html__('Fill Settings', 'graphina-lang'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_is_fill_color_show',
            [
                'label' => esc_html__('Color Show', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'graphina-lang'),
                'label_off' => esc_html__('No', 'graphina-lang'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_is_fill_opacity',
            [
                'label' => esc_html__('Opacity', 'graphina-lang'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0.00,
                'max' => 1,
                'step' => 0.05
            ]
        );

        $this->add_control(
            'iq_chart_upward_color',
            [
                'label' => esc_html__('Upward Color', 'graphina-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => $this->upwardColor,
            ]
        );

        $this->add_control(
            'iq_chart_downward_color',
            [
                'label' => esc_html__('Downward Color', 'graphina-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => $this->downwardColor,
            ]
        );

        graphina_animation($this, $type);

        $this->end_controls_section();

        graphina_chart_label_setting($this, $type);

        graphina_advance_x_axis_setting($this, $type);

        graphina_advance_y_axis_setting($this, $type);

        graphina_series_setting($this, $type, ['color'], false);

        for ($i = 0; $i < 10; $i++) {
            $this->start_controls_section(
                'iq_' . $type . '_section_3_' . $i,
                [
                    'label' => esc_html__('Element ' . ($i + 1), 'graphina-lang'),
                    'condition' => [
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, 10),
                        'iq_' . $type . '_chart_data_option' => 'manual'
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'relation' => 'and',
                                'terms' => [
                                    [
                                        'name' => 'iq_' . $type . '_chart_is_pro',
                                        'operator' => '==',
                                        'value' => 'false'
                                    ],
                                    [
                                        'name' => 'iq_' . $type . '_chart_data_option',
                                        'operator' => '==',
                                        'value' => 'manual'
                                    ]
                                ]
                            ],
                            [
                                'relation' => 'and',
                                'terms' => [
                                    [
                                        'name' => 'iq_' . $type . '_chart_is_pro',
                                        'operator' => '==',
                                        'value' => 'true'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            );

            $this->add_control(
                'iq_' . $type . '_chart_title_3_' . $i,
                [
                    'label' => esc_html__('Title', 'graphina-lang'),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Add Tile', 'graphina-lang'),
                    'default' => 'Element ' . ($i + 1)
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'iq_' . $type . '_chart_value_date_3_' . $i,
                [
                    'label' => esc_html__('Chart Date ( X ) Value', 'graphina-lang'),
                    'type' => Controls_Manager::DATE_TIME,
                    'placeholder' => esc_html__('Select Date', 'graphina-lang'),
                ]
            );

            $repeater->add_control(
                'iq_' . $type . '_chart_value_open_3_' . $i,
                [
                    'label' => esc_html__('Open Value', 'graphina-lang'),
                    'type' => Controls_Manager::NUMBER,
                    'placeholder' => esc_html__('Add Value', 'graphina-lang')
                ]
            );

            $repeater->add_control(
                'iq_' . $type . '_chart_value_high_3_' . $i,
                [
                    'label' => esc_html__('High Value', 'graphina-lang'),
                    'type' => Controls_Manager::NUMBER,
                    'placeholder' => esc_html__('Add Value', 'graphina-lang')
                ]
            );

            $repeater->add_control(
                'iq_' . $type . '_chart_value_low_3_' . $i,
                [
                    'label' => esc_html__('Low Value', 'graphina-lang'),
                    'type' => Controls_Manager::NUMBER,
                    'placeholder' => esc_html__('Add Value', 'graphina-lang')
                ]
            );

            $repeater->add_control(
                'iq_' . $type . '_chart_value_close_3_' . $i,
                [
                    'label' => esc_html__('Close Value', 'graphina-lang'),
                    'type' => Controls_Manager::NUMBER,
                    'placeholder' => esc_html__('Add Value', 'graphina-lang')
                ]
            );

            /** Chart value list. */
            $this->add_control(
                'iq_' . $type . '_chart_value_list_3_1_' . $i,
                [
                    'label' => esc_html__('Chart value list', 'graphina-lang'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => $this->candleDataGenerator('candle', $i, 6000, 8000, 500, 50),
                    'title_field' => '{{{ iq_' . $type . '_chart_value_date_3_' . $i . ' }}}',
                ]
            );

            $this->end_controls_section();
        }

        graphina_style_section($this, $type);

        graphina_card_style($this, $type);

        graphina_chart_style($this, $type);
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $mainId = $this->get_id();
        $type = $this->get_chart_type();
        $gradient = [];
        $data = ['series' => [], 'category' => []];
        $seriesCount = isset($settings['iq_' . $type . '_chart_data_series_count']) ? $settings['iq_' . $type . '_chart_data_series_count'] : 0;
        $callAjax = false;
        $loadingText = esc_html__((isset($settings['iq_' . $type . '_chart_no_data_text']) ? $settings['iq_' . $type . '_chart_no_data_text'] : ''), 'graphina-lang');

        $yLabelPrefix = $yLabelPostfix = $xLabelPrefix = $xLabelPostfix = '';

        if ($settings['iq_' . $type . '_chart_xaxis_label_show'] === 'yes') {
            $xLabelPrefix = $settings['iq_' . $type . '_chart_xaxis_label_prefix'];
            $xLabelPostfix = $settings['iq_' . $type . '_chart_xaxis_label_postfix'];
        }

        if ($settings['iq_' . $type . '_chart_yaxis_label_show'] === 'yes') {
            $yLabelPrefix = $settings['iq_' . $type . '_chart_yaxis_label_prefix'];
            $yLabelPostfix = $settings['iq_' . $type . '_chart_yaxis_label_postfix'];
        }

        if (isGraphinaPro() && $settings['iq_' . $type . '_chart_data_option'] === 'dynamic') {
            $new_settings = graphina_setting_sort($settings);
            $callAjax = true;
            $gradient = $second_gradient = ['#ffffff'];
            $loadingText = esc_html__('Loading...', 'graphina-lang');
        } else {
            $new_settings = [];
            for ($i = 0; $i < $seriesCount; $i++) {
                $gradient[] = strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]);
            }
            for ($i = 0; $i < $seriesCount; $i++) {
                $chartData = [];
                $valueList = $settings['iq_' . $type . '_chart_value_list_3_1_' . $i];
                if (gettype($valueList) === "NULL") {
                    $valueList = [];
                }
                foreach ($valueList as $val) {
                    $chartData[] = [
                        'x' => strtotime($val['iq_' . $type . '_chart_value_date_3_' . $i]),
                        'y' => [
                            $val['iq_' . $type . '_chart_value_open_3_' . $i],
                            $val['iq_' . $type . '_chart_value_high_3_' . $i],
                            $val['iq_' . $type . '_chart_value_low_3_' . $i],
                            $val['iq_' . $type . '_chart_value_close_3_' . $i]
                        ]
                    ];
                }
                $data['series'][] = [
                    'name' => $settings['iq_' . $type . '_chart_title_3_' . $i],
                    'data' => $chartData
                ];
            }
            if ($settings['iq_' . $type . '_chart_data_option'] === 'dynamic') {
                $data = ['series' => [], 'category' => []];
            }
            $gradient_new = [];
            $desiredLength = count($data['series']);
            while (count($gradient_new) < $desiredLength) {
                $gradient_new = array_merge($gradient_new, $gradient);
            }
            $gradient = array_slice($gradient_new, 0, $desiredLength);
        }

        $gradient = implode('_,_', $gradient);
        $chartDataJson = json_encode($data['series']);
        require GRAPHINA_ROOT . '/elementor/charts/candle/render/candle_chart.php';
        ?>

        <script>
            var myElement = document.querySelector(".candle-chart-<?php esc_attr_e($mainId); ?>");

            if (typeof isInit === 'undefined') {
                var isInit = {};
            }
            isInit['<?php esc_attr_e($mainId); ?>'] = false;

            var candleOptions = {
                series: <?php echo $chartDataJson; ?>,
                chart: {
                    height: parseInt('<?php echo $settings['iq_' . $type . '_chart_height']; ?>'),
                    type: 'candlestick',
                    toolbar: {
                        show: '<?php echo $settings['iq_' . $type . '_can_chart_show_toolbar'] === "yes"; ?>'
                    },
                    animations: {
                        enabled: '<?php echo($settings['iq_' . $type . '_chart_animation'] === "yes"); ?>',
                        speed: '<?php echo $settings['iq_' . $type . '_chart_animation_speed']; ?>',
                        delay: '<?php echo $settings['iq_' . $type . '_chart_animation_delay']; ?>'
                    }
                },
                plotOptions: {
                    candlestick: {
                        colors: {
                            upward: '<?php echo $settings['iq_chart_upward_color']; ?>',
                            downward: '<?php echo $settings['iq_chart_downward_color']; ?>'
                        },
                        wick: {
                            useFillColor: '<?php echo $settings['iq_' . $type . '_chart_is_fill_color_show'] === "yes"; ?>',
                        }
                    }
                },
                noData: {
                    text: '<?php echo $loadingText; ?>',
                    align: 'center',
                    verticalAlign: 'middle',
                    style: {
                        fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                        fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                        color: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>'
                    }
                },
                grid: {
                    yaxis: {
                        lines: {
                            show: '<?php echo $settings['iq_' . $type . '_chart_yaxis_line_show'] === "yes"; ?>'
                        }
                    }
                },
                xaxis: {
                    type: 'datetime',
                    position: '<?php esc_html_e($settings['iq_' . $type . '_chart_xaxis_datalabel_position']); ?>',
                    tickAmount: parseInt("<?php esc_html_e($settings['iq_' . $type . '_chart_xaxis_datalabel_tick_amount']); ?>"),
                    tickPlacement: "<?php esc_html_e($settings['iq_' . $type . '_chart_xaxis_datalabel_tick_placement']) ?>",
                    labels: {
                        show: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_show'] === "yes"; ?>',
                        rotateAlways: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_auto_rotate'] === "yes"; ?>',
                        rotate: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_rotate']; ?>',
                        offsetX: parseInt('<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_offset_x']; ?>'),
                        offsetY: parseInt('<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_offset_y']; ?>'),
                        trim: true,
                        style: {
                            colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>',
                            fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                            fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                            fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight']; ?>'
                        },
                        formatter: function (val) {
                            var date = new Date(val * 1000);
                            var hours = date.getHours();
                            var minutes = "0" + date.getMinutes();
                            var formattedTime = hours + ':' + minutes.substr(-2);
                            return '<?php esc_html_e($xLabelPrefix) ?>' + formattedTime + '<?php esc_html_e($xLabelPostfix) ?>';
                        }
                    }
                },
                yaxis: {
                    opposite: '<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_datalabel_position']); ?>',
                    tickAmount: parseInt("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_datalabel_tick_amount']); ?>"),
                    decimalsInFloat: parseInt("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_datalabel_decimals_in_float']); ?>"),
                    labels: {
                        show: '<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_show'] === "yes"; ?>',
                        rotate: '<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_rotate']; ?>',
                        offsetX: parseInt('<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_offset_x']; ?>'),
                        offsetY: parseInt('<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_offset_y']; ?>'),
                        style: {
                            colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>',
                            fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                            fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                            fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight']; ?>'
                        }
                    },
                    tooltip: {
                        enabled: '<?php echo $settings['iq_' . $type . '_can_chart_yaxis_show'] === "yes"; ?>'
                    }
                },
                colors: '<?php echo $gradient; ?>'.split('_,_'),
                stroke: {
                    show: true,
                    width: 0.7,
                    colors: '<?php echo $gradient; ?>'.split('_,_')
                },
                fill: {
                    opacity: parseFloat('<?php echo $settings['iq_' . $type . '_chart_is_fill_opacity'] ?>')
                },
                legend: {
                    onItemClick: {
                        toggleDataSeries: false
                    },
                    show: '<?php echo $settings['iq_' . $type . '_chart_legend_show'] === "yes"; ?>',
                    position: '<?php esc_html_e($settings['iq_' . $type . '_chart_legend_position']); ?>',
                    horizontalAlign: '<?php esc_html_e($settings['iq_' . $type . '_chart_legend_horizontal_align']); ?>',
                    fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                    fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                    fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight']; ?>',
                    labels: {
                        colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>'
                    }
                },
                tooltip: {
                    theme: '<?php echo $settings['iq_' . $type . '_chart_tooltip_theme']; ?>',
                    enabled: '<?php echo $settings['iq_' . $type . '_chart_tooltip'] === "yes"; ?>',
                    style: {
                        fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                        fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>'
                    }
                }
            };

            if("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_label_show']); ?>" === "yes") {
                candleOptions.yaxis.labels.formatter = function (val) {
                    return '<?php esc_html_e($yLabelPrefix); ?>' + val + '<?php esc_html_e($yLabelPostfix); ?>';
                }
            }

            initNow(
                myElement,
                {
                    ele: myElement,
                    options: candleOptions,
                    series:[{name: '', data: []}],
                    animation: true
                },
                '<?php esc_attr_e($mainId); ?>'
            );
            if ('<?php echo $callAjax; ?>' === "1") {
                getDataForChartsAjax(<?php echo json_encode($new_settings); ?>, '<?php echo $type; ?>', '<?php echo $mainId; ?>');
            }

        </script>
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type(new Candle_chart());
