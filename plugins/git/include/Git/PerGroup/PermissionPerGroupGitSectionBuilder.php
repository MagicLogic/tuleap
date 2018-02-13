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

namespace Tuleap\Git\PerGroup;

use Git;
use Tuleap\Project\Admin\PerGroup\PermissionPerGroupPanePresenter;
use Tuleap\Project\Admin\PerGroup\PermissionPerGroupUGroupFormatter;
use Tuleap\Project\Admin\Permission\PermissionPerGroupPaneCollector;
use Tuleap\Project\Admin\Permission\PermissionPerGroupUGroupRetriever;
use UGroupManager;

class PermissionPerGroupGitSectionBuilder
{
    /**
     * @var PermissionPerGroupUGroupRetriever
     */
    private $permisson_retriever;
    /**
     * @var PermissionPerGroupUGroupFormatter
     */
    private $formatter;
    /**
     * @var UGroupManager
     */
    private $ugroup_manager;

    public function __construct(
        PermissionPerGroupUGroupRetriever $permisson_retriever,
        PermissionPerGroupUGroupFormatter $formatter,
        UGroupManager $ugroup_manager
    ) {
        $this->permisson_retriever = $permisson_retriever;
        $this->formatter           = $formatter;
        $this->ugroup_manager      = $ugroup_manager;
    }

    public function buildPresenter(PermissionPerGroupPaneCollector $event)
    {
        $permissions = array();
        $selected_ugroup_id = $event->getSelectedUGroupId();

        if ($event->getSelectedUGroupId()) {
            $all_permissions = $this->permisson_retriever->getAdminUGroupIdsForProjectContainingUGroupId(
                $event->getProject(),
                $event->getProject()->getID(),
                Git::PERM_ADMIN,
                $selected_ugroup_id
            );
        } else {
            $all_permissions = $this->permisson_retriever->getAllUGroupForObject(
                $event->getProject(),
                $event->getProject()->getID(),
                Git::PERM_ADMIN
            );
        }

        // This is done to avoid listing many times Project admins. See https://tuleap.net/plugins/tracker/?aid=11125
        $unique_permissions = array_unique($all_permissions, SORT_NUMERIC);
        foreach ($unique_permissions as $permission) {
            $permissions[] = $this->formatter->formatGroup($event->getProject(), $permission);
        }

        $selected_ugroup = $this->ugroup_manager->getUGroup($event->getProject(), $selected_ugroup_id);

        return new PermissionPerGroupPanePresenter($permissions, $selected_ugroup);
    }
}