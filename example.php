<?php

include "smart.php";
include "saw.php";

$alternatif = ['a1','a2','a3'];

# nilai kriteria setiap alternatif
$dataset = [
              [25000, 153, 15.3, 250],   #a1
              [33000, 177, 12.3, 380],   #a2
              [40000, 199, 11.1, 480]    #a3
];

# Bobot
$grades = [30, 1, 15, 1];

# Criterion Type: 'cost' or 'benefit'
$criterion_type = ['cost', 'benefit', 'cost', 'benefit'];

$smart = smart($alternatif , $dataset, $grades, $criterion_type);

$saw = saw($alternatif , $dataset, $grades, $criterion_type);

?>

<h1>SMART</h1>
<table>
    <thead>
        <th style="border: 1px solid black;">Alternatif</th>
        <th style="border: 1px solid black;">Value</th>
    </thead>
    <tbody>
        <?php foreach ($smart as $value) :?>
            <tr>
                <td style="border: 1px solid black;"><?php echo $value['alternatif']?></td>
                <td style="border: 1px solid black;"><?php echo $value['value']?></td>
            </tr>
        <?php endforeach?>
    </tbody>
</table>

<h1>SAW</h1>
<table>
    <thead>
        <th style="border: 1px solid black;">Alternatif</th>
        <th style="border: 1px solid black;">Value</th>
    </thead>
    <tbody>
        <?php foreach ($saw as $value) :?>
            <tr>
                <td style="border: 1px solid black;"><?php echo $value['alternatif']?></td>
                <td style="border: 1px solid black;"><?php echo $value['value']?></td>
            </tr>
        <?php endforeach?>
    </tbody>
</table>