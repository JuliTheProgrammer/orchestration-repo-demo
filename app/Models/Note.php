<?php

namespace App\Models;

use Database\Factories\NoteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Note extends Model
{
    /** @use HasFactory<NoteFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = ['title', 'body', 'reactions'];

    /** @var array<int, string> */
    public const REACTIONS = ['👍', '❤️', '😂', '🎉', '😮', '😢'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['reactions' => 'array'];
    }

    public function react(string $emoji): void
    {
        if (! in_array($emoji, self::REACTIONS, true)) {
            return;
        }

        $reactions = $this->reactions ?? [];
        $reactions[$emoji] = ($reactions[$emoji] ?? 0) + 1;

        $this->update(['reactions' => $reactions]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'body', 'reactions'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
