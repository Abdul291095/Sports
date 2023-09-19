<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;



class UserController extends Controller
{
    public function uploadForm()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        try 
        {

            $file = $request->file('file');
            
            $allowedExtensions = ['xls', 'xlsx'];
            $extension = $file->getClientOriginalExtension();
            
            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->back()->withErrors(['file' => 'The uploaded file must be an Excel file (.xls or .xlsx).'])->withInput();
            }
            
            $import = new UsersImport;
            Excel::import($import, $file);
            
            $users = $import->getUsers(); 
            
            $addedUsers = [];
        }
        catch(\Exception $e) 
        {
            return back()->withErrors(['file' => $e->getMessage()]); // Custom error message


        }            
            foreach ($users as $userData) {
                $dateOfBirth =  Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($userData['date_of_birth'] - 2)->format('Y-m-d');
                $users = User::create([
                    'first_name' => $userData['first_name'],
                    'middle_name' => $userData['middle_name'],
                'last_name' => $userData['last_name'],
                'date_of_birth' => $dateOfBirth,
                'mobile_number' => $userData['mobile_number'],
                'father_full_name' => $userData['father_full_name'],
                'state' => $userData['state'],
                'city' => $userData['city'],
                'address' => $userData['address'],
            ]);

           $addedUsers[] = $users;
        }


        $perPage = 10; // Number of users per page
        $currentPage = Paginator::resolveCurrentPage('page');
        $users = new Paginator($addedUsers, $perPage, $currentPage, ['path' => Paginator::resolveCurrentPath()]);

        return view('users', ['users' => $users]);
    }

    public function getUsers(Request $request)
    {
        $users = User::paginate(10); 
    
        return view('users', ['users' => $users]);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,JPG',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        $file = $request->file('photo');
        $filename = 'profile_' . $user->id . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/profile-photos', $filename);

        // Update the user's profile photo field
        $user->profile_photo = 'profile-photos/' . $filename;
        $user->save();

        $url = asset('storage/'.$user->profile_photo );

        return response()->json(['message' => 'Photo uploaded successfully.', "url" => $url]);
    }

}
