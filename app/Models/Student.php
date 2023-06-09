<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    use Searchable, Filterable;

    protected $searchFields = ['first_name', 'last_name', 'email'];

    protected $guarded = ['id'];

    protected $appends = [
        'full_name',
    ];

    public function getFullNameAttribute()
    {
        $names = [];
        foreach (['first_name', 'last_name'] as $key) {
            $value = $this->getAttribute($key);
            if (!empty($value)) {
                $names[] = $value;
            }
        }
        return implode(' ', $names);
    }
}
