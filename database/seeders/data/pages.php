<?php

/*
 * Page content. Translatable values are [fr, en, de, zh].
 *
 * Each section declares the fields its layout displays ("fields") and, when it
 * holds a list, the fields of its items ("item_fields"); the back-office only
 * shows those fields.
 */

$expressNeed = ['Exprimer un besoin', 'Submit a request', 'Bedarf melden', '提交需求'];
$becomeSupplier = ['Devenir fournisseur', 'Become a supplier', 'Lieferant werden', '成为供应商'];
$contactUs = ['Nous contacter', 'Contact us', 'Kontakt aufnehmen', '联系我们'];
$howItWorks = ['Comment ça marche', 'How it works', 'So funktioniert’s', '运作方式'];

return [

    /* ------------------------------------------------------------------ */
    'accueil' => [
        'name' => 'Accueil',
        'meta_title' => ['Sabonea   Direct access to the best suppliers', 'Sabonea – Direct access to the best suppliers', 'Sabonea – Direkter Zugang zu den besten Lieferanten', 'Sabonea – 直达优质供应商'],
        'meta_keywords' => [
            'Sabonea, marketplace B2B, fournisseurs équipements nettoyage, entretien, maintenance, aéroportuaire, hospitalier, municipal, industriel',
            'Sabonea, B2B marketplace, cleaning equipment suppliers, upkeep, maintenance, airport, hospital, municipal, industrial',
            'Sabonea, B2B-Marktplatz, Lieferanten Reinigungsausrüstung, Pflege, Wartung, Flughafen, Krankenhaus, Kommune, Industrie',
            'Sabonea, B2B 交易平台, 清洁设备供应商, 保养, 维护, 机场, 医院, 市政, 工业',
        ],
        'meta_description' => [
            'Sabonea est une marketplace B2B internationale qui met en relation les fournisseurs d\'équipements professionnels de nettoyage, d\'entretien et de maintenance avec les organisations qui en ont besoin.',
            'Sabonea is an international B2B marketplace connecting suppliers of professional cleaning, upkeep and maintenance equipment with the organisations that need it.',
            'Sabonea ist ein internationaler B2B-Marktplatz, der Lieferanten professioneller Reinigungs-, Pflege- und Wartungsausrüstung mit den Organisationen verbindet, die sie benötigen.',
            'Sabonea 是一个国际 B2B 交易平台，连接专业清洁、保养与维护设备供应商与有需求的机构。',
        ],
        'sections' => [
            [
                'key' => 'hero_strip', 'name' => 'Carrousel : bandeau des secteurs', 'fields' => [], 'item_fields' => ['title', 'icon'],
                'items' => [
                    ['icon' => 'fa fa-plane', 'title' => ['Aéroports', 'Airports', 'Flughäfen', '机场']],
                    ['icon' => 'fa fa-hospital', 'title' => ['Hôpitaux', 'Hospitals', 'Krankenhäuser', '医院']],
                    ['icon' => 'fa fa-city', 'title' => ['Municipalités', 'Municipalities', 'Kommunen', '市政']],
                    ['icon' => 'fa fa-industry', 'title' => ['Industriel', 'Industrial', 'Industrie', '工业']],
                ],
            ],
            [
                'key' => 'key_facts', 'name' => 'Points clés', 'fields' => [], 'item_fields' => ['title', 'text', 'icon', 'variant'],
                'items' => [
                    ['icon' => 'fa fa-globe-americas', 'title' => ['Internationale', 'International', 'International', '国际化'], 'text' => [
                        'Une marketplace B2B pensée pour connecter acheteurs et fournisseurs partout dans le monde.',
                        'A B2B marketplace designed to connect buyers and suppliers all over the world.',
                        'Ein B2B-Marktplatz, der Einkäufer und Lieferanten weltweit zusammenbringt.',
                        '致力于连接全球买家与供应商的 B2B 交易平台。',
                    ]],
                    ['icon' => 'fa fa-layer-group', 'variant' => 'alt-green', 'title' => ['4 secteurs', '4 sectors', '4 Branchen', '4 大行业'], 'text' => [
                        'Aéroportuaire, hospitalier, municipal et industriel : des besoins que nous connaissons.',
                        'Airports, hospitals, municipalities and industry: needs we know well.',
                        'Flughäfen, Krankenhäuser, Kommunen und Industrie: Anforderungen, die wir kennen.',
                        '机场、医院、市政与工业：我们深谙这些领域的需求。',
                    ]],
                    ['icon' => 'fa fa-user-check', 'variant' => 'alt-orange', 'title' => ['Mise en relation qualifiée', 'Qualified introductions', 'Qualifizierte Vermittlung', '精准对接'], 'text' => [
                        'Chaque besoin est analysé par notre équipe avant d\'être transmis au bon fournisseur.',
                        'Every requirement is analysed by our team before being passed on to the right supplier.',
                        'Jeder Bedarf wird von unserem Team analysiert, bevor er an den richtigen Lieferanten weitergeleitet wird.',
                        '每一项需求都经我们团队分析后，再转交给合适的供应商。',
                    ]],
                    ['icon' => 'fa fa-language', 'title' => ['Multilingue', 'Multilingual', 'Mehrsprachig', '多语言'], 'text' => [
                        'Disponible en français, en anglais, en allemand et en chinois.',
                        'Available in French, English, German and Chinese.',
                        'Verfügbar auf Französisch, Englisch, Deutsch und Chinesisch.',
                        '支持法语、英语、德语和中文。',
                    ]],
                ],
            ],
            [
                'key' => 'about', 'name' => 'À propos (aperçu)', 'fields' => ['eyebrow', 'title', 'body', 'image', 'cta'], 'item_fields' => ['title'],
                'image' => 'about-ingenieure.jpg',
                'image_alt' => ['Sabonea, une entreprise portée par une vision d\'infrastructures plus propres', 'Sabonea, a company driven by a vision of cleaner infrastructure', 'Sabonea, ein Unternehmen mit der Vision sauberer Infrastrukturen', 'Sabonea，致力于打造更洁净基础设施的企业'],
                'eyebrow' => ['À propos de Sabonea', 'About Sabonea', 'Über Sabonea', '关于 Sabonea'],
                'title' => ['Contribuer à des villes et des infrastructures plus propres, plus modernes', 'Helping to build cleaner, more modern cities and infrastructure', 'Für sauberere, modernere Städte und Infrastrukturen', '助力打造更洁净、更现代的城市与基础设施'],
                'body' => [
                    'Sabonea est une marketplace B2B internationale qui met en relation les fournisseurs d\'équipements professionnels de nettoyage, d\'entretien et de maintenance avec les organisations qui en ont besoin   notamment dans les secteurs aéroportuaire, hospitalier, municipal et industriel.',
                    'Sabonea is an international B2B marketplace connecting suppliers of professional cleaning, upkeep and maintenance equipment with the organisations that need it – particularly in the airport, hospital, municipal and industrial sectors.',
                    'Sabonea ist ein internationaler B2B-Marktplatz, der Lieferanten professioneller Reinigungs-, Pflege- und Wartungsausrüstung mit den Organisationen verbindet, die sie benötigen – insbesondere in den Bereichen Flughafen, Krankenhaus, Kommune und Industrie.',
                    'Sabonea 是一个国际 B2B 交易平台，将专业清洁、保养与维护设备供应商与有需求的机构对接，尤其服务于机场、医院、市政和工业领域。',
                ],
                'cta_label' => ['Découvrir notre histoire', 'Discover our story', 'Unsere Geschichte entdecken', '了解我们的故事'], 'cta_url' => 'a-propos',
                'items' => [
                    ['title' => ['Un lien direct entre acheteurs et fournisseurs', 'A direct link between buyers and suppliers', 'Eine direkte Verbindung zwischen Einkäufern und Lieferanten', '买家与供应商直接对接']],
                    ['title' => ['Une sélection analysée par notre équipe', 'A selection reviewed by our team', 'Eine von unserem Team geprüfte Auswahl', '经团队分析的精选供应商']],
                    ['title' => ['Un accompagnement à l\'international', 'Support for international growth', 'Begleitung auf internationaler Ebene', '国际化全程支持']],
                ],
            ],
            [
                'key' => 'sectors_preview', 'name' => 'Aperçu des secteurs', 'fields' => ['eyebrow', 'title', 'cta'],
                'eyebrow' => ['Nos secteurs', 'Our sectors', 'Unsere Branchen', '服务行业'],
                'title' => ['Des besoins que nous comprenons, sur le terrain', 'Needs we understand, on the ground', 'Anforderungen, die wir aus der Praxis kennen', '我们深入一线，懂得您的需求'],
                'cta_label' => ['Voir tous les secteurs & équipements', 'See all sectors & equipment', 'Alle Branchen & Ausrüstungen ansehen', '查看全部行业与设备'], 'cta_url' => 'secteurs',
            ],
            [
                'key' => 'how_it_works', 'name' => 'Comment ça fonctionne (aperçu)', 'fields' => ['eyebrow', 'title', 'body', 'image', 'cta'], 'item_fields' => ['title', 'text', 'icon'],
                'image' => 'comment-mise-en-relation.jpg',
                'image_alt' => ['Mise en relation qualifiée entre acheteur et fournisseur', 'Qualified introduction between buyer and supplier', 'Qualifizierte Vermittlung zwischen Einkäufer und Lieferant', '买家与供应商之间的精准对接'],
                'eyebrow' => ['Comment ça fonctionne', 'How it works', 'So funktioniert’s', '运作方式'],
                'title' => ['Un formulaire, une équipe, la bonne mise en relation', 'One form, one team, the right introduction', 'Ein Formular, ein Team, die richtige Vermittlung', '一份表单，一个团队，一次精准对接'],
                'body' => [
                    'Sabonea n\'est pas un catalogue en libre recherche. Vous exprimez votre besoin, notre équipe l\'analyse et identifie le ou les fournisseurs les plus adaptés dans son réseau   puis fait les présentations.',
                    'Sabonea is not a self-service catalogue. You describe your requirement, our team analyses it, identifies the most suitable supplier(s) in its network – and makes the introductions.',
                    'Sabonea ist kein frei durchsuchbarer Katalog. Sie beschreiben Ihren Bedarf, unser Team analysiert ihn, findet den oder die passendsten Lieferanten in seinem Netzwerk – und stellt den Kontakt her.',
                    'Sabonea 不是一个自助检索的产品目录。您提交需求，我们的团队进行分析，在网络中找到最合适的供应商，并为您牵线搭桥。',
                ],
                'cta_label' => ['Voir le parcours complet', 'See the full process', 'Den gesamten Ablauf ansehen', '查看完整流程'], 'cta_url' => 'comment-ca-fonctionne',
                'items' => [
                    ['icon' => 'fa fa-file-signature', 'title' => ['Expression', 'Submitting', 'Erfassung', '提交'], 'text' => ['du besoin', 'your request', 'des Bedarfs', '需求']],
                    ['icon' => 'fa fa-search', 'title' => ['Analyse', 'Analysis', 'Analyse', '分析'], 'text' => ['par l\'équipe', 'by our team', 'durch das Team', '由团队完成']],
                    ['icon' => 'fa fa-handshake', 'title' => ['Mise en relation', 'Introduction', 'Vermittlung', '对接'], 'text' => ['qualifiée', 'qualified', 'qualifiziert', '精准高效']],
                    ['icon' => 'fa fa-sync-alt', 'title' => ['Suivi', 'Follow-up', 'Begleitung', '跟进'], 'text' => ['dans la durée', 'over time', 'auf Dauer', '长期持续']],
                ],
            ],
            [
                'key' => 'why', 'name' => 'Pourquoi Sabonea (en-tête)', 'fields' => ['eyebrow', 'title', 'cta'],
                'eyebrow' => ['Pourquoi Sabonea', 'Why Sabonea', 'Warum Sabonea', '为何选择 Sabonea'],
                'title' => ['Deux publics, un même gain de temps', 'Two audiences, the same time saved', 'Zwei Zielgruppen, dieselbe Zeitersparnis', '两类客户，同样省时'],
                'cta_label' => ['En savoir plus', 'Learn more', 'Mehr erfahren', '了解更多'], 'cta_url' => 'pourquoi-sabonea',
            ],
            [
                'key' => 'why_suppliers', 'name' => 'Pourquoi Sabonea : carte fournisseurs', 'fields' => ['title', 'body'], 'item_fields' => ['title'],
                'title' => ['Pour les fournisseurs', 'For suppliers', 'Für Lieferanten', '致供应商'],
                'body' => [
                    'Une visibilité internationale ciblée et des demandes de devis qualifiées, sans dispersion sur des canaux génériques.',
                    'Targeted international visibility and qualified quote requests, without spreading yourself thin across generic channels.',
                    'Gezielte internationale Sichtbarkeit und qualifizierte Angebotsanfragen – ohne Streuverluste über generische Kanäle.',
                    '精准的国际曝光与高质量询价，无需在泛化渠道上分散精力。',
                ],
                'items' => [
                    ['title' => ['Demandes qualifiées', 'Qualified requests', 'Qualifizierte Anfragen', '优质询盘']],
                    ['title' => ['Accompagnement à l\'export', 'Export support', 'Exportbegleitung', '出口支持']],
                    ['title' => ['Présence continue sur la plateforme', 'Ongoing presence on the platform', 'Dauerhafte Präsenz auf der Plattform', '平台持续曝光']],
                ],
            ],
            [
                'key' => 'why_buyers', 'name' => 'Pourquoi Sabonea : carte acheteurs', 'fields' => ['title', 'body'], 'item_fields' => ['title'],
                'title' => ['Pour les acheteurs', 'For buyers', 'Für Einkäufer', '致买家'],
                'body' => [
                    'Un seul besoin exprimé, une équipe qui identifie le bon fournisseur pour vous : un vrai gain de temps.',
                    'One request submitted, a team that finds the right supplier for you: real time saved.',
                    'Ein einziger gemeldeter Bedarf, ein Team, das den richtigen Lieferanten für Sie findet: echte Zeitersparnis.',
                    '只需提交一次需求，团队为您找到合适的供应商：真正节省时间。',
                ],
                'items' => [
                    ['title' => ['Fournisseurs présélectionnés et vérifiés', 'Pre-selected, verified suppliers', 'Vorausgewählte und geprüfte Lieferanten', '经预选和核实的供应商']],
                    ['title' => ['Mise en relation qualifiée', 'Qualified introductions', 'Qualifizierte Vermittlung', '精准对接']],
                    ['title' => ['Un interlocuteur unique dans la durée', 'A single point of contact over time', 'Ein fester Ansprechpartner auf Dauer', '长期唯一对接人']],
                ],
            ],
            [
                'key' => 'cta', 'name' => 'Bannière d\'appel à l\'action', 'fields' => ['title', 'body', 'cta'],
                'title' => ['Prêt à trouver le bon fournisseur ?', 'Ready to find the right supplier?', 'Bereit, den richtigen Lieferanten zu finden?', '准备好寻找合适的供应商了吗？'],
                'body' => [
                    'Décrivez votre besoin : type d\'équipement, secteur, pays, délai. Notre équipe s\'occupe du reste.',
                    'Describe your requirement: type of equipment, sector, country, timeframe. Our team takes care of the rest.',
                    'Beschreiben Sie Ihren Bedarf: Art der Ausrüstung, Branche, Land, Zeitrahmen. Unser Team kümmert sich um den Rest.',
                    '描述您的需求：设备类型、行业、国家、时间要求。其余的交给我们的团队。',
                ],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'a-propos' => [
        'name' => 'À propos',
        'meta_title' => ['À propos   Sabonea', 'About us – Sabonea', 'Über uns – Sabonea', '关于我们 – Sabonea'],
        'meta_description' => [
            'Sabonea est une marketplace B2B internationale qui connecte fournisseurs et acheteurs d\'équipements professionnels de nettoyage, d\'entretien et de maintenance. Découvrez notre histoire et notre mission.',
            'Sabonea is an international B2B marketplace connecting suppliers and buyers of professional cleaning, upkeep and maintenance equipment. Discover our story and our mission.',
            'Sabonea ist ein internationaler B2B-Marktplatz, der Lieferanten und Einkäufer professioneller Reinigungs-, Pflege- und Wartungsausrüstung verbindet. Entdecken Sie unsere Geschichte und Mission.',
            'Sabonea 是连接专业清洁、保养与维护设备供应商与买家的国际 B2B 交易平台。了解我们的故事与使命。',
        ],
        'header_title' => ['À propos de nous', 'About us', 'Über uns', '关于我们'],
        'breadcrumb' => ['À propos', 'About us', 'Über uns', '关于我们'],
        'header_image' => 'about-ingenieure.jpg',
        'header_image_alt' => ['Notre histoire chez Sabonea', 'Our story at Sabonea', 'Unsere Geschichte bei Sabonea', 'Sabonea 的故事'],
        'sections' => [
            [
                'key' => 'intro', 'name' => 'Qui sommes-nous', 'fields' => ['eyebrow', 'subtitle', 'body'],
                'eyebrow' => ['Qui sommes-nous', 'Who we are', 'Wer wir sind', '我们是谁'],
                'subtitle' => [
                    'Sabonea est une marketplace B2B internationale qui met en relation les fournisseurs d\'équipements professionnels de nettoyage, d\'entretien et de maintenance avec les organisations qui en ont besoin.',
                    'Sabonea is an international B2B marketplace connecting suppliers of professional cleaning, upkeep and maintenance equipment with the organisations that need it.',
                    'Sabonea ist ein internationaler B2B-Marktplatz, der Lieferanten professioneller Reinigungs-, Pflege- und Wartungsausrüstung mit den Organisationen verbindet, die sie benötigen.',
                    'Sabonea 是一个国际 B2B 交易平台，连接专业清洁、保养与维护设备供应商与有需求的机构。',
                ],
                'body' => [
                    'Nous accompagnons notamment les secteurs aéroportuaire, hospitalier, municipal et industriel, en facilitant l\'accès à des solutions adaptées à chaque besoin. Notre plateforme crée un lien direct entre acheteurs et fournisseurs, partout dans le monde.',
                    'We work in particular with the airport, hospital, municipal and industrial sectors, making it easier to access solutions suited to every need. Our platform creates a direct link between buyers and suppliers, all over the world.',
                    'Wir begleiten insbesondere die Bereiche Flughafen, Krankenhaus, Kommune und Industrie und erleichtern den Zugang zu Lösungen, die zu jedem Bedarf passen. Unsere Plattform schafft eine direkte Verbindung zwischen Einkäufern und Lieferanten – weltweit.',
                    '我们尤其服务于机场、医院、市政和工业领域，让每一项需求都能便捷地找到合适的解决方案。我们的平台在全球范围内直接连接买家与供应商。',
                ],
            ],
            [
                'key' => 'story', 'name' => 'Notre histoire', 'fields' => ['eyebrow', 'title', 'subtitle', 'body', 'image'],
                'image' => 'about-mission.jpg',
                'image_alt' => ['Notre histoire, une conviction portée depuis le début', 'Our story, a conviction from day one', 'Unsere Geschichte – eine Überzeugung von Anfang an', '我们的故事，始终如一的信念'],
                'eyebrow' => ['Notre histoire', 'Our story', 'Unsere Geschichte', '我们的故事'],
                'title' => ['Une idée simple, portée par une vision plus grande', 'A simple idea, driven by a bigger vision', 'Eine einfache Idee, getragen von einer größeren Vision', '简单的想法，远大的愿景'],
                'subtitle' => [
                    '« Le projet a commencé par une idée simple, portée par une vision plus grande : contribuer, progressivement, à des villes et des infrastructures plus propres, plus modernes et mieux équipées. »',
                    '“The project began with a simple idea, driven by a bigger vision: to gradually help build cleaner, more modern and better-equipped cities and infrastructure.”',
                    '„Das Projekt begann mit einer einfachen Idee, getragen von einer größeren Vision: Schritt für Schritt zu saubereren, moderneren und besser ausgestatteten Städten und Infrastrukturen beizutragen.“',
                    '“这个项目始于一个简单的想法，源于一个更远大的愿景：逐步助力打造更洁净、更现代、装备更完善的城市与基础设施。”',
                ],
                'body' => [
                    'En tant que jeune ingénieure, je construis Sabonea avec cette conviction : une entreprise peut être à la fois ambitieuse, internationale et porteuse d\'un impact positif.',
                    'As a young engineer, I am building Sabonea with this conviction: a company can be ambitious, international and have a positive impact, all at once.',
                    'Als junge Ingenieurin baue ich Sabonea mit dieser Überzeugung auf: Ein Unternehmen kann zugleich ambitioniert, international und wirkungsvoll im positiven Sinne sein.',
                    '作为一名年轻的工程师，我怀着这样的信念打造 Sabonea：一家企业可以同时兼具雄心、国际视野与积极影响。',
                ],
            ],
            [
                'key' => 'mission', 'name' => 'Notre mission', 'fields' => ['eyebrow', 'title', 'subtitle'], 'item_fields' => ['title', 'text', 'icon', 'variant'],
                'eyebrow' => ['Notre mission', 'Our mission', 'Unsere Mission', '我们的使命'],
                'title' => ['Donner à chaque acheteur un accès direct aux meilleurs fournisseurs', 'Giving every buyer direct access to the best suppliers', 'Jedem Einkäufer direkten Zugang zu den besten Lieferanten verschaffen', '让每一位买家直达优质供应商'],
                'subtitle' => [
                    'Où qu\'ils se trouvent dans le monde   et offrir aux fournisseurs une vitrine internationale pour développer leurs marchés à l\'export.',
                    'Wherever they are in the world – and giving suppliers an international showcase to grow their export markets.',
                    'Wo auch immer sie sich auf der Welt befinden – und Lieferanten ein internationales Schaufenster bieten, um ihre Exportmärkte auszubauen.',
                    '无论买家身处世界何处；同时为供应商提供国际展示窗口，助其拓展出口市场。',
                ],
                'items' => [
                    ['icon' => 'fa fa-search', 'title' => ['Simplifier la recherche', 'Simplify the search', 'Die Suche vereinfachen', '简化搜寻'], 'text' => [
                        'De fournisseurs fiables pour les grandes infrastructures.',
                        'For reliable suppliers for major infrastructure.',
                        'Nach zuverlässigen Lieferanten für große Infrastrukturen.',
                        '为大型基础设施寻找可靠的供应商。',
                    ]],
                    ['icon' => 'fa fa-bullseye', 'variant' => 'alt-green', 'title' => ['Orienter chaque besoin', 'Direct every request', 'Jeden Bedarf gezielt lenken', '精准匹配需求'], 'text' => [
                        'Vers le fournisseur le plus adapté, plutôt que de laisser l\'acheteur chercher seul.',
                        'To the most suitable supplier, rather than leaving the buyer to search alone.',
                        'Zum passendsten Lieferanten, statt den Einkäufer allein suchen zu lassen.',
                        '将需求引向最合适的供应商，而不是让买家独自寻找。',
                    ]],
                    ['icon' => 'fa fa-globe', 'variant' => 'alt-orange', 'title' => ['Accompagner à l\'export', 'Support exports', 'Den Export begleiten', '助力出口'], 'text' => [
                        'Les fournisseurs dans leur développement à l\'international.',
                        'Helping suppliers grow internationally.',
                        'Lieferanten bei ihrer internationalen Entwicklung unterstützen.',
                        '支持供应商的国际化发展。',
                    ]],
                    ['icon' => 'fa fa-language', 'title' => ['Rester accessible', 'Stay accessible', 'Zugänglich bleiben', '保持易用'], 'text' => [
                        'Une plateforme multilingue, pensée pour un public professionnel mondial.',
                        'A multilingual platform designed for a global professional audience.',
                        'Eine mehrsprachige Plattform für ein weltweites Fachpublikum.',
                        '面向全球专业用户的多语言平台。',
                    ]],
                ],
            ],
            [
                'key' => 'cta', 'name' => 'Bannière d\'appel à l\'action', 'fields' => ['title', 'body', 'cta'],
                'title' => ['Découvrez comment nous travaillons', 'Discover how we work', 'Entdecken Sie, wie wir arbeiten', '了解我们的工作方式'],
                'body' => [
                    'De l\'expression du besoin jusqu\'à la mise en relation qualifiée.',
                    'From submitting your request to a qualified introduction.',
                    'Von der Bedarfsmeldung bis zur qualifizierten Vermittlung.',
                    '从提交需求到精准对接。',
                ],
                'cta_label' => $howItWorks, 'cta_url' => 'comment-ca-fonctionne',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'comment-ca-fonctionne' => [
        'name' => 'Comment ça marche',
        'meta_title' => ['Comment ça marche   Sabonea', 'How it works – Sabonea', 'So funktioniert’s – Sabonea', '运作方式 – Sabonea'],
        'meta_description' => [
            'Découvrez le parcours fournisseur et le parcours acheteur sur Sabonea : de l\'expression du besoin à la mise en relation qualifiée.',
            'Discover the supplier journey and the buyer journey on Sabonea: from submitting a request to a qualified introduction.',
            'Entdecken Sie den Ablauf für Lieferanten und Einkäufer bei Sabonea: von der Bedarfsmeldung bis zur qualifizierten Vermittlung.',
            '了解 Sabonea 上供应商与买家的流程：从提交需求到精准对接。',
        ],
        'header_title' => $howItWorks,
        'breadcrumb' => $howItWorks,
        'header_image' => 'comment-mise-en-relation.jpg',
        'header_image_alt' => ['Mise en relation entre acheteur et fournisseur', 'Introduction between buyer and supplier', 'Vermittlung zwischen Einkäufer und Lieferant', '买家与供应商对接'],
        'sections' => [
            [
                'key' => 'principle', 'name' => 'Encadré : principe', 'fields' => ['title', 'body'],
                'title' => ['Sabonea n\'est pas un catalogue en libre recherche', 'Sabonea is not a self-service catalogue', 'Sabonea ist kein frei durchsuchbarer Katalog', 'Sabonea 不是自助检索目录'],
                'body' => [
                    'Vous ne parcourez pas librement une liste de fournisseurs. Vous exprimez votre besoin via un formulaire dédié, et c\'est notre équipe qui analyse la demande et vous met en relation avec le ou les fournisseurs les plus adaptés. Objectif : vous faire gagner du temps, et garantir des mises en relation réellement qualifiées.',
                    'You do not browse a list of suppliers on your own. You describe your requirement using a dedicated form, and our team analyses the request and introduces you to the most suitable supplier(s). The goal: to save you time and guarantee genuinely qualified introductions.',
                    'Sie durchsuchen keine Lieferantenliste auf eigene Faust. Sie beschreiben Ihren Bedarf über ein spezielles Formular, und unser Team analysiert die Anfrage und bringt Sie mit dem oder den passendsten Lieferanten zusammen. Ziel: Ihnen Zeit sparen und wirklich qualifizierte Kontakte garantieren.',
                    '您无需自行浏览供应商列表。只需通过专用表单描述需求，我们的团队会分析您的请求，并为您对接最合适的供应商。目标：为您节省时间，确保每一次对接都真正精准。',
                ],
            ],
            [
                'key' => 'buyers', 'name' => 'Parcours acheteurs', 'fields' => ['eyebrow', 'title', 'cta'], 'item_fields' => ['title', 'text'],
                'eyebrow' => ['Pour les acheteurs', 'For buyers', 'Für Einkäufer', '致买家'],
                'title' => ['Du besoin à la mise en relation', 'From requirement to introduction', 'Vom Bedarf zur Vermittlung', '从需求到对接'],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
                'items' => [
                    ['title' => ['Expression du besoin', 'Submitting the request', 'Bedarfsmeldung', '提交需求'], 'text' => [
                        'L\'acheteur remplit un formulaire simple décrivant son besoin (type d\'équipement, secteur, pays, délai).',
                        'The buyer fills in a simple form describing the requirement (type of equipment, sector, country, timeframe).',
                        'Der Einkäufer füllt ein einfaches Formular zu seinem Bedarf aus (Art der Ausrüstung, Branche, Land, Zeitrahmen).',
                        '买家填写一份简单的表单，说明需求（设备类型、行业、国家、时间要求）。',
                    ]],
                    ['title' => ['Analyse par l\'équipe Sabonea', 'Analysis by the Sabonea team', 'Analyse durch das Sabonea-Team', 'Sabonea 团队分析'], 'text' => [
                        'Notre équipe étudie la demande et identifie, dans son réseau, le ou les fournisseurs les plus adaptés.',
                        'Our team reviews the request and identifies the most suitable supplier(s) in its network.',
                        'Unser Team prüft die Anfrage und ermittelt in seinem Netzwerk den oder die passendsten Lieferanten.',
                        '我们的团队研究需求，并在网络中找到最合适的供应商。',
                    ]],
                    ['title' => ['Mise en relation qualifiée', 'Qualified introduction', 'Qualifizierte Vermittlung', '精准对接'], 'text' => [
                        'Sabonea transmet la demande au(x) fournisseur(s) sélectionné(s) et fait les présentations.',
                        'Sabonea passes the request on to the selected supplier(s) and makes the introductions.',
                        'Sabonea leitet die Anfrage an den oder die ausgewählten Lieferanten weiter und stellt den Kontakt her.',
                        'Sabonea 将需求转交给选定的供应商，并为双方牵线。',
                    ]],
                    ['title' => ['Échange direct', 'Direct exchange', 'Direkter Austausch', '直接沟通'], 'text' => [
                        'L\'acheteur et le fournisseur poursuivent l\'échange commercial (devis, négociation, livraison) directement entre eux.',
                        'The buyer and supplier continue the commercial discussion (quote, negotiation, delivery) directly with each other.',
                        'Einkäufer und Lieferant führen den geschäftlichen Austausch (Angebot, Verhandlung, Lieferung) direkt miteinander fort.',
                        '买家与供应商直接进行商务沟通（报价、谈判、交付）。',
                    ]],
                    ['title' => ['Suivi dans la durée', 'Ongoing follow-up', 'Begleitung auf Dauer', '长期跟进'], 'text' => [
                        'Sabonea reste votre interlocuteur unique pour l\'ensemble de vos besoins en équipements : à chaque nouvelle recherche, c\'est nous qui identifions le fournisseur adapté.',
                        'Sabonea remains your single point of contact for all your equipment needs: for every new search, we find the right supplier.',
                        'Sabonea bleibt Ihr zentraler Ansprechpartner für Ihren gesamten Ausrüstungsbedarf: Bei jeder neuen Suche finden wir den passenden Lieferanten.',
                        'Sabonea 始终是您所有设备需求的唯一对接人：每一次新的寻源，都由我们为您找到合适的供应商。',
                    ]],
                ],
            ],
            [
                'key' => 'suppliers', 'name' => 'Parcours fournisseurs', 'fields' => ['eyebrow', 'title', 'cta'], 'item_fields' => ['title', 'text'],
                'eyebrow' => ['Pour les fournisseurs', 'For suppliers', 'Für Lieferanten', '致供应商'],
                'title' => ['De l\'inscription à la demande de devis', 'From registration to quote request', 'Von der Anmeldung zur Angebotsanfrage', '从注册到收到询价'],
                'cta_label' => $becomeSupplier, 'cta_url' => 'devenir-fournisseur',
                'items' => [
                    ['title' => ['Inscription', 'Registration', 'Anmeldung', '注册'], 'text' => [
                        'Le fournisseur soumet son profil et ses gammes de produits via notre formulaire dédié.',
                        'The supplier submits its profile and product ranges via our dedicated form.',
                        'Der Lieferant übermittelt sein Profil und seine Produktsortimente über unser spezielles Formular.',
                        '供应商通过专用表单提交企业简介与产品系列。',
                    ]],
                    ['title' => ['Validation', 'Approval', 'Prüfung', '审核'], 'text' => [
                        'L\'équipe Sabonea étudie le dossier (certifications, références, conditions commerciales) et valide l\'intégration.',
                        'The Sabonea team reviews the application (certifications, references, commercial terms) and approves onboarding.',
                        'Das Sabonea-Team prüft die Unterlagen (Zertifizierungen, Referenzen, Geschäftsbedingungen) und bestätigt die Aufnahme.',
                        'Sabonea 团队审核资料（认证、业绩、商务条款）并确认入驻。',
                    ]],
                    ['title' => ['Référencement', 'Listing', 'Listung', '上架展示'], 'text' => [
                        'Le profil et les produits sont publiés sur la marketplace sous forme de vitrine (gammes, certifications, secteurs couverts), visible par les acheteurs professionnels.',
                        'The profile and products are published on the marketplace as a showcase (ranges, certifications, sectors covered), visible to professional buyers.',
                        'Profil und Produkte werden auf dem Marktplatz als Schaufenster veröffentlicht (Sortimente, Zertifizierungen, abgedeckte Branchen) – sichtbar für professionelle Einkäufer.',
                        '企业简介与产品以展示页形式发布在平台上（产品系列、认证、覆盖行业），供专业买家浏览。',
                    ]],
                    ['title' => ['Mise en relation', 'Introductions', 'Vermittlung', '对接'], 'text' => [
                        'Le fournisseur reçoit des demandes de devis directement via la plateforme.',
                        'The supplier receives quote requests directly through the platform.',
                        'Der Lieferant erhält Angebotsanfragen direkt über die Plattform.',
                        '供应商通过平台直接收到询价请求。',
                    ]],
                ],
            ],
            [
                'key' => 'showcase', 'name' => 'La vitrine fournisseur', 'fields' => ['eyebrow', 'title', 'body', 'note', 'image', 'cta'],
                'image' => 'comment-fournisseur.jpg',
                'image_alt' => ['Vitrine fournisseur sur Sabonea', 'Supplier showcase on Sabonea', 'Lieferanten-Schaufenster bei Sabonea', 'Sabonea 上的供应商展示页'],
                'eyebrow' => ['La vitrine fournisseur', 'The supplier showcase', 'Das Lieferanten-Schaufenster', '供应商展示页'],
                'title' => ['Une page publique, sans coordonnées directes', 'A public page, with no direct contact details', 'Eine öffentliche Seite ohne direkte Kontaktdaten', '公开页面，不展示直接联系方式'],
                'body' => [
                    'Chaque fournisseur abonné dispose d\'une page vitrine publique : logo, gammes de produits, certifications, secteurs couverts. Pour garder Sabonea au centre de chaque mise en relation, aucune coordonnée directe (e-mail, téléphone) n\'y est affichée.',
                    'Every subscribed supplier has a public showcase page: logo, product ranges, certifications, sectors covered. To keep Sabonea at the heart of every introduction, no direct contact details (email, phone) are displayed.',
                    'Jeder Lieferant im Abonnement verfügt über ein öffentliches Schaufenster: Logo, Produktsortimente, Zertifizierungen, abgedeckte Branchen. Damit Sabonea im Zentrum jeder Vermittlung bleibt, werden keine direkten Kontaktdaten (E-Mail, Telefon) angezeigt.',
                    '每位订阅供应商都拥有一个公开展示页：标志、产品系列、认证、覆盖行业。为确保每次对接都经由 Sabonea，页面上不展示任何直接联系方式（电子邮件、电话）。',
                ],
                'note' => [
                    'À la place, un bouton **« Être mis en relation via Sabonea »** renvoie vers le formulaire de demande, afin que chaque contact passe par une mise en relation qualifiée par notre équipe.',
                    'Instead, a **“Get introduced via Sabonea”** button leads to the request form, so that every contact goes through a qualified introduction by our team.',
                    'Stattdessen führt eine Schaltfläche **„Über Sabonea vermitteln lassen“** zum Anfrageformular, damit jeder Kontakt über eine qualifizierte Vermittlung durch unser Team läuft.',
                    '取而代之的是一个 **“通过 Sabonea 对接”** 按钮，引导至需求表单，确保每一次联系都经过我们团队的精准对接。',
                ],
                'cta_label' => ['Voir un exemple de vitrine', 'See an example showcase', 'Beispiel-Schaufenster ansehen', '查看展示页示例'], 'cta_url' => 'fournisseur-exemple',
            ],
            [
                'key' => 'cta', 'name' => 'Bannière d\'appel à l\'action', 'fields' => ['title', 'body', 'cta', 'cta2'],
                'title' => ['Une question sur le parcours ?', 'A question about the process?', 'Eine Frage zum Ablauf?', '对流程有疑问？'],
                'body' => [
                    'Que vous soyez acheteur ou fournisseur, notre équipe vous accompagne à chaque étape.',
                    'Whether you are a buyer or a supplier, our team supports you every step of the way.',
                    'Ob Einkäufer oder Lieferant – unser Team begleitet Sie bei jedem Schritt.',
                    '无论您是买家还是供应商，我们的团队都将全程陪伴。',
                ],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
                'cta2_label' => $contactUs, 'cta2_url' => 'contact',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'secteurs' => [
        'name' => 'Secteurs & équipements',
        'meta_title' => ['Secteurs & équipements   Sabonea', 'Sectors & equipment – Sabonea', 'Branchen & Ausrüstung – Sabonea', '行业与设备 – Sabonea'],
        'meta_description' => [
            'Sabonea couvre les équipements professionnels pour les aéroports, hôpitaux, municipalités, sites industriels, chantiers, hôtels, centres commerciaux, universités et plateformes logistiques.',
            'Sabonea covers professional equipment for airports, hospitals, municipalities, industrial sites, construction sites, hotels, shopping centres, universities and logistics hubs.',
            'Sabonea deckt professionelle Ausrüstung für Flughäfen, Krankenhäuser, Kommunen, Industriestandorte, Baustellen, Hotels, Einkaufszentren, Universitäten und Logistikzentren ab.',
            'Sabonea 覆盖机场、医院、市政、工业场所、建筑工地、酒店、购物中心、大学及物流中心所需的专业设备。',
        ],
        'header_title' => ['Secteurs & équipements', 'Sectors & equipment', 'Branchen & Ausrüstung', '行业与设备'],
        'breadcrumb' => ['Secteurs', 'Sectors', 'Branchen', '行业'],
        'sections' => [
            [
                'key' => 'sectors', 'name' => 'Nos secteurs (en-tête de la liste)', 'fields' => ['eyebrow', 'title'],
                'eyebrow' => ['Nos secteurs d\'activité', 'Our sectors', 'Unsere Branchen', '服务行业'],
                'title' => ['Sabonea couvre les équipements professionnels destinés à :', 'Sabonea covers professional equipment for:', 'Sabonea deckt professionelle Ausrüstung ab für:', 'Sabonea 覆盖以下领域的专业设备：'],
            ],
            [
                'key' => 'equipment', 'name' => 'Types d\'équipements (en-tête de la liste)', 'fields' => ['eyebrow', 'title'],
                'eyebrow' => ['Types d\'équipements', 'Types of equipment', 'Ausrüstungsarten', '设备类型'],
                'title' => ['Les familles de machines que nous référençons', 'The machine families we list', 'Die Maschinenfamilien, die wir listen', '我们收录的设备类别'],
            ],
            [
                'key' => 'cta', 'name' => 'Bannière d\'appel à l\'action', 'fields' => ['title', 'body', 'cta'],
                'title' => ['Vous ne trouvez pas votre équipement ?', 'Can\'t find your equipment?', 'Sie finden Ihre Ausrüstung nicht?', '找不到您需要的设备？'],
                'body' => [
                    'Décrivez-nous votre besoin : notre équipe identifie le fournisseur adapté dans son réseau.',
                    'Describe your requirement: our team will find the right supplier in its network.',
                    'Beschreiben Sie uns Ihren Bedarf: Unser Team findet den passenden Lieferanten in seinem Netzwerk.',
                    '告诉我们您的需求：我们的团队会在网络中为您找到合适的供应商。',
                ],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'pourquoi-sabonea' => [
        'name' => 'Pourquoi Sabonea',
        'meta_title' => ['Pourquoi Sabonea   Sabonea', 'Why Sabonea – Sabonea', 'Warum Sabonea – Sabonea', '为何选择 Sabonea – Sabonea'],
        'meta_description' => [
            'Pourquoi choisir Sabonea : pour les fournisseurs, une visibilité internationale ciblée et des demandes qualifiées. Pour les acheteurs, un gain de temps et des fournisseurs vérifiés.',
            'Why choose Sabonea: for suppliers, targeted international visibility and qualified requests. For buyers, time saved and verified suppliers.',
            'Warum Sabonea: für Lieferanten gezielte internationale Sichtbarkeit und qualifizierte Anfragen. Für Einkäufer Zeitersparnis und geprüfte Lieferanten.',
            '为何选择 Sabonea：对供应商而言，是精准的国际曝光与优质询盘；对买家而言，是节省时间与经过核实的供应商。',
        ],
        'header_title' => ['Pourquoi choisir Sabonea', 'Why choose Sabonea', 'Warum Sabonea', '为何选择 Sabonea'],
        'breadcrumb' => ['Pourquoi Sabonea', 'Why Sabonea', 'Warum Sabonea', '为何选择 Sabonea'],
        'sections' => [
            [
                'key' => 'suppliers', 'name' => 'Pour les fournisseurs', 'fields' => ['eyebrow', 'title', 'image', 'cta'], 'item_fields' => ['title'],
                'image' => 'pourquoi-fournisseurs.jpg',
                'image_alt' => ['Avantages Sabonea pour les fournisseurs', 'Sabonea benefits for suppliers', 'Vorteile von Sabonea für Lieferanten', 'Sabonea 为供应商带来的优势'],
                'eyebrow' => ['Pour les fournisseurs', 'For suppliers', 'Für Lieferanten', '致供应商'],
                'title' => ['Une vitrine à l\'international, des contacts qualifiés', 'An international showcase, qualified contacts', 'Ein internationales Schaufenster, qualifizierte Kontakte', '国际展示窗口，优质客户资源'],
                'cta_label' => $becomeSupplier, 'cta_url' => 'devenir-fournisseur',
                'items' => [
                    ['title' => ['Une visibilité internationale ciblée, sans dispersion sur des canaux génériques', 'Targeted international visibility, without spreading yourself thin across generic channels', 'Gezielte internationale Sichtbarkeit – ohne Streuverluste über generische Kanäle', '精准的国际曝光，无需在泛化渠道上分散精力']],
                    ['title' => ['Des demandes de devis qualifiées, provenant d\'acheteurs professionnels identifiés', 'Qualified quote requests from identified professional buyers', 'Qualifizierte Angebotsanfragen von identifizierten professionellen Einkäufern', '来自专业买家的优质询价']],
                    ['title' => ['Un accompagnement pour le développement à l\'export', 'Support for growing your exports', 'Begleitung bei der Exportentwicklung', '出口业务发展支持']],
                    ['title' => ['Une présence continue sur la plateforme, pour rester visible sur la durée', 'An ongoing presence on the platform, to stay visible over time', 'Dauerhafte Präsenz auf der Plattform, um langfristig sichtbar zu bleiben', '在平台上持续曝光，长期保持可见度']],
                ],
            ],
            [
                'key' => 'buyers', 'name' => 'Pour les acheteurs', 'fields' => ['eyebrow', 'title', 'image', 'cta'], 'item_fields' => ['title'],
                'image' => 'pourquoi-acheteurs.jpg',
                'image_alt' => ['Avantages Sabonea pour les acheteurs', 'Sabonea benefits for buyers', 'Vorteile von Sabonea für Einkäufer', 'Sabonea 为买家带来的优势'],
                'eyebrow' => ['Pour les acheteurs', 'For buyers', 'Für Einkäufer', '致买家'],
                'title' => ['Un vrai gain de temps, un interlocuteur unique', 'Real time saved, a single point of contact', 'Echte Zeitersparnis, ein zentraler Ansprechpartner', '真正省时，唯一对接人'],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
                'items' => [
                    ['title' => ['Un seul besoin exprimé, une équipe qui identifie le bon fournisseur pour vous', 'One request submitted, a team that finds the right supplier for you', 'Ein einziger gemeldeter Bedarf, ein Team, das den richtigen Lieferanten für Sie findet', '只需提交一次需求，团队为您找到合适的供应商']],
                    ['title' => ['Des fournisseurs présélectionnés et vérifiés par notre équipe', 'Suppliers pre-selected and verified by our team', 'Von unserem Team vorausgewählte und geprüfte Lieferanten', '由我们团队预选并核实的供应商']],
                    ['title' => ['Une mise en relation qualifiée, directement avec le fournisseur le plus pertinent', 'A qualified introduction, directly with the most relevant supplier', 'Eine qualifizierte Vermittlung direkt mit dem relevantesten Lieferanten', '与最匹配的供应商直接精准对接']],
                    ['title' => ['Un accompagnement dans la durée, pour tous vos besoins en équipement', 'Long-term support for all your equipment needs', 'Langfristige Begleitung für Ihren gesamten Ausrüstungsbedarf', '长期支持您的全部设备需求']],
                ],
            ],
            [
                'key' => 'cta', 'name' => 'Bannière d\'appel à l\'action', 'fields' => ['title', 'body', 'cta', 'cta2'],
                'title' => ['Prêt à démarrer avec Sabonea ?', 'Ready to get started with Sabonea?', 'Bereit für den Start mit Sabonea?', '准备好开始使用 Sabonea 了吗？'],
                'body' => [
                    'Que vous cherchiez un fournisseur ou que vous en soyez un, notre équipe vous accompagne.',
                    'Whether you are looking for a supplier or you are one, our team is here to help.',
                    'Ob Sie einen Lieferanten suchen oder selbst einer sind – unser Team begleitet Sie.',
                    '无论您是在寻找供应商，还是您本身就是供应商，我们的团队都将为您提供支持。',
                ],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
                'cta2_label' => $contactUs, 'cta2_url' => 'contact',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'contact' => [
        'name' => 'Contact',
        'meta_title' => ['Contact   Sabonea', 'Contact – Sabonea', 'Kontakt – Sabonea', '联系我们 – Sabonea'],
        'meta_description' => [
            'Une question, une demande de partenariat, ou vous souhaitez rejoindre Sabonea en tant que fournisseur ? Contactez-nous par e-mail ou sur les réseaux sociaux.',
            'A question, a partnership request, or would you like to join Sabonea as a supplier? Contact us by email or on social media.',
            'Eine Frage, eine Partnerschaftsanfrage oder möchten Sie Sabonea als Lieferant beitreten? Kontaktieren Sie uns per E-Mail oder über soziale Netzwerke.',
            '有疑问、合作意向，或希望以供应商身份加入 Sabonea？欢迎通过电子邮件或社交媒体联系我们。',
        ],
        'header_title' => ['Contact', 'Contact', 'Kontakt', '联系我们'],
        'breadcrumb' => ['Contact', 'Contact', 'Kontakt', '联系我们'],
        'header_image' => 'contact-banner.jpg',
        'header_image_alt' => ['Contacter Sabonea', 'Contact Sabonea', 'Sabonea kontaktieren', '联系 Sabonea'],
        'sections' => [
            [
                'key' => 'intro', 'name' => 'Introduction', 'fields' => ['eyebrow', 'title', 'subtitle'],
                'eyebrow' => ['Parlons-en', 'Let\'s talk', 'Sprechen wir darüber', '欢迎交流'],
                'title' => [
                    'Une question, une demande de partenariat, ou vous souhaitez rejoindre Sabonea en tant que fournisseur ?',
                    'A question, a partnership request, or would you like to join Sabonea as a supplier?',
                    'Eine Frage, eine Partnerschaftsanfrage oder möchten Sie Sabonea als Lieferant beitreten?',
                    '有疑问、合作意向，或希望以供应商身份加入 Sabonea？',
                ],
                'subtitle' => ['Contactez-nous   nous revenons vers vous rapidement.', 'Contact us – we will get back to you quickly.', 'Kontaktieren Sie uns – wir melden uns schnell bei Ihnen.', '请联系我们，我们会尽快回复。'],
            ],
            [
                'key' => 'form', 'name' => 'Formulaire de contact', 'fields' => ['title'],
                'title' => ['Envoyez-nous un message', 'Send us a message', 'Senden Sie uns eine Nachricht', '给我们留言'],
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'expression-de-besoin' => [
        'name' => 'Exprimer un besoin',
        'meta_title' => ['Exprimer un besoin   Sabonea', 'Submit a request – Sabonea', 'Bedarf melden – Sabonea', '提交需求 – Sabonea'],
        'meta_description' => [
            'Décrivez votre besoin en équipement professionnel de nettoyage, d\'entretien ou de maintenance. L\'équipe Sabonea analyse votre demande et vous met en relation avec le fournisseur le plus adapté.',
            'Describe your need for professional cleaning, upkeep or maintenance equipment. The Sabonea team analyses your request and introduces you to the most suitable supplier.',
            'Beschreiben Sie Ihren Bedarf an professioneller Reinigungs-, Pflege- oder Wartungsausrüstung. Das Sabonea-Team analysiert Ihre Anfrage und vermittelt Ihnen den passendsten Lieferanten.',
            '描述您对专业清洁、保养或维护设备的需求。Sabonea 团队将分析您的请求，并为您对接最合适的供应商。',
        ],
        'header_title' => $expressNeed,
        'breadcrumb' => $expressNeed,
        'sections' => [
            [
                'key' => 'intro', 'name' => 'Introduction et étapes', 'fields' => ['eyebrow', 'title', 'body', 'note'], 'item_fields' => ['title', 'text'],
                'eyebrow' => ['Étape 1 sur 3', 'Step 1 of 3', 'Schritt 1 von 3', '第 1 步，共 3 步'],
                'title' => ['Décrivez votre besoin en quelques champs', 'Describe your requirement in a few fields', 'Beschreiben Sie Ihren Bedarf in wenigen Feldern', '只需填写几项，描述您的需求'],
                'body' => [
                    'Type d\'équipement, secteur, pays, délai souhaité : ces quelques informations suffisent à notre équipe pour commencer l\'analyse de votre demande.',
                    'Type of equipment, sector, country, desired timeframe: this information is all our team needs to start analysing your request.',
                    'Art der Ausrüstung, Branche, Land, gewünschter Zeitrahmen: Diese wenigen Angaben genügen unserem Team, um mit der Analyse Ihrer Anfrage zu beginnen.',
                    '设备类型、行业、国家、期望时间：有了这些信息，我们的团队就可以开始分析您的需求。',
                ],
                'note' => [
                    'Vos coordonnées ne sont partagées qu\'au moment de la mise en relation qualifiée par notre équipe   jamais publiées ni diffusées librement.',
                    'Your contact details are only shared at the time of the qualified introduction by our team – never published or freely distributed.',
                    'Ihre Kontaktdaten werden erst bei der qualifizierten Vermittlung durch unser Team weitergegeben – niemals veröffentlicht oder frei verbreitet.',
                    '您的联系方式仅在我们团队进行精准对接时才会共享，绝不会被公开或随意传播。',
                ],
                'items' => [
                    ['title' => ['Vous exprimez votre besoin', 'You submit your requirement', 'Sie melden Ihren Bedarf', '您提交需求'], 'text' => [
                        'Via ce formulaire, en quelques minutes.',
                        'Using this form, in just a few minutes.',
                        'Über dieses Formular, in wenigen Minuten.',
                        '通过本表单，几分钟即可完成。',
                    ]],
                    ['title' => ['Notre équipe analyse la demande', 'Our team analyses the request', 'Unser Team analysiert die Anfrage', '团队分析需求'], 'text' => [
                        'Et identifie le ou les fournisseurs les plus adaptés dans son réseau.',
                        'And identifies the most suitable supplier(s) in its network.',
                        'Und ermittelt den oder die passendsten Lieferanten in seinem Netzwerk.',
                        '并在网络中找到最合适的供应商。',
                    ]],
                    ['title' => ['Vous êtes mis en relation', 'You are introduced', 'Sie werden vermittelt', '为您对接'], 'text' => [
                        'Directement avec le fournisseur sélectionné, pour poursuivre l\'échange (devis, négociation, livraison).',
                        'Directly with the selected supplier, to continue the discussion (quote, negotiation, delivery).',
                        'Direkt mit dem ausgewählten Lieferanten, um den Austausch fortzusetzen (Angebot, Verhandlung, Lieferung).',
                        '直接与选定的供应商联系，继续后续沟通（报价、谈判、交付）。',
                    ]],
                ],
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'fournisseur-exemple' => [
        'name' => 'Exemple de vitrine fournisseur',
        'noindex' => true,
        'meta_title' => ['Exemple de vitrine fournisseur   Sabonea', 'Example supplier showcase – Sabonea', 'Beispiel eines Lieferanten-Schaufensters – Sabonea', '供应商展示页示例 – Sabonea'],
        'meta_description' => [
            'Exemple illustratif d\'une page vitrine fournisseur sur Sabonea : gammes, certifications, secteurs couverts   sans coordonnées de contact directes.',
            'Illustrative example of a supplier showcase page on Sabonea: ranges, certifications, sectors covered – without direct contact details.',
            'Beispielhaftes Lieferanten-Schaufenster bei Sabonea: Sortimente, Zertifizierungen, abgedeckte Branchen – ohne direkte Kontaktdaten.',
            'Sabonea 供应商展示页示例：产品系列、认证、覆盖行业——不含直接联系方式。',
        ],
        'breadcrumb' => ['Exemple de vitrine', 'Example showcase', 'Beispiel-Schaufenster', '展示页示例'],
        'header_image' => 'fournisseur-cover.jpg',
        'header_image_alt' => ['Bannière de la vitrine fournisseur', 'Supplier showcase banner', 'Banner des Lieferanten-Schaufensters', '供应商展示页横幅'],
        'sections' => [
            [
                'key' => 'ribbon', 'name' => 'Bandeau « exemple »', 'fields' => ['title'],
                'title' => [
                    'Exemple illustratif de vitrine fournisseur   contenu fictif à titre de maquette',
                    'Illustrative supplier showcase – fictitious content for demonstration purposes',
                    'Beispielhaftes Lieferanten-Schaufenster – fiktive Inhalte zu Demonstrationszwecken',
                    '供应商展示页示例——内容均为虚构，仅供演示',
                ],
            ],
            [
                'key' => 'profile', 'name' => 'Identité du fournisseur', 'fields' => ['eyebrow', 'title', 'subtitle'],
                'eyebrow' => ['NF', 'SN', 'LN', 'NF'],
                'title' => ['Nom du fournisseur', 'Supplier name', 'Name des Lieferanten', '供应商名称'],
                'subtitle' => ['Pays d\'origine   Fournisseur abonné', 'Country of origin – Subscribed supplier', 'Herkunftsland – Lieferant im Abonnement', '原产国 – 订阅供应商'],
            ],
            [
                'key' => 'tags', 'name' => 'Étiquettes (secteurs & certifications)', 'fields' => [], 'item_fields' => ['title', 'icon', 'variant'],
                'items' => [
                    ['icon' => 'fa fa-plane', 'title' => ['Aéroports', 'Airports', 'Flughäfen', '机场']],
                    ['icon' => 'fa fa-city', 'title' => ['Municipalités', 'Municipalities', 'Kommunen', '市政']],
                    ['icon' => 'fa fa-industry', 'title' => ['Sites industriels', 'Industrial sites', 'Industriestandorte', '工业场所']],
                    ['icon' => 'fa fa-certificate', 'variant' => 'green', 'title' => ['ISO 9001', 'ISO 9001', 'ISO 9001', 'ISO 9001']],
                    ['icon' => 'fa fa-certificate', 'variant' => 'green', 'title' => ['Marquage CE', 'CE marking', 'CE-Kennzeichnung', 'CE 认证']],
                ],
            ],
            [
                'key' => 'presentation', 'name' => 'Présentation', 'fields' => ['title', 'body'],
                'title' => ['Présentation', 'Overview', 'Vorstellung', '企业简介'],
                'body' => [
                    'C\'est ici que s\'affiche la présentation du fournisseur : son activité, son expérience, ses marchés couverts et ce qui le distingue. Ce contenu est fourni par le fournisseur lors de son inscription puis validé par l\'équipe Sabonea avant publication.',
                    'This is where the supplier’s overview appears: its business, experience, markets covered and what sets it apart. This content is provided by the supplier at registration and approved by the Sabonea team before publication.',
                    'Hier erscheint die Vorstellung des Lieferanten: seine Tätigkeit, seine Erfahrung, seine Märkte und was ihn auszeichnet. Diese Inhalte stellt der Lieferant bei der Anmeldung bereit; sie werden vor der Veröffentlichung vom Sabonea-Team geprüft.',
                    '此处展示供应商简介：业务、经验、覆盖市场以及独特优势。内容由供应商在注册时提供，并在发布前经 Sabonea 团队审核。',
                ],
            ],
            [
                'key' => 'ranges', 'name' => 'Gammes de produits', 'fields' => ['title'], 'item_fields' => ['title', 'image'],
                'title' => ['Gammes de produits', 'Product ranges', 'Produktsortimente', '产品系列'],
                'items' => [
                    ['image' => 'equip-balayeuse.jpg', 'title' => ['Balayeuses de voirie', 'Road sweepers', 'Straßenkehrmaschinen', '道路清扫车']],
                    ['image' => 'equip-haute-pression.jpg', 'title' => ['Nettoyeurs haute pression', 'High-pressure cleaners', 'Hochdruckreiniger', '高压清洗机']],
                    ['image' => 'equip-scrubber.jpg', 'title' => ['Autolaveuses industrielles', 'Industrial scrubber-dryers', 'Industrielle Scheuersaugmaschinen', '工业洗地机']],
                ],
            ],
            [
                'key' => 'contact_card', 'name' => 'Encadré « mise en relation »', 'fields' => ['title', 'subtitle', 'body', 'cta', 'cta2'],
                'title' => ['Mise en relation', 'Introduction', 'Vermittlung', '对接'],
                'subtitle' => [
                    'Coordonnées non affichées   mise en relation gérée par Sabonea',
                    'Contact details hidden – introductions handled by Sabonea',
                    'Kontaktdaten ausgeblendet – Vermittlung durch Sabonea',
                    '不显示联系方式——由 Sabonea 负责对接',
                ],
                'body' => [
                    'Pour préserver la qualité des échanges, aucun e-mail ni numéro de téléphone n\'est publié sur cette page. Toute demande passe par notre équipe.',
                    'To keep exchanges high-quality, no email address or phone number is published on this page. Every request goes through our team.',
                    'Um die Qualität des Austauschs zu sichern, werden auf dieser Seite weder E-Mail-Adresse noch Telefonnummer veröffentlicht. Jede Anfrage läuft über unser Team.',
                    '为保证沟通质量，本页面不公布任何电子邮件或电话号码。所有请求均由我们的团队处理。',
                ],
                'cta_label' => ['Être mis en relation via Sabonea', 'Get introduced via Sabonea', 'Über Sabonea vermitteln lassen', '通过 Sabonea 对接'], 'cta_url' => 'expression-de-besoin',
                'cta2_label' => ['Voir tous les secteurs', 'See all sectors', 'Alle Branchen ansehen', '查看全部行业'], 'cta2_url' => 'secteurs',
            ],
            [
                'key' => 'checklist', 'name' => 'Encadré « sur cette vitrine »', 'fields' => ['title'], 'item_fields' => ['title'],
                'title' => ['Sur cette vitrine', 'On this showcase', 'In diesem Schaufenster', '展示页内容'],
                'items' => [
                    ['title' => ['Logo & présentation', 'Logo & overview', 'Logo & Vorstellung', '标志与简介']],
                    ['title' => ['Gammes de produits', 'Product ranges', 'Produktsortimente', '产品系列']],
                    ['title' => ['Certifications', 'Certifications', 'Zertifizierungen', '认证']],
                    ['title' => ['Secteurs couverts', 'Sectors covered', 'Abgedeckte Branchen', '覆盖行业']],
                ],
            ],
        ],
    ],
];
