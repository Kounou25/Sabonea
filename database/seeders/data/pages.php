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
        'meta_title' => ['Sabonea | Fournisseurs d\'équipements de nettoyage et de maintenance', 'Sabonea | Suppliers of cleaning and maintenance equipment', 'Sabonea | Lieferanten für Reinigungs- und Wartungstechnik', 'Sabonea | 清洁与维护设备供应商对接'],
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
                    ['icon' => 'fa fa-globe-americas', 'title' => ['Un formulaire, pas un catalogue', 'A form, not a catalogue', 'Ein Formular statt eines Katalogs', '一份表单，而非目录'], 'text' => [
                        'Vous décrivez l\'équipement recherché. Notre équipe cherche le fournisseur à votre place.',
                        'You describe the equipment you need. Our team looks for the supplier on your behalf.',
                        'Sie beschreiben die gesuchte Ausrüstung. Unser Team sucht den Lieferanten für Sie.',
                        '您只需描述所需设备，由我们的团队代您寻找供应商。',
                    ]],
                    ['icon' => 'fa fa-layer-group', 'variant' => 'alt-green', 'title' => ['Des fournisseurs vérifiés', 'Verified suppliers', 'Geprüfte Lieferanten', '经过审核的供应商'], 'text' => [
                        'Certifications, références et conditions commerciales sont examinées avant tout référencement.',
                        'Certifications, references and commercial terms are checked before any supplier is listed.',
                        'Zertifizierungen, Referenzen und Geschäftsbedingungen werden vor jeder Listung geprüft.',
                        '每家供应商上架前，我们都会审核其认证、业绩和商务条款。',
                    ]],
                    ['icon' => 'fa fa-user-check', 'variant' => 'alt-orange', 'title' => ['Puis un échange direct', 'Then direct contact', 'Danach direkter Austausch', '随后直接洽谈'], 'text' => [
                        'Une fois les présentations faites, acheteur et fournisseur négocient entre eux.',
                        'Once the introduction is made, buyer and supplier negotiate with each other.',
                        'Nach der Vorstellung verhandeln Einkäufer und Lieferant direkt miteinander.',
                        '引荐完成后，买卖双方直接洽谈。',
                    ]],
                    ['icon' => 'fa fa-language', 'title' => ['Quatre langues', 'Four languages', 'Vier Sprachen', '四种语言'], 'text' => [
                        'Français, anglais, allemand et chinois.',
                        'French, English, German and Chinese.',
                        'Französisch, Englisch, Deutsch und Chinesisch.',
                        '法语、英语、德语和中文。',
                    ]],
                ],
            ],
            [
                'key' => 'about', 'name' => 'À propos (aperçu)', 'fields' => ['eyebrow', 'title', 'body', 'image', 'cta'], 'item_fields' => ['title'],
                'image' => 'about-ingenieure.jpg',
                'image_alt' => ['Sabonea, une entreprise portée par une vision d\'infrastructures plus propres', 'Sabonea, a company driven by a vision of cleaner infrastructure', 'Sabonea, ein Unternehmen mit der Vision sauberer Infrastrukturen', 'Sabonea，致力于打造更洁净基础设施的企业'],
                'eyebrow' => ['À propos de Sabonea', 'About Sabonea', 'Über Sabonea', '关于 Sabonea'],
                'title' => ['Une place de marché dédiée au nettoyage et à la maintenance professionnels', 'A marketplace dedicated to professional cleaning and maintenance equipment', 'Ein Marktplatz für professionelle Reinigungs- und Wartungstechnik', '专注专业清洁与维护设备的交易平台'],
                'body' => [
                    'Balayeuses, autolaveuses, nettoyeurs haute pression, matériel de déneigement : Sabonea met en relation les fabricants et distributeurs de ces équipements avec les aéroports, hôpitaux, collectivités et sites industriels qui en ont besoin.',
                    'Road sweepers, scrubber-dryers, high-pressure cleaners, snow removal equipment: Sabonea connects the manufacturers and distributors of these machines with the airports, hospitals, local authorities and industrial sites that need them.',
                    'Kehrmaschinen, Scheuersaugmaschinen, Hochdruckreiniger, Winterdiensttechnik: Sabonea verbindet Hersteller und Händler dieser Geräte mit Flughäfen, Krankenhäusern, Kommunen und Industriestandorten, die sie benötigen.',
                    '道路清扫车、洗地机、高压清洗机、除雪设备：Sabonea 将这些设备的制造商和经销商，与有需求的机场、医院、市政部门和工业场所对接。',
                ],
                'cta_label' => ['Découvrir notre histoire', 'Discover our story', 'Unsere Geschichte entdecken', '了解我们的故事'], 'cta_url' => 'a-propos',
                'items' => [
                    ['title' => ['Chaque demande est lue et triée par notre équipe', 'Every request is read and sorted by our team', 'Jede Anfrage wird von unserem Team gelesen und eingeordnet', '每项需求都由团队阅读并分类']],
                    ['title' => ['Aucune coordonnée publiée : chaque contact passe par Sabonea', 'No contact details published: every contact goes through Sabonea', 'Keine veröffentlichten Kontaktdaten: Jeder Kontakt läuft über Sabonea', '不公开联系方式：每次联系都经由 Sabonea']],
                    ['title' => ['Un accompagnement des fournisseurs à l\'export', 'Export support for suppliers', 'Exportbegleitung für Lieferanten', '为供应商提供出口支持']],
                ],
            ],
            [
                'key' => 'sectors_preview', 'name' => 'Aperçu des secteurs', 'fields' => ['eyebrow', 'title', 'cta'],
                'eyebrow' => ['', '', '', ''],
                'title' => ['Des sites où l\'entretien ne s\'arrête jamais', 'Sites where upkeep never stops', 'Standorte, an denen die Reinigung nie stillsteht', '清洁维护从不停歇的场所'],
                'cta_label' => ['Voir tous les secteurs & équipements', 'See all sectors & equipment', 'Alle Branchen & Ausrüstungen ansehen', '查看全部行业与设备'], 'cta_url' => 'secteurs',
            ],
            [
                'key' => 'how_it_works', 'name' => 'Comment ça fonctionne (aperçu)', 'fields' => ['eyebrow', 'title', 'body', 'image', 'cta'], 'item_fields' => ['title', 'text', 'icon'],
                'image' => 'comment-mise-en-relation.jpg',
                'image_alt' => ['Mise en relation qualifiée entre acheteur et fournisseur', 'Qualified introduction between buyer and supplier', 'Qualifizierte Vermittlung zwischen Einkäufer und Lieferant', '买家与供应商之间的精准对接'],
                'eyebrow' => ['', '', '', ''],
                'title' => ['Vous décrivez le besoin, nous cherchons le fournisseur', 'You describe the need, we find the supplier', 'Sie beschreiben den Bedarf, wir suchen den Lieferanten', '您描述需求，我们寻找供应商'],
                'body' => [
                    'Sabonea n\'est pas un catalogue en libre accès. Notre équipe lit chaque demande, cherche dans son réseau le ou les fournisseurs capables d\'y répondre, puis vous les présente.',
                    'Sabonea is not a self-service catalogue. Our team reads each request, looks through its network for the supplier(s) able to meet it, then introduces them to you.',
                    'Sabonea ist kein frei durchsuchbarer Katalog. Unser Team liest jede Anfrage, sucht in seinem Netzwerk nach passenden Lieferanten und stellt sie Ihnen anschließend vor.',
                    'Sabonea 不是自助检索的目录。我们的团队会阅读每一项需求，在网络中寻找能够满足需求的供应商，然后为您引荐。',
                ],
                'cta_label' => ['Voir le parcours complet', 'See the full process', 'Den gesamten Ablauf ansehen', '查看完整流程'], 'cta_url' => 'comment-ca-fonctionne',
                'items' => [
                    ['icon' => 'fa fa-file-signature', 'title' => ['Vous décrivez le besoin', 'You describe the need', 'Sie beschreiben den Bedarf', '您描述需求'], 'text' => ['Équipement, secteur, pays, délai.', 'Equipment, sector, country, timeframe.', 'Ausrüstung, Branche, Land, Zeitrahmen.', '设备、行业、国家、时间。']],
                    ['icon' => 'fa fa-search', 'title' => ['Nous étudions la demande', 'We review the request', 'Wir prüfen die Anfrage', '我们审核需求'], 'text' => ['Et cherchons les fournisseurs adaptés dans notre réseau.', 'And look for suitable suppliers in our network.', 'Und suchen passende Lieferanten in unserem Netzwerk.', '并在网络中寻找合适的供应商。']],
                    ['icon' => 'fa fa-handshake', 'title' => ['Nous faisons les présentations', 'We make the introduction', 'Wir stellen den Kontakt her', '我们为您引荐'], 'text' => ['Vous échangez ensuite directement avec le fournisseur.', 'You then deal directly with the supplier.', 'Danach sprechen Sie direkt mit dem Lieferanten.', '之后您直接与供应商沟通。']],
                    ['icon' => 'fa fa-sync-alt', 'title' => ['Nous restons votre contact', 'We remain your contact', 'Wir bleiben Ihr Ansprechpartner', '我们始终是您的联系人'], 'text' => ['Pour chaque nouvelle recherche d\'équipement.', 'For every new equipment search.', 'Bei jeder neuen Ausrüstungssuche.', '每一次新的设备寻源都可找我们。']],
                ],
            ],
            [
                'key' => 'why', 'name' => 'Pourquoi Sabonea (en-tête)', 'fields' => ['eyebrow', 'title', 'cta'],
                'eyebrow' => ['', '', '', ''],
                'title' => ['Ce que chacun y gagne', 'What each side gains', 'Was beide Seiten davon haben', '双方各有所得'],
                'cta_label' => ['Pourquoi passer par Sabonea', 'Why go through Sabonea', 'Warum über Sabonea', '为何通过 Sabonea'], 'cta_url' => 'pourquoi-sabonea',
            ],
            [
                'key' => 'why_suppliers', 'name' => 'Pourquoi Sabonea : carte fournisseurs', 'fields' => ['title', 'body'], 'item_fields' => ['title'],
                'title' => ['Pour les fournisseurs', 'For suppliers', 'Für Lieferanten', '致供应商'],
                'body' => [
                    'Des demandes de devis triées par notre équipe, venant d\'acheteurs professionnels identifiés, et une vitrine visible à l\'étranger.',
                    'Quote requests sorted by our team, from identified professional buyers, and a showcase visible abroad.',
                    'Von unserem Team vorsortierte Angebotsanfragen identifizierter Einkäufer und ein im Ausland sichtbares Schaufenster.',
                    '由我们团队筛选、来自身份明确的专业买家的询价，以及面向海外的展示页。',
                ],
                'items' => [
                    ['title' => ['Demandes de devis triées en amont', 'Quote requests screened in advance', 'Vorab geprüfte Angebotsanfragen', '预先筛选的询价']],
                    ['title' => ['Accompagnement à l\'export', 'Export support', 'Exportbegleitung', '出口支持']],
                    ['title' => ['Vitrine publique, sans coordonnées exposées', 'Public showcase, contact details kept private', 'Öffentliches Schaufenster ohne offene Kontaktdaten', '公开展示页，不暴露联系方式']],
                ],
            ],
            [
                'key' => 'why_buyers', 'name' => 'Pourquoi Sabonea : carte acheteurs', 'fields' => ['title', 'body'], 'item_fields' => ['title'],
                'title' => ['Pour les acheteurs', 'For buyers', 'Für Einkäufer', '致买家'],
                'body' => [
                    'Une seule demande à rédiger. Notre équipe cherche le fournisseur, vérifie son sérieux et vous le présente.',
                    'One request to write. Our team finds the supplier, checks it is reliable and introduces it to you.',
                    'Nur eine Anfrage. Unser Team findet den Lieferanten, prüft seine Zuverlässigkeit und stellt ihn Ihnen vor.',
                    '只需提交一次需求。我们的团队负责寻找供应商、核实其可靠性，并为您引荐。',
                ],
                'items' => [
                    ['title' => ['Fournisseurs vérifiés avant référencement', 'Suppliers verified before listing', 'Vor der Listung geprüfte Lieferanten', '上架前经过审核的供应商']],
                    ['title' => ['Présentation directe au bon interlocuteur', 'Direct introduction to the right person', 'Direkte Vorstellung beim richtigen Ansprechpartner', '直接对接合适的负责人']],
                    ['title' => ['Le même contact pour vos prochains achats', 'The same contact for your future purchases', 'Derselbe Kontakt für künftige Beschaffungen', '今后采购仍由同一联系人跟进']],
                ],
            ],
            [
                'key' => 'cta', 'name' => 'Bannière d\'appel à l\'action', 'fields' => ['title', 'body', 'cta'],
                'title' => ['Décrivez l\'équipement dont vous avez besoin', 'Describe the equipment you need', 'Beschreiben Sie die benötigte Ausrüstung', '描述您需要的设备'],
                'body' => [
                    'Type de machine, secteur, pays, délai. Nous revenons vers vous avec un ou plusieurs fournisseurs.',
                    'Type of machine, sector, country, timeframe. We come back to you with one or more suppliers.',
                    'Maschinentyp, Branche, Land, Zeitrahmen. Wir melden uns mit einem oder mehreren Lieferanten zurück.',
                    '设备类型、行业、国家、时间。我们会为您推荐一家或多家供应商。',
                ],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'a-propos' => [
        'name' => 'À propos',
        'meta_title' => ['À propos | Sabonea', 'About us | Sabonea', 'Über uns | Sabonea', '关于我们 | Sabonea'],
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
                    'Sabonea aide les organisations qui gèrent de grands sites à trouver leurs équipements de nettoyage et de maintenance, et les fabricants de ces équipements à trouver des clients.',
                    'Sabonea helps organisations that run large sites find their cleaning and maintenance equipment, and helps the makers of that equipment find customers.',
                    'Sabonea hilft Organisationen, die große Standorte betreiben, ihre Reinigungs- und Wartungstechnik zu finden, und den Herstellern dieser Geräte, Kunden zu finden.',
                    'Sabonea 帮助管理大型场所的机构找到所需的清洁与维护设备，也帮助这些设备的制造商找到客户。',
                ],
                'body' => [
                    'Nous travaillons en priorité avec les aéroports, les hôpitaux, les collectivités et l\'industrie. Plutôt qu\'un annuaire à parcourir, nous proposons une équipe qui lit chaque demande et la transmet au fournisseur capable d\'y répondre.',
                    'We focus first on airports, hospitals, local authorities and industry. Instead of a directory to browse, we offer a team that reads every request and passes it on to the supplier able to meet it.',
                    'Unser Schwerpunkt liegt auf Flughäfen, Krankenhäusern, Kommunen und Industrie. Statt eines Verzeichnisses zum Durchblättern bieten wir ein Team, das jede Anfrage liest und an den Lieferanten weitergibt, der sie erfüllen kann.',
                    '我们重点服务机场、医院、市政和工业领域。我们提供的不是一份需要自行翻阅的名录，而是一支会阅读每项需求、并将其转交给合适供应商的团队。',
                ],
            ],
            [
                'key' => 'story', 'name' => 'Notre histoire', 'fields' => ['eyebrow', 'title', 'subtitle', 'body', 'image'],
                'image' => 'about-mission.jpg',
                'image_alt' => ['Notre histoire, une conviction portée depuis le début', 'Our story, a conviction from day one', 'Unsere Geschichte: eine Überzeugung von Anfang an', '我们的故事，始终如一的信念'],
                'eyebrow' => ['Notre histoire', 'Our story', 'Unsere Geschichte', '我们的故事'],
                'title' => ['Pourquoi j\'ai créé Sabonea', 'Why I started Sabonea', 'Warum ich Sabonea gegründet habe', '我为什么创立 Sabonea'],
                'subtitle' => [
                    '« Je voulais contribuer, à mon échelle, à des villes et des infrastructures plus propres et mieux équipées. »',
                    '“I wanted to help, in my own way, make cities and infrastructure cleaner and better equipped.”',
                    '„Ich wollte auf meine Weise dazu beitragen, dass Städte und Infrastrukturen sauberer und besser ausgestattet sind.“',
                    '“我希望以自己的方式，让城市和基础设施更洁净、装备更完善。”',
                ],
                'body' => [
                    'Jeune ingénieure, je construis Sabonea avec une conviction : une entreprise peut viser l\'international et avoir un effet concret sur la propreté des villes et des infrastructures.',
                    'As a young engineer, I am building Sabonea on one conviction: a company can aim for international growth and have a concrete effect on how clean our cities and infrastructure are.',
                    'Als junge Ingenieurin baue ich Sabonea mit einer Überzeugung auf: Ein Unternehmen kann international wachsen und zugleich konkret zu saubereren Städten und Infrastrukturen beitragen.',
                    '作为一名年轻的工程师，我怀着一个信念打造 Sabonea：一家企业可以走向国际，同时切实改善城市和基础设施的清洁状况。',
                ],
            ],
            [
                'key' => 'mission', 'name' => 'Notre mission', 'fields' => ['eyebrow', 'title', 'subtitle'], 'item_fields' => ['title', 'text', 'icon', 'variant'],
                'eyebrow' => ['Notre mission', 'Our mission', 'Unsere Mission', '我们的使命'],
                'title' => ['Trouver le bon fournisseur à la place de l\'acheteur', 'Finding the right supplier on the buyer\'s behalf', 'Den passenden Lieferanten für den Einkäufer finden', '代替买家找到合适的供应商'],
                'subtitle' => [
                    'Où que se trouvent l\'acheteur et le fournisseur. Et, côté fournisseurs, leur ouvrir des marchés à l\'export.',
                    'Wherever buyer and supplier are based. And, for suppliers, opening up export markets.',
                    'Egal, wo Einkäufer und Lieferant ansässig sind. Und für Lieferanten: neue Exportmärkte erschließen.',
                    '无论买家和供应商身在何处。同时，帮助供应商开拓出口市场。',
                ],
                'items' => [
                    ['icon' => 'fa fa-search', 'title' => ['Simplifier la recherche', 'Simplify the search', 'Die Suche vereinfachen', '简化寻源'], 'text' => [
                        'Aéroports, hôpitaux et collectivités ont besoin de fournisseurs fiables. Nous les trouvons pour eux.',
                        'Airports, hospitals and local authorities need reliable suppliers. We find them.',
                        'Flughäfen, Krankenhäuser und Kommunen brauchen zuverlässige Lieferanten. Wir finden sie.',
                        '机场、医院和市政部门需要可靠的供应商，由我们来寻找。',
                    ]],
                    ['icon' => 'fa fa-bullseye', 'variant' => 'alt-green', 'title' => ['Orienter chaque demande', 'Direct every request', 'Jede Anfrage gezielt lenken', '精准分派需求'], 'text' => [
                        'Chaque demande va au fournisseur le plus adapté : l\'acheteur n\'a pas à chercher seul.',
                        'Each request goes to the most suitable supplier: the buyer does not have to search alone.',
                        'Jede Anfrage geht an den passendsten Lieferanten: Der Einkäufer muss nicht allein suchen.',
                        '每项需求都会转给最合适的供应商，买家无需独自寻找。',
                    ]],
                    ['icon' => 'fa fa-globe', 'variant' => 'alt-orange', 'title' => ['Accompagner à l\'export', 'Support exports', 'Den Export begleiten', '助力出口'], 'text' => [
                        'Nous aidons les fournisseurs à se faire connaître d\'acheteurs à l\'étranger.',
                        'We help suppliers get known by buyers abroad.',
                        'Wir helfen Lieferanten, bei Einkäufern im Ausland bekannt zu werden.',
                        '帮助供应商被海外买家了解。',
                    ]],
                    ['icon' => 'fa fa-language', 'title' => ['Rester accessible', 'Stay accessible', 'Zugänglich bleiben', '保持易用'], 'text' => [
                        'Le site est disponible en français, anglais, allemand et chinois.',
                        'The site is available in French, English, German and Chinese.',
                        'Die Website ist auf Französisch, Englisch, Deutsch und Chinesisch verfügbar.',
                        '网站提供法语、英语、德语和中文版本。',
                    ]],
                ],
            ],
            [
                'key' => 'cta', 'name' => 'Bannière d\'appel à l\'action', 'fields' => ['title', 'body', 'cta'],
                'title' => ['Le parcours, étape par étape', 'The process, step by step', 'Der Ablauf, Schritt für Schritt', '逐步了解流程'],
                'body' => [
                    'Du formulaire de demande jusqu\'à l\'échange direct avec le fournisseur.',
                    'From the request form to direct contact with the supplier.',
                    'Vom Anfrageformular bis zum direkten Austausch mit dem Lieferanten.',
                    '从提交需求表单到与供应商直接沟通。',
                ],
                'cta_label' => $howItWorks, 'cta_url' => 'comment-ca-fonctionne',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'comment-ca-fonctionne' => [
        'name' => 'Comment ça marche',
        'meta_title' => ['Comment ça marche | Sabonea', 'How it works | Sabonea', 'So funktioniert’s | Sabonea', '运作方式 | Sabonea'],
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
                    'Vous ne parcourez pas une liste de fournisseurs. Vous décrivez votre besoin dans un formulaire ; notre équipe l\'étudie et vous présente le ou les fournisseurs capables d\'y répondre. Vous évitez ainsi de contacter des fournisseurs qui ne correspondent pas à votre besoin.',
                    'You do not browse a list of suppliers. You describe your requirement in a form; our team studies it and introduces you to the supplier(s) able to meet it. That way you avoid contacting suppliers who do not match your needs.',
                    'Sie durchsuchen keine Lieferantenliste. Sie beschreiben Ihren Bedarf in einem Formular; unser Team prüft ihn und stellt Ihnen den oder die Lieferanten vor, die ihn erfüllen können. So kontaktieren Sie keine Lieferanten, die nicht zu Ihrem Bedarf passen.',
                    '您无需浏览供应商列表。只需在表单中描述需求，我们的团队会进行研究，并为您引荐能够满足需求的供应商。这样，您就不必联系不符合需求的供应商。',
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
                    ['title' => ['Présentation du fournisseur', 'Supplier introduction', 'Vorstellung des Lieferanten', '引荐供应商'], 'text' => [
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
                        'Profil und Produkte werden auf dem Marktplatz als Schaufenster veröffentlicht (Sortimente, Zertifizierungen, abgedeckte Branchen) und sind für professionelle Einkäufer sichtbar.',
                        '企业简介与产品以展示页形式发布在平台上（产品系列、认证、覆盖行业），供专业买家浏览。',
                    ]],
                    ['title' => ['Demandes de devis', 'Quote requests', 'Angebotsanfragen', '接收询价'], 'text' => [
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
                    'À la place, un bouton **« Être mis en relation via Sabonea »** renvoie vers le formulaire de demande : chaque contact passe par notre équipe.',
                    'Instead, a **“Get introduced via Sabonea”** button leads to the request form: every contact goes through our team.',
                    'Stattdessen führt eine Schaltfläche **„Über Sabonea vermitteln lassen“** zum Anfrageformular: Jeder Kontakt läuft über unser Team.',
                    '取而代之的是一个 **“通过 Sabonea 对接”** 按钮，引导至需求表单：每一次联系都经由我们的团队。',
                ],
                'cta_label' => ['Voir un exemple de vitrine', 'See an example showcase', 'Beispiel-Schaufenster ansehen', '查看展示页示例'], 'cta_url' => 'fournisseur-exemple',
            ],
            [
                'key' => 'cta', 'name' => 'Bannière d\'appel à l\'action', 'fields' => ['title', 'body', 'cta', 'cta2'],
                'title' => ['Une question sur le parcours ?', 'A question about the process?', 'Eine Frage zum Ablauf?', '对流程有疑问？'],
                'body' => [
                    'Écrivez-nous : nous répondons aux acheteurs comme aux fournisseurs.',
                    'Write to us: we answer buyers and suppliers alike.',
                    'Schreiben Sie uns: Wir antworten Einkäufern wie Lieferanten.',
                    '欢迎来信：买家和供应商的问题我们都会解答。',
                ],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
                'cta2_label' => $contactUs, 'cta2_url' => 'contact',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'secteurs' => [
        'name' => 'Secteurs & équipements',
        'meta_title' => ['Secteurs & équipements | Sabonea', 'Sectors & equipment | Sabonea', 'Branchen & Ausrüstung | Sabonea', '行业与设备 | Sabonea'],
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
                'eyebrow' => ['', '', '', ''],
                'title' => ['Les secteurs que nous couvrons', 'The sectors we cover', 'Die Branchen, die wir abdecken', '我们覆盖的行业'],
            ],
            [
                'key' => 'equipment', 'name' => 'Types d\'équipements (en-tête de la liste)', 'fields' => ['eyebrow', 'title'],
                'eyebrow' => ['', '', '', ''],
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
        'meta_title' => ['Pourquoi Sabonea | Sabonea', 'Why Sabonea | Sabonea', 'Warum Sabonea | Sabonea', '为何选择 Sabonea | Sabonea'],
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
                'title' => ['Une vitrine à l\'étranger et des demandes déjà triées', 'A showcase abroad and requests already screened', 'Ein Schaufenster im Ausland und vorab geprüfte Anfragen', '海外展示窗口，预先筛选的询价'],
                'cta_label' => $becomeSupplier, 'cta_url' => 'devenir-fournisseur',
                'items' => [
                    ['title' => ['Une visibilité auprès d\'acheteurs professionnels étrangers, plutôt que sur des annuaires généralistes', 'Visibility with professional buyers abroad, rather than on general directories', 'Sichtbarkeit bei professionellen Einkäufern im Ausland statt in allgemeinen Verzeichnissen', '面向海外专业买家曝光，而非泛泛的综合名录']],
                    ['title' => ['Des demandes de devis venant d\'acheteurs professionnels identifiés', 'Quote requests from identified professional buyers', 'Angebotsanfragen von identifizierten professionellen Einkäufern', '来自身份明确的专业买家的询价']],
                    ['title' => ['Un accompagnement pour le développement à l\'export', 'Support for growing your exports', 'Begleitung bei der Exportentwicklung', '出口业务发展支持']],
                    ['title' => ['Une présence continue sur la plateforme, pour rester visible sur la durée', 'An ongoing presence on the platform, to stay visible over time', 'Dauerhafte Präsenz auf der Plattform, um langfristig sichtbar zu bleiben', '在平台上持续曝光，长期保持可见度']],
                ],
            ],
            [
                'key' => 'buyers', 'name' => 'Pour les acheteurs', 'fields' => ['eyebrow', 'title', 'image', 'cta'], 'item_fields' => ['title'],
                'image' => 'pourquoi-acheteurs.jpg',
                'image_alt' => ['Avantages Sabonea pour les acheteurs', 'Sabonea benefits for buyers', 'Vorteile von Sabonea für Einkäufer', 'Sabonea 为买家带来的优势'],
                'eyebrow' => ['Pour les acheteurs', 'For buyers', 'Für Einkäufer', '致买家'],
                'title' => ['Une seule demande, un seul interlocuteur', 'One request, one point of contact', 'Eine Anfrage, ein Ansprechpartner', '一次需求，一位联系人'],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
                'items' => [
                    ['title' => ['Un seul besoin exprimé, une équipe qui identifie le bon fournisseur pour vous', 'One request submitted, a team that finds the right supplier for you', 'Ein einziger gemeldeter Bedarf, ein Team, das den richtigen Lieferanten für Sie findet', '只需提交一次需求，团队为您找到合适的供应商']],
                    ['title' => ['Des fournisseurs présélectionnés et vérifiés par notre équipe', 'Suppliers pre-selected and verified by our team', 'Von unserem Team vorausgewählte und geprüfte Lieferanten', '由我们团队预选并核实的供应商']],
                    ['title' => ['Une présentation directe au fournisseur le plus pertinent', 'A direct introduction to the most relevant supplier', 'Eine direkte Vorstellung beim relevantesten Lieferanten', '直接引荐最匹配的供应商']],
                    ['title' => ['Un accompagnement dans la durée, pour tous vos besoins en équipement', 'Long-term support for all your equipment needs', 'Langfristige Begleitung für Ihren gesamten Ausrüstungsbedarf', '长期支持您的全部设备需求']],
                ],
            ],
            [
                'key' => 'cta', 'name' => 'Bannière d\'appel à l\'action', 'fields' => ['title', 'body', 'cta', 'cta2'],
                'title' => ['Acheteur ou fournisseur, tout commence par un formulaire', 'Buyer or supplier, it all starts with a form', 'Ob Einkäufer oder Lieferant: Alles beginnt mit einem Formular', '无论买家还是供应商，都从一份表单开始'],
                'body' => [
                    'Les acheteurs décrivent leur besoin, les fournisseurs présentent leur entreprise. Pour toute autre question, écrivez-nous.',
                    'Buyers describe their requirement, suppliers introduce their company. For anything else, write to us.',
                    'Einkäufer beschreiben ihren Bedarf, Lieferanten stellen ihr Unternehmen vor. Für alles andere schreiben Sie uns.',
                    '买家描述需求，供应商介绍企业。其他问题，欢迎来信。',
                ],
                'cta_label' => $expressNeed, 'cta_url' => 'expression-de-besoin',
                'cta2_label' => $contactUs, 'cta2_url' => 'contact',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'contact' => [
        'name' => 'Contact',
        'meta_title' => ['Contact | Sabonea', 'Contact | Sabonea', 'Kontakt | Sabonea', '联系我们 | Sabonea'],
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
                'eyebrow' => ['', '', '', ''],
                'title' => [
                    'Une question, une demande de partenariat, ou vous souhaitez rejoindre Sabonea en tant que fournisseur ?',
                    'A question, a partnership request, or would you like to join Sabonea as a supplier?',
                    'Eine Frage, eine Partnerschaftsanfrage oder möchten Sie Sabonea als Lieferant beitreten?',
                    '有疑问、合作意向，或希望以供应商身份加入 Sabonea？',
                ],
                'subtitle' => ['Écrivez-nous via le formulaire ou directement par e-mail.', 'Write to us using the form or directly by email.', 'Schreiben Sie uns über das Formular oder direkt per E-Mail.', '请通过表单或直接发送电子邮件与我们联系。'],
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
        'meta_title' => ['Exprimer un besoin | Sabonea', 'Submit a request | Sabonea', 'Bedarf melden | Sabonea', '提交需求 | Sabonea'],
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
                'eyebrow' => ['Pour les acheteurs', 'For buyers', 'Für Einkäufer', '致买家'],
                'title' => ['Décrivez votre besoin en quelques champs', 'Describe your requirement in a few fields', 'Beschreiben Sie Ihren Bedarf in wenigen Feldern', '只需填写几项，描述您的需求'],
                'body' => [
                    'Type d\'équipement, secteur, pays, délai souhaité : ces quelques informations suffisent à notre équipe pour commencer l\'analyse de votre demande.',
                    'Type of equipment, sector, country, desired timeframe: this information is all our team needs to start analysing your request.',
                    'Art der Ausrüstung, Branche, Land, gewünschter Zeitrahmen: Diese wenigen Angaben genügen unserem Team, um mit der Analyse Ihrer Anfrage zu beginnen.',
                    '设备类型、行业、国家、期望时间：有了这些信息，我们的团队就可以开始分析您的需求。',
                ],
                'note' => [
                    'Vos coordonnées ne sont transmises qu\'au fournisseur retenu, au moment de la mise en relation. Elles ne sont jamais publiées.',
                    'Your contact details are only passed on to the selected supplier, at the time of the introduction. They are never published.',
                    'Ihre Kontaktdaten werden nur bei der Vermittlung an den ausgewählten Lieferanten weitergegeben. Sie werden nie veröffentlicht.',
                    '您的联系方式仅在对接时提供给选定的供应商，绝不会公开。',
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
        'meta_title' => ['Exemple de vitrine fournisseur | Sabonea', 'Example supplier showcase | Sabonea', 'Beispiel eines Lieferanten-Schaufensters | Sabonea', '供应商展示页示例 | Sabonea'],
        'meta_description' => [
            'Exemple illustratif d\'une page vitrine fournisseur sur Sabonea : gammes, certifications et secteurs couverts, sans coordonnées de contact directes.',
            'Illustrative example of a supplier showcase page on Sabonea: ranges, certifications and sectors covered, without direct contact details.',
            'Beispielhaftes Lieferanten-Schaufenster bei Sabonea: Sortimente, Zertifizierungen und abgedeckte Branchen, ohne direkte Kontaktdaten.',
            'Sabonea 供应商展示页示例：产品系列、认证和覆盖行业，不含直接联系方式。',
        ],
        'breadcrumb' => ['Exemple de vitrine', 'Example showcase', 'Beispiel-Schaufenster', '展示页示例'],
        'header_image' => 'fournisseur-cover.jpg',
        'header_image_alt' => ['Bannière de la vitrine fournisseur', 'Supplier showcase banner', 'Banner des Lieferanten-Schaufensters', '供应商展示页横幅'],
        'sections' => [
            [
                'key' => 'ribbon', 'name' => 'Bandeau « exemple »', 'fields' => ['title'],
                'title' => [
                    'Exemple de vitrine fournisseur : contenu fictif, à titre de maquette',
                    'Example supplier showcase: fictitious content, for illustration only',
                    'Beispiel eines Lieferanten-Schaufensters: fiktive Inhalte, nur zur Veranschaulichung',
                    '供应商展示页示例：内容为虚构，仅作演示',
                ],
            ],
            [
                'key' => 'profile', 'name' => 'Identité du fournisseur', 'fields' => ['eyebrow', 'title', 'subtitle'],
                'eyebrow' => ['NF', 'SN', 'LN', 'NF'],
                'title' => ['Nom du fournisseur', 'Supplier name', 'Name des Lieferanten', '供应商名称'],
                'subtitle' => ['Pays d\'origine · Fournisseur abonné', 'Country of origin · Subscribed supplier', 'Herkunftsland · Lieferant im Abonnement', '原产国 · 订阅供应商'],
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
                    'Coordonnées non affichées : la mise en relation passe par Sabonea',
                    'Contact details hidden: introductions go through Sabonea',
                    'Kontaktdaten ausgeblendet: Die Vermittlung läuft über Sabonea',
                    '不显示联系方式：由 Sabonea 负责对接',
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
