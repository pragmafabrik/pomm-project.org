<?php

namespace Model\PommProject\PommSchema;

use PommProject\ModelManager\Model\FlexibleEntity;

/**
 * News
 *
 * Flexible entity for relation
 * pomm.news
 *
 * @see FlexibleEntity
 */
class News extends FlexibleEntity
{
    public function getAge()
    {
        $interval = $this->get('age');

        if ($interval->y > 0) {
            return $interval->y > 1
                ? sprintf("%d years ago", $interval->y)
                : 'last year'
                ;
        } elseif ($interval->m > 0) {
            return $interval->m > 1
                ? sprintf("%d months ago", $interval->m)
                : 'last month'
                ;
        } elseif ($interval->d > 0) {
            return $interval->d > 1
                ? sprintf("%d days ago", $interval->d)
                : 'yesterday'
                ;
        } else { return 'today'; }
    }
}
