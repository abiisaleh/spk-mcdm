<?php

class mcdm
{
    private function normalisasi($bobot)
    {
        foreach ($bobot as $Bobot) {
            $w[] = $Bobot / array_sum($bobot);
        }

        return $w;
    }

    private function getValue($count, $dataset, $type = 'max')
    {
        for ($i = 0; $i < $count; $i++) {
            if ($type == 'max')
                $value[] = max(array_column($dataset, $i));
            else
                $value[] = min(array_column($dataset, $i));
        }

        return $value;
    }

    private function sortData($array)
    {
        return usort($array, fn ($a, $b) => $b['value'] <=> $a['value']);
    }

    /**
     * Metode SAW (Simple Additive Weighting)
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
    public function saw(array $alternatif, array $dataset, array $grades, array $criterion_type)
    {
        $w = $this->normalisasi($grades);
        $min = $this->getValue(count($grades), $dataset, 'min');
        $max = $this->getValue(count($grades), $dataset, 'max');

        for ($i = 0; $i < count($dataset); $i++) {
            for ($j = 0; $j < count($dataset[$i]); $j++) {
                //rating
                if ($criterion_type[$j] == 'cost')
                    $r = $min[$j] / $dataset[$i][$j];
                else
                    $r = $dataset[$i][$j] / $max[$j];

                //rangking
                $uw[$i][] = $r * $w[$j];
            }

            $result[$i]['alternatif'] = $alternatif[$i];
            $result[$i]['value'] = array_sum($uw[$i]);
        }

        //urutkan
        $this->sortData($result);

        return $result;
    }

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
    function smart(array $alternatif, array $dataset, array $grades, array $criterion_type)
    {
        $w = $this->normalisasi($grades);
        $min = $this->getValue(count($grades), $dataset, 'min');
        $max = $this->getValue(count($grades), $dataset, 'max');

        for ($i = 0; $i < count($dataset); $i++) {
            for ($j = 0; $j < count($dataset[$i]); $j++) {
                //utility
                if ($criterion_type[$j] == 'cost')
                    $u = ($max[$j] - $dataset[$i][$j]) / ($max[$j] - $min[$j]);
                else
                    $u = ($dataset[$i][$j] - $min[$j]) / ($max[$j] - $min[$j]);

                //rangking
                $uw[$i][] = $u * $w[$j];
            }

            $result[$i]['alternatif'] = $alternatif[$i];
            $result[$i]['value'] = array_sum($uw[$i]);
        }

        //urutkan
        $this->sortData($result);

        return $result;
    }
}
