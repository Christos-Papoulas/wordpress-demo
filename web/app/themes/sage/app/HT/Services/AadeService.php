<?php

namespace App\HT\Services;

class AadeService
{
    public const WSDL = 'https://www1.gsis.gr/wsaade/RgWsPublic2/RgWsPublic2?WSDL';

    public const ENDPOINT = 'https://www1.gsis.gr/wsaade/RgWsPublic2/RgWsPublic2';

    public const XSD = 'https://www1.gsis.gr/wsaade/RgWsPublic2/RgWsPublic2?xsd=1';

    public const SECURITY_WSS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';

    public static function isAlive()
    {
        $handle = curl_init(self::WSDL);

        curl_setopt_array($handle, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_NOBODY => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        curl_exec($handle);

        $httpCode = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

        return $httpCode >= 200 && $httpCode < 300;
    }

    public static function validate_afm($afm, $username, $password)
    {
        try {
            // since soap client does not support custom namespaces in root element we have to do it manually.
            $envelope = '<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope" xmlns:ns1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ns2="http://rgwspublic2/RgWsPublic2Service" xmlns:ns3="http://rgwspublic2/RgWsPublic2">
                           <env:Header>
                              <ns1:Security>
                                 <ns1:UsernameToken>
                                    <ns1:Username>'.$username.'</ns1:Username>
                                    <ns1:Password>'.$password.'</ns1:Password>
                                 </ns1:UsernameToken>
                              </ns1:Security>
                           </env:Header>
                           <env:Body>
                              <ns2:rgWsPublic2AfmMethod>
                                 <ns2:INPUT_REC>
                                    <ns3:afm_called_by/>
                                    <ns3:afm_called_for>'.$afm.'</ns3:afm_called_for>
                                 </ns2:INPUT_REC>
                              </ns2:rgWsPublic2AfmMethod>
                           </env:Body>
                        </env:Envelope>';

            $handle = curl_init();

            curl_setopt_array($handle, [
                CURLOPT_URL => self::WSDL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $envelope,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/soap+xml',
                    'SOAPAction: "#POST"',
                ],
            ]);

            $response = curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

            if ($httpCode !== 200 && $httpCode < 300) {
                error_log('HTTP Error: '.$httpCode.' with Request Body: '.json_encode($envelope));

                return false;
            }

            curl_close($handle);
            $xml = simplexml_load_string($response);
            $xml->registerXPathNamespace('env', 'http://www.w3.org/2003/05/soap-envelope');
            $xml->registerXPathNamespace('srvc', 'http://rgwspublic2/RgWsPublic2Service');
            $elements = $xml->xpath('//env:Envelope/env:Body/srvc:rgWsPublic2AfmMethodResponse/srvc:result');
            if (is_bool($elements)) {
                return false;
            }

            $body = reset($elements);
            $vat_id = (string) $body->rg_ws_public2_result_rtType->basic_rec->afm;
            $name = (string) $body->rg_ws_public2_result_rtType->basic_rec->onomasia;
            $tax_office = (string) $body->rg_ws_public2_result_rtType->basic_rec->doy_descr;
            $activity = (string) $body->rg_ws_public2_result_rtType->firm_act_tab->item[0]->firm_act_descr;

            $postal_address = (string) $body->rg_ws_public2_result_rtType->basic_rec->postal_address;
            $postal_address_no = (string) $body->rg_ws_public2_result_rtType->basic_rec->postal_address_no;
            $postal_zip_code = (string) $body->rg_ws_public2_result_rtType->basic_rec->postal_zip_code;
            $postal_area_description = (string) $body->rg_ws_public2_result_rtType->basic_rec->postal_area_description;

            // $i_ni_flag_descr = (string) $body->rg_ws_public2_result_rtType->basic_rec->i_ni_flag_descr;
            // $deactivation_flag = (string) $body->rg_ws_public2_result_rtType->basic_rec->deactivation_flag;
            // $deactivation_flag_descr = (string) $body->rg_ws_public2_result_rtType->basic_rec->deactivation_flag_descr;
            // $normal_vat_system_flag = (string) $body->rg_ws_public2_result_rtType->basic_rec->normal_vat_system_flag;

            if (empty($vat_id) || empty($name) || empty($tax_office) || empty($activity)) {
                return false;
            }

            return [
                'vat_id' => $vat_id,
                'name' => $name,
                'tax_office' => $tax_office,
                'activity' => $activity,
                'postal_address' => $postal_address,
                'postal_address_no' => $postal_address_no,
                'postal_zip_code' => $postal_zip_code,
                'postal_area_description' => $postal_area_description,

                // 'i_ni_flag_descr' => $i_ni_flag_descr,
                // 'deactivation_flag' => $deactivation_flag,
                // 'deactivation_flag_descr' => $deactivation_flag_descr,
                // 'normal_vat_system_flag' => $normal_vat_system_flag,
            ];
        } catch (\Exception $e) {
            error_log('Exception: '.$e->getMessage());
        }
    }
}
