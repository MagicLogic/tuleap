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

namespace Tuleap\CrossTracker\Report\Query\Advanced\QueryBuilder\Metadata;

use Tracker;
use Tuleap\CrossTracker\Report\Query\Advanced\AllowedMetadata;
use Tuleap\CrossTracker\Report\Query\IProvideParametrizedFromAndWhereSQLFragments;
use Tuleap\Tracker\Report\Query\Advanced\Grammar\Comparison;
use Tuleap\Tracker\Report\Query\Advanced\Grammar\Metadata;

class NotInComparisonFromWhereBuilder implements FromWhereBuilder
{
    /** @var AlwaysThereField\Users\FromWhereBuilder */
    private $submitted_by_builder;

    public function __construct(AlwaysThereField\Users\FromWhereBuilder $submitted_by_builder)
    {
        $this->submitted_by_builder = $submitted_by_builder;
    }

    /**
     * @param Metadata $metadata
     * @param Comparison $comparison
     * @param Tracker[] $trackers
     * @return IProvideParametrizedFromAndWhereSQLFragments
     */
    public function getFromWhere(Metadata $metadata, Comparison $comparison, array $trackers)
    {
        switch ($metadata->getName()) {
            case AllowedMetadata::SUBMITTED_BY:
                return $this->submitted_by_builder->getFromWhere($metadata, $comparison, $trackers);
                break;
        }
    }
}