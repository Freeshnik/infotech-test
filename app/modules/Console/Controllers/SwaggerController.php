<?php

namespace Console\Controllers;


use OpenApi\Generator;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class SwaggerController extends Controller
{
    /**
     * Генерирует и сохраняет openapi.json файл.
     */
    public function actionGenerate(): int
    {
        $generator = new Generator();
        // Путь к папке с контроллерами API
        $openapi = $generator->generate([
            Yii::getAlias('@App/Controller'),
            Yii::getAlias('@Api/Controllers'),
        ]);
//        $openapi = Generator::scan([
//            \Yii::getAlias('@App/Controller'), // или @frontend/controllers
//            \Yii::getAlias('@Api/Controllers'), // если используете модели
//        ]);

        // Путь для сохранения JSON файла
        $jsonPath = \Yii::getAlias('@Web/docs/swagger.json');
        file_put_contents($jsonPath, $openapi->toJson());

        echo "Swagger документация сгенерирована: " . $jsonPath . "\n";

        return ExitCode::OK;
        // Путь, куда будет сохранен сгенерированный файл.
        // Обычно его кладут в веб-доступную папку.
        $outputPath = Yii::getAlias('@app/../web/docs/openapi.json');

        // Папки, которые нужно просканировать на наличие аннотаций.
        // Обязательно добавьте все места, где у вас есть контроллеры, модели (DTO) и т.д.
        $scanPaths = [
            Yii::getAlias('@App/Controller'), // Ваш базовый ApiController
            Yii::getAlias('@Api/Controllers') // Контроллеры вашего API-модуля
        ];

        // Создаем директорию, если она не существует
        $outputDir = dirname($outputPath);
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0775, true);
        }

        $this->stdout("Сканирование папок:\n");
        foreach ($scanPaths as $path) {
            $this->stdout("- " . $path . "\n");
        }

        // Генерируем спецификацию
        $openapi = Generator::scan($scanPaths);

        // Сохраняем результат в файл
        if ($openapi->saveAs($outputPath)) {
            $this->stdout("Документация успешно сгенерирована в: {$outputPath}\n");
            return ExitCode::OK;
        }

        $this->stderr("Произошла ошибка при сохранении файла.\n");
        return ExitCode::UNSPECIFIED_ERROR;
    }
}