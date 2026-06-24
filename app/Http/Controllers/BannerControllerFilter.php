<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerControllerFilter extends Controller
{
    public function index()
    {
        $data['banners'] = Banner::orderBy('id', 'DESC')->get();
        return view('banner/with_filter/view', $data);
    }

    public function add(Request $request)
    {
        if (!$_POST) {
            // Determine the sequence number
            $lastSequence = Banner::max('sequence'); // Get the highest sequence number
            $data['sequence'] = $lastSequence ? $lastSequence + 1 : 1; // If no record, start at 1
            return view('banner/with_filter/add', $data);
        } else {
            // server side validation
            $request->validate(
                [
                    // 'short_title' => [
                    //     'nullable',
                    //     'min:3',
                    //     'max:100',
                    //     'regex:/^(?=.*[a-zA-Z])(?!.*<script>)[a-zA-Z0-9\s!@#$%^&*()_+{}\[\]:;\"\'<>,.?\/\\\\|-]+$/i'
                    // ],
                    // 'title' => [
                    //     'required',
                    //     'min:10',
                    //     'max:100',
                    //     'regex:/^(?=.*[a-zA-Z])(?!.*<script>)[a-zA-Z0-9\s!@#$%^&*()_+{}\[\]:;\"\'<>,.?\/\\\\|-]+$/i',
                    //     'unique:banners,title'
                    // ],
                    'image' => 'required|image|mimes:jpeg,jpg,png|max:1024',
                    'description' => 'nullable',
                   // 'sequence' => 'required|numeric',
                    'link' => 'nullable|url',
                    // 'status' => 'required'
                ],
                    [
                    // 'short_title.min' => 'Short Title range from 3 to 100 characters',
                    // 'short_title.max' => 'Short Title range from 3 to 100 characters',
                    // 'short_title.regex'=>'This field is an invalid format',
                    // 'title.min' => 'Title range from 10 to 100 characters',
                    // 'title.max' => 'Title range from 10 to 100 characters',
                    // 'title.unique' => 'Already exists',
                    // 'title.regex'=>'This field is an invalid format',
                    //'image.dimensions' => 'Image range from 493 px to 640 px',
                    'image.mimes' => 'Upload a valid image file (e.g., .jpg, .jpeg, .png)',
                    'image.image' => 'Upload a valid image file (e.g., .jpg, .jpeg, .png)',
                    'image.max' => 'The field must not be greater than 1 MB.',
                    //'sequence.numeric'=> 'This field is an invalid format',

                ]
            );

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'banner_' . time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('assets/img/banner');
                $image->move($destinationPath, $imageName);
                $imagePath = $imageName;
            }

            // Insert data into the database
            $banner = new Banner();
            //$banner->short_title = $request->short_title;
           // $banner->title = $request->title;
            $banner->image = $imagePath;
            $banner->description = $request->description;
            //$banner->sequence = $request->sequence;
            $banner->link = $request->link;
            // $banner->status = $request->status;
            $banner->save();

            session()->flash('success', 'Banner added successfully');
            return redirect('admin/banners');
        }
    }

    public function update(Request $request)
    {
        $banner = Banner::find($request->id);
        if (!$_POST) {
            if (!$banner) {
                return view('errors.404');
            }

            $data['banner'] = Banner::find($request->id);
            return view('banner/with_filter/edit', $data);
        } else {
            // server side validation
            $request->validate(
                [
                    // 'short_title' => [
                    //     'nullable',
                    //     'min:3',
                    //     'max:100',
                    //     'regex:/^(?=.*[a-zA-Z])(?!.*<script>)[a-zA-Z0-9\s!@#$%^&*()_+{}\[\]:;\"\'<>,.?\/\\\\|-]+$/i'
                    // ],
                    // 'title' => [
                    //     'required',
                    //     'min:10',
                    //     'max:100',
                    //     'regex:/^(?=.*[a-zA-Z])(?!.*<script>)[a-zA-Z0-9\s!@#$%^&*()_+{}\[\]:;\"\'<>,.?\/\\\\|-]+$/i',
                    //     'unique:banners,title,' . $request->id
                    // ],
                    'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1024',
                    'description' => 'nullable',
                    //'sequence' => 'required|numeric',
                    'link' => 'nullable|url',
                    // 'status' => 'required'
                ],
                    [
                    // 'short_title.min' => 'Short Title range from 3 to 100 characters',
                    // 'short_title.max' => 'Short Title range from 3 to 100 characters',
                    // 'short_title.regex'=>'This field is an invalid format',
                    // 'title.min' => 'Title range from 10 to 100 characters',
                    // 'title.max' => 'Title range from 10 to 100 characters',
                    // 'title.unique' => 'Already exists',
                    // 'title.regex'=>'This field is an invalid format',
                    //'image.dimensions' => 'Image range from 493 px to 640 px',
                    'image.mimes' => 'Upload a valid image file (e.g., .jpg, .jpeg, .png)',
                    'image.image' => 'Upload a valid image file (e.g., .jpg, .jpeg, .png)',
                    'image.max' => 'The field must not be greater than 1 MB.',
                    //'sequence.numeric'=> 'This field is an invalid format',

                ]
            );

            // Handle image update
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($banner->image && file_exists(public_path('assets/img/banner/' . $banner->image))) {
                    unlink(public_path('assets/img/banner/' . $banner->image));
                }

                // Save the new image
                $image = $request->file('image');
                $imageName = 'banner_' . time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('assets/img/banner');
                $image->move($destinationPath, $imageName);
                $banner->image = $imageName;
            }

            // Update other fields
           // $banner->short_title = $request->short_title;
            //$banner->title = $request->title;
            $banner->description = $request->description;
            //$banner->sequence = $request->sequence;
            $banner->link = $request->link;
            // $banner->status = $request->status;

            // Save the updated banner
            $banner->save();

            session()->flash('success', 'Banner updated successfully');
            return redirect('admin/banners');
        }
    }

    public function delete(Request $request)
    {
        $banner = Banner::find($request->id);

        // Delete the old image if it exists
        if ($banner->image && file_exists(public_path('assets/img/banner/' . $banner->image))) {
            unlink(public_path('assets/img/banner/' . $banner->image));
        }

        $banner->delete();
        session()->flash('danger', 'Banner deleted successfully');

        return redirect('admin/banners');
    }

    public function change_status(Request $request)
    {

        if ($request->status == 'Active') {
            Banner::find($request->id)->update(['status' => 'Active']);
            $status = Banner::where('id', '=', $request->id)->pluck('status')->first();
            return response()->json(['status' => $status, 'success' => true]);
        } else {
            Banner::find($request->id)->update(['status' => 'Inactive']);
            $status = Banner::where('id', '=', $request->id)->pluck('status')->first();
            return response()->json(['status' => $status, 'success' => true]);
        }
    }

    public function banner_status(Request $request) {
        $banner_status = Banner::where('status', '=', $request->banner_status)->get();
        return response()->json(['banners' => $banner_status, 'success' => true]);
    }
}
