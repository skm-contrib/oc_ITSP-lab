<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class FileController extends Controller
{
    public function convertFileToText(Request $request)
    {
    if (!$request->hasFile('file')) {
        return response()->json(['error' => 'Файл не був завантажений.']);
    }

    $file = $request->file('file');

    if (!$file->isValid()) {
        return response()->json(['error' => 'Неприпустимий тип файлу або розмір.']);
    }

    $originalImagePath = $file->store('original_images');

    $pathToOriginalImage = storage_path("app/{$originalImagePath}");

    $image = Image::make($pathToOriginalImage);
    $origImage = Image::make($pathToOriginalImage);

    if (!File::exists(public_path('original_images'))) {
        File::makeDirectory(public_path('original_images'), 0755, true);
    }

    $pathToOrigImg = public_path('original_images/' . $file->getClientOriginalName());
    $origImage->save($pathToOrigImg);


    $image->greyscale(100);
    $image->contrast(50);

    if (!File::exists(public_path('processed_images'))) {
        File::makeDirectory(public_path('processed_images'), 0755, true);
    }

    $pathToProcessedImage = public_path('processed_images/' . $file->getClientOriginalName());
    $image->save($pathToProcessedImage);

    $text = (new TesseractOCR($pathToProcessedImage))
        ->withoutTempFiles()
        ->psm(6)
        ->lang('ukr', 'eng')
        ->run();

    $text = mb_strtolower($text);

    $words = str_word_count($text, 1, 'абвгґдеєжзиіїйклмнопрстуфхцчшщьюя1234567890.,\'');
        $filteredWords = array_filter($words, function ($word) {
            return mb_strlen($word, 'UTF-8') > 3;
        });
        $filteredText = implode(' ', $filteredWords);

    $category = DataSamples::LearnMachine($filteredText);

    return Inertia::render('FileUpload', [
        'text' => $filteredText,
        'category' => $category,
        'originalImage' => asset('original_images/' . $file->getClientOriginalName()),
        'processedImage' => asset('processed_images/' . $file->getClientOriginalName())]);
    }

}
