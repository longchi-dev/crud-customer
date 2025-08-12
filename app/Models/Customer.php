<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $uuid
 * @property string $name
 * @property float $totalAmount
 * @property int $vipLevel
 * @property string $createdBy
 * @mixin Builder
 */
class Customer extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'totalAmount',
        'vipLevel',
        'createdBy'
    ];

    protected $primaryKey = 'id';

    public static function make(
        string $name,
        float $totalAmount,
        int $vipLevel
    ): Customer
    {
        return new static([
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'totalAmount' => $totalAmount,
            'vipLevel' => $vipLevel,
            'createdBy' => $name
        ]);
    }

}

