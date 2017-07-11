<?php

namespace Tokosync\Resources;

use Tokosync\Filters\SyncFilter;
use Tokosync\SyncItem;

/**
 * Created by PhpStorm.
 * User: hendra
 * Date: 7/11/17
 * Time: 4:17 PM
 */
class MemorySyncResource implements SyncResource
{

    private $items = [];
    private $readFilters = [];
    private $writeFilters = [];

    /**
     * @return SyncItem[]
     */
    public function items()
    {
        return $this->items;
    }

    public function add(SyncItem $item)
    {
        $this->items[] = $item;
    }

    public function update($id, SyncItem $new_data, SyncItem $old_data)
    {
        $this->items = collect($this->items)->map(function ($item, $key) use ($id, $new_data, $old_data) {
            if ($item['id'] == $id) {
                return $new_data;
            }

            return $item;
        })->all();
    }

    public function remove($id)
    {
        $this->items = collect($this->items)->filter(function ($val, $key) use ($id) {
            return $id != $val['id'];
        })
            ->values()
            ->all();
    }

    /**
     * Filter that will be called foreach read items
     * @param SyncFilter $filter
     * @return mixed
     */
    public function addReadFilter(SyncFilter $filter)
    {
        $this->readFilters[] = $filter;
    }

    /**
     * Filter that will be called foreach items before write
     * @param SyncFilter $filter
     * @return mixed
     */
    public function addWriteFilter(SyncFilter $filter)
    {
        $this->writeFilters[] = $filter;
    }

    public function readFilters()
    {
        return $this->readFilters;
    }

    public function writeFilters()
    {
        return $this->writeFilters;
    }
}