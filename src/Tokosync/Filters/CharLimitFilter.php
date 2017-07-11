<?php
/**
 * Created by PhpStorm.
 * User: hendra
 * Date: 7/11/17
 * Time: 5:48 PM
 */

namespace Tokosync\Filters;


use Tokosync\Resources\SyncItem;

class CharLimitFilter implements SyncFilter
{

    protected $fields = [];

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function apply(SyncItem $item)
    {
        if (empty($this->fields)) return;


    }
}