<?php

declare(strict_types=1);

namespace Engelsystem\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer $id
 * @property string $name
 * @property boolean $from_frab
 * @property string|null $map_url
 * @property string|null $description
 * @method static Builder|Room newModelQuery()
 * @method static Builder|Room newQuery()
 * @method static Builder|Room query()
 * @method static Builder|Room whereDescription($value)
 * @method static Builder|Room whereFromFrab($value)
 * @method static Builder|Room whereId($value)
 * @method static Builder|Room whereMapUrl($value)
 * @method static Builder|Room whereName($value)
 */
class Room extends BaseModel
{
    /** @var string[] */
    protected $fillable = [
        'name',
        'from_frab',
        'map_url',
        'description',
    ];

    /** @var string[] */
    protected $casts = [
        'from_frab' => 'boolean',
    ];

    /** @var array */
    protected $attributes = [
        'from_frab' => false,
    ];
}
