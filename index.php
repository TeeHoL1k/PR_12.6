<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

// Разбивает fulname на строки
function getPartsFromFullname($fullname) {
    $parts = explode(" ", $fullname);
    
    $nameparts = [
        'surname', 
        'name', 
        'patronomyc', 
    ];
    
    return array_combine($nameparts, $parts);
}

// Соединяет строки в Ф И О
function getFullnameFromParts($surname, $name, $patronomyc) {
    return $surname . ' ' . $name . ' ' . $patronomyc;
}


// Возврат ФИО в формате Иван И.
function getShortName($fullname) {
    $parts = getPartsFromFullname($fullname);

    return $parts['name'] . ' ' . mb_substr($parts['surname'], 0, 1) . '.';
}

// Определяет пол
function getGenderFromName($fullname) {
    $parts = getPartsFromFullname($fullname);
    $gender = 0;

    if (str_ends_with($parts['patronomyc'], 'ич')) {
        $gender += 1;
    } 
    
    if (str_ends_with($parts['name'], 'й')
        ||
        (str_ends_with($parts['name'], 'н'))) {
        $gender += 1;
    }

    if (str_ends_with($parts['surname'], 'в')) {
        $gender += 1;
    } 

    if (str_ends_with($parts['patronomyc'], 'вна')) {
        $gender -= 1;
    }

    if (str_ends_with($parts['name'], 'а')) {
        $gender -= 1;
    }

    if (str_ends_with($parts['surname'], 'ва')) {
        $gender -= 1;
    }

    if ($gender > 0) {
        return 1;
    } elseif ($gender < 0) {
        return -1;
    } else {
        return 0;
    }
}

// Определение возрастно-полового состава
function getGenderDescription($persons) {

    $male = array_filter($persons, function ($person) {
        return getGenderFromName($person['fullname']) === 1;
    });

    $female = array_filter($persons, function ($person) {
        return getGenderFromName($person['fullname']) === -1;
    });

    $nogender = array_filter($persons, function ($person) {
        return getGenderFromName($person['fullname']) === 0;
    });

    $maleCount = count($male);
    $femaleCount = count($female);
    $nogenderCount = count($nogender);

    $total = $maleCount + $femaleCount + $nogenderCount;
    $malePercent = round(($maleCount / $total) * 100, 1);
    $femalePercent = round(($femaleCount / $total) * 100, 1);
    $nogenderPercent = round(($nogenderCount / $total) * 100, 1);

    echo "Гендерный состав аудитории:\n";
    echo "---------------------------\n";
    echo 'Мужчины - ' . $malePercent . "%\n";
    echo 'Женщины - ' . $femalePercent . "%\n";
    echo 'Не удалось определить - ' . $nogenderPercent . "%\n";

}

// Идеальный подбор пары
function getPerfectPartner($surname, $name, $patronomyc, $partners) {
    $name = mb_convert_case($name, MB_CASE_TITLE);
    $surname = mb_convert_case($surname, MB_CASE_TITLE);
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE);

    $firstpartner = getFullnameFromParts($surname, $name, $patronomyc);
    $firstGender = getGenderFromName($firstpartner);

    do {
        $randomKey = array_rand($partners);
        $secondPartner = $partners[$randomKey]['fullname'];
        $secondGender = getGenderFromName($secondPartner);
    } while ($firstGender * $secondGender == 1);
    
    $firstShortName = getShortName($firstpartner);
    $secondShortName = getShortName($secondPartner);
    $percent = rand(5000, 10000) / 100;
    $percent = number_format($percent, 2);

    echo $firstShortName . ' + ' . $secondShortName . " = \n";
    echo '♡ Идеально на ' . $percent . "% ♡\n";
}


// ЗАПРОСЫ ДЛЯ ПРОВЕРКИ ФУНКЦИЙ
// 1. var_dump(getPartsFromFullname('Иванов Иван Иванович'));
// 2. echo getShortName('Иванов Иван Иванович');
// 3. var_dump(getGenderFromName('Иванов Иван Иванович'));
// 4. getGenderDescription($example_persons_array);
// 5. getPerfectPartner('Иванов', 'Иван', 'Иванович', $example_persons_array);

