<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Collective\Html\FormFacade;

class Images extends Model
{
    protected $table = 'images';
    protected $guarded = [];
    public $timestamps = false;

    public static function getUserImages($userid)
    {
        $images = Images::where('user_id', $userid);
        //$images = Images::where('user_id', '=', $userid)->get();
        foreach ($images as $image) {
            $basename = $image->thumbin;

            echo '<a href="/getfullimg/' . $image->id. '">
            <img src="data:image/' . $image->filetype . ';base64,' . $image->thumbin .'">
            </a>
            ';
            /*echo '
               <a href="' . '" target="_blank">
                    <img src="' . asset('images/') . $username . "/" . $basename . '" alt="' . $basename .'"/>
                </a>'; */
            echo FormFacade::checkbox('delete[]', $image->id);
            echo FormFacade::label('delete[]', 'delete');
            echo "<br/>";
        }
    }
}
