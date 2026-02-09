<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Language extends Model
{
    protected $table = 'languages';

    protected $primaryKey = 'language_id';

    public $timestamps = false;

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'code',
        'file',
    ];
}
