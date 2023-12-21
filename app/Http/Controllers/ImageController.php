<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{

    public function index(): View
    {
        
    }
    public function storeImage(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if($user->cannot('create', Image::class)){
            return redirect()->back();
        }
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $imageName = time().'.'.$request->image->extension();

        $request->image->move(public_path('images'), $imageName);

        $image = new Image();
        $image->imagepath = $imageName;
        $image->save();


        $affected = DB::table('users')
              ->where('id', $id)
              ->update(['profilepictureid' => $image->imageid]);

        return redirect('/home');
    }
}
