<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    
    const UPDATED_AT = null; 
    

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'username',
        'telefono',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    
    public function managedProjects() { return $this->hasMany(Project::class, 'manager_id'); }
    public function collaboratingProjects() { return $this->belongsToMany(Project::class, 'project_collaborators', 'user_id', 'project_id'); }
}