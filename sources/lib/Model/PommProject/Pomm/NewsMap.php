<?php

namespace Model\PommProject\Pomm;

use Model\PommProject\Pomm\Base\NewsMap as BaseNewsMap;
use Model\PommProject\Pomm\News;
use \Pomm\Exception\Exception;
use \Pomm\Query\Where;

class NewsMap extends BaseNewsMap
{
    public function paginateFindAllShorten($mpp, $page = 1)
    {
        $sql = <<<SQL
SELECT
    :news_fields,
    cut_nicely(content, 450) AS content
FROM 
    :news_table
ORDER BY news.published_at DESC
SQL;

        $fields = $this->getSelectFields();
        unset($fields['content']);

        $sql = strtr($sql, array(
            ':news_fields' => join(', ', $fields),
            ':news_table' => $this->getTableName(),
        ));

        return $this->paginateQuery($sql, "SELECT count(*) FROM pomm.news", array(), $mpp, $page);
    }

    public function findBySlugWithNeighbours($slug)
    {
        $sql = <<<SQL
WITH
  neighbour AS (
    SELECT
      slug,
      lag(slug) OVER (ORDER BY published_at DESC) AS next_slug,
      lead(slug) OVER (ORDER BY published_at DESC) AS prev_slug
    FROM
    :news_table
    )
SELECT
    :news_fields,
    n.next_slug AS next_slug,
    n.prev_slug AS prev_slug
FROM
    :news_table
      NATURAL JOIN neighbour n
WHERE
    news.slug = $*
SQL;

        $sql = strtr($sql, array(
            ':news_fields' => $this->formatFields('getSelectFields'),
            ':news_table' => $this->getTableName()
        ));

        return $this->query($sql, array($slug))->current();
    }
}
