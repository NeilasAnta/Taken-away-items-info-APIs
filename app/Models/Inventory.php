<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Inventory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected  $with = ['user'];

    public $timestamps = true;

    public function scopeFilter($query, array $filters) //Post::newQuery()->filter()
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->whereHas('user', function ($query) use ($search) {
                return $query->where(DB::raw('concat(name," ",surname)') , 'LIKE' , '%' . $search .'%')
                    ->orWhere('email', 'like', '%' . $search. '%');
            })->orWhere('model', 'like', '%' . $search. '%')
              ->orWhere('serial_number', 'like', '%' . $search. '%')
              ->orWhere('name', 'like', '%' . $search. '%');
        }
        );
        $query->when($filters['status'] ?? false, function ($query, $search) use ($filters) {

            return ($filters['status'] == 'assign') ? $query->whereNotNull('user_id') : $query->where('user_id', null);
        }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
