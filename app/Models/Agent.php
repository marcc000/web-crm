<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agent';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_id',
        'zone',
        'name',
    ];

     /**
     * Get the zones of the agent.
     */
    public function zones(): HasMany
    {
        return $this->hasMany(AgentZone::class, 'agent_id', 'agent_id');
    }
}
