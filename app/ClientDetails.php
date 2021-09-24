<?php

namespace App;

use App\Traits\CustomFieldsTrait;
use Illuminate\Database\Eloquent\Model;

class ClientDetails extends BaseModel
{
    use CustomFieldsTrait;

    protected $fillable = [
        'user_id',
        'address',
        'postal_code',
        'country',
        'state',
        'city',
        'office',
        'cell',
        'note',
        'category_id'
    ];

    protected $default = [
        'id',
        'address',
        'country',
        'state',
        'city',
        'postal_code',
        'note',
        'name',
        'email',
        'category_id'
    ];

    protected $table = 'client_details';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function clientCategory()
    {
        return $this->belongsTo(ClientCategory::class, 'category_id');
    }
    public function countries()
    {
        return $this->belongsTo(Country::class, 'country');
    }
    public function states()
    {
        return $this->belongsTo(State::class, 'state');
    }
}
