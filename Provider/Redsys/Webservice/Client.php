<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice;

class ClientClient extends \Zend\Soap\Client
{
    // Environments
    const ENV_INTEGRATION						= 'integration';
    const ENV_TESTING							= 'testing';
    const ENV_PRODUCTION						= 'production';

    const SOAP_REQUEST_WRAPPER					= 'datoEntrada';

    /**
     *
     * Wsdl for the different environments
     *
     * @var string
     */
    private $wsdls = array (
        self::ENV_INTEGRATION 	=> 'https://sis-i.redsys.es:25443/sis/services/SerClsWSEntrada/wsdl/SerClsWSEntrada.wsdl',
        self::ENV_TESTING 		=> 'https://sis-t.sermepa.es:25443/sis/services/SerClsWSEntrada?WSDL',
        self::ENV_PRODUCTION	=> 'https://sis.sermepa.es/sis/services/SerClsWSEntrada?WSDL',
    );


    /**
     *
     * Class Constructor
     *
     * @param string $environment
     */
    public function __construct($environment = self::ENV_PRODUCTION){

        parent::__construct(
            $this->wsdls[$environment], array(
            'soap_version' => SOAP_1_1
        ));

    }


    /**
     *
     * Send a request and process response
     *
     * @param Request $request
     * @return Response
     */
    public function makeRequest(Request $request){

        $response = $this->sendRequest($request);
        return $this->processResponse($response);

    }


    /**
     *
     * Send a request
     * @param Request $request
     * @return Object
     */
    private function  sendRequest(Request $request){

        $xmlRequest = $request->toXml();
        $response = $this->trataPeticion(array(
            self::SOAP_REQUEST_WRAPPER => $xmlRequest
        ));

        return $response;

    }

    /**
     *
     * Process response
     * @param Response $response
     */
    private function processResponse($response){

        $response = new Response($response->trataPeticionReturn);
        return $response;
    }



}

