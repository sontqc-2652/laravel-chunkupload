<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChunkFile;

class ChunkFileController extends Controller
{
    public function create()
    {
        return view('chunkUpload');
    }

    public function chunkStore(Request $request)
    {
        $input = $request->all();

        // THE UPLOAD DESITINATION - CHANGE THIS TO YOUR OWN

        $filePath = storage_path('app/public/upload/testChunk');

        if (!file_exists($filePath)) { 

            if (!mkdir($filePath, 0777, true)) {
                return response()->json(["ok" => 0, "info" => "Failed to create $filePath"]);
            }
        }

        $fileName = isset($input["name"]) ? $input["name"] : $input["file"]->getClientOriginalName();
        $filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;

        // DEAL WITH CHUNKS

        $chunk = isset($input["chunk"]) ? (int)($input["chunk"]) : 0;
        $chunks = isset($input["chunks"]) ? (int)($input["chunks"]) : 0;
        $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");

        if ($out) {
            $in = fopen($input["file"]->getPathName(), "rb");

            if ($in) {
                while ($buff = fread($in, 4096)) { fwrite($out, $buff); }
            } else {
                return response()->json(["ok" => 0, "info" => 'Failed to open input stream']);
            }

            fclose($in);
            fclose($out);
            unlink($input["file"]->getPathName());
        }

        if (!$chunks || $chunk == $chunks - 1) {
            rename("{$filePath}.part", $filePath);
            $array = ['file' => $fileName];
            ChunkFile::create($array);
        }

        $info = "Upload OK";
        $ok = 1;

        return response()->json(["ok" => $ok, "info" => $info]);
    }
}
