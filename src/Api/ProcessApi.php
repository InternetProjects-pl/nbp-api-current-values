<?php

namespace NbpApiCurrentValues\Api;

/**
 * ProcessApi - klasa zawierająca metody służące do przetwarzania danych z API NBP
 */
class ProcessApi
{
  
  /**
   * apiUrlExchangeRates
   *
   * @var mixed
   */
  private $apiUrlExchangeRates;
  
  /**
   * apiUrlGoldPrice
   *
   * @var mixed
   */
  private $apiUrlGoldPrice;
  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct(
    string $apiUrlExchangeRates = 'http://api.nbp.pl/api/exchangerates/rates/A/',
    string $apiUrlGoldPrice = 'http://api.nbp.pl/api/cenyzlota'
  ) {
    $this->apiUrlExchangeRates = $apiUrlExchangeRates;
    $this->apiUrlGoldPrice = $apiUrlGoldPrice;
  }
  
  /**
   * getApiValues
   *
   * @param  mixed $currencyCode
   * @return void
   */
  public function getApiValues(string $currencyCode)
  {
    if (empty($currencyCode)) {
      $url = $this->apiUrlGoldPrice;
    } else {
      $url = $this->apiUrlExchangeRates . $currencyCode . '/';
    }

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
      return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($currencyCode)) {
      return isset($data[0]['cena']) ? $data[0]['cena'] : false;
    } else {
      return isset($data['rates'][0]['mid']) ? $data['rates'][0]['mid'] : false;
    }

  }
}

