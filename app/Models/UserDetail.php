<?php

namespace app\Models;

use App\Enum\Activity;
use App\Enum\Gender;
use App\Enum\Goal;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use HasFactory, SoftDeletes, HasTimestamps;

    protected $connection = 'mongodb';

    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'height',
        'weight',
        'birthday',
        'bmi',
        'bmr',
        'gender',
        'activity',
        'goal',
        'user_id'
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birthday' => 'date',
            'bmi' => 'double',
            'bmr' => 'double',
            'height' => 'double',
            'weight' => 'double',
            'gender' => Gender::class,
            'activity' => Activity::class,
            'goal' => Goal::class
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
