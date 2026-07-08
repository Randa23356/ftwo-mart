<?php

use App\Models\WebsiteSetting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

it('can clear a setting value even when the database column is not nullable', function () {
    Schema::dropIfExists('website_settings');

    Schema::create('website_settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique();
        $table->text('value')->nullable(false);
        $table->string('type')->default('text');
        $table->timestamps();
    });

    WebsiteSetting::setValue('logo', 'website/logo.png', 'image');
    WebsiteSetting::setValue('logo', null, 'image');

    $setting = WebsiteSetting::where('key', 'logo')->first();

    expect($setting)->not->toBeNull()
        ->and($setting->value)->toBe('');
});
