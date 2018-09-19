<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JasperPHP\JasperPHP;

class ReportController extends Controller
{
    /**
     * Render a report.
     *
     * @param Request $request
     * @param string $report
     *
     * @return array|null|string
     *
     * @throws \Exception
     */
    public function render(Request $request, $report)
    {
        $builder = new JasperPHP();

        $jasperFile = storage_path($report . '.jasper');
        $jasperFile2 = storage_path($report);
        $jrxmlFile = resource_path('reports/' . $report . '.jrxml');
        $outputFile = storage_path(uniqid());
        $dataFilename = storage_path(uniqid() . '.json');

        if (file_exists($jasperFile) === false) {
            $builder->compile($jrxmlFile, $jasperFile2, false, false)->execute();
        }

        $data = $request->input('data', []);
        $params = $request->input('params', []);

        file_put_contents($dataFilename, json_encode([
            'main' => $data
        ]));

        $format = ['pdf'];
        $driver = [
            'driver'=>'json',
            'json_query' => 'main',
            'data_file' =>  $dataFilename
        ];

        $builder->process(
            $jasperFile,
            $outputFile,
            $format,
            $params,
            $driver,
            false
        )->execute();

        $outputFile .= '.pdf';

        $response = [
            'success' => true,
            'message' => 'OK',
            'data' => [
                'file_content' => base64_encode(file_get_contents($outputFile)),
                'file_name' => $parameters['title'] ?? 'Report',
            ]
        ];

        unlink($outputFile);
        unlink($dataFilename);

        return $response;
    }
}
