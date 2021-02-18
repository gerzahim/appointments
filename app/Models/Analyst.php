<?php

namespace App\Models;
use App\Models\Scopes\Searchable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Analyst extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'created_at',
        'updated_at',
        'is_active',
    ];
    protected $dates = ['deleted_at'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    
    public function appoinments()
    {
        return $this->hasMany(Appoinment::class);
    }
    
    
}
