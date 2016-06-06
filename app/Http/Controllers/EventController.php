<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationServiceProvider;
use Intervention\Image\Facades\Image;
use App\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json(Event::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required|unique:events',
            'venue' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all());
        }

        $return_path_logo = 'upload/logo/';
        $path_logo = public_path() . '/' . $return_path_logo;
        if(!is_dir($path_logo)) {
            mkdir($path_logo, 0777, true);
        }
        if($inputs['logo']) {
            $result = $this->uploadImage($inputs['logo'], $path_logo, $return_path_logo, 125, 90);
            if($result['error']) {
                return response()->json(['The size of image must be less 1MB']);
            }
            $inputs['logo'] = $result['path'];
        }

        $return_path_banner = 'upload/banner/';
        $path_banner = public_path() . '/' . $return_path_banner;
        if(!is_dir($path_banner)) {
            mkdir($path_banner, 0777, true);
        }
        if($inputs['banner']) {
            $result = $this->uploadImage($inputs['banner'], $path_banner, $return_path_banner, 1080, 526);
            if($result['error']) {
                return response()->json(['The size of image must be less 1MB']);
            }
            $inputs['banner'] = $result['path'];
        }

        return Event::create($inputs);

    }

    private function uploadImage($file, $path, $returnPath, $width, $height) {
        $file = str_replace('data:image/png;base64,', '', $file);
        $img = str_replace(' ', '+', $file);
        $data = base64_decode($img);
        $filename = date('ymdhis') . '_image' . ".png";
        $file = $path . $filename;
        file_put_contents($file, $data);

        // THEN RESIZE IT
        $returnPath = $returnPath . $filename;
        $image = Image::make(file_get_contents(URL::asset($returnPath)));
        if($image->filesize() > 1048576) {
            return ['error' => true];
        }
        $image->resize($width, $height)->save($path . $filename, 80);

        return ['error' => false, 'path' => $returnPath];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $event = Event::find($id);
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $inputs = Input::all();
        $event = Event::find($id);

        $validator = Validator::make($inputs, [
            'name' => 'required|unique:events, name, ' . $event->_id . ', _id',
            'venue' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->all());
        }

        if(strpos($inputs['logo'], 'data:image/png;base64,') !== false) {
            $return_path_logo = 'upload/logo/';
            $path_logo = public_path() . '/' . $return_path_logo;
            $del_link = public_path() . '/' . $event->logo;

            if(File::exists($del_link) && $event->logo) {
                unlink($del_link);
            }
            $result = $this->uploadImage($inputs['logo'], $path_logo, $return_path_logo, 125, 90);
            if($result['error']) {
                return response()->json(['The size of image must be less 1MB']);
            }
            $inputs['logo'] = $result['path'];
        }

        if(strpos($inputs['banner'], 'data:image/png;base64,') !== false) {
            $return_path_banner = 'upload/banner/';
            $path_banner = public_path() . '/' . $return_path_banner;
            $del_link = public_path() . '/' .$event->banner;

            if(File::exists($del_link) && $event->banner) {
                unlink($del_link);
            }
            $result = $this->uploadImage($inputs['banner'], $path_banner, $return_path_banner, 1080, 526);
            if($result['error']) {
                return response()->json(['The size of image must be less 1MB']);
            }
            $inputs['banner'] = $result['path'];
        }

        $event->update($inputs);

        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);

        $del_link = public_path() . '/' . $event->logo;
        if(File::exists($del_link) && $event->logo) {
            unlink($del_link);
        }

        $del_link = public_path() . '/' .$event->banner;
        if(File::exists($del_link) && $event->banner) {
            unlink($del_link);
        }

        $event->delete();
    }

    public function search($string){

        $events = Event::where('name', 'LIKE', '%'.$string.'%')
            ->orWhere('venue', 'LIKE', '%'.$string.'%')
            ->orWhere('description', 'LIKE', '%'.$string.'%')
            ->orWhere('date', 'LIKE', '%'.$string.'%')
            ->orWhere('time', 'LIKE', '%'.$string.'%')
            ->get();

        return response()->json($events);
    }
}
