<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PdfUtilityController extends Controller
{
    public function index()
    {
        return view('utility.split-pdf');
    }

    public function split(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10240', // max 10MB
        ]);

        $file = $request->file('pdf_file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $tempPath = $file->getRealPath();

        // Create a unique temporary directory for the split files
        $uniqueId = uniqid('split_pdf_');
        $tempDir = storage_path('app/temp/' . $uniqueId);
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        try {
            $fpdi = new Fpdi();
            $pageCount = $fpdi->setSourceFile($tempPath);

            if ($pageCount === 0) {
                throw new \Exception("File PDF tidak memiliki halaman.");
            }

            $zip = new ZipArchive();
            $zipFileName = $originalName . '_split.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \Exception("Tidak dapat membuat file ZIP.");
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $newPdf = new Fpdi();
                $newPdf->setSourceFile($tempPath);
                $templateId = $newPdf->importPage($pageNo);
                $size = $newPdf->getTemplateSize($templateId);

                $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
                $newPdf->AddPage($orientation, [$size['width'], $size['height']]);
                $newPdf->useTemplate($templateId);

                $outputPdfPath = $tempDir . '/' . $originalName . '_page_' . $pageNo . '.pdf';
                $newPdf->Output($outputPdfPath, 'F');
                
                $zip->addFile($outputPdfPath, $originalName . '_page_' . $pageNo . '.pdf');
            }

            $zip->close();

            // Clean up individual pdf files
            File::deleteDirectory($tempDir);

            // Return zip file as download, delete it after sending
            return response()->download($zipPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Clean up directory on error
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            return back()->with('error', 'Gagal memproses PDF: ' . $e->getMessage());
        }
    }
}
