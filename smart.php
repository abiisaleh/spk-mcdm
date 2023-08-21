<?php

/**
  * Metode SMART (Simple Multi Attribute Rating Technique)
  *
  * Dataset adalah data alternatif kuantitatif dalam bentuk array.
  * ex. $dataset = [
  *          [25000, 153, 15.3, 250],   #a1
  *          [33000, 177, 12.3, 380],   #a2
  *          [40000, 199, 11.1, 480]    #a3
  * ];
  *
  * Grades digunakan untuk menghitung bobot.
  * ex. $grades = [9, 5, 7, 6];
  *
  * Criterion Type: 'benefit' or 'cost'.
  * ex. $criterion_type = ['cost', 'benefit', 'cost', 'benefit'];
  */
function smart(array $alternatif, array $dataset,array $grades,array $criterion_type) {
    // normalisasi bobot
    foreach ($grades as $bobot) {
        $w[] = $bobot / array_sum($grades);
    }

    //max & min value
    for ($i=0; $i < count($grades); $i++) { 
        $max[] = max(array_column($dataset, $i));
        $min[] = min(array_column($dataset, $i));
    }

    for ($i=0; $i < count($dataset); $i++) { 
        for ($j=0; $j < count($dataset[$i]); $j++) { 
            //utility
            if ($criterion_type[$j] == 'cost') {
                $u = ($max[$j] - $dataset[$i][$j]) / ($max[$j] - $min[$j]);
            } else {
                $u = ($dataset[$i][$j] - $min[$j]) / ($max[$j] - $min[$j]);
            }
            $utility[$i][] = $u;

            //rangking
            $uw[$i][] = $u * $w[$j];
        }

        $result[$i]['alternatif'] = $alternatif[$i];
        $result[$i]['value'] = array_sum($uw[$i]);
    }

    //urutkan
    usort($result, fn($a, $b) => $b['value'] <=> $a['value']);

    return $result;
}