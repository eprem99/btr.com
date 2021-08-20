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
        'note',
        'gst_number',
        'name',
        'email',
        'category_id'
    ];

    protected $table = 'client_details';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }
    public function clientCategory()
    {
        return $this->belongsTo(ClientCategory::class, 'category_id');
    }
}
