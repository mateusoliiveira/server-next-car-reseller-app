<?php
namespace App\Http\Controllers;

class MediaController extends Controller
{

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Product  $product
    * @return \Illuminate\Http\Response
    */
    public function getPhoto($filename)
    {
        $path = public_path().'/storage/images/'.$filename;
        return response()->download($path);
    }
}