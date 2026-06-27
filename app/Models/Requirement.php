<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'procedure_id',
        'libelle',
        'description',
        'est_obligatoire',
    ];

    protected function casts(): array
    {
        return [
            'est_obligatoire' => 'boolean',
        ];
    }

    // ─── Relations ──────────────────────────────────────────────────────────

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }
}
