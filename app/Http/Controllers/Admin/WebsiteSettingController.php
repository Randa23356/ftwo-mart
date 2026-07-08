<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class WebsiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "role:admin"]);
    }

    public function index()
    {
        $settings = WebsiteSetting::all()->keyBy("key");
        return view("admin.settings.index", compact("settings"));
    }

    public function update(Request $request)
    {
        $request->validate([
            "website_name" => "required|string|max:255",
            "website_description" => "required|string",
            "phone" => "required|string|max:20",
            "address" => "required|string",
            "email" => "required|email",
            "instagram" => "nullable|string|max:255",
            "facebook" => "nullable|string|max:255",
            "opening_hours" => "required|string|max:255",
            "delivery_fee" => "required|numeric|min:0",
            "min_order" => "required|numeric|min:0",
        ]);

        foreach ($request->all() as $key => $value) {
            if (
                in_array($key, [
                    "website_name",
                    "website_description",
                    "phone",
                    "address",
                    "email",
                    "instagram",
                    "facebook",
                    "opening_hours",
                    "delivery_fee",
                    "min_order",
                ])
            ) {
                WebsiteSetting::setValue($key, $value);
            }
        }

        return back()->with("success", "Pengaturan website berhasil diupdate");
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            "logo" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        if ($request->hasFile("logo")) {
            // Ensure directory exists
            if (!is_dir(public_path('website'))) {
                mkdir(public_path('website'), 0755, true);
            }

            $file = $request->file("logo");
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('website'), $filename);
            $logoPath = 'website/' . $filename;
            WebsiteSetting::setValue("logo", $logoPath, "image");
        }

        return back()->with("success", "Logo website berhasil diupdate");
    }

    public function updateHeroImage(Request $request)
    {
        $request->validate([
            "hero_image" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        if ($request->hasFile("hero_image")) {
            // Ensure directory exists
            if (!is_dir(public_path('website'))) {
                mkdir(public_path('website'), 0755, true);
            }

            $file = $request->file("hero_image");
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('website'), $filename);
            $heroPath = 'website/' . $filename;
            WebsiteSetting::setValue("hero_image", $heroPath, "image");
        }

        return back()->with("success", "Hero image berhasil diupdate");
    }

    public function updateAboutContent(Request $request)
    {
        $request->validate([
            "about_title" => "required|string|max:255",
            "about_content" => "required|string",
            "about_image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        WebsiteSetting::setValue("about_title", $request->about_title);
        WebsiteSetting::setValue("about_content", $request->about_content);

        if ($request->hasFile("about_image")) {
            $aboutImagePath = $request
                ->file("about_image")
                ->store("website", "public");
            WebsiteSetting::setValue("about_image", $aboutImagePath, "image");
        }

        return back()->with("success", "Konten about berhasil diupdate");
    }

    public function updateContactInfo(Request $request)
    {
        $request->validate([
            "contact_title" => "required|string|max:255",
            "contact_description" => "required|string",
            "contact_whatsapp" => "nullable|string|max:20",
            "contact_maps_embed" => "nullable|string",
        ]);

        WebsiteSetting::setValue("contact_title", $request->contact_title);
        WebsiteSetting::setValue(
            "contact_description",
            $request->contact_description,
        );
        WebsiteSetting::setValue(
            "contact_whatsapp",
            $request->contact_whatsapp,
        );
        WebsiteSetting::setValue(
            "contact_maps_embed",
            $request->contact_maps_embed,
        );

        return back()->with("success", "Informasi kontak berhasil diupdate");
    }

    public function inlineUpdate(Request $request)
    {
        $request->validate([
            "key" => "required|string",
            "value" => "required|string",
        ]);

        WebsiteSetting::setValue($request->key, $request->value);

        return response()->json(["success" => true]);
    }
}
