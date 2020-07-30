<?php

namespace Drupal\bart;

use Drupal\Component\Serialization\Json;

class BartClient {

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Get BART data.
     *
     * @param string $station
     *
     * @return array
     */
    public function getRoutes($station) {
        //get schedule data from BART API
        $client = \Drupal::httpClient();
        $url = "http://api.bart.gov/api/sched.aspx?key=MW9S-E7SL-26DU-VV8V&json=y&cmd=stnsched&orig=" . $station . "&l=1";
        try {

            $response = $client->get($url);

            $data = $response->getBody();
            if (empty($data)) {
                drupal_set_message('Empty response.');
            } else {
                //put response in an associative array
                $decoded = json_decode($data, TRUE);
                return $decoded;
            }
        } catch (RequestException $e) {
            watchdog_exception('bart', $e->getMessage());
        }
    }

    public function showRoutes($bart_data) {
        //parse data and pass to Twig template
        $station_name = $bart_data['root']['station']['name'];

        $item_count = count($bart_data['root']['station']['item']);

        if ($item_count > 0) {
            $items = []; //array to hold the routes

            for ($x = 0; $x < $item_count; $x++) {
                $bart_item = $bart_data['root']['station']['item'][$x];

                $items[$x]['line'] = $bart_item['@line']; //name of line
                $items[$x]['origTime'] = $bart_item['@origTime']; //original time
                $items[$x]['trainHeadStation'] = $bart_item['@trainHeadStation']; // original station
                $items[$x]['bikeflag'] = $bart_item['@bikeflag'];
            }
        }
        return [
            '#theme' => 'bart_edt',
            '#station' => $station_name,
            '#items' => $items,
            '#number_routes' => $item_count,
        ];
    }

    public function returnStations() {
        //TODO: Reprogram to get this list dynamically from the API
 //       $stations='test';
       $stations =array(
       '12TH' => '12th St. Oakland City Center',
    '16TH' =>'16th St. Mission (SF)',
    '19TH' => '19th St. Oakland',
    '24TH' => '24th St. Mission (SF)',
    'ANTC' => 'Antioch',
    'ASHB' => 'Ashby (Berkeley)',
    'BALB' =>'Balboa Park (SF)',
    'BAYF' => 'Bay Fair (San Leandro)',
    'BERY' => 'Berryessa/North San José',
    'CAST' => 'Castro Valley',
    'CIVC' => 'Civic Center / UN Plaza',
    'COLS' => 'Coliseum',
    'COLM' => 'Colma',
    'CONC' => 'Concord',
    'DALY' => 'Daly City',
    'DBRK' => 'Downtown Berkeley',
    'DUBL' => 'Dublin / Pleasanton',
    'DELN' => 'El Cerrito del Norte',
    'PLZA' => 'El Cerrito Plaza',
    'EMBR' => 'Embarcadero (SF)',
    'FRMT' => 'Fremont',
    'FTVL' => 'Fruitvale (Oakland)',
    'GLEN' => 'Glen Park (SF)',
    'HAYW' => 'Hayward',
    'LAFY' => 'Lafayette',
    'LAKE' => 'Lake Merritt (Oakland)',
    'MCAR' => 'MacArthur (Oakland)',
    'MLBR' => 'Millbrae',
    'MLPT' => 'Milpitas',
    'MONT' => 'Montgomery St. (SF)',
    'NBRK' =>'North Berkeley',
    'NCON' => 'North Concord / Martinez',
    'OAKL' => 'Oakland International Airport',
    'ORIN' => 'Orinda',
    'PITT' => 'Pittsburg / Bay Point',
    'PCTR' => 'Pittsburg Center',
    'PHIL' => 'Pleasant Hill / Contra Costa Centre',
    'POWL' => 'Powell St. (SF)',
    'RICH' =>'Richmond',
    'ROCK' => 'Rockridge (Oakland)',
    'SBRN' => 'San Bruno',
    'SFIA' => 'San Francisco International Airport',
    'SANL' => 'San Leandro',
    'SHAY' => 'South Hayward',
    'SSAN' => 'South San Francisco',
    'UCTY' => 'Union City',
    'WCRK' => 'Walnut Creek',
    'WARM' => 'Warm Springs / South Fremont',
    'WDUB' => 'West Dublin / Pleasanton',
    'WOAK' => 'West Oakland',
 
);
        return $stations;
    }

}
