<?php

namespace App\Models;

use App\Models\Scopes\Searchable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];

    protected $searchableFields = ['*'];
    
    
    
    public function appoinments()
    {
        return $this->hasMany(Appoinment::class);
    }
    
}
