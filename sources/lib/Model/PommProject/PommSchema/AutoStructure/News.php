<?php
/**
 * This file has been automaticaly generated by Pomm Cli package.
 * You MIGHT NOT edit this file as your changes will be lost at next
 * generation.
 */

namespace Model\PommProject\PommSchema\AutoStructure;

use PommProject\ModelManager\Model\RowStructure;

/**
 * News
 *
 * Structure class for relation pomm.news.
 *
 * Class and fields comments are inspected from table and fields comments.
 * Just add comments in your database and they will appear here.
 * @see http://www.postgresql.org/docs/9.0/static/sql-comment.html
 *
 *
 *
 * @see RowStructure
 */
class News extends RowStructure
{
    /**
     * initialize
     *
     * @see RowStructure
     */
    protected function initialize()
    {
        $this
            ->setRelation('pomm.news')
            ->setPrimaryKey(['slug'])
            ->addField('slug', 'varchar')
            ->addField('title', 'varchar')
            ->addField('created_at', 'timestamp')
            ->addField('published_at', 'timestamp')
            ->addField('content', 'text')
            ->addField('accept_comments', 'bool')
            ;
    }
}
