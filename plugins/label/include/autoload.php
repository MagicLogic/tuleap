<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
function autoloadf6a68a4e7d2d4485155db9a53ec6ad35($class) {
    static $classes = null;
    if ($classes === null) {
        $classes = array(
            'labelplugin' => '/labelPlugin.class.php',
            'tuleap\\label\\plugin\\plugindescriptor' => '/Label/Plugin/PluginDescriptor.php',
            'tuleap\\label\\plugin\\plugininfo' => '/Label/Plugin/PluginInfo.php',
            'tuleap\\label\\widget\\projectlabeleditems' => '/Label/Widget/ProjectLabeledItems.php',
            'tuleap\\label\\widget\\projectlabeleditemspresenter' => '/Label/Widget/ProjectLabeledItemsPresenter.php'
        );
    }
    $cn = strtolower($class);
    if (isset($classes[$cn])) {
        require dirname(__FILE__) . $classes[$cn];
    }
}
spl_autoload_register('autoloadf6a68a4e7d2d4485155db9a53ec6ad35');
// @codeCoverageIgnoreEnd