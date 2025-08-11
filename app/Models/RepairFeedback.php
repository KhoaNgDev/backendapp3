<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepairFeedback extends Model
{
    use HasFactory;

    protected $fillable = ['repair_id', 'feedback', 'rating'];

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }
    public function adminReplies()
    {
        return $this->hasMany(AdminReply::class, 'repair_feedback_id');
    }

}
