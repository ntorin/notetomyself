<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $table = 'websites';
    protected $guarded = [];
    public $timestamps = false;

    public static function getUserWebsites($userid)
    {
        $websites = Website::where('user_id', '=', $userid)->get();

        foreach ($websites as $website) {
            echo '<input type="text" name="websites[]" value="' . $website->url . '" onClick="openInNew(this)">';
            echo "<br/>";
        }
    }

}
