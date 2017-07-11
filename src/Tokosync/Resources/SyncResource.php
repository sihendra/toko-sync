<?php
/**
 * Created by PhpStorm.
 * User: hendra
 * Date: 27/05/17
 * Time: 15:29
 */

namespace Tokosync\Resources;


use Tokosync\Filters\SyncFilter;
use Tokosync\SyncItem;

interface SyncResource
{

    /**
     * @return SyncItem[]
     */
    public function items();

    public function add(SyncItem $item);

    public function update($id, SyncItem $new_data, SyncItem $old_data);

    public function remove($id);


    public function readFilters();

    /**
     * Filter that will be called on each read items
     * @param SyncFilter $filter
     * @return mixed
     */
    public function addReadFilter(SyncFilter $filter);

    public function writeFilters();

    /**
     * Filter that will be called on each items before write
     * @param SyncFilter $filter
     * @return mixed
     */
    public function addWriteFilter(SyncFilter $filter);
}