<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gallery = Gallery::paginate(4);
        return Inertia::render('Gallery/Index', [
            'gallery' => $gallery
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gallery = Gallery::paginate(4);
        return Inertia::render('Gallery/Create', [
            'gallery' => $gallery
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // dd($request);
         $request->validate([
            'title' => 'required|string',
            'about' => 'required|string',
            'video_id' => 'required|string',
            'image_logo' => 'required|image'
        ]);

        $filepath_image_logo = '';
        $filepath_image_1 = '';
        $filepath_image_2 = '';
        $filepath_image_3 = '';

        if ($request->hasFile('image_logo')) {
            $image_logo = $request->file('image_logo');
            $filepath_image_logo = $image_logo->store('public/image');
            $filepath_image_logo = str_replace('public/', '', $filepath_image_logo); // Remove 'public/' from the path
           
        }
        if ($request->hasFile('image1')) {
            $image_1 = $request->file('image1');
            $filepath_image_1 = $image_1->store('public/image');
            $filepath_image_1 = str_replace('public/', '', $filepath_image_logo); // Remove 'public/' from the path
          
        }
        if ($request->hasFile('image2')) {
            $image_2 = $request->file('image2');
            $filepath_image_2 = $image_2->store('public/image');
            $filepath_image_2 = str_replace('public/', '', $filepath_image_logo); // Remove 'public/' from the path
            
        }
        if ($request->hasFile('image3')) {
            $image_3 = $request->file('image3');
            $filepath_image_3 = $image_3->store('public/image');
            $filepath_image_3 = str_replace('public/', '', $filepath_image_logo); // Remove 'public/' from the path
            
        }

        $gallery = new Gallery([
            'title' => $request->title,
            'about' => $request->about,
            'descripton' => $request->descripton,
            'video_id' => $request->video_id,
            'image_logo' => $filepath_image_logo,
            'image1' => $filepath_image_1,
            'image2' => $filepath_image_2,
            'image3' => $filepath_image_3,

        ]);
        $gallery->save();
        return redirect()->route('gallery.create')->with('message', 'Media Uploaded Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        return Inertia::render('Gallery/Show', [
            'gallery' => $gallery
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        return Inertia::render('Gallery/Edit', [
            'gallery' => $gallery
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        
    }

    public function update_gallery(Request $request, Gallery $gallery)
    {
        // dd($request);
        // dd($gallery);
        // Validate file existence
        if ($request->hasFile('image_logo')) {
            $image_logo = $request->file('image_logo');
            $filepath_image_logo = $image_logo->store('public/image');
            $filepath_image_logo = str_replace('public/', '', $filepath_image_logo); // Remove 'public/' from the path
            $gallery->image_logo = $filepath_image_logo;
        }
        if ($request->hasFile('image1')) {
            $image_1 = $request->file('image1');
            $filepath_image_1 = $image_1->store('public/image');
            $filepath_image_1 = str_replace('public/', '', $filepath_image_logo); // Remove 'public/' from the path
            $gallery->image1 = $filepath_image_1;
        }
        if ($request->hasFile('image2')) {
            $image_2 = $request->file('image2');
            $filepath_image_2 = $image_2->store('public/image');
            $filepath_image_2 = str_replace('public/', '', $filepath_image_logo); // Remove 'public/' from the path
            $gallery->image2 = $filepath_image_2;
        }
        if ($request->hasFile('image3')) {
            $image_3 = $request->file('image3');
            $filepath_image_3 = $image_3->store('public/image');
            $filepath_image_3 = str_replace('public/', '', $filepath_image_logo); // Remove 'public/' from the path
            $gallery->image3 = $filepath_image_3;
        }

        $gallery->title = $request->title;
        $gallery->about = $request->about;
        $gallery->descripton = $request->descripton;
        $gallery->video_id = $request->video_id;
        $gallery->save();

        return redirect()->route('gallery.create')->with('message', 'Gallery Edited');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        
        return redirect()->route('gallery.create')->with('message', 'Gallery Deleted');
    }
}
