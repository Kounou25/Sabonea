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
            'title' => ['Un accès direct aux bons fournisseurs', 'Direct access to the right suppliers', 'Direkter Zugang zu den richtigen Lieferanten', '直达合适的供应商'],
            'text' => [
                'Sabonea met en relation les fabricants et distributeurs d\'équipements de nettoyage, d\'entretien et de maintenance avec les organisations qui les utilisent.',
                'Sabonea connects manufacturers and distributors of cleaning, upkeep and maintenance equipment with the organisations that use it.',
                'Sabonea verbindet Hersteller und Händler von Reinigungs-, Pflege- und Wartungstechnik mit den Organisationen, die sie einsetzen.',
                'Sabonea 将清洁、保养与维护设备的制造商和经销商，与使用这些设备的机构对接。',
            ],
            'cta_label' => ['Exprimer un besoin', 'Submit a request', 'Bedarf melden', '提交需求'],
            'cta_url' => 'expression-de-besoin',
            'cta2_label' => ['Devenir fournisseur', 'Become a supplier', 'Lieferant werden', '成为供应商'],
            'cta2_url' => 'devenir-fournisseur',
        ],
        [
            'image' => 'hero-voirie-nuit.jpg',
            'image_alt' => ['Nettoyage municipal et entretien de la voirie', 'Municipal cleaning and road maintenance', 'Kommunale Reinigung und Straßenunterhaltung', '市政清洁与道路养护'],
            'eyebrow' => ['Pour les acheteurs', 'For buyers', 'Für Einkäufer', '致买家'],
            'title' => ['Le bon fournisseur, sans le chercher vous-même', 'The right supplier, without the search', 'Der passende Lieferant, ohne selbst zu suchen', '无需亲自寻找，也能找到合适的供应商'],
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
            'eyebrow' => ['Pour les fournisseurs', 'For suppliers', 'Für Lieferanten', '致供应商'],
            'title' => ['Une vitrine internationale pour nos fournisseurs', 'An international showcase for our suppliers', 'Ein internationales Schaufenster für unsere Lieferanten', '为供应商打造的国际展示窗口'],
            'text' => [
                'Nous accompagnons les fournisseurs à l\'export, avec des demandes triées par notre équipe et venant d\'acheteurs professionnels identifiés.',
                'We support suppliers in their export business, with requests sorted by our team and coming from identified professional buyers.',
                'Wir begleiten Lieferanten beim Export, mit Anfragen, die unser Team vorsortiert und die von identifizierten professionellen Einkäufern stammen.',
                '我们助力供应商拓展出口业务，所转交的询盘均经团队筛选，来自身份明确的专业买家。',
            ],
            'cta_label' => ['Devenir fournisseur', 'Become a supplier', 'Lieferant werden', '成为供应商'],
            'cta_url' => 'devenir-fournisseur',
            'cta2_label' => ['Pourquoi Sabonea', 'Why Sabonea', 'Warum Sabonea', '为何选择 Sabonea'],
            'cta2_url' => 'pourquoi-sabonea',
        ],
    ],

    /*
     * Shared reference lists (specification of the supplier forms, section 2).
     * "form_label" is the label of the forms when it differs from the name shown on the site.
     */
    'sectors' => [
        ['key' => 'SEC_AIRPORT', 'icon' => 'fa fa-plane', 'image' => 'secteur-aeroports.jpg', 'home' => true, 'name' => ['Aéroports', 'Airports', 'Flughäfen', '机场']],
        ['key' => 'SEC_HOSPITAL', 'icon' => 'fa fa-hospital', 'image' => 'secteur-hopitaux.jpg', 'home' => true, 'name' => ['Hôpitaux', 'Hospitals', 'Krankenhäuser', '医院'],
            'form_label' => ['Hôpitaux et établissements de santé', 'Hospitals and healthcare facilities', 'Krankenhäuser und Gesundheitseinrichtungen', '医院及医疗机构']],
        ['key' => 'SEC_CONSTRUCTION', 'icon' => 'fa fa-hard-hat', 'image' => 'secteur-chantiers.jpg', 'name' => ['Chantiers de construction', 'Construction sites', 'Baustellen', '建筑工地'],
            'form_label' => ['Chantiers et entreprises de construction', 'Construction sites and companies', 'Baustellen und Bauunternehmen', '建筑工地及建筑企业']],
        ['key' => 'SEC_HOTEL', 'icon' => 'fa fa-hotel', 'image' => 'secteur-hotels.jpg', 'name' => ['Hôtels', 'Hotels', 'Hotels', '酒店']],
        ['key' => 'SEC_LOGISTICS', 'icon' => 'fa fa-warehouse', 'image' => 'secteur-logistique.jpg', 'name' => ['Plateformes logistiques', 'Logistics hubs', 'Logistikzentren', '物流中心']],
        ['key' => 'SEC_MUNICIPALITY', 'icon' => 'fa fa-city', 'image' => 'secteur-municipalites.jpg', 'home' => true, 'name' => ['Municipalités / villes', 'Municipalities / cities', 'Kommunen / Städte', '市政 / 城市'],
            'short_name' => ['Municipalités', 'Municipalities', 'Kommunen', '市政']],
        ['key' => 'SEC_INDUSTRIAL', 'icon' => 'fa fa-industry', 'image' => 'secteur-industriel.jpg', 'home' => true, 'name' => ['Sites industriels', 'Industrial sites', 'Industriestandorte', '工业场所']],
        ['key' => 'SEC_RETAIL', 'icon' => 'fa fa-shopping-bag', 'image' => 'secteur-commerces.jpg', 'name' => ['Centres commerciaux', 'Shopping centres', 'Einkaufszentren', '购物中心']],
        ['key' => 'SEC_CAMPUS', 'icon' => 'fa fa-university', 'image' => 'secteur-universites.jpg', 'name' => ['Universités / campus', 'Universities / campuses', 'Universitäten / Campus', '大学 / 校园']],
        ['key' => 'SEC_PORT', 'icon' => 'fa fa-anchor', 'site' => false, 'name' => ['Ports et zones portuaires', 'Ports and port areas', 'Häfen und Hafengebiete', '港口及港区']],
        ['key' => 'SEC_OTHER', 'icon' => 'fa fa-ellipsis-h', 'site' => false, 'other' => true, 'name' => ['Autre', 'Other', 'Sonstiges', '其他']],
    ],

    'equipment_types' => [
        ['key' => 'EQ_ROAD_SWEEPER', 'icon' => 'fa fa-broom', 'name' => ['Balayeuses / nettoyeuses de voirie', 'Road sweepers / street cleaners', 'Kehrmaschinen / Straßenreinigungsfahrzeuge', '道路清扫车 / 洗扫车']],
        ['key' => 'EQ_HIGH_PRESSURE', 'icon' => 'fa fa-tint', 'name' => ['Machines de nettoyage haute pression', 'High-pressure cleaning machines', 'Hochdruckreinigungsmaschinen', '高压清洗设备']],
        ['key' => 'EQ_AIRPORT', 'icon' => 'fa fa-plane-departure', 'name' => ['Équipements de nettoyage aéroportuaire', 'Airport cleaning equipment', 'Flughafen-Reinigungsausrüstung', '机场清洁设备']],
        ['key' => 'EQ_SNOW', 'icon' => 'fa fa-snowplow', 'name' => ['Équipements de déneigement', 'Snow removal equipment', 'Winterdienstausrüstung', '除雪设备']],
        ['key' => 'EQ_INDUSTRIAL', 'icon' => 'fa fa-industry', 'name' => ['Machines de nettoyage industriel', 'Industrial cleaning machines', 'Industrielle Reinigungsmaschinen', '工业清洁设备']],
        ['key' => 'EQ_FACADE', 'icon' => 'fa fa-building', 'name' => ['Nettoyage de façades / bâtiments', 'Facade / building cleaning', 'Fassaden- / Gebäudereinigung', '外墙 / 建筑清洁']],
        ['key' => 'EQ_ROAD_MAINTENANCE', 'icon' => 'fa fa-road', 'name' => ['Entretien des routes et voiries', 'Road and highway maintenance', 'Straßen- und Wegeunterhaltung', '道路养护']],
        ['key' => 'EQ_WASTE_COLLECTION', 'icon' => 'fa fa-dumpster', 'name' => ['Véhicules de collecte des déchets', 'Waste collection vehicles', 'Abfallsammelfahrzeuge', '垃圾收集车']],
        ['key' => 'EQ_SCRUBBER', 'icon' => 'fa fa-circle-notch', 'site' => false, 'name' => ['Autolaveuses et monobrosses (sols intérieurs)', 'Scrubber-dryers and single-disc machines (indoor floors)', 'Scheuersaugmaschinen und Einscheibenmaschinen (Innenböden)', '洗地机及单擦机（室内地面）']],
        ['key' => 'EQ_VACUUM', 'icon' => 'fa fa-wind', 'site' => false, 'name' => ['Aspirateurs industriels et aspirateurs à eau', 'Industrial and wet vacuum cleaners', 'Industrie- und Nasssauger', '工业吸尘器及吸水机']],
        ['key' => 'EQ_DISINFECTION', 'icon' => 'fa fa-spray-can', 'site' => false, 'name' => ['Équipements de désinfection et d\'hygiène (milieu hospitalier)', 'Disinfection and hygiene equipment (healthcare settings)', 'Desinfektions- und Hygieneausrüstung (Krankenhausbereich)', '消毒与卫生设备（医疗环境）']],
        ['key' => 'EQ_SPARE_PARTS', 'icon' => 'fa fa-cogs', 'site' => false, 'name' => ['Pièces détachées et consommables', 'Spare parts and consumables', 'Ersatzteile und Verbrauchsmaterial', '备件及耗材']],
        ['key' => 'EQ_OTHER', 'icon' => 'fa fa-ellipsis-h', 'site' => false, 'other' => true, 'name' => ['Autre', 'Other', 'Sonstiges', '其他']],
    ],

    /*
     * Choice lists of the contact and buyer forms: [key, [fr, en, de, zh]].
     * The lists of the supplier forms are in supplier_options.php.
     */
    'form_options' => [
        'contact_subject' => [
            ['SUBJECT_BUYER', ['Je suis acheteur : j\'ai un besoin en équipement', 'I am a buyer: I need equipment', 'Ich bin Einkäufer: Ich habe einen Ausrüstungsbedarf', '我是买家：我有设备需求']],
            ['SUBJECT_SUPPLIER', ['Je suis fournisseur : je souhaite rejoindre Sabonea', 'I am a supplier: I would like to join Sabonea', 'Ich bin Lieferant: Ich möchte Sabonea beitreten', '我是供应商：我想加入 Sabonea']],
            ['SUBJECT_PARTNERSHIP', ['Demande de partenariat', 'Partnership request', 'Partnerschaftsanfrage', '合作咨询']],
            ['SUBJECT_OTHER', ['Autre question', 'Other question', 'Sonstige Frage', '其他问题']],
        ],
        'need_deadline' => [
            ['DEADLINE_URGENT', ['Urgent (moins d\'1 mois)', 'Urgent (less than 1 month)', 'Dringend (weniger als 1 Monat)', '紧急（1 个月内）']],
            ['DEADLINE_1_3_MONTHS', ['1 à 3 mois', '1 to 3 months', '1 bis 3 Monate', '1 至 3 个月']],
            ['DEADLINE_3_6_MONTHS', ['3 à 6 mois', '3 to 6 months', '3 bis 6 Monate', '3 至 6 个月']],
            ['DEADLINE_TBD', ['À définir', 'To be determined', 'Noch offen', '待定']],
        ],
    ],
];
