<?php

class BopApi {

    static function getLocations() {
        // $result = file_get_contents('https://www.bop.gov/PublicInfo/execute/locations/?todo=query&output=json');
        $result = file_get_contents('./locations.json');

        $result = json_decode($result);
        // $locations = $result->Locations;

        // return $locations;


        return $result;
    }

    static function searchInmateById($type, $id) {
        $result = file_get_contents('https://www.bop.gov/PublicInfo/execute/inmateloc?todo=query&output=json&inmateNum=' . $id . '&inmateNumType=' . $type);

        $result = json_decode($result);
        $inmates = $result->InmateLocator;

        return $inmates;
    }

    static function searchInmateByDetails(array $search) {
        $nameFirst = ! empty($search['first_name']) ? $search['first_name'] : '';
        $nameMiddle = ! empty($search['middle_name']) ? $search['middle_name'] : '';
        $nameLast = ! empty($search['last_name']) ? $search['last_name'] : '';
        $age = ! empty($search['race']) ? $search['race'] : '';
        $race = ! empty($search['sex']) ? $search['sex'] : '';
        $sex = ! empty($search['age']) ? $search['age'] : '';
        $location = ! empty($search['location']) ? $search['location'] : '';

        $result = file_get_contents('https://www.bop.gov/PublicInfo/execute/inmateloc?todo=query&output=json&inmateNum=&nameFirst=' . $nameFirst . '&nameMiddle=' . $nameMiddle . '&nameLast=' . $nameLast . '&age=' . $age . '&race=' . $race . '&sex=' . $sex);

        $result = json_decode($result);
        $inmates = $result->InmateLocator;

        if (! empty($location)) {
            $__inmates = [];

            foreach ($inmates as $i => $inmate) {
                // echo $inmate->faclCode . "<br>";
                if ($inmate->faclCode == $location) {
                    $__inmates[] = $inmate;
                }
            }

            $inmates = $__inmates;
        }

        return $inmates;
    }

}


