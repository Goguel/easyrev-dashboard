<?php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Define o relacionamento: um quarto (Room) tem muitos preços (Price).
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }
}