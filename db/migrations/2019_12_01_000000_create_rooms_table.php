<?php

declare(strict_types=1);

namespace Engelsystem\Migrations;

use Engelsystem\Database\Migration\Migration;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Schema\Blueprint;
use stdClass;

/**
 * This migration creates the "rooms" table and migrates the existing "Room" records to the new table.
 */
class CreateRoomsTable extends Migration
{
    use ChangesReferences;

    /**
     * @return void
     */
    public function up(): void
    {
        $this->createRoomsTable();

        if ($this->schema->hasTable('Room')) {
            $this->migrateRoomRecords();
            $this->changeReferences(
                'Room',
                'RID',
                'rooms',
                'id',
                'unsignedInteger'
            );
            $this->schema->drop('Room');
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->createRoomTable();
        $this->migrateRoomsRecords();
        $this->changeReferences(
            'rooms',
            'id',
            'Room',
            'RID',
            'unsignedInteger'
        );
        $this->schema->drop('rooms');
    }

    /**
     * @return void
     */
    private function createRoomsTable(): void
    {
        $this->schema->create(
            'rooms',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 35)->default('');
                $table->boolean('from_frab')->default(0);
                $table->string('map_url', 300)->nullable();
                $table->text('description')->nullable();
                $table->index('name');
            }
        );
    }

    /**
     * @return void
     */
    private function createRoomTable(): void
    {
        $this->schema->create(
            'Room',
            function (Blueprint $table) {
                $table->increments('RID');
                $table->string('Name', 35)->default('');
                $table->tinyInteger('from_frab');
                $table->string('map_url', 300)->nullable();
                $table->text('description')->nullable();
            }
        );
    }

    /**
     * Migrates the records from the previous "Room" table to the new "rooms" table.
     *
     * @return void
     */
    private function migrateRoomsRecords(): void
    {
        $connection = $this->schema->getConnection();
        /** @var Collection|stdClass[] $roomsRecords */
        $roomsRecords = $connection
            ->table('rooms')
            ->get();

        foreach ($roomsRecords as $roomsRecord) {
            $connection->table('Room')->insert(
                [
                    'RID'         => $roomsRecord->id,
                    'Name'        => $roomsRecord->name,
                    'from_frab'   => $roomsRecord->from_frab,
                    'map_url'     => $roomsRecord->map_url,
                    'description' => $roomsRecord->description,
                ]
            );
        }
    }

    /**
     * Migrates the records from the new "rooms" table to the previous "Room" table.
     *
     * @return void
     */
    private function migrateRoomRecords(): void
    {
        $connection = $this->schema->getConnection();
        /** @var stdClass[] $roomRecords */
        $roomRecords = $connection
            ->table('Room')
            ->get();

        foreach ($roomRecords as $roomRecord) {
            $connection->table('rooms')->insert(
                [
                    'id'          => $roomRecord->RID,
                    'name'        => $roomRecord->Name,
                    'from_frab'   => $roomRecord->from_frab,
                    'map_url'     => $roomRecord->map_url,
                    'description' => $roomRecord->description,
                ]
            );
        }
    }
}
