<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Storage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        // get image user sign
        $user = User::find(Auth::user()->id);

        return view('profile.show', compact('user'));
    }

    public function store(Request $request)
    {
        try {

            //! validation
            app()->make('App\Http\Requests\UpdateProfileRequest');


            // upload
            if ($request->hasFile('sign')) { 
                $uploadedFile = $request->file('sign');
                $fileExtension = $uploadedFile->getClientOriginalExtension();
                $fileName = app('string.helper')->setNameSignUserByEmail($request->email);
                $fileNameWithExtenstion = $fileName . '.' . $fileExtension;

                Storage::disk('user_sign')->put(
                    $fileNameWithExtenstion,
                    File::get($uploadedFile)
                );
            }


            // update data user
            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->hasFile('sign')) {
                $user->signature = $fileNameWithExtenstion;
            }

            $user->save();
            return redirect()->back()->with('info', 'Success update profile');

        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return redirect()->back()->withErrors($errors);
            }
            return redirect()->back()->with('info', $e->getMessage());
        }
    }
}
