<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryImage;
use Illuminate\Support\Facades\Storage;

class TemporaryImageController extends Controller
{
    public function uploadTemporary(Request $request)
    {
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $folders = [];

            foreach ($images as $image) {
                $filename = $image->getClientOriginalName();
                $folder = uniqid('image-', true);

                // Store in the public/images/tmp directory
                $image->storeAs('images/tmp/' . $folder, $filename, 'public');

                TemporaryImage::create([
                    'folder' => $folder,
                    'file' => $filename,
                ]);
                $folders[] = $folder;
            }

            return $folders;
        }

        return '';
    }

    public function deleteTemporary(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $folder = $payload[0];
        $temporaryImage = TemporaryImage::where('folder', $folder)->first();

        if ($temporaryImage) {
            try {
                // Delete files from public storage
                Storage::disk('public')->deleteDirectory('images/tmp/' . $temporaryImage->folder);

                // Delete record from the database
                $temporaryImage->delete();

                return response()->noContent();
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to delete temporary image.'], 500);
            }
        }

        return response()->json(['message' => 'Temporary image not found.'], 404);
    }

    public function uploadImageDirectlyToDB(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $fileNameProduct = [];

            foreach ($images as $image) {
                $extensionTemp = $image->getClientOriginalExtension();
                $fileNameProductImage = Str::random(20) . '.' . $extensionTemp;

                // Ensure the product directory exists
                $productFolderPath = public_path('storage/images/product/' . $product->slug);
                if (!file_exists($productFolderPath)) {
                    mkdir($productFolderPath, 0777, true);
                }

                // Move the image to the public storage directory
                $image->move($productFolderPath, $fileNameProductImage);

                // Store the image path in the database
                Foto::create([
                    'foto' => '/storage/images/product/' . $product->slug . '/' . $fileNameProductImage,
                    'product_id' => $id,
                ]);

                $fileNameProduct[] = $fileNameProductImage;
            }

            return $fileNameProduct;
        }

        return '';
    }
}
