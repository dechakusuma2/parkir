<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/** Merepresentasikan pengguna terautentikasi beserta peran dan labelnya. */
class User extends Authenticatable
{
    /** Menentukan atribut pengguna yang dapat diisi dan dirahasiakan. */
    protected $fillable = ['name', 'email', 'password', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    /** Memeriksa apakah pengguna memiliki peran pemilik. */
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    /** Memeriksa apakah pengguna memiliki peran admin. */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /** Memeriksa apakah pengguna memiliki peran petugas. */
    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    /** Menyediakan HTML lencana berdasarkan peran pengguna. */
    public function getRoleBadgeAttribute(): string
    {
        $badges = [
            'admin' => ['label' => 'Admin', 'class' => 'bg-primary'],
            'petugas' => ['label' => 'Petugas', 'class' => 'bg-info'],
            'owner' => ['label' => 'Owner', 'class' => 'bg-warning'],
        ];
        $badge = $badges[$this->role] ?? ['label' => ucfirst($this->role), 'class' => 'bg-secondary'];

        return '<span class="badge '.$badge['class'].'">'.e($badge['label']).'</span>';
    }
}
