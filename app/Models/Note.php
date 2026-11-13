<?php

namespace App\Models;

use Database\Factories\NoteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /** @use HasFactory<NoteFactory> */
    use HasFactory;

    protected $fillable = ['title', 'body'];

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
}
