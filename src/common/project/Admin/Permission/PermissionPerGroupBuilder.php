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

namespace Tuleap\Project\Admin\Permission;

use HTTPRequest;
use Project;
use ProjectUGroup;
use Tuleap\User\UserGroup\NameTranslator;
use UGroupManager;

class PermissionPerGroupBuilder
{
    /**
     * @var UGroupManager
     */
    private $ugroup_manager;

    public function __construct(UGroupManager $ugroup_manager)
    {
        $this->ugroup_manager = $ugroup_manager;
    }

    public function buildUGroup(Project $project, HTTPRequest $request)
    {
        $static_ugroups = $this->ugroup_manager->getStaticUGroups($project);
        if ($project->usesWiki()) {
            $static_ugroups[] = $this->ugroup_manager->getUGroup($project, ProjectUGroup::WIKI_ADMIN);
        }

        if ($project->usesForum()) {
            $static_ugroups[] = $this->ugroup_manager->getUGroup($project, ProjectUGroup::FORUM_ADMIN);
        }

        if ($project->usesNews()) {
            $static_ugroups[] = $this->ugroup_manager->getUGroup($project, ProjectUGroup::NEWS_WRITER);
            $static_ugroups[] = $this->ugroup_manager->getUGroup($project, ProjectUGroup::NEWS_ADMIN);
        }

        return $this->getUGroupsThatCanBeUsedAsTemplate($static_ugroups, $request);
    }

    /**
     * @param \ProjectUGroup[] $static_ugroups
     *
     * @return array
     */
    private function getUGroupsThatCanBeUsedAsTemplate(array $static_ugroups, HTTPRequest $request)
    {
        $selected_group = $request->get('group');

        $ugroups = array();

        $ugroups[] = array(
            'id'          => ProjectUGroup::PROJECT_MEMBERS,
            'name'        => NameTranslator::getUserGroupDisplayName(NameTranslator::PROJECT_MEMBERS),
            'is_selected' => (int)$selected_group === ProjectUGroup::PROJECT_MEMBERS
        );

        $ugroups[] = array(
            'id'          => ProjectUGroup::PROJECT_ADMIN,
            'name'        => NameTranslator::getUserGroupDisplayName(NameTranslator::PROJECT_ADMINS),
            'is_selected' => (int)$selected_group === ProjectUGroup::PROJECT_ADMIN
        );

        foreach ($static_ugroups as $ugroup) {
            $ugroups[] = array(
                'id'          => $ugroup->getId(),
                'name'        => NameTranslator::getUserGroupDisplayName($ugroup->getName()),
                'is_selected' => $selected_group === $ugroup->getId()
            );
        }

        return $ugroups;
    }
}