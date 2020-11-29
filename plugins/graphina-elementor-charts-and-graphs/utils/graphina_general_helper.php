<?php
function getGraphinaProFileUrl($file)
{
    return GRAPHINA_PRO_ROOT . '/elementor/' . $file;
}

function isGraphinaPro()
{
    return get_option('graphina_is_activate') === "1";
}

function isGraphinaProInstall()
{
    return get_option('graphina_pro_is_install') === "1";
}

function graphina_plugin_activation($is_deactivate = false)
{
    $pluginName = "Graphina";
    $arg = 'plugin=' . $pluginName . '&domain=' . get_bloginfo('wpurl') . '&site_name=' . get_bloginfo('name');
    if ($is_deactivate) {
        $arg .= '&is_deactivated=true';
    }
    wp_remote_get('https://innoquad.in/plugin-server/active-server.php?' . $arg);
}