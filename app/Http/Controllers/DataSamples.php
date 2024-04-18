<?php

namespace App\Http\Controllers;

use Phpml\Classification\SVC;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\NGramTokenizer;
use Phpml\FeatureExtraction\StopWords\English;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Pipeline;
use League\Csv\Reader;
use League\Csv\Writer;
use IntlChar;

class DataSamples extends Controller
{
    public static function LearnMachine(String $text)
    {
        $modelPath = storage_path('app/machine_model.phpml');

        // Перевірка, чи існує збережена модель
        if (file_exists($modelPath)) {
            $classifier = unserialize(file_get_contents($modelPath));
        } else {
            // Завантаження даних з файлу CSV
            $csvPath = base_path('resources/csvs/data.csv');
            $csv = Reader::createFromPath($csvPath);
            $csv->setDelimiter(',');
            $records = $csv->getRecords();

            $samples = [];
            $labels = [];

            foreach ($records as $record) {
                $labels[] = $record[0]; // Перша колонка - це мітки
                $samples[] = $record[1]; // Друга колонка - це зразки
            }

            $classifier = new Pipeline([
                new TokenCountVectorizer(new NGramTokenizer(1, 3), new English()),
                new TfIdfTransformer(),
            ], new SVC());

            $classifier->train($samples, $labels);

            // Збереження моделі
            file_put_contents($modelPath, serialize($classifier));
        }

        $prediction = $classifier->predict([$text]);
        return $prediction[0];
    }
}
