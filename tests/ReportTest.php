<?php

class ReportTest extends TestCase
{
    /**
     * Test render requirements report.
     *
     * @return void
     */
    public function testRenderRequirementsReport()
    {
        $this->post('reports/requirements', [
            "data" => [
                [
                    "name" => "Lumen Framework",
                    "version" => "5.7",
                    "link" => "https://lumen.laravel.com/"
                ],
                [
                    "name" => "JasperReports for PHP",
                    "version" => "2.5",
                    "link" => "https://github.com/cossou/JasperPHP"
                ]
            ],
            "params" => [
                "title" => "Requirements"
            ]
        ]);

        $this->assertResponseOk();

        $response = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('success', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('data', $response);
    }
}
