<?php

namespace App\Http\Controllers;

use App\Jobs\ImportCsv;
use App\Jobs\SaleCsvProcess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $products = User::paginate(5);
        return $products;
    }
    public function store(Request $request)
    {

        if ($request->has('file') && $request->file != "undefined") {
            $fileName = time() . '.' . $request->file->extension();
            $request->file->move(public_path('file'), $fileName);
            $fileUrl =  'file/' . $fileName;
        }
        if (isset($request->id) && $request->id != "") {
            $user = User::find($request->id);
        } else {
            $user = new User();
        }
        $user->title = $request->title;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        $user->post_code = $request->post_code;
        $user->gender = $request->gender;
        $user->profile_image = $fileUrl ?? null;
        $user->save();
        return "Record Saved";
        return response()->json('Product created!');
    }
    public function show($id)
    {
        $product = User::find($id);
        return response()->json($product);
    }
    public function update($id, Request $request)
    {
        $product = User::find($id);
        $product->update($request->all());
        return response()->json('Product updated!');
    }
    public function destroy($id)
    {
        $product = User::find($id);
        $product->delete();
        return response()->json('Product deleted!');
    }
    public function uploadUsers(Request $request)
    {
        if ($request->has('file')) {
            $csv    = file($request->file);
            $chunks = array_chunk($csv, 1000);
            $batch  = Bus::batch([])->dispatch();
            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);
                if ($key == 0) {
                    $header = $data[0];
                    unset($data[0]);
                }
                $batch->add(new SaleCsvProcess($data, $header));
            }
            return $batch;
        }
        return "please upload csv file";
    }

    public function exportUsers(Request $request)
    {
        $data    = User::select('title', 'first_name', 'last_name', 'email', 'phone', 'city', 'state', 'country', 'post_code', 'gender')->get()->toArray();
        $headers = ['title', 'first_name', 'last_name', 'email', 'phone', 'city', 'state', 'country', 'post_code', 'gender'];
        $data = array_merge([$headers], $data);
        return (new \LaravelCsvGenerator\LaravelCsvGenerator)->setData($data)->renderStream();
    }
}
