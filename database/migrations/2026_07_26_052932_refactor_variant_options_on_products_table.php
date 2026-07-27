<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. products: add variant_options JSON, migrate data, drop old cols
        Schema::table('products', function (Blueprint $table) {
            $table->json('variant_options')->nullable()->after('available_colors');
        });

        // Migrate available_sizes + available_colors → variant_options
        $products = DB::table('products')
            ->whereNotNull('available_sizes')
            ->orWhereNotNull('available_colors')
            ->get();

        foreach ($products as $product) {
            $variantOptions = [];
            $sizes = json_decode($product->available_sizes, true);
            $colors = json_decode($product->available_colors, true);

            if (is_array($sizes) && count($sizes) > 0) {
                $variantOptions['Ukuran'] = $sizes;
            }
            if (is_array($colors) && count($colors) > 0) {
                $variantOptions['Warna'] = $colors;
            }

            if (count($variantOptions) > 0) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['variant_options' => json_encode($variantOptions)]);
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['available_sizes', 'available_colors']);
        });

        // 2. cart: add selected_variants JSON, migrate data, drop old cols
        Schema::table('cart', function (Blueprint $table) {
            $table->json('selected_variants')->nullable()->after('quantity');
        });

        $cartItems = DB::table('cart')
            ->whereNotNull('selected_size')
            ->orWhereNotNull('selected_color')
            ->get();

        foreach ($cartItems as $item) {
            $selected = [];
            if ($item->selected_size) $selected['Ukuran'] = $item->selected_size;
            if ($item->selected_color) $selected['Warna'] = $item->selected_color;

            if (count($selected) > 0) {
                DB::table('cart')
                    ->where('id', $item->id)
                    ->update(['selected_variants' => json_encode($selected)]);
            }
        }

        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn(['selected_size', 'selected_color']);
        });

        // 3. order_items: add selected_variants JSON, migrate data, drop old cols
        Schema::table('order_items', function (Blueprint $table) {
            $table->json('selected_variants')->nullable()->after('quantity');
        });

        $orderItems = DB::table('order_items')
            ->whereNotNull('selected_size')
            ->orWhereNotNull('selected_color')
            ->get();

        foreach ($orderItems as $item) {
            $selected = [];
            if ($item->selected_size) $selected['Ukuran'] = $item->selected_size;
            if ($item->selected_color) $selected['Warna'] = $item->selected_color;

            if (count($selected) > 0) {
                DB::table('order_items')
                    ->where('id', $item->id)
                    ->update(['selected_variants' => json_encode($selected)]);
            }
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['selected_size', 'selected_color']);
        });
    }

    public function down(): void
    {
        // Reverse: add back old columns, migrate data, drop variant_options/selected_variants
        Schema::table('products', function (Blueprint $table) {
            $table->string('available_sizes')->nullable()->after('stock');
            $table->string('available_colors')->nullable()->after('available_sizes');
        });

        $products = DB::table('products')->whereNotNull('variant_options')->get();
        foreach ($products as $product) {
            $options = json_decode($product->variant_options, true);
            if (is_array($options)) {
                $sizes = $options['Ukuran'] ?? null;
                $colors = $options['Warna'] ?? null;
                DB::table('products')->where('id', $product->id)->update([
                    'available_sizes' => $sizes ? json_encode($sizes) : null,
                    'available_colors' => $colors ? json_encode($colors) : null,
                ]);
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('variant_options');
        });

        // cart
        Schema::table('cart', function (Blueprint $table) {
            $table->string('selected_size')->nullable()->after('quantity');
            $table->string('selected_color')->nullable()->after('selected_size');
        });

        $cartItems = DB::table('cart')->whereNotNull('selected_variants')->get();
        foreach ($cartItems as $item) {
            $variants = json_decode($item->selected_variants, true);
            if (is_array($variants)) {
                DB::table('cart')->where('id', $item->id)->update([
                    'selected_size' => $variants['Ukuran'] ?? null,
                    'selected_color' => $variants['Warna'] ?? null,
                ]);
            }
        }

        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('selected_variants');
        });

        // order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('selected_size')->nullable()->after('quantity');
            $table->string('selected_color')->nullable()->after('selected_size');
        });

        $orderItems = DB::table('order_items')->whereNotNull('selected_variants')->get();
        foreach ($orderItems as $item) {
            $variants = json_decode($item->selected_variants, true);
            if (is_array($variants)) {
                DB::table('order_items')->where('id', $item->id)->update([
                    'selected_size' => $variants['Ukuran'] ?? null,
                    'selected_color' => $variants['Warna'] ?? null,
                ]);
            }
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('selected_variants');
        });
    }
};
