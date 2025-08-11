<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminReply extends Model
{
    protected $fillable = ['repair_feedback_id', 'reply'];

    public function repairFeedback()
    {
        return $this->belongsTo(RepairFeedback::class);
    }
}
