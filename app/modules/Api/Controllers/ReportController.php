<?php

namespace Api\Controllers;

use App\Controller\RestController;
use App\Services\ReportService;
use Yii;

class ReportController extends RestController
{
    public function actionIndex(): array
    {
        $selectedYear = (int) $this->request->getQueryParam ('year', 2010);

        $service = Yii::createObject(ReportService::class, ['year' => $selectedYear]);

        $dataProvider = $service->getTopTenAuthorsData();
        $allYears = $service->getAllYears();

        return [
            'selected_year' => $selectedYear,
            'all_years' => $allYears,
            'items' => $dataProvider->getMODels(),
        ];
    }
}