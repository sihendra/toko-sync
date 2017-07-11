<?php
/**
 * Created by PhpStorm.
 * User: hendra
 * Date: 27/05/17
 * Time: 15:25
 */

namespace Tokosync;


class SyncService
{
    /**
     * @var SyncResource
     */
    protected $source;

    /**
     * @var SyncResource
     */
    protected $dest;

    /**
     * @var boolean
     */
    protected $delete_on_missing;

    /**
     * @var boolean
     */
    protected $dry_run;

    /**
     * @var SyncItem[]
     */
    protected $source_items = [];

    /**
     * @var SyncItem[]
     */
    protected $dest_items = [];

    /**
     * @var array
     */
    protected $new_items = [];

    /**
     * @var array
     */
    protected $update_items = [];

    /**
     * @var array
     */
    protected $missing_items = [];


    /**
     * @param SyncResource $source
     * @return SyncService
     */
    public function source($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @param SyncResource $dest
     * @return SyncService
     */
    public function destination($dest)
    {
        $this->dest = $dest;
        return $this;
    }

    /**
     * @return SyncService
     */
    public function deleteOnMissing()
    {
        $this->delete_on_missing = true;
        return $this;
    }

    /**
     * @return SyncService
     */
    public function doNotDeleteOnMissing()
    {
        $this->delete_on_missing = false;
        return $this;
    }

    /**
     * @return SyncService
     */
    public function dryRun()
    {
        $this->dry_run = true;
        return $this;
    }

    public function sync()
    {
        $this->readSourceItems();
        $this->readDestinationItems();

        $this->findNewItems();
        $this->addNewItems();
        $this->findUpdateItems();
        $this->updateExistingItems();

        if ($this->delete_on_missing) {
            $this->findMissingItems();
            $this->removeMissingItems();
        }
    }

    private function readSourceItems()
    {
        $this->source_items = $this->source->items();

        $names = collect($this->source_items)->map(function($el){ return $el['name']; })->all();
        printf("Source Items: %s\n", json_encode($names));
    }

    private function readDestinationItems()
    {
        $this->dest_items = $this->dest->items();

        $names = collect($this->dest_items)->map(function($el){ return $el['name']; })->all();
        printf("Dest Items: %s\n", json_encode($names));
    }

    private function addNewItems()
    {
        foreach ($this->new_items as $item) {
            !$this->dry_run && $this->dest->add($item);
        }
    }

    private function updateExistingItems()
    {
        foreach ($this->update_items as $source_dest_pair) {
            $source_item = $source_dest_pair[0];
            $dest_item = $source_dest_pair[1];

            !$this->dry_run && $this->dest->update($dest_item['id'], $source_item, $dest_item);
        }
    }

    private function removeMissingItems()
    {
        foreach ($this->missing_items as $item) {
            !$this->dry_run && $this->dest->remove($item['id']);
        }
    }

    private function findNewItems()
    {
        $this->new_items = [];
        foreach ($this->source_items as $source_item) {
            $exists = false;
            foreach($this->dest_items as $dest_item) {
                if ($dest_item->isSimilar($source_item)) {
                    $exists = true;
                }
            }

            if (!$exists) {
                $this->new_items[] = $source_item;
            }
        }

        $names = collect($this->new_items)->map(function($el){ return $el['name']; })->all();
        printf("New Items: %s\n", json_encode($names));
    }

    private function findUpdateItems()
    {
        $this->update_items = [];
        foreach ($this->source_items as $source_item) {
            foreach($this->dest_items as $dest_item) {
                if ($dest_item->isSimilar($source_item)) {
                    $this->update_items[] = [$source_item, $dest_item];
                    break;
                };
            }
        }

        $names = collect($this->update_items)->map(function($el){ return $el[1]['name']; })->all();
        printf("Update Items: %s\n", json_encode($names));

    }

    private function findMissingItems()
    {
        $this->missing_items = [];
        foreach ($this->dest_items as $dest_item) {
            $exists = false;
            foreach($this->source_items as $source_item) {
                if ($source_item->isSimilar($dest_item)) {
                    $exists = true;
                };
            }

            if (!$exists) {
                $this->missing_items[] = $dest_item;
            }
        }

        $names = collect($this->missing_items)->map(function($el){ return $el['name']; })->all();
        printf("Missing Items: %s\n", json_encode($names));

    }

}