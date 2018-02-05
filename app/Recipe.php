<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'instructions', 'prep_time', 'cooking_time', 'total_cooking_time', 'num_of_people'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    /**
     * Get the user associated with this recipe.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the ingredients associated with this recipe.
     */
    public function ingredients()
    {
        return $this->hasMany('App\Ingredient');
    }

    /**
     * Get the categories that this recipe is in.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }


}
