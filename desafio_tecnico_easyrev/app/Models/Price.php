<?php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'price',
        'effective_date',
    ];

    /**
     * Define o relacionamento: um preço (Price) pertence a um quarto (Room).
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}