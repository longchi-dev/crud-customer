<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $name
 * @property float $totalAmount
 * @property int $vipLevel
 * @property string $createdBy
 * @mixin Builder
 */
class Customer extends Model implements \JsonSerializable
{
    protected $fillable = ['id', 'name', 'totalAmount', 'vipLevel', 'createdBy'];

    protected $primaryKey = 'id';

    public static function make(array $attributes): Customer
    {
        $instance = new static();

        if (empty($attributes['id'])) {
            $attributes['id'] = (string)Str::uuid();
        };

        if (!empty($attributes['name'])) {
            $attributes['createdBy'] = $attributes['name'];
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

