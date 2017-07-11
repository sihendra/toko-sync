<?php

namespace Tokosync\Resources;
use PHPUnit\Framework\TestCase;
use Tokosync\Filters\CharLimitFilter;
use Tokosync\Resources\SyncItem;

/**
 * Created by PhpStorm.
 * User: hendra
 * Date: 7/11/17
 * Time: 4:17 PM
 */
class MemorySyncResourceTest extends TestCase
{

    public function test_items()
    {
        $res = new MemorySyncResource();
        $res->add(new SyncItem());
        $res->add(new SyncItem());
        $res->add(new SyncItem());

        $this->assertCount(3, $res->items());
    }

    public function test_update()
    {

        $item1 = new SyncItem();
        $item1->setId(1);
        $item1->setName('Item 1');

        $item2 = new SyncItem();
        $item2->setId(2);
        $item2->setName('Item 2');

        $item2_update = new SyncItem();
        $item2_update->setId(2);
        $item2_update->setName('Item 2 Updated');

        $res = new MemorySyncResource();
        $res->add($item1);
        $res->add($item2);

        $res->update(2, $item2_update, $item2);

        $updated = null;
        foreach($res->items() as $i) {
            if ($i->getName() == $item2_update->getName()) {
                $updated = $i;
                break;
            }
        }

        $this->assertNotNull($updated);

        $this->assertEquals($item2_update->getName(), $updated->getName());


    }

    public function test_remove()
    {
        $item1 = new SyncItem();
        $item1->setId(1);
        $item1->setName('Item 1');

        $item2 = new SyncItem();
        $item2->setId(2);
        $item2->setName('Item 2');

        $res = new MemorySyncResource();
        $res->add($item1);
        $res->add($item2);

        $res->remove(2);

        $this->assertCount(1, $res->items());

        $this->assertEquals($item1->getId(), $res->items()[0]->getId());
    }

    public function test_list_read_filters()
    {
        
        $res = new MemorySyncResource();
        $res->addReadFilter(new CharLimitFilter());
        $res->addReadFilter(new CharLimitFilter());


        $this->assertCount(2, $res->readFilters());
    }

    public function test_list_write_filters()
    {

        $res = new MemorySyncResource();
        $res->addWriteFilter(new CharLimitFilter());
        $res->addWriteFilter(new CharLimitFilter());


        $this->assertCount(2, $res->writeFilters());
    }

}