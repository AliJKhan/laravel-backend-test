<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    public static string $BADGE_TITLE;
    public static int $UNLOCKS_AT;

    /**
     * @return mixed
     * Get the next available badge
     */
    public function next():mixed
    {
        return (Badge::where('id', '>', $this->id)->orderBy('id','asc')->first() ? Badge::where('id', '>', $this->id)->orderBy('id','asc')->first(): '') ;

    }
}
