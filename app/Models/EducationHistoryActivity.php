<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationHistoryActivity extends Model
{
    protected $table = 'education_history_activities';
    protected $fillable = ['education_name', 'tgl_input', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
