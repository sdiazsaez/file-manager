<?php

namespace Larangular\UnidadFomento\Tests;

use Illuminate\Support\Collection;
use Larangular\UnidadFomento\Http\Controllers\FileManager\Gateway;
use Larangular\UnidadFomento\Http\Controllers\FileManager\UnidadFomentoController;
use Larangular\UnidadFomento\Models\FileManager;

class UnidadFomentoTest extends AbstractTestCase {

    private $gateway;
    private $unidadFomentoController;

    private function getGateway(): Gateway {
        if (!isset($this->gateway)) {
            $this->gateway = new Gateway();
        }
        return $this->gateway;
    }

    private function getUnidadFomentoController(): UnidadFomentoController {
        if (!isset($this->unidadFomentoController)) {
            $this->unidadFomentoController = resolve(UnidadFomentoController::class);
        }
        return $this->unidadFomentoController;
    }

    private function getRandomDate(?int $min, ?int $max) {
        if (is_null($min)) {
            $min = 1262055681;
        }

        if (is_null($max)) {
            $max = 1533055681;
        }

        $int = mt_rand($min, $max);
        $date = date("Y-m-d", $int);
        return $date;
    }

    private function isRequestDateEqual($date): bool {
        $response = $this->getGateway()
                         ->current($date);
        $responseDate = date_format(date_create($response->date), 'Y-m-d');
        return ($responseDate == $date);
    }

    public function testJsonSchema() {
        $this->get('/api/uf/date')
             ->assertJsonStructure([
                                       'id',
                                       'UF',
                                       'date',
                                   ]);
    }

    public function testGetCurrentWithDate() {
        $date = date('Y-m-d');
        $this->assertTrue($this->isRequestDateEqual($date));
    }

    public function testYesterday() {
        $date = new \DateTime();
        $date->add(\DateInterval::createFromDateString('yesterday'));
        $this->assertTrue($this->isRequestDateEqual($date->format('Y-m-d')));
    }

    public function testRandomDateUnidadFomento() {
        $date = $this->getRandomDate();
        $this->assertTrue($this->isRequestDateEqual($date));
    }

    public function testMissingValue() {
        $date = $this->getRandomDate(1733055681, 1933055681);
        $lastValue = FileManager::lastFirst()
                                ->first();
        $response = $this->getGateway()
                         ->current($date);

        $responseDate = date_format(date_create($response->date), 'Y-m-d');

        $isDateEqual = ($responseDate == $date);
        $isEqualToLastValue = ($lastValue->UF == $response->UF);

        $this->assertTrue(($isDateEqual && $isEqualToLastValue));
    }

    public function testLoadRange() {
        $range = new Collection([
                                    '2017-01-12',
                                    '2017-01-13',
                                    '2017-01-14',
                                    '2017-01-15',
                                    '2017-01-16',
                                ]);

        $this->getUnidadFomentoController()
                         ->loadRange($range->first(), $range->last());

        $records = FileManager::whereIn('date', $range)->get();
        $this->assertCount(count($range), $records);
    }

}
