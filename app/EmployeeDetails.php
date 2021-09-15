<?php

namespace App;

use App\Observers\EmployeeDetailsObserver;
use App\Traits\CustomFieldsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetails extends BaseModel
{
  //  use CustomFieldsTrait;

    protected $table = 'employee_details';

    protected $dates = ['joining_date', 'last_date'];

    protected static function boot()
    {
        parent::boot();
      //  static::observe(EmployeeDetailsObserver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function department()
    {
        return $this->belongsTo(Team::class, 'department_id');
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
