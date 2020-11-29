<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit;

$settings = $this->get_settings();
?>

<div class="<?php echo $settings['iq_radar_chart_card_show'] === 'yes' ? 'chart-card' : ''; ?>">
    <div class="">
        <?php if ($settings['iq_radar_is_card_heading_show'] && $settings['iq_radar_chart_card_show']) { ?>
            <h4 class="heading graphina-chart-heading" style="text-align: <?php echo $settings['iq_radar_card_title_align'];?>; color: <?php echo strval($settings['iq_radar_card_title_font_color']);?>;"><?php echo $settings['iq_radar_chart_heading']; ?></h4>
        <?php }
        if ($settings['iq_radar_is_card_desc_show'] && $settings['iq_radar_chart_card_show']) { ?>
            <p class="sub-heading graphina-chart-sub-heading" style="text-align: <?php echo $settings['iq_radar_card_subtitle_align'];?>; color: <?php echo strval($settings['iq_radar_card_subtitle_font_color']);?>;"><?php echo $settings['iq_radar_chart_content']; ?></p>
        <?php } ?>
    </div>
    <div class="<?php echo $settings['iq_radar_chart_border_show'] === 'yes' ? 'chart-box' : ''; ?>">
        <div id="radar-chart" class="chart-texture radar-chart-<?php esc_attr_e($this->get_id()); ?>"></div>
    </div>
</div>