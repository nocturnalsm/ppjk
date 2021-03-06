<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator;
use DB;
use Auth;

class RoleController extends Controller {

	public function __construct()
	{
		$this->middleware('permission:roles');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$columns = ["name"];
		if ($request->ajax()){
				$data = Role::select("id","name");
				$totalData = $data->count();
	    	$totalFiltered = $totalData;
				if (!empty($request->search["value"])){
					foreach($columns as $key=>$col){
							if ($key == 0){
								$data->where($col, "LIKE", '%' .$request->search['value'] .'%');
							}
							else {
								$data->orWhere($col, "LIKE", '%' .$request->search['value'] .'%');
							}
					}
					$totalFiltered = $data->count();
			}
			$data->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])
				 	 ->skip($request->start)
				 	 ->take($request->length);
			return ["draw" => intval($request->draw), "recordsTotal" => $totalData,
							"recordsFiltered" => $totalFiltered,
							"data" => $data->get()];
		}
		else {
			$breadcrumb[] = Array("link" => "../", "text" => "Home");
      $breadcrumb[] = Array("text" => "Group User");
			return view ("admin.roles.index", ["breads" => $breadcrumb]);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
			$data = new Role;
	    $permissions = Permission::orderBy("name")->get();
			return view('admin.roles.form', ["data" => $data, "permissions" => $permissions,
									"userPerm" => [], "action" => "add"]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
			$v = Validator::make($request->all(), [
	            'name' => 'required|unique:roles'
			],
			["name.required" => "Nama group harus diisi",
			 "name.unique" => "Nama group sudah ada"]);

			if ($v->fails())
			{
				return response()->json(["errors" => $v->errors()]);
			}
			DB::beginTransaction();
			try {
				$data = new Role;
				$data->name = $request->name;
				$data->save();

				foreach ($request->perm as $key=>$perm){
					$permission = Permission::find($key);
					if ($perm == "Y"){
						$data->givePermissionTo($permission);
					}
				}
				DB::commit();
				return response()->json(["type" => "success", "text" => "Penyimpanan Berhasil"]);
			}
			catch (Exception $e){
				DB::rollBack();
				return response()->json(["type" => "error", "text" => "Penyimpanan gagal.<br>" .$e->getMessage()]);
			}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data = Role::find($id);
		$userPerms = DB::table("role_has_permissions")
						 ->select("role_has_permissions.permission_id")
						 ->join("roles", "role_has_permissions.role_id","=","roles.id")
						 ->where("roles.id", $id)->get();
		$arrPerms = [];
		foreach($userPerms as $up){
			$arrPerms[] = $up->permission_id;
		}

    $permissions = Permission::orderBy("name")->get();
		return view('admin.roles.form', ["data" => $data, "permissions" => $permissions,
								   "userPerm" => $arrPerms, "action" => "edit"]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$v = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' .$id,
		],
		["name.required" => "Nama group harus diisi",
		 "name.unique" => "Nama group sudah ada"]);

		if ($v->fails())
		{
			return response()->json(["errors" => $v->errors()]);
		}

		DB::beginTransaction();
		try {
			$data = Role::find($id);
			$data->name = $request->name;
			$data->save();
			$permissions = Array();
			foreach ($request->perm as $key=>$perm){
				if ($perm == "Y"){
				    $permissions[] = Permission::find($key);
				}
			}
			$data->syncPermissions($permissions);
			DB::commit();
			return response()->json(["type" => "success", "text" => "Penyimpanan Berhasil"]);
		}
		catch (Exception $e){
			DB::rollBack();
			return response()->json(["type" => "error", "text" => "Penyimpanan gagal.<br>" .$e->getMessage()]);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request)
	{
		try {
			$data = Role::find($request->id)->delete();
			return response()->json(["type" => "info", "text" => "Data berhasil dihapus"]);
		}
		catch (Exception $e){
			$response = response()->json(["type" => "error", "text" => "Penghapusan data gagal"]);
			return response($response, 500)
							->header('Content-Type', 'text/html');
		}
	}

}
