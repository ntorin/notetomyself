<?php

namespace App\Http\Controllers;

use App\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;

class TestController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function saveUserData(Request $request)
    {
        //$user = User::where('id', $request->input('userid'))->first();
        //$user->notes = $request->input('notes');
        //$user->tbd = $request->input('tbd');
        //$user->save();

       // $target_dir = asset('images/' . $user->email);

        //$this->confirmDirExists($target_dir);


        //$img = $request->input('file');

        //$img = $_FILES['file']['name'];
        //dd($_FILES['file']['tmp_name']);

        $imageFileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        //dd($imageFileType);

        $imgcount = Images::where('id', $request->input('userid'))->take(10)->get()->count();
        $img = $_FILES['file']['tmp_name'];

        $thumb = imagecreatetruecolor(100, 100);

        switch ($imageFileType) {
            case 'jpg':
            case 'jpeg':
                $original = imagecreatefromjpeg($img);
                break;
            case 'gif':
                $original = imagecreatefromgif($img);
                break;
            case 'png':
            default:
                $img = false;
                break;
        }





        //uploads file if correct type

        if($imgcount < 4 && ($imageFileType == "jpg" || $imageFileType == "jpeg" ||$imageFileType == "gif")){

            imagecopyresampled($thumb, $original, 0, 0, 0, 0, 150, 150, imagesx($original), imagesy($original));
            imagejpeg($thumb, __DIR__ . '/tmp.' . $imageFileType, 100);

            Images::create([
                'user_id' => 0,
                'filetype' => $imageFileType,
                'fullbin' => base64_encode(file_get_contents($_FILES['file']['tmp_name'])),
                'thumbin' => base64_encode(file_get_contents(__DIR__ . '/tmp.' . $imageFileType)),
            ]);


            unlink(__DIR__ . '/tmp.' . $imageFileType);
        }


        /*if ($imgcount <= 4 && ($imageFileType == "jpg" || $imageFileType == "gif")) {
            $name = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
            $target_file = $target_dir . "/" . time() . $name . "." . $imageFileType;
            move_uploaded_file($_FILES['file']['tmp_name'], ($target_file));
            createThumbnail($target_file);
        } */


        return Redirect::to('/test');
    }

    private function createThumbnail($img){
        $thumb = imagecreatetruecolor(100, 100);

        $path_parts = pathinfo($img, PATHINFO_EXTENSION);

        switch ($path_parts) {
            case 'jpg':
            case 'jpeg':
                $original = imagecreatefromjpeg($img);
                break;
            case 'gif':
                $original = imagecreatefromgif($img);
                break;
            default:
                $img = false;
                break;


        }


        $name = pathinfo($img, PATHINFO_FILENAME);

        imagecopyresampled($thumb, $original, 0, 0, 0, 0, 100, 100, imagesx($original), imagesy($original));

        $shortlink = explode("/", $img);

        imagejpeg($thumb, asset('images/') . $name . "thumb" . "." . $path_parts, 100);
    }

    private function confirmDirExists($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

}
