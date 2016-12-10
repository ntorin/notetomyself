<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Website;
use App\Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function saveUserData(Request $request)
    {
        $user = User::where('id', $request->input('userid'))->first();
        $user->notes = $request->input('notes');
        $user->tbd = $request->input('tbd');
        $user->save();

        $target_dir = asset('images/' . $user->email);

        $this->confirmDirExists($target_dir);

        $websites = $request->input('websites[]');
        $storedsites = Website::where('user_id', $request->input('userid'))->get();

        $i = 0;
        foreach ($storedsites as $storedsite) {
            $storedsite->url = $websites[$i];
            $storedsite->save();
            $i++;
        }

        $deletes = $request->input('deletes[]');
        Image::destroy($deletes);


        if(!strcmp(trim($websites[$i]), "")){
            Website::create([
                'url' => $websites[$i],
                'user_id' => $request->input('userid'),
            ]);
        }

        $img = $request->input('file');

        $imageFileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        $imgcount = Image::where('id', $request->input('userid'))->take(10)->get()->count();

        //uploads file if correct type
        if ($imgcount <= 4 && ($imageFileType == "jpg" || $imageFileType == "gif")) {
            $name = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
            $target_file = $target_dir . "/" . time() . $name . "." . $imageFileType;
            move_uploaded_file($_FILES['file']['tmp_name'], ($target_file));
            createThumbnail($target_file);
        }

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
