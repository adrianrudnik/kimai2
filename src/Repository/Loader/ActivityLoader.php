<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository\Loader;

use App\Entity\Activity;
use Doctrine\ORM\EntityManagerInterface;

final class ActivityLoader implements LoaderInterface
{
    private $entityManager;
    private $fullyHydrated;

    public function __construct(EntityManagerInterface $entityManager, bool $fullyHydrated = false)
    {
        $this->entityManager = $entityManager;
        $this->fullyHydrated = $fullyHydrated;
    }

    /**
     * @param Activity[] $activities
     */
    public function loadResults(array $activities): void
    {
        $ids = array_map(function (Activity $activity) {
            return $activity->getId();
        }, $activities);

        $loader = new ActivityIdLoader($this->entityManager, $this->fullyHydrated);
        $loader->loadResults($ids);
    }
}
