<?php

/*
 * Catalog content. Translatable values are [fr, en, de, zh].
 */

return [
    'hero_slides' => [
        [
            'image' => 'hero-aeroport.jpg',
            'image_alt' => ['Équipements professionnels sur tarmac d\'aéroport', 'Professional equipment on an airport apron', 'Professionelle Ausrüstung auf dem Flughafenvorfeld', '机场停机坪上的专业设备'],
            'eyebrow' => ['Marketplace B2B internationale', 'International B2B marketplace', 'Internationaler B2B-Marktplatz', '国际 B2B 交易平台'],
            'title' => ['Direct access to the best suppliers', 'Direct access to the best suppliers', 'Direct access to the best suppliers', 'Direct access to the best suppliers'],
            'text' => [
                'Sabonea met en relation les fournisseurs d\'équipements professionnels de nettoyage, d\'entretien et de maintenance avec les organisations qui en ont besoin, partout dans le monde.',
                'Sabonea connects suppliers of professional cleaning, upkeep and maintenance equipment with the organisations that need it, anywhere in the world.',
                'Sabonea bringt Lieferanten professioneller Reinigungs-, Pflege- und Wartungsausrüstung mit den Organisationen zusammen, die sie benötigen – weltweit.',
                'Sabonea 将专业清洁、保养与维护设备供应商与有需求的机构对接，覆盖全球。',
            ],
            'cta_label' => ['Exprimer un besoin', 'Submit a request', 'Bedarf melden', '提交需求'],
            'cta_url' => 'expression-de-besoin',
            'cta2_label' => ['Devenir fournisseur', 'Become a supplier', 'Lieferant werden', '成为供应商'],
            'cta2_url' => 'contact',
        ],
        [
            'image' => 'hero-voirie-nuit.jpg',
            'image_alt' => ['Nettoyage municipal et entretien de la voirie', 'Municipal cleaning and road maintenance', 'Kommunale Reinigung und Straßenunterhaltung', '市政清洁与道路养护'],
            'eyebrow' => ['Marketplace B2B internationale', 'International B2B marketplace', 'Internationaler B2B-Marktplatz', '国际 B2B 交易平台'],
            'title' => ['Un accès direct aux meilleurs fournisseurs', 'The right supplier, without the search', 'Direkter Zugang zu den besten Lieferanten', '直达优质供应商'],
            'text' => [
                'Aéroports, hôpitaux, municipalités, sites industriels : nous analysons votre besoin et vous orientons vers le fournisseur le plus adapté.',
                'Airports, hospitals, municipalities, industrial sites: we analyse your requirement and point you to the most suitable supplier.',
                'Flughäfen, Krankenhäuser, Kommunen, Industriestandorte: Wir analysieren Ihren Bedarf und vermitteln Ihnen den passendsten Lieferanten.',
                '机场、医院、市政、工业场所：我们分析您的需求，为您推荐最合适的供应商。',
            ],
            'cta_label' => ['Exprimer un besoin', 'Submit a request', 'Bedarf melden', '提交需求'],
            'cta_url' => 'expression-de-besoin',
            'cta2_label' => ['Comment ça marche', 'How it works', 'So funktioniert’s', '运作方式'],
            'cta2_url' => 'comment-ca-fonctionne',
        ],
        [
            'image' => 'hero-logistique.jpg',
            'image_alt' => ['Logistique et équipements industriels', 'Logistics and industrial equipment', 'Logistik und Industrieausrüstung', '物流与工业设备'],
            'eyebrow' => ['Marketplace B2B internationale', 'International B2B marketplace', 'Internationaler B2B-Marktplatz', '国际 B2B 交易平台'],
            'title' => ['Une vitrine internationale pour nos fournisseurs', 'An international showcase for our suppliers', 'Ein internationales Schaufenster für unsere Lieferanten', '为供应商打造的国际展示窗口'],
            'text' => [
                'Nous accompagnons les fournisseurs dans leur développement à l\'export, avec des demandes qualifiées provenant d\'acheteurs professionnels identifiés.',
                'We support suppliers in growing their export business, with qualified requests from identified professional buyers.',
                'Wir begleiten Lieferanten bei ihrer Exportentwicklung – mit qualifizierten Anfragen von identifizierten professionellen Einkäufern.',
                '我们助力供应商拓展出口业务，为其带来来自专业买家的有效询盘。',
            ],
            'cta_label' => ['Devenir fournisseur', 'Become a supplier', 'Lieferant werden', '成为供应商'],
            'cta_url' => 'contact',
            'cta2_label' => ['Pourquoi Sabonea', 'Why Sabonea', 'Warum Sabonea', '为何选择 Sabonea'],
            'cta2_url' => 'pourquoi-sabonea',
        ],
    ],

    'sectors' => [
        ['icon' => 'fa fa-plane', 'image' => 'secteur-aeroports.jpg', 'home' => true, 'name' => ['Aéroports', 'Airports', 'Flughäfen', '机场'], 'short_name' => null],
        ['icon' => 'fa fa-hospital', 'image' => 'secteur-hopitaux.jpg', 'home' => true, 'name' => ['Hôpitaux', 'Hospitals', 'Krankenhäuser', '医院'], 'short_name' => null],
        ['icon' => 'fa fa-hard-hat', 'image' => 'secteur-chantiers.jpg', 'home' => false, 'name' => ['Chantiers de construction', 'Construction sites', 'Baustellen', '建筑工地'], 'short_name' => null],
        ['icon' => 'fa fa-hotel', 'image' => 'secteur-hotels.jpg', 'home' => false, 'name' => ['Hôtels', 'Hotels', 'Hotels', '酒店'], 'short_name' => null],
        ['icon' => 'fa fa-warehouse', 'image' => 'secteur-logistique.jpg', 'home' => false, 'name' => ['Plateformes logistiques', 'Logistics hubs', 'Logistikzentren', '物流中心'], 'short_name' => null],
        ['icon' => 'fa fa-city', 'image' => 'secteur-municipalites.jpg', 'home' => true, 'name' => ['Municipalités / villes', 'Municipalities / cities', 'Kommunen / Städte', '市政 / 城市'], 'short_name' => ['Municipalités', 'Municipalities', 'Kommunen', '市政']],
        ['icon' => 'fa fa-industry', 'image' => 'secteur-industriel.jpg', 'home' => true, 'name' => ['Sites industriels', 'Industrial sites', 'Industriestandorte', '工业场所'], 'short_name' => null],
        ['icon' => 'fa fa-shopping-bag', 'image' => 'secteur-commerces.jpg', 'home' => false, 'name' => ['Centres commerciaux', 'Shopping centres', 'Einkaufszentren', '购物中心'], 'short_name' => null],
        ['icon' => 'fa fa-university', 'image' => 'secteur-universites.jpg', 'home' => false, 'name' => ['Universités / campus', 'Universities / campuses', 'Universitäten / Campus', '大学 / 校园'], 'short_name' => null],
    ],

    'equipment_types' => [
        ['icon' => 'fa fa-broom', 'name' => ['Balayeuses / nettoyeuses de voirie', 'Road sweepers / street cleaners', 'Kehrmaschinen / Straßenreinigungsfahrzeuge', '道路清扫车 / 洗扫车']],
        ['icon' => 'fa fa-tint', 'name' => ['Machines de nettoyage haute pression', 'High-pressure cleaning machines', 'Hochdruckreinigungsmaschinen', '高压清洗设备']],
        ['icon' => 'fa fa-plane-departure', 'name' => ['Équipements de nettoyage aéroportuaire', 'Airport cleaning equipment', 'Flughafen-Reinigungsausrüstung', '机场清洁设备']],
        ['icon' => 'fa fa-snowplow', 'name' => ['Équipements de déneigement', 'Snow removal equipment', 'Winterdienstausrüstung', '除雪设备']],
        ['icon' => 'fa fa-industry', 'name' => ['Machines de nettoyage industriel', 'Industrial cleaning machines', 'Industrielle Reinigungsmaschinen', '工业清洁设备']],
        ['icon' => 'fa fa-building', 'name' => ['Nettoyage de façades / bâtiments', 'Facade / building cleaning', 'Fassaden- / Gebäudereinigung', '外墙 / 建筑清洁']],
        ['icon' => 'fa fa-road', 'name' => ['Entretien des routes et voiries', 'Road and highway maintenance', 'Straßen- und Wegeunterhaltung', '道路养护']],
        ['icon' => 'fa fa-dumpster', 'name' => ['Véhicules de collecte des déchets', 'Waste collection vehicles', 'Abfallsammelfahrzeuge', '垃圾收集车']],
    ],

    'form_options' => [
        'contact_subject' => [
            ['Je suis acheteur   j\'ai un besoin en équipement', 'I am a buyer – I need equipment', 'Ich bin Einkäufer – ich habe einen Ausrüstungsbedarf', '我是买家——我有设备需求'],
            ['Je suis fournisseur   je souhaite rejoindre Sabonea', 'I am a supplier – I would like to join Sabonea', 'Ich bin Lieferant – ich möchte Sabonea beitreten', '我是供应商——我想加入 Sabonea'],
            ['Demande de partenariat', 'Partnership request', 'Partnerschaftsanfrage', '合作咨询'],
            ['Autre question', 'Other question', 'Sonstige Frage', '其他问题'],
        ],
        'need_deadline' => [
            ['Urgent (moins d\'1 mois)', 'Urgent (less than 1 month)', 'Dringend (weniger als 1 Monat)', '紧急（1 个月内）'],
            ['1 à 3 mois', '1 to 3 months', '1 bis 3 Monate', '1 至 3 个月'],
            ['3 à 6 mois', '3 to 6 months', '3 bis 6 Monate', '3 至 6 个月'],
            ['À définir', 'To be determined', 'Noch offen', '待定'],
        ],
    ],
];
