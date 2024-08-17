<?php

namespace Wednesdev\Mail\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailAttachment extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'email_id',
        'file_path',
        'file_name',
        'mime_type'
    ];

    /**
     * @return BelongsTo
     */
    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }
}
