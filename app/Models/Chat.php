<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'users' => 'array',
    ];

    public function setUsers($value)
    {
        if (!is_array($value)) {
            $value = json_decode($value);
        }

        $users = [];

        foreach ($value as $array_item) {
            $users[] = $array_item;
        }

        $this->attributes['users'] = json_encode($users);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function not_read_messages()
    {
        return $this->messages()->where('read_at', null)->where('receiver_id', auth()->user()->id);
    }
}
