<?php

declare(strict_types=1);

namespace Unit\Models;

use Engelsystem\Models\Room;
use Engelsystem\Test\Unit\HasDatabase;
use Engelsystem\Test\Unit\TestCase;

class RoomTest extends TestCase
{
    use HasDatabase;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->initDatabase();
    }

    /**
     * Tests that storing and loading a room works.
     *
     * @return void
     */
    public function testStoreLoadRoom(): void
    {
        $room1 = Room::create(
            [
                'name'        => 'test room 1',
                'from_frab'   => false,
                'map_url'     => 'map url 1',
                'description' => 'description 1',
            ]
        );
        $room2 = Room::create(
            [
                'name'      => 'test room 2',
                'from_frab' => true,
            ]
        );

        $rooms = Room::query()->orderBy('id')->get();
        $this->assertCount(2, $rooms);

        $dbRoom1 = $rooms[0];
        $this->assertSame($dbRoom1->id, $room1->id);
        $this->assertSame('test room 1', $dbRoom1->name);
        $this->assertFalse($dbRoom1->from_frab);
        $this->assertSame('map url 1', $dbRoom1->map_url);
        $this->assertSame('description 1', $dbRoom1->description);

        $dbRoom2 = $rooms[1];
        $this->assertSame($dbRoom2->id, $room2->id);
        $this->assertSame('test room 2', $dbRoom2->name);
        $this->assertTrue($dbRoom2->from_frab);
        $this->assertNull($dbRoom2->map_url);
        $this->assertNull($dbRoom2->description);
    }
}
