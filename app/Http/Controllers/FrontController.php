<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FrontController extends Controller {
    public function index( Request $request ) {
        $result[ 'data' ] = DB::table( 'front' )->where( 'type', 'image' )->get();
        $result[ 'data2' ] = DB::table( 'front' )->where( 'type', 'message' )->get();
        $result[ 'banners' ] = DB::table( 'banners' )->get();
        return view( 'admin/frontsettings', $result );
    }

    public function addimg( Request $request ) {
        if ( $files = $request->file( 'img' ) ) {
            $a = 0;
            foreach ( $files as $file ) {
                $a = $a + 1;
                $image_name = time().$a;
                $ext = $file->extension();
                $image_fullname = $image_name.'.'.$ext;
                $upload_path = 'docs/';
                $image_url = $upload_path.$image_fullname;
                $file->move( $upload_path, $image_fullname );
                $image[] = $image_url;
                DB::table( 'front' )->insert( [
                    'image'=>$image_url,
                    'type'=>'image',
                ] );
            }

        }
        return redirect( 'frontsettings' );
    }

    public function deleteimg( Request $request, $path, $name ) {
        $image_path = $path.'/'.$name;
        if ( File::exists( $image_path ) ) {
            File::delete( $image_path );
        }
        DB::table( 'front' )->where( [ 'image'=>$image_path ] )->delete();

        return redirect( 'frontsettings' );
    }

    public function addmsg( Request $request ) {
        $message = $request->post( 'message' );
        DB::table( 'front' )->insert( [
            'message'=>$message,
            'type'=>'message'
        ] );
        return redirect( 'frontsettings' );
    }

    public function deletemsg( Request $request, $id ) {
        DB::table( 'front' )->where( 'id', $id )->delete();
        return redirect( 'frontsettings' );
    }

    public function sliderimgs() {
        $res = DB::table( 'front' )
        ->where( 'type', 'image' )
        ->orderBy( 'id', 'DESC' )
        ->get( [ 'id', 'image' ] )
        ->map( function ( $item ) {
            return [
                'id' => $item->id,
                'path' => $item->image, // Replace 'image' with 'path'
            ];
        }
    );
    return response()->json( $res, 200 );
}

public function editterms() {
    $result[ 'data' ] =  DB::table( 'terms' )->where( 'id', 1 )->first();
    return view( 'admin/editterms', $result );
}

public function editterms_process( Request $request ) {
    DB::table( 'terms' )->where( 'id', 1 )->update( [
        'terms' => $request->post( 'terms' )
    ] );
    return redirect( '/' );
}

public function getTerms() {
    $terms =  DB::table( 'terms' )->where( 'id', 1 )->first();

    return response()->json( $terms->terms, 200 );
}

public function editpolicy() {
    $result[ 'data' ] =  DB::table( 'policy' )->where( 'id', 1 )->first();
    return view( 'admin/editpolicy', $result );
}

public function editpolicy_process( Request $request ) {
    DB::table( 'policy' )->where( 'id', 1 )->update( [
        'policy' => $request->post( 'policy' )
    ] );
    return redirect( '/' );
}

public function getPolicy() {
    $policy =  DB::table( 'policy' )->where( 'id', 1 )->first();

    return response()->json( $policy->policy, 200 );
}

public function updateBanner( Request $request, $id ) {
    // Validate the input
    $request->validate( [
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'url' => 'required|string|max:255',
    ] );

    // Retrieve the current banner record
    $banner = DB::table( 'banners' )->where( 'id', $id )->first();

    // Initialize the update data array
    $updateData = [
        'url' => $request->input( 'url' )
    ];

    // Check if an image is uploaded
    if ( $request->hasFile( 'image' ) ) {
        $file = $request->file( 'image' );
        $image_name = time();
        // Generate a unique file name based on time
        $ext = $file->extension();
        $image_fullname = $image_name . '.' . $ext;

        // Define the upload path in 'public/banners'
        $upload_path = public_path( 'banners/' );

        // Create the directory if it doesn't exist
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        // Define the relative path to store in the database
        $image_url = 'banners/' . $image_fullname;
        
        // Move the uploaded file to 'public/banners/'
        $file->move($upload_path, $image_fullname);

        // Add the image URL to the update data
        $updateData['image'] = $image_url;

        // Delete the old image if it exists
        if (!empty($banner->image) && file_exists(public_path($banner->image))) {
            unlink(public_path($banner->image));  // Delete the old image
        }
    }

    // Update the banner record in the database
    DB::table('banners')->where('id', $id)->update($updateData);

    return redirect()->back()->with('success', 'Banner updated successfully!' );
    }

    public function getBanners()
    {
        // Retrieve all data from the banners table
        $banners = DB::table('banners' )->get();

        // Return the data as a JSON response
        return response()->json( $banners );
    }

}
