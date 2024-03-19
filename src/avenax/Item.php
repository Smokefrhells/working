<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 25.08.2019
 * Time: 13:21
 */

class Item {


    public static function Runes($type = false) {
        $obj = (object)[
            1 => (object)[
                'name' => "Обычная руна", // имя
                'params' => 15, // + к параметрам
                'price' => 100, // цена
                'img' => '/images/runes/rune-1.png',
                'color' => 'ceb591',
                'id' => 1
            ],
            2 => (object)[
                'name' => "Необычная руна",
                'params' => 30,
                'price' => 500,
                'img' => '/images/runes/rune-2.png',
                'color' => '15de78',
                'id' => 2
            ],
            3 => (object)[
                'name' => "Редкая руна",
                'params' => 60,
                'price' => 1000,
                'img' => '/images/runes/rune-3.png',
                'color' => '6090ff',
                'id' => 3
            ],
            4 => (object)[
                'name' => "Эпическая руна",
                'params' => 120,
                'price' => 5000,
                'img' => '/images/runes/rune-4.png',
                'color' => 'da63ff',
                'id' => 4
            ],
            5 => (object)[
                'name' => "Легендарная руна",
                'params' => 200,
                'price' => 10000,
                'img' => '/images/runes/rune-5.png',
                'color' => '15e3ef',
                'id' => 5
            ],
            6 => (object)[
                'name' => "Мифическая руна",
                'params' => 300,
                'price' => 25000,
                'img' => '/images/runes/rune-6.png',
                'color' => 'ea5d5c',
                'id' => 6
            ],
        ];
        if (!empty($type)) {
            $type = "$type";
            return $obj->$type;
        }
        return $obj;
    }


    /**
     * >+100 ПР.качество<
     * >+500 СТ.качество<
     * >+1000 ЭП.качество<
     * >+5000 МА.качество<
     * >+10000 БЕ.качество<
     * >+20000 ДР.качество<
     */
    public static function ratingHard($level) {
        $arr = [
            1 => 100, 500, 1000, 5000, 10000, 20000, 30000
        ];
        return $arr[$level];
    }

    public static function getTypeItem($id_weapon = false) {
        $arr = [
            'head' => [1, 9, 17, 25, 33, 41, 49, 57, 65, 73, 81, 89, 97, 105, 113, 121, 129, 137, 145, 153, 161, 169, 177, 185, 193, 201, 209, 217, 225], // голова
            'shoulder' => [2, 10, 18, 26, 34, 42, 50, 58, 66, 74, 82, 90, 98, 106, 114, 122, 130, 138, 146, 154, 162, 170, 178, 186, 194, 202, 210, 218, 226], // плечо
            'armor' => [3, 11, 19, 27, 35, 43, 51, 59, 67, 75, 83, 91, 99, 107, 115, 123, 131, 139, 147, 155, 163, 171, 179, 187, 195, 203, 211, 219, 227], // броня
            'gloves' => [4, 12, 20, 28, 36, 44, 52, 60, 68, 76, 84, 92, 100, 108, 116, 124, 132, 140, 148, 156, 164, 172, 180, 188, 196, 204, 212, 220, 228], // перчатки
            'weapons_1' => [5, 13, 21, 29, 37, 45, 53, 61, 69, 77, 85, 93, 101, 109, 117, 125, 133, 141, 149, 157, 165, 173, 181, 189, 197, 205, 213, 221, 229], // клинок
            'weapons_2' => [6, 14, 22, 30, 38, 46, 54, 62, 70, 78, 86, 94, 102, 110, 118, 126, 134, 142, 150, 158, 166, 174, 182, 190, 198, 206, 214, 222, 230], // клинок
            'pants' => [7, 15, 23, 31, 39, 47, 55, 63, 71, 79, 87, 95, 103, 111, 119, 127, 135, 143, 151, 159, 167, 175, 183, 191, 199, 207, 215, 223, 231], // штаны
            'boots' => [8, 16, 24, 32, 40, 48, 56, 64, 72, 80, 88, 96, 104, 112, 120, 128, 136, 144, 152, 160, 168, 176, 184, 192, 200, 208, 216, 224, 232], // ботинки
        ];

        if ($id_weapon == true) {
            foreach ($arr as $type => $v) {
                if (in_array($id_weapon, $v)) {
                    return $type;
                }
            }
        }
        return $arr;
    }

    public static function priceSale($level) {
        $arr = [1 => 100, 200, 300, 400, 500, 600, 700];
        return $arr[$level];
    }

    public static function getColor($id) {
        $list = Item::Items();
        foreach ($list as $k => $v) {
            if (in_array($id, $v->id)) {
                return 'bor-' . $k;
            }
        }
    }

    /**
     * настройки вещей
     * @param bool $type
     * @return object
     */
    public static function Items($type = false) {
        $obj = (object)[
            1 => (object)[
                'id' => range(1, 32),
                'type' => '<span style="color: #bfbfbf;">Простое</span>',
                'type_img' => '/style/images/quality/1.png',
                'gold' => 25, // цены золото
                'silver' => 1000, // цены серебро
                'min_quenching' => 1, // мин лвл закалки
                'max_quenching' => 10, // макс лвл закалки
                'max_sharpening' => 10, // макс лвл заточки
                'str' => 50,
                'def' => 50,
                'hp' => 50,
                'level' => 1,
                'quenching_rating' => 500,
                'output_set' => [
                    1 => [
                        'name' => 'Комплект охотника',
                        'img' => '/images/sets/set1.jpg',
                        'output_item' => [
                            1 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника',
                        ]
                    ],
                    2 => [
                        'name' => 'Комплект стражника',
                        'img' => '/images/sets/set2.jpg',
                        'output_item' => [
                            9 => 'Капюшон копейщика',
                            'Наплечник копейщика',
                            'Броня копейщика',
                            'Перчатки копейщика',
                            'Клинок копейщика',
                            'Лук копейщика',
                            'Штаны копейщика',
                            'Сапоги копейщика'
                        ]
                    ],
                    3 => [
                        'name' => 'Комплект ополченца',
                        'img' => '/images/sets/set3.jpg',
                        'output_item' => [
                            17 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    4 => [
                        'name' => 'Комплект странника',
                        'img' => '/images/sets/set4.jpg',
                        'output_item' => [
                            25 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                ]
            ],
            2 => (object)[
                'id' => range(33, 64),
                'type' => '<span style="color: #18b40c;">Стальное</span>',
                'type_img' => '/style/images/quality/2.png',
                'gold' => 80,
                'silver' => 11500,
                'min_quenching' => 10,
                'max_quenching' => 20,
                'max_sharpening' => 20,
                'str' => 130,
                'def' => 130,
                'hp' => 130,
                'level' => 15,
                'quenching_rating' => 3000,
                'output_set' => [
                    1 => [
                        'name' => 'Комплект берсеркера',
                        'img' => '/images/sets/set5.jpg',
                        'output_item' => [
                            33 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника',
                        ]
                    ],
                    2 => [
                        'name' => 'Комплект легионера',
                        'img' => '/images/sets/set6.jpg',
                        'output_item' => [
                            41 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    3 => [
                        'name' => 'Комплект лучника',
                        'img' => '/images/sets/set7.jpg',
                        'output_item' => [
                            49 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    4 => [
                        'name' => 'Комплект ассасина',
                        'img' => '/images/sets/set8.jpg',
                        'output_item' => [
                            57 => 'Комплект ассасина',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                ]
            ],
            3 => (object)[
                'id' => range(65, 96),
                'type' => '<span style="color: #2066ce;">Эпическое</span>',
                'type_img' => '/style/images/quality/4.png',
                'gold' => 300,
                'silver' => 180000,
                'min_quenching' => 20,
                'max_quenching' => 35,
                'max_sharpening' => 30,
                'str' => 260,
                'def' => 260,
                'hp' => 260,
                'level' => 35,
                'quenching_rating' => 10000,
                'output_set' => [
                    1 => [
                        'name' => 'Комплект тяжелого лучника',
                        'img' => '/images/sets/set9.jpg',
                        'output_item' => [
                            65 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника',
                        ]
                    ],
                    2 => [
                        'name' => 'Комплект волкодава',
                        'img' => '/images/sets/set10.jpg',
                        'output_item' => [
                            73 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    3 => [
                        'name' => 'Комплект палача',
                        'img' => '/images/sets/set11.jpg',
                        'output_item' => [
                            81 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    4 => [
                        'name' => 'Комплект паладина',
                        'img' => '/images/sets/set12.jpg',
                        'output_item' => [
                            89 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                ]
            ],
            4 => (object)[
                'id' => range(97, 128),
                'type' => '<span style="color: #921ece;">Мастерское</span>',
                'type_img' => '/style/images/quality/5.png',
                'gold' => 750,
                'silver' => 500000,
                'min_quenching' => 35,
                'max_quenching' => 50,
                'max_sharpening' => 40,
                'str' => 400,
                'def' => 400,
                'hp' => 400,
                'level' => 50,
                'quenching_rating' => 30000,
                'output_set' => [
                    1 => [
                        'name' => 'Комплект Хранителя Святынь',
                        'img' => '/images/sets/set13.jpg',
                        'output_item' => [
                            97 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника',
                        ]
                    ],
                    2 => [
                        'name' => 'Комплект Лунного Воина',
                        'img' => '/images/sets/set14.jpg',
                        'output_item' => [
                            105 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    3 => [
                        'name' => 'Комплект Королевского Стража',
                        'img' => '/images/sets/set15.jpg',
                        'output_item' => [
                            113 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    4 => [
                        'name' => 'Комплект Разрушителя',
                        'img' => '/images/sets/set16.jpg',
                        'output_item' => [
                            121 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                ]
            ],
            5 => (object)[
                'id' => range(129, 160),
                'type' => '<span style="color: #15e3ef;">Безупречное</span>',
                'type_img' => '/style/images/quality/3.png',
                'gold' => 2400,
                'silver' => 1000000,
                'min_quenching' => 50,
                'max_quenching' => 75,
                'max_sharpening' => 50,
                'str' => 550,
                'def' => 550,
                'hp' => 550,
                'level' => 60,
                'quenching_rating' => 55000,
                'output_set' => [
                    1 => [
                        'name' => 'Комплект Устранителя',
                        'img' => '/images/sets/set17.jpg',
                        'output_item' => [
                            129 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника',
                        ]
                    ],
                    2 => [
                        'name' => 'Комплект Высшего Стража',
                        'img' => '/images/sets/set18.jpg',
                        'output_item' => [
                            137 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    3 => [
                        'name' => 'Комплект Бессмертного',
                        'img' => '/images/sets/set19.jpg',
                        'output_item' => [
                            145 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    4 => [
                        'name' => 'Комплект Судьи',
                        'img' => '/images/sets/set20.jpg',
                        'output_item' => [
                            153 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                ]
            ],
            6 => (object)[
                'id' => range(161, 192),
                'private' => true,
                'type' => '<span style="color: #ff0000;">Драконье</span>',
                'type_img' => '/style/images/quality/7.png',
                'gold' => 5000,
                'diamond' => 200000,
                'min_quenching' => 75,
                'max_quenching' => 100,
                'max_sharpening' => 60,
                'str' => 700,
                'def' => 700,
                'hp' => 700,
                'level' => 60,
                'quenching_rating' => 100000,
                'output_set' => [
                    1 => [
                        'name' => 'Комплект Непрощённого',
                        'img' => '/images/sets/set21.jpg',
                        'output_item' => [
                            161 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника',
                        ]
                    ],
                    2 => [
                        'name' => 'Комплект Безликого',
                        'img' => '/images/sets/set22.jpg',
                        'output_item' => [
                            169 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    3 => [
                        'name' => 'Комплект Беспощадного',
                        'img' => '/images/sets/set23.jpg',
                        'output_item' => [
                            177 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                    4 => [
                        'name' => 'Комплект Несокрушимого',
                        'img' => '/images/sets/set24.jpg',
                        'output_item' => [
                            185 => 'Капюшон охотника',
                            'Наплечник охотника',
                            'Броня охотника',
                            'Перчатки охотника',
                            'Клинок охотника',
                            'Лук охотника',
                            'Штаны охотника',
                            'Сапоги охотника'
                        ]
                    ],
                ]
            ],
            7 => (object)[
                'id' => range(193, 200),
                'private' => true,
                'type' => '<span style="color: #ff6600;">Бонусное</span>',
                'type_img' => '/style/images/quality/7.png',
                'min_quenching' => 100,
                'max_quenching' => 125,
                'max_sharpening' => 70,
                'str' => 888,
                'def' => 888,
                'hp' => 888,
                'level' => 60,
                'quenching_rating' => 120000,
                'output_set' => [
                    1 => [
                        'name' => 'Комлект Дракона',
                        'img' => '/images/sets/set25.jpg',
                        'output_item' => [
                            193 => 'Капюшон Дракона',
                            'Наплечник Дракона',
                            'Броня Дракона',
                            'Перчатки Дракона',
                            'Щит Дракона',
                            'Топор Дракона',
                            'Штаны Дракона',
                            'Сапоги Дракона',
                        ]
                    ],
                ]
            ]
        ];
        if ($type == true) {
            return $obj->$type;
        }
        return $obj;
    }


    public static function getPriceSetType($gold, $silver, $diamond = 0) {

        if (!empty($diamond)) {
            return [
                1 => [
                    'table' => 'gold',
                    'price' => $gold
                ],
                2 => [
                    'table' => 'crystal',
                    'price' => $diamond
                ]
            ];
        }

        return [
            1 => [
                'table' => 'gold',
                'price' => $gold
//                'price' => $items->gold
            ],
            2 => [
                'table' => 'silver',
                'price' => $silver
//                'price' => $items->silver
            ]
        ];

    }

    /**
     * цены на заточку
     * @param $level
     * @return mixed
     */
    public static function priceZatochka($level = false) {


        $arr = [
            1 => ['table' => 'silver', 'price' => 300],
            ['table' => 'silver', 'price' => 600],
            ['table' => 'silver', 'price' => 900],
            ['table' => 'gold', 'price' => 15],
            ['table' => 'silver', 'price' => 1200],
            ['table' => 'silver', 'price' => 1600],
            ['table' => 'silver', 'price' => 2400],
            ['table' => 'gold', 'price' => 50],
            ['table' => 'silver', 'price' => 4000],
            ['table' => 'silver', 'price' => 6000],
            ['table' => 'silver', 'price' => 10000],
            ['table' => 'gold', 'price' => 100],
            ['table' => 'silver', 'price' => 16000],
            ['table' => 'silver', 'price' => 22000],
            ['table' => 'silver', 'price' => 28000],
            ['table' => 'gold', 'price' => 500],
            ['table' => 'silver', 'price' => 34000],
            ['table' => 'silver', 'price' => 44000],
            ['table' => 'silver', 'price' => 54000],
            ['table' => 'gold', 'price' => 1000],
            ['table' => 'silver', 'price' => 64000],
            ['table' => 'silver', 'price' => 75000],
            ['table' => 'silver', 'price' => 86000],
            ['table' => 'gold', 'price' => 2500],
            ['table' => 'silver', 'price' => 100000],
            ['table' => 'silver', 'price' => 125000],
            ['table' => 'silver', 'price' => 150000],
            ['table' => 'gold', 'price' => 3500],
            ['table' => 'silver', 'price' => 200000],
            ['table' => 'silver', 'price' => 300000],
            ['table' => 'silver', 'price' => 400000],
            ['table' => 'gold', 'price' => 4000],
            ['table' => 'silver', 'price' => 500000],
            ['table' => 'silver', 'price' => 700000],
            ['table' => 'silver', 'price' => 900000],
            ['table' => 'gold', 'price' => 4500],
            ['table' => 'silver', 'price' => 1200000],
            ['table' => 'silver', 'price' => 1500000],
            ['table' => 'silver', 'price' => 2000000],
            ['table' => 'gold', 'price' => 5000],
            ['table' => 'silver', 'price' => 2500000],
            ['table' => 'silver', 'price' => 3000000],
            ['table' => 'silver', 'price' => 3500000],
            ['table' => 'gold', 'price' => 5500],
            ['table' => 'silver', 'price' => 4500000],
            ['table' => 'silver', 'price' => 5000000],
            ['table' => 'silver', 'price' => 5500000],
            ['table' => 'gold', 'price' => 6000],
            ['table' => 'silver', 'price' => 7500000],
            ['table' => 'silver', 'price' => 10000000],
            ['table' => 'silver', 'price' => 12000000],
            ['table' => 'gold', 'price' => 6500],
            ['table' => 'silver', 'price' => 15000000],
            ['table' => 'silver', 'price' => 17500000],
            ['table' => 'silver', 'price' => 20000000],
            ['table' => 'gold', 'price' => 7000],
            ['table' => 'silver', 'price' => 25000000],
            ['table' => 'silver', 'price' => 30000000],
            ['table' => 'silver', 'price' => 35000000],
            ['table' => 'gold', 'price' => 8000],
        ];
        if ($level) {
            return $arr[$level + 1];
        }
        return $arr;
    }

    /**
     * получаем итем по типу
     * @param $user
     * @param array $typeItem
     * @return array
     */
    public static function getItemOneType($user, array $typeItem = []) {
        $userStateItems = Maneken::getUserItems($user);
        $itemStateUser = []; // хранит данные о веще, которая одета или пусто
        foreach ($userStateItems as $userState) {
            // узнаём тип вещи, которая одета
            if (in_array(Item::getTypeItem($userState['weapon_id']), $typeItem)) {
                $itemStateUser = $userState;
            }
        }
        return $itemStateUser;
    }

    public static function getUserRang($clan_u) {
        if ($clan_u == 0) {
            $prava = 'Новичок';
        }
        if ($clan_u == 1) {
            $prava = 'Боец';
        }
        if ($clan_u == 2) {
            $prava = 'Ветеран';
        }
        if ($clan_u == 3) {
            $prava = 'Старейшина';
        }
        if ($clan_u == 4) {
            $prava = 'Основатель';
        }
        return $prava;
    }
}