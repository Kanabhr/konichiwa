<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $apiUrl = "https://qrcode.konichiwachemicals.com/get_product.php";
        $remoteImageFolder = "https://qrcode.konichiwachemicals.com/";
        $secretKey = "Konichiwa_Secure_Token_789";
        $perPage = 10;

        try {
            // Added .withoutVerifying() to prevent SSL certificate issues
            $response = Http::withoutVerifying()->withHeaders([
                'X-KONICHIWA-AUTH' => $secretKey
            ])->get($apiUrl);

            if ($response->successful()) {
                $jsonData = $response->json();

                // Ensure data exists and is an array
                $allProducts = $jsonData['data'] ?? [];

                foreach ($allProducts as &$product) {
                    if (!empty($product['image_path'])) {
                        // Logic to ensure no double slashes (e.g., //assets/...)
                        $path = ltrim($product['image_path'], '/');
                        $product['full_image_url'] = rtrim($remoteImageFolder, '/') . '/' . $path;
                    } else {
                        $product['full_image_url'] = asset('assets/images/no-product.jpg');
                    }
                }

                // Pagination logic â€” slice the full array based on ?page= query param
                $totalProducts = count($allProducts);
                $totalPages    = (int) ceil($totalProducts / $perPage);
                $currentPage   = max(1, min((int) request()->get('page', 1), $totalPages ?: 1));
                $offset        = ($currentPage - 1) * $perPage;
                $products      = array_slice($allProducts, $offset, $perPage);

                return view('product-catalog', compact('products', 'currentPage', 'totalPages'));
            } else {
                // This helps you see why the API failed (401, 404, 500 etc)
                Log::error("API Failed with status: " . $response->status());
            }

        } catch (\Exception $e) {
            Log::error("Connection Error: " . $e->getMessage());
        }

        // Return empty array so the view doesn't crash on @foreach
        return view('product-catalog', [
            'products'    => [],
            'currentPage' => 1,
            'totalPages'  => 1,
        ]);
    }
}
