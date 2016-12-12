<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Website;
use App\Image;
use Illuminate\Support\Facades\Redirect;

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

        $websites = $request->input('websites');
        $storedsites = Website::where('user_id', $request->input('userid'))->get();

        $i = 0;
        foreach ($storedsites as $storedsite) {
            $storedsite->url = $websites[$i];
            $storedsite->save();
            $i++;
        }

        $deletes = $request->input('deletes');
        Image::destroy($deletes);


        if(!strcmp(trim($websites[$i]), "")){
            Website::create([
                'url' => $websites[$i],
                'user_id' => $request->input('userid'),
            ]);
        }

        $imageFileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        //dd($imageFileType);

        $imgcount = Image::where('id', $request->input('userid'))->take(10)->get()->count();
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

        return Redirect::to('/home');
    }

    public function getFullImage(Request $request, $imgid){
        $img = Images::where('id', $imgid)->get()->first();

        echo '
            <img src="data:image/' . $img->filetype . ';base64,' . $img->fullbin .'">';
    }

}
