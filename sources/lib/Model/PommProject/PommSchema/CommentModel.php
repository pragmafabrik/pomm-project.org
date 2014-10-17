<?php

namespace Model\PommProject\PommSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\ModelTrait\WriteTrait;

use PommProject\Foundation\Where;

use Model\PommProject\PommSchema\AutoStructure\Comment as CommentStructure;
use Model\PommProject\PommSchema\Comment;

/**
 * CommentModel
 *
 * Model class for table comment.
 *
 * @see Model
 */
class CommentModel extends Model
{
    use WriteTrait;

    /**
     * __construct()
     *
     * Model constructor
     *
     * @access public
     * @return void
     */
    protected function __construct()
    {
        $this->structure = new CommentStructure;
        $this->flexible_entity_class = "\Model\PommProject\PommSchema\Comment";
    }
}
