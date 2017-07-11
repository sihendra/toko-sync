<?php
/**
 * Created by PhpStorm.
 * User: hendra
 * Date: 27/05/17
 * Time: 15:25
 */

namespace Tokosync;


use PHPUnit\Framework\TestCase;
use Tokosync\Resources\MemorySyncResource;

class SyncServiceTest extends TestCase
{
    public function test_sync() {

        $source = new MemorySyncResource();
        $source->add((new SyncItem())->setId(1)->setName('item 1'));
        $source->add((new SyncItem())->setId(2)->setName('item 2'));
        $source->add((new SyncItem())->setId(3)->setName('item 3'));

        $dest = new MemorySyncResource();


        (new SyncService())
            ->source($source)
            ->destination($dest)
            ->sync();

        $this->assertCount(3,$dest->items());
    }
}