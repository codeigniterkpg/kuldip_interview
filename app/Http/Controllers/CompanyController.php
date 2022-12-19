<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        return view("company")->with(["companies" => $companies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name"      => "required",
            "email"     => "required",
            "address"   => "required",
            "website"   => "required",
            "image"     => "nullable|image",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false, "errors" => $validator->errors()->all()]);
        }

        $image = "";

        $company = new Company();
        $company->name      = $request->name;
        $company->email     = $request->email;
        $company->website   = $request->website;
        $company->address   = $request->address;
        $company->image     = $image;
        $company->save();

        $this->upload_image($request, $company->id);
        return response()->json(["status" => true, "message" => $company->name . " successfully created.", "data" => $company, "edit_url" => route("api.company.edit", $company->id)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find($id);
        return response()->json(["status" => true, "data" => $company]);
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
        $validator = Validator::make($request->all(), [
            "name"      => "required",
            "email"     => "required",
            "address"   => "required",
            "website"   => "required",
            "image"     => "nullable|image",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false, "error" => $validator->error()]);
        }


        $company = Company::find($id);
        $company->name      = $request->name;
        $company->email     = $request->email;
        $company->website   = $request->website;
        $company->address   = $request->address;
        $company->save();
        $this->upload_image($request, $company->id);
        return response()->json(["status" => true, "message" => $company->name . " successfully updated."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();
        return response()->json(["status" => true, "message" => $company->name . " successfully deleted."]);
    }

    private function upload_image(Request $request, $id) {
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put($fileName, 'public/company/');

            $company        = Company::find($id);
            $company->image = $fileName;
            $company->save();
        }
    }
}
