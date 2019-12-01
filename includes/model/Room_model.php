<?php

use Engelsystem\Database\DB;
use Engelsystem\Models\Room;
use Engelsystem\ValidationResult;

/**
 * Validate a name for a room.
 *
 * @param string $name    The new name
 * @param int    $room_id The room id
 * @return ValidationResult
 */
function Room_validate_name($name, $room_id)
{
    $valid = true;
    if (empty($name)) {
        $valid = false;
    }

    if (Room::where('name', $name)->where('id', '<>', $room_id)->count() > 0) {
        $valid = false;
    }

    return new ValidationResult($valid, $name);
}

/**
 * Returns Room id array
 *
 * @return array
 */
function Room_ids()
{
    $result = DB::select('SELECT `id` FROM `rooms`');
    return select_array($result, 'id', 'id');
}

/**
 * Delete a room
 *
 * @param Room $room
 */
function Room_delete(Room $room)
{
    $room->delete();
    engelsystem_log('Room deleted: ' . $room->name);
}

/**
 * Delete a room by its name
 *
 * @param string $name
 */
function Room_delete_by_name($name)
{
    Room::where('name', $name)->delete();
    engelsystem_log('Room deleted: ' . $name);
}

/**
 * Create a new room
 *
 * @param string  $name      Name of the room
 * @param boolean $from_frab Is this a frab imported room?
 * @param string  $map_url   URL to a map tha can be displayed in an iframe
 * @param string description Markdown description
 * @return Room
 */
function Room_create($name, $from_frab, $map_url, $description)
{
    $room = Room::create(
        [
            'name'        => $name,
            'from_frab'   => $from_frab,
            'map_url'     => $map_url,
            'description' => $description,
        ]
    );

    engelsystem_log(
        'Room created: ' . $name
        . ', frab import: ' . ($from_frab ? 'Yes' : '')
        . ', map_url: ' . $map_url
        . ', description: ' . $description
    );

    return $room;
}

/**
 * update a room
 *
 * @param int     $room_id     The rooms id
 * @param string  $name        Name of the room
 * @param boolean $from_frab   Is this a frab imported room?
 * @param string  $map_url     URL to a map tha can be displayed in an iframe
 * @param string  $description Markdown description
 * @return Room
 */
function Room_update($room_id, $name, $from_frab, $map_url, $description)
{
    $room = Room::find($room_id);
    $room->name = $name;
    $room->from_frab = $from_frab;
    $room->map_url = $map_url;
    $room->description = $description;
    $room->save();

    engelsystem_log(
        'Room updated: ' . $name .
        ', frab import: ' . ($from_frab ? 'Yes' : '') .
        ', map_url: ' . $map_url .
        ', description: ' . $description
    );

    return $room;
}
