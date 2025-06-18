<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nim',
        'jurusan',
    ];

    /**
     * Get the responses for the survey.
     */
    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
