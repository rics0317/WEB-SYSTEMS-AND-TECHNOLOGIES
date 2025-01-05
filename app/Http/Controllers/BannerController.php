<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BannerImage;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with('images')->get();
        return view('banners.index', compact('banners'));
    }

    public function create()
    {
        return view('banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images' => 'required|array|max:5', // Ensure no more than 5 images are uploaded
        ]);

        $banner = Banner::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->has('status'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);

                BannerImage::create([
                    'banner_id' => $banner->id,
                    'image_path' => $imageName,
                ]);
            }
        }

        return redirect()->route('banners.index')->with('success', 'Banner created successfully.');
    }

    public function show(Banner $banner)
    {
        $banner->load('images');
        return view('banners.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        $banner->load('images');
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images' => 'array|max:5', // Ensure no more than 5 images are uploaded
        ]);

        $banner->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->has('status'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);

                BannerImage::create([
                    'banner_id' => $banner->id,
                    'image_path' => $imageName,
                ]);
            }
        }

        return redirect()->route('banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('banners.index')->with('success', 'Banner deleted successfully.');
    }
}
