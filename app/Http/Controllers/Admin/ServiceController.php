<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "role:admin"]);
    }

    public function index()
    {
        $services = Service::paginate(15);
        return view("admin.services.index", compact("services"));
    }

    public function create()
    {
        return view("admin.services.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255|unique:services",
            "icon" => "nullable|string|max:255",
            "url" => "nullable|url",
            "is_active" => "boolean",
        ]);

        $data = $request->only(['name', 'icon', 'url']);
        $data["is_active"] = $request->has("is_active");

        Service::create($data);

        return redirect()
            ->route("admin.services.index")
            ->with("success", "Layanan berhasil ditambahkan");
    }

    public function edit(Service $service)
    {
        return view("admin.services.edit", compact("service"));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            "name" =>
                "required|string|max:255|unique:services,name," . $service->id,
            "icon" => "nullable|string|max:255",
            "url" => "nullable|url",
            "is_active" => "boolean",
        ]);

        $data = $request->only(['name', 'icon', 'url']);
        $data["is_active"] = $request->has("is_active");

        $service->update($data);

        return redirect()
            ->route("admin.services.index")
            ->with("success", "Layanan berhasil diupdate");
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route("admin.services.index")
            ->with("success", "Layanan berhasil dihapus");
    }
}
