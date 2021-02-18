<?php

namespace App\Models;

use App\Models\Scopes\Searchable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Appoinment extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;


    protected $fillable = [
        'date',
        'time',
        'name',
        'email',
        'phone',
        'note',
        'service_id',
        'analyst_id',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $dates = ['deleted_at'];

    protected $searchableFiels = ['*'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function analyst()
    {
        return $this->belongsTo(Analyst::class);
    }
}
