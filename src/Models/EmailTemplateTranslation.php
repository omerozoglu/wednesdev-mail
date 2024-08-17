<?php

namespace Wednesdev\Mail\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplateTranslation extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'email_template_id',
        'locale',
        'subject',
        'body'
    ];

    /**
     * @return BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }
}
