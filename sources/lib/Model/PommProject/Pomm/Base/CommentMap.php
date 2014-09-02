<?php

namespace Model\PommProject\Pomm\Base;

use \Pomm\Object\BaseObjectMap;
use \Pomm\Exception\Exception;

abstract class CommentMap extends BaseObjectMap
{
    public function initialize()
    {

        $this->object_class =  'Model\PommProject\Pomm\Comment';
        $this->object_name  =  '"pomm"."comment"';

        $this->addField('id', 'int4');
        $this->addField('article_slug', 'varchar');
        $this->addField('email', 'varchar');
        $this->addField('created_at', 'timestamp');
        $this->addField('website', 'varchar');
        $this->addField('content', 'text');
        $this->addField('name', 'varchar');

        $this->pk_fields = array('');
    }
}
