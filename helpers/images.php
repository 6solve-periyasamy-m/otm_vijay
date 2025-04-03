<?php

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\UploadedFile;

if (!function_exists('store_file')) {
    /**
     * Store an uploaded file publicly, and delete the old one if provided
     * @param UploadedFile $file
     * @param string|null $old The storage location of the old file (if it needs deleting)
     * @return string The location of the stored file
     */
    function store_file(UploadedFile $file, string|null $old = null): string
    {
        $path = $file->storePublicly('uploads/images');
        isset($old) && Storage::delete($old);
        return $path;
    }
}
if (!function_exists('img_to_b64')) {
    function img_to_b64(string $file, string|null $prefix = null): string
    {
        $path = public_path($file);
        if (file_exists($path)) {
            $prefix = $prefix ?? "data:image/" . pathinfo($path, PATHINFO_EXTENSION) . ";base64,";
            return $prefix.base64_encode(file_get_contents(public_path($file)));
        }
        return "";
    }
}
if (!function_exists('svg_to_b64')) {
    function svg_to_b64(string $file): string
    {
        return img_to_b64($file, "data:image/svg+xml;base64,");
    }
}
if (!function_exists('generate_qr')) {
    /**
     * Generate a Base64 QR code for a given content
     * @param string $content
     * @param string $prefix Prefix to use for the QR code (defaults to base64 SVG for img tags)
     * @return string base64 representation of the QR code
     */
    function generate_qr(string $content, string $prefix = "data:image/svg+xml;base64,"): string
    {
        return $prefix . base64_encode((new Writer(
                new ImageRenderer(
                    new RendererStyle(400),
                    new SvgImageBackEnd(),
                )))->writeString($content));
    }
}
