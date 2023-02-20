<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\ChildrenPickedUp;
class Children extends Model
{
    use HasFactory;
    public static $rules = [
        'name'             => 'required|string|max:70',
        'date_of_birth'    => 'required|string',
        'class'            => 'required|string',
        'country_id'       => 'required',
        'state_id'         => 'required',
        'city_id'          => 'required',
        'zipcode'          => 'required|numeric',
        'address'          => 'required|string|min:3|max:300',
        'photo'            => 'required|max:10000'
    ];
    protected $appends = ['created_at_show'];

    public function getCreatedAtShowAttribute()
    {
        if (isset($this->attributes['date_of_birth']) && $this->attributes['date_of_birth'] != null) {
            return date('m/d/Y', strtotime($this->attributes['date_of_birth']));
        }
        return 'N/A';
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id')->where('status', 1)->orderBy('name','ASC');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id')->where('status', 1)->orderBy('name','ASC');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id')->where('status', 1)->orderBy('name','ASC');
    }

    public function details()
    {
        return $this->hasMany(ChildrenPickedUp::class, 'children_id', 'id')->orderBy('name','ASC');
    }
}
