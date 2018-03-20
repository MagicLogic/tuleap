<?php
/**
 * Copyright (c) Enalean, 2018. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// @codingStandardsIgnoreLine
class create_test_envPlugin extends Plugin
{
    const NAME = 'create_test_env';

    public function __construct($id)
    {
        parent::__construct($id);
        $this->setScope(self::SCOPE_SYSTEM);
        bindTextDomain('tuleap-create_test_env', __DIR__ . '/../site-content');
    }

    /**
     * @return Tuleap\CreateTestEnv\Plugin\PluginInfo
     */
    public function getPluginInfo()
    {
        if (!$this->pluginInfo) {
            $this->pluginInfo = new Tuleap\CreateTestEnv\Plugin\PluginInfo($this);
        }
        return $this->pluginInfo;
    }

    public function getHooksAndCallbacks()
    {
        $this->addHook(Event::REST_RESOURCES);

        return parent::getHooksAndCallbacks();
    }

    public function restResources(array $params)
    {
        $injector = new \Tuleap\CreateTestEnv\REST\ResourcesInjector();
        $injector->populate($params['restler']);
    }
}
