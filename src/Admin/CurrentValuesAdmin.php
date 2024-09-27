<?php

namespace NbpApiCurrentValues\Admin;

use NbpApiCurrentValues\Api\ProcessApi;

/**
 * CurrentValuesAdmin - klasa służąca do wyświetlenia danych pobranych z API NBP
 */
class CurrentValuesAdmin
{  
  /**
   * api
   *
   * @var mixed
   */
  private $api;
  
  /**
   * parameters
   *
   * @var mixed
   */
  public $parameters;
  
  /**
   * timezone
   *
   * @var mixed
   */  
  /**
   * timezone
   *
   * @var mixed
   */
  public $timezone;  
  /**
   * __construct
   *
   * @return void
   */
  public function __construct ()
  {
    $this->api = new ProcessApi();
    $this->parameters =  [
      'Złoto' => '',
      'Euro' => 'EUR',
      'Funt brytyjski' => 'GBP',
      'Dolar amerykański' => 'USD'
      // TODO - do przerobienia na pobieranie z opcji plugina, gdzie będziemy definiować, jakich walut kursy będziemy pobierać
    ];
    $this->timezone = date_default_timezone_set('Europe/Warsaw');
    
  }
    
  /**
   * init
   *
   * @return void
   */
  public function init()
  {
    add_action('after-post_tag-table', [$this, 'displayCurrentData']);
  }
   
   /**
    * displayCurrentData
    *
    * @return void
    */
   public function displayCurrentData()
  {
    add_filter('data_format', function ($data) {
      return number_format($data, 2);
    });

    $showList = '';

    foreach ($this->parameters as $key => $value) {
      $currencyRate = apply_filters('data_format', $this->api->getApiValues($value));
      if($key == 'Złoto') {
        $unit = 'PLN/gram';
      } else {
        $unit = 'PLN';
      }
      $showList .= '<tr><td>' . $key . '</td><td>' . $currencyRate . ' ' . $unit .'</td></tr>';
    }
    if (!empty($showList)) {

      $showCurrentData =
        '<h3>Aktualna cena złota oraz kursy średnie walut: ' . date('Y-m-d H:i') . '</h3>
        <table class="gold-currencies-table">
        <thead>
        <tr><th>Pozycja</th><th>Wartość</th></tr>
        </thead>
        <tbody>';
      $showCurrentData .= $showList;
      $showCurrentData .=
        '</tbody>
        </table>
        ';

      echo $showCurrentData;
    } else {
      echo '<div><p>Błąd przy pobieraniu danych z API NBP.</p></div>';
    }
  }
    
  /**
   * registerCss
   *
   * @return void
   */
  function registerCss()
  {
    add_action('admin_enqueue_scripts', array($this, 'customCss'));
  }
    
  /**
   * customCss
   *
   * @return void
   */
  function customCss()
  {
    wp_enqueue_style('NbpApiStyle', plugin_dir_url(__FILE__) . '../../assets/css/admin_style.css', '', time());
  }

}