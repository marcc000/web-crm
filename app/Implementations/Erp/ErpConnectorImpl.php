<?php

namespace App\Implementations\Erp;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Spatie\ArrayToXml\ArrayToXml;

class ErpConnectorImpl
{
    private SoapWrapper $soapWrapper;

    private array $callContext = [
        'codeLang' => 'ITA',
        'poolAlias' => 'WSTST',
        'requestConfig' => '', //'adxwss.trace.on=on&adxwss.trace.size=16384&adonix.trace.on=on&adonix.trace.level=3&adonix.trace.size=8'
    ];

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->soapWrapper = new SoapWrapper();
        $this->soapWrapper->add('X3', function ($service) {
            $service
                ->wsdl('http://172.28.1.92:8124/soap-wsdl/syracuse/collaboration/syracuse/CAdxWebServiceXmlCC?wsdl')
                ->trace(true)
                ->options([
                    'login' => 'SFA',
                    'password' => 'Sfa!2017',
                ]);
        });
    }

    public function createProspect()
    {
        $xml = ArrayToXml::convert(
            [
                'FLD' => [
                    [
                        '_attributes' => ['NAME' => 'BCGCOD'],
                        '_value' => 'IT',
                    ],
                    [
                        '_attributes' => ['NAME' => 'REP'],
                        '_value' => 'GUD',
                    ],
                    [
                        '_attributes' => ['NAME' => 'BPRNAM'],
                        '_value' => 'RAGSOC',
                    ],
                ],
                'LST' => [
                    [
                        '_attributes' => ['NAME' => 'TSCCOD'],
                        'ITM' => [
                            '1',
                            'D',
                        ],
                    ],
                ],
                'TAB' => [
                    [
                        '_attributes' => ['ID' => 'BPAC_1'],
                        'LIN' => [
                            'FLD' => [
                                [
                                    '_attributes' => ['NAME' => 'CODADR'],
                                    '_value' => '000',
                                ],
                            ],
                        ],
                    ],
                    [
                        '_attributes' => ['ID' => 'BPC4_1'],
                        'LIN' => [
                            'FLD' => [
                                [
                                    '_attributes' => ['NAME' => 'BPAADD'],
                                    '_value' => '000',
                                ],
                            ],
                        ],
                    ],
                ],
            ], 'PARAM');
        echo $xml;
        $response = $this->soapWrapper->call('X3.save', [
            'callContext' => $this->callContext,
            'publicName' => 'YBPC',
            'objectXml' => $xml,
        ]);

        //var_dump($this->soapWrapper);
        var_dump($response);
    }
}
