<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $name
 * @property float $total_amount
 * @property int $vip_level
 * @property string $created_by
 */
class Customer extends Model implements \JsonSerializable
{
    protected $fillable = ['id', 'name', 'total_amount', 'vip_level', 'created_by'];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public static function make(array $attributes): Customer
    {
        $instance = new self();

        if (empty($attributes['id'])) {
            $attributes['id'] = (string)Str::uuid();
        };

        if (!empty($attributes['name'])) {
            $attributes['created_by'] = $attributes['name'];
        }

        foreach ($attributes as $key => $value) {
            if (in_array($key, $instance->fillable)) {
                $instance->$key = $value;
            }
        }

        return $instance;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}

