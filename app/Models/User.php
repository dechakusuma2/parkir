<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name','email','password','role'];
    protected $hidden = ['password','remember_token'];

    public function isOwner()  { return $this->role === 'owner'; }
    public function isAdmin()  { return $this->role === 'admin'; }
    public function isPetugas(){ return $this->role === 'petugas'; }
}
