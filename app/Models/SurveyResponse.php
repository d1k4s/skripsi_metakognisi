<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'pertanyaan_id',
        'jawaban',
    ];

    /**
     * Get the survey that owns the response.
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
