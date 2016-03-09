<?php
/**
 * Copyright (c) Enalean, 2016. All Rights Reserved.
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

namespace Tuleap\HudsonSvn\Job;

use Tuleap\Svn\Repository\Repository;
use Tuleap\Svn\Commit\CommitInfo;
use Jenkins_Client;
use Jenkins_ClientUnableToLaunchBuildException;
use Logger;

class Launcher {

    const ROOT_DIRECTORY = '/';

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Factory
     */
    private $factory;
    private $ci_client;

    private $launched_jobs = array();

    public function __construct(Factory $factory, Logger $logger, Jenkins_Client $ci_client) {
        $this->factory   = $factory;
        $this->logger    = $logger;
        $this->ci_client = $ci_client;
    }

    public function launch(Repository $repository, CommitInfo $commit_info) {
        if (! $repository->getProject()->usesService('hudson')) {
            return;
        }

        $jobs = $this->getJobsForRepository($repository);

        foreach ($jobs as $job) {
            if ($this->doesCommitTriggerjob($commit_info, $job) && !$this->isJobAlreadyLaunched($job)) {
                $this->logger->info("Launching job #id:" . $job->getId() . " triggered by repository ".$repository->getFullName()." with the url " .$job->getUrl());
                try {
                    $this->ci_client->setToken($job->getToken());
                    $this->ci_client->launchJobBuild($job->getUrl());

                    $this->launched_jobs[] = $job->getUrl();
                } catch(Jenkins_ClientUnableToLaunchBuildException $exception) {
                    $this->logger->error("Launching job #id:" . $job->getId() . " triggered by repository ".$repository->getFullName()." with the url " .$job->getUrl()." got error " .$exception->getMessage());
                }

                continue;
            }
        }
    }

    private function isJobAlreadyLaunched(Job $job) {
        return in_array($job->getUrl(), $this->launched_jobs);
    }

    private function doesCommitTriggerjob(CommitInfo $commit_info, Job $job) {
        $job_paths                       = explode(PHP_EOL, $job->getPath());
        $well_formed_changed_directories = $this->getWellFormedChangedDirectories($commit_info);

        foreach ($job_paths as $path) {
            $regexp = $this->getRegExpFromPath($path);

            foreach ($well_formed_changed_directories as $directory) {
                if (preg_match($regexp, $directory)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getRegExpFromPath($path) {
        $path = preg_quote($path);
        $path = str_replace('\*', '[^/]+', $path);
        $path = '#^' . $path . '#';

        return $path;
    }

    /**
     * @return array
     */
    private function getWellFormedChangedDirectories(CommitInfo $commit_info) {
        $well_formed_directories = array();
        foreach ($commit_info->getChangedDirectories() as $changed_directory) {
            if ($changed_directory !== self::ROOT_DIRECTORY) {
                $changed_directory = self::ROOT_DIRECTORY . $changed_directory;
            }

            $well_formed_directories[] = $changed_directory;
        }

        return $well_formed_directories;
    }

    private function getJobsForRepository(Repository $repository) {
        return $this->factory->getJobsByRepository($repository);
    }

}