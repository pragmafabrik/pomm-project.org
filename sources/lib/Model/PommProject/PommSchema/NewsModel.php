<?php

namespace Model\PommProject\PommSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;
use PommProject\Foundation\Pager;

use Model\PommProject\PommSchema\AutoStructure\News as NewsStructure;
use Model\PommProject\PommSchema\News;

/**
 * NewsModel
 *
 * Model class for table news.
 *
 * @see Model
 */
class NewsModel extends Model
{
    use WriteQueries;

    /**
     * __construct()
     *
     * Model constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->structure = new NewsStructure;
        $this->flexible_entity_class = "\Model\PommProject\PommSchema\News";
    }

    /**
     * createProjection
     *
     * Default projection.
     *
     * @return Projection
     */
    public function createProjection()
    {
        return parent::createProjection()
            ->setField('age', 'age(now(), %:published_at:%)', 'interval')
            ->unsetField('lexem')
            ;
    }

    /**
     * paginateFindAllShorten
     *
     * Return a paginated list of news with content cut to a defined number of
     * chars.
     *
     * @access public
     * @param  int $mpp  message per page
     * @param  int $page page index
     * @return Pager
     */
    public function paginateFindAllShorten($mpp, $page = 1)
    {
        $sql = <<<SQL
select
    :news_fields
from
    :news_table n
order by n.published_at desc
SQL;

        $projection = $this
            ->createProjection()
            ->unsetField('content')
            ->setField('content', "cut_nicely(regexp_replace(substr(%:content:%, 1, 900), '<[^>]+>', ' ', 'g'), 450)", 'text')
            ;

        $sql = strtr(
            $sql,
            [
                ':news_fields' => $projection->formatFieldsWithFieldAlias('n'),
                ':news_table' => $this->structure->getRelation(),
            ]
        );

        $count = $this->countWhere(new Where());

        return $this->paginateQuery($sql, [], $count, $mpp, $page, $projection);
    }

    /**
     * findBySlugWithNeighbours
     *
     * Return a News instance from its slug. It is augmented with the slugs of
     * the previous and the following news if exist.
     *
     * @access public
     * @param  string $slug
     * @return News
     */
    public function findBySlugWithNeighbours($slug)
    {
        $sql = <<<SQL
with
  neighbour as (
    select
      slug,
      lag(slug) over published_at_wdw as next_slug,
      lead(slug) over  published_at_wdw as prev_slug
    from
      :news_table
    window
      published_at_wdw as (order by published_at desc)
    )
select
    :news_fields
from
    :news_table
      left join neighbour n using (slug)
      left join :news_table nn on n.next_slug = nn.slug
      left join :news_table pn on n.prev_slug = pn.slug
where
    news.slug = $*
SQL;

        $projection = $this
            ->createProjection()
            ->setField('next_slug', 'n.next_slug', 'varchar')
            ->setField('prev_slug', 'n.prev_slug', 'varchar')
            ->setField('next_title', 'nn.title', 'varchar')
            ->setField('prev_title', 'pn.title', 'varchar')
            ;

        $sql = strtr($sql,
            [
            ':news_fields' => $projection->formatFieldsWithFieldAlias('news'),
            ':news_table' => $this->structure->getRelation(),
            ]
        );

        return $this->query($sql, array($slug), $projection)->current();
    }

    /**
     * search
     *
     * A short description here
     *
     * @access public
     * @param  string $search_string
     * @return CollectionIterator
     */
    public function search($search_string)
    {
        $words = $this->prepareTsWords($search_string, $limit);
        $sql = <<<SQL
select
  :projection
from
  :news,
  to_tsquery('english', $*) as query
where lexem @@ query
order by ranking desc
limit $*
SQL;

        $projection = $this->createProjection()
            ->unsetField('content')
            ->setField('ranking', "ts_rank_cd(%:lexem:%, query)", 'float4')
            ->setField('content', "ts_headline('english', %:content:%, query)", 'text')
            ;

        $sql = strtr($sql, [
            ':projection' => $projection,
            ':news'       => $this->structure->getRelation(),
            ]);

        return $this->query($sql, [$words, $limit], $projection);
    }

    /**
     * prepareTsWords
     *
     * Prepare search words for ts_query.
     *
     * @access private
     * @param  string $string
     * @return string
     */
    private function prepareTsWords($string)
    {
        return join(' & ', preg_split('/\W+/', trim($string)));
    }
}
