<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}

