<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Subir imagen
     */
    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // max 5MB
            'folder' => 'nullable|string|in:gallos,profile,otros',
        ]);

        $folder = $validated['folder'] ?? 'gallos';
        
        try {
            $file = $request->file('image');
            
            // Generar nombre único para el archivo
            $filename = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Guardar en storage/app/public/{folder}
            $path = $file->storeAs("images/{$folder}", $filename, 'public');
            
            // Generar URL pública
            $url = Storage::url($path);
            
            return response()->json([
                'success' => true,
                'message' => 'Imagen subida exitosamente',
                'data' => [
                    'filename' => $filename,
                    'path' => $path,
                    'url' => url($url),
                    'full_url' => config('app.url') . $url,
                ],
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir la imagen',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar imagen
     */
    public function deleteImage(Request $request)
    {
        $validated = $request->validate([
            'path' => 'required|string',
        ]);

        try {
            if (Storage::disk('public')->exists($validated['path'])) {
                Storage::disk('public')->delete($validated['path']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Imagen eliminada exitosamente',
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Archivo no encontrado',
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la imagen',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Subir múltiples imágenes
     */
    public function uploadMultiple(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'folder' => 'nullable|string|in:gallos,profile,otros',
        ]);

        $folder = $validated['folder'] ?? 'gallos';
        $uploadedFiles = [];

        try {
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs("images/{$folder}", $filename, 'public');
                $url = Storage::url($path);
                
                $uploadedFiles[] = [
                    'filename' => $filename,
                    'path' => $path,
                    'url' => url($url),
                    'full_url' => config('app.url') . $url,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Imágenes subidas exitosamente',
                'data' => $uploadedFiles,
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir las imágenes',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
