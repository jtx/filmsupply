<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeveloperResume extends Model
{
    protected $table = 'developer_resume';

    protected $fillable = ['resume_data'];
}
