<?php
/**
 * Created by PhpStorm.
 * User: hendra
 * Date: 27/05/17
 * Time: 17:07
 */

namespace Tokosync\Filters;


use Tokosync\SyncItem;

interface SyncFilter
{

    public function setFields($fields);
    
    public function apply(SyncItem $item);
}