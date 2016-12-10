<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $guarded = [];

    public static function getUserImages($userid, $username)
    {
        $images = Image::where('user_id', '=', $userid)->get();
        foreach ($images as $image) {
            $basename = basename($image->url);

            echo '
               <a href="' . $image->url . '" target="_blank">
                    <img src="' . asset('images/') . $username . "/" . $basename . '" alt="' . $basename .'"/>
                </a>';
            echo Form::checkbox('delete[]', $image->id);
            echo Form::label('delete[]', 'delete');
            echo "<br/>";
        }
    }
}
