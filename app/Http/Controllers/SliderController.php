<?php

namespace App\Http\Controllers\front;

use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        // $this->middleware(['permission:permissions.index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slider=Slider::get();
        return view('admin.frontmenu.slider.index',compact('slider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.frontmenu.slider.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'operator'      => 'required',
        ]);

        //upload image
        $image = $request->FILE('image');
        $image->storeAs('public/gallery', $image->hashName());

        $slider =Slider::create([
            'image'       => $image->hashName(),
            'status'      => 1, 
            'operator'    => Auth::user()->name, 
             

        ]);

        if($slider){
            //redirect dengan pesan sukses
            Alert::success('success','Berhasil Di Simpan');
            return redirect('/data-slider');
        }else{
            //redirect dengan pesan error
            Alert::error('error','Berhasil Di Simpan');
            return redirect('/data-slider');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePermanent($id)
    {
        $slider= Slider::findOrFail($id);
        $slider->delete();

        if($slider){
            //redirect dengan pesan sukses
            Alert::success('success','Berhasil Di Hapus');
           return back();
        }else{
            //redirect dengan pesan error
            Alert::error('error','Berhasil Di Hapus');
           return back();
        }
        
        
    } 
}
