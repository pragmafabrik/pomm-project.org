<?php

namespace Model\PommProject\Pomm\Base;

use \Pomm\Object\BaseObjectMap;
use \Pomm\Exception\Exception;

abstract class NewsMap extends BaseObjectMap
{
    public function initialize()
    {

        $this->object_class =  'Model\PommProject\Pomm\News';
        $this->object_name  =  'pomm.news';

        $this->addField('slug', 'varchar');
        $this->addField('title', 'varchar');
        $this->addField('created_at', 'timestamp');
        $this->addField('published_at', 'timestamp');
        $this->addField('content', 'text');
        $this->addField('accept_comments', 'bool');

        $this->pk_fields = array('slug');
    }
}
