<?php

/*
 * Choice lists of the supplier forms: list => [[key, [fr, en, de, zh], flag?], ...].
 * Flags: "other" (asks for a free text when selected), "exclusive" (cannot be combined, e.g. "None").
 * Keys are fixed: answers and exports store them; labels can be edited from the back-office.
 */

$yes = ['Oui', 'Yes', 'Ja', '是'];
$no = ['Non', 'No', 'Nein', '否'];
$maybe = ['Peut-être', 'Maybe', 'Vielleicht', '也许'];
$other = ['Autre', 'Other', 'Sonstiges', '其他'];
$dependsOnModel = ['Selon le modèle', 'Depends on the model', 'Je nach Modell', '视型号而定'];

return [
    // ---------------------------------------------------------------- Form 1
    'company_age' => [
        ['AGE_LT_2', ['Moins de 2 ans', 'Less than 2 years', 'Weniger als 2 Jahre', '不足 2 年']],
        ['AGE_2_5', ['2 à 5 ans', '2 to 5 years', '2 bis 5 Jahre', '2 至 5 年']],
        ['AGE_5_10', ['5 à 10 ans', '5 to 10 years', '5 bis 10 Jahre', '5 至 10 年']],
        ['AGE_GT_10', ['Plus de 10 ans', 'More than 10 years', 'Mehr als 10 Jahre', '10 年以上']],
    ],
    'sales_languages' => [
        ['LANG_EN', ['Anglais', 'English', 'Englisch', '英语']],
        ['LANG_FR', ['Français', 'French', 'Französisch', '法语']],
        ['LANG_ZH', ['Chinois', 'Chinese', 'Chinesisch', '中文']],
        ['LANG_DE', ['Allemand', 'German', 'Deutsch', '德语']],
        ['LANG_ES', ['Espagnol', 'Spanish', 'Spanisch', '西班牙语']],
        ['LANG_AR', ['Arabe', 'Arabic', 'Arabisch', '阿拉伯语']],
        ['LANG_OTHER', $other, 'other'],
    ],
    'supplier_type' => [
        ['MANUFACTURER', ['Fabricant', 'Manufacturer', 'Hersteller', '制造商']],
        ['DISTRIBUTOR', ['Distributeur', 'Distributor', 'Händler', '经销商']],
        ['MANUFACTURER_DISTRIBUTOR', ['Fabricant et distributeur', 'Manufacturer and distributor', 'Hersteller und Händler', '制造商兼经销商']],
    ],
    'exports' => [
        ['YES', $yes],
        ['NO', $no],
        ['OCCASIONALLY', ['Occasionnellement', 'Occasionally', 'Gelegentlich', '偶尔']],
    ],
    'interest_level' => [
        ['VERY_INTERESTED', ['Très intéressé', 'Very interested', 'Sehr interessiert', '非常感兴趣']],
        ['INTERESTED', ['Intéressé', 'Interested', 'Interessiert', '感兴趣']],
        ['MAYBE', $maybe],
        ['NOT_INTERESTED', ['Pas intéressé', 'Not interested', 'Nicht interessiert', '不感兴趣']],
    ],
    'rfq_ready' => [
        ['YES', $yes],
        ['NO', $no],
        ['MAYBE', $maybe],
    ],
    'contact_role' => [
        ['CEO_FOUNDER', ['CEO / Fondateur', 'CEO / Founder', 'CEO / Gründer', '首席执行官 / 创始人']],
        ['SALES_EXPORT_MANAGER', ['Sales / Export Manager', 'Sales / Export Manager', 'Vertriebs- / Exportleiter', '销售 / 出口经理']],
        ['SALES_REPRESENTATIVE', ['Commercial', 'Sales representative', 'Vertriebsmitarbeiter', '销售代表']],
        ['OTHER', $other, 'other'],
    ],
    'preferred_channel' => [
        ['EMAIL', ['E-mail', 'Email', 'E-Mail', '电子邮件']],
        ['WHATSAPP', ['WhatsApp', 'WhatsApp', 'WhatsApp', 'WhatsApp']],
        ['PHONE', ['Téléphone', 'Phone', 'Telefon', '电话']],
    ],
    'preferred_language' => [
        ['LANG_EN', ['Anglais', 'English', 'Englisch', '英语']],
        ['LANG_FR', ['Français', 'French', 'Französisch', '法语']],
        ['LANG_ZH', ['Chinois', 'Chinese', 'Chinesisch', '中文']],
        ['LANG_DE', ['Allemand', 'German', 'Deutsch', '德语']],
    ],
    'source' => [
        ['DIRECT_CONTACT', ['Contact direct', 'Direct contact', 'Direkter Kontakt', '直接联系']],
        ['LINKEDIN', ['LinkedIn', 'LinkedIn', 'LinkedIn', 'LinkedIn']],
        ['GOOGLE', ['Google', 'Google', 'Google', 'Google']],
        ['TRADE_SHOW', ['Salon ou événement', 'Trade show or event', 'Messe oder Veranstaltung', '展会或活动']],
        ['REFERRAL', ['Recommandation', 'Referral', 'Empfehlung', '推荐']],
        ['OTHER', $other, 'other'],
    ],

    // ---------------------------------------------------------------- Form 2
    'headcount' => [
        ['HEADCOUNT_1_10', ['1 à 10', '1 to 10', '1 bis 10', '1 至 10 人']],
        ['HEADCOUNT_11_50', ['11 à 50', '11 to 50', '11 bis 50', '11 至 50 人']],
        ['HEADCOUNT_51_200', ['51 à 200', '51 to 200', '51 bis 200', '51 至 200 人']],
        ['HEADCOUNT_201_500', ['201 à 500', '201 to 500', '201 bis 500', '201 至 500 人']],
        ['HEADCOUNT_GT_500', ['Plus de 500', 'More than 500', 'Mehr als 500', '500 人以上']],
    ],
    'annual_revenue' => [
        ['REVENUE_LT_1M', ['Moins de 1 M€', 'Less than €1M', 'Weniger als 1 Mio. €', '少于 100 万欧元']],
        ['REVENUE_1_5M', ['1 à 5 M€', '€1M to €5M', '1 bis 5 Mio. €', '100 万至 500 万欧元']],
        ['REVENUE_5_20M', ['5 à 20 M€', '€5M to €20M', '5 bis 20 Mio. €', '500 万至 2000 万欧元']],
        ['REVENUE_20_100M', ['20 à 100 M€', '€20M to €100M', '20 bis 100 Mio. €', '2000 万至 1 亿欧元']],
        ['REVENUE_GT_100M', ['Plus de 100 M€', 'More than €100M', 'Mehr als 100 Mio. €', '超过 1 亿欧元']],
        ['REVENUE_UNDISCLOSED', ['Préfère ne pas répondre', 'Prefer not to say', 'Keine Angabe', '不便透露']],
    ],
    'customization' => [
        ['YES', $yes],
        ['NO', $no],
        ['DEPENDS_ON_MODEL', $dependsOnModel],
    ],
    'production_mode' => [
        ['SERIES', ['En série', 'Series production', 'Serienfertigung', '批量生产']],
        ['MADE_TO_ORDER', ['Sur commande', 'Made to order', 'Auftragsfertigung', '按订单生产']],
        ['BOTH', ['Les deux', 'Both', 'Beides', '两者皆有']],
    ],
    'certifications' => [
        ['CERT_CE', ['CE / Marquage européen', 'CE / European marking', 'CE / Europäische Kennzeichnung', 'CE / 欧盟认证']],
        ['CERT_ISO_9001', ['ISO 9001', 'ISO 9001', 'ISO 9001', 'ISO 9001']],
        ['CERT_ISO_14001', ['ISO 14001', 'ISO 14001', 'ISO 14001', 'ISO 14001']],
        ['CERT_ISO_45001', ['ISO 45001', 'ISO 45001', 'ISO 45001', 'ISO 45001']],
        ['CERT_AIRPORT', ['Conformité aux exigences aéroportuaires (préciser)', 'Compliance with airport requirements (please specify)', 'Konformität mit Flughafenanforderungen (bitte angeben)', '符合机场要求（请说明）'], 'other'],
        ['CERT_OTHER', $other, 'other'],
        ['CERT_NONE', ['Aucune', 'None', 'Keine', '无'], 'exclusive'],
    ],
    'warranty_duration' => [
        ['WARRANTY_LT_1Y', ['Moins d\'1 an', 'Less than 1 year', 'Weniger als 1 Jahr', '不足 1 年']],
        ['WARRANTY_1Y', ['1 an', '1 year', '1 Jahr', '1 年']],
        ['WARRANTY_2Y', ['2 ans', '2 years', '2 Jahre', '2 年']],
        ['WARRANTY_3Y_PLUS', ['3 ans ou plus', '3 years or more', '3 Jahre oder mehr', '3 年及以上']],
        ['DEPENDS_ON_MODEL', $dependsOnModel],
    ],
    'after_sales' => [
        ['YES_CONTRACT', ['Oui, avec contrat dédié', 'Yes, with a dedicated contract', 'Ja, mit eigenem Vertrag', '是，签订专项合同']],
        ['YES_ON_REQUEST', ['Oui, sur demande', 'Yes, on request', 'Ja, auf Anfrage', '是，按需提供']],
        ['YES_PARTNERS', ['Oui, via un réseau de partenaires', 'Yes, through a partner network', 'Ja, über ein Partnernetz', '是，通过合作伙伴网络']],
        ['NO', $no],
    ],
    'spare_parts' => [
        ['YES', $yes],
        ['YES_LIMITED', ['Oui, pendant une durée définie (préciser)', 'Yes, for a defined period (please specify)', 'Ja, für einen bestimmten Zeitraum (bitte angeben)', '是，在规定期限内（请说明）'], 'other'],
        ['NO', $no],
    ],
    'remote_support' => [
        ['YES', $yes],
        ['NO', $no],
        ['DEPENDS_ON_CONTRACT', ['Selon le contrat', 'Depends on the contract', 'Je nach Vertrag', '视合同而定']],
    ],
    'service_network' => [
        ['YES', ['Oui (préciser les pays)', 'Yes (please list the countries)', 'Ja (bitte Länder angeben)', '是（请注明国家）']],
        ['NO', $no],
        ['IN_PROGRESS', ['En cours de développement', 'Being developed', 'Im Aufbau', '正在建设中']],
    ],
    'yes_no' => [
        ['YES', $yes],
        ['NO', $no],
    ],
    'lead_time' => [
        ['LEAD_TIME_LT_1W', ['Moins d\'une semaine', 'Less than a week', 'Weniger als eine Woche', '不到一周']],
        ['LEAD_TIME_1_4W', ['1 à 4 semaines', '1 to 4 weeks', '1 bis 4 Wochen', '1 至 4 周']],
        ['LEAD_TIME_1_3M', ['1 à 3 mois', '1 to 3 months', '1 bis 3 Monate', '1 至 3 个月']],
        ['LEAD_TIME_GT_3M', ['Plus de 3 mois', 'More than 3 months', 'Mehr als 3 Monate', '3 个月以上']],
        ['DEPENDS_ON_MODEL', $dependsOnModel],
    ],
    'production_ownership' => [
        ['IN_HOUSE', ['Production interne', 'In-house production', 'Eigenfertigung', '自主生产']],
        ['PARTIAL_SUBCONTRACTING', ['Sous-traitance partielle', 'Partial subcontracting', 'Teilweise Fremdfertigung', '部分外包']],
        ['FULL_SUBCONTRACTING', ['Sous-traitance totale', 'Full subcontracting', 'Vollständige Fremdfertigung', '完全外包']],
    ],
    'pricing_model' => [
        ['FIXED_PRICES', ['Prix fixes', 'Fixed prices', 'Festpreise', '固定价格']],
        ['VOLUME_PRICING', ['Grille tarifaire selon quantité', 'Volume-based price list', 'Mengenabhängige Preisliste', '按数量阶梯定价']],
        ['CUSTOM_QUOTE', ['Devis personnalisé', 'Custom quote', 'Individuelles Angebot', '定制报价']],
        ['MIXED', ['Mixte', 'Mixed', 'Gemischt', '混合']],
    ],
    'currency' => [
        ['EUR', ['EUR', 'EUR', 'EUR', 'EUR']],
        ['USD', ['USD', 'USD', 'USD', 'USD']],
        ['GBP', ['GBP', 'GBP', 'GBP', 'GBP']],
        ['CNY', ['CNY', 'CNY', 'CNY', 'CNY']],
        ['OTHER', $other, 'other'],
    ],
    'payment_methods' => [
        ['BANK_TRANSFER', ['Virement bancaire', 'Bank transfer', 'Banküberweisung', '银行转账']],
        ['LETTER_OF_CREDIT', ['Lettre de crédit (L/C)', 'Letter of credit (L/C)', 'Akkreditiv (L/C)', '信用证（L/C）']],
        ['CARD', ['Carte bancaire', 'Bank card', 'Bankkarte', '银行卡']],
        ['PAYPAL', ['PayPal', 'PayPal', 'PayPal', 'PayPal']],
        ['OTHER', $other, 'other'],
    ],
    'payment_terms' => [
        ['CASH', ['Comptant', 'Cash', 'Sofort', '现付']],
        ['NET_30', ['30 jours', '30 days', '30 Tage', '30 天']],
        ['NET_30_EOM', ['30 jours fin de mois', '30 days end of month', '30 Tage zum Monatsende', '月结 30 天']],
        ['NET_60', ['60 jours', '60 days', '60 Tage', '60 天']],
        ['NET_90', ['90 jours', '90 days', '90 Tage', '90 天']],
        ['OTHER', $other, 'other'],
    ],
    'volume_discounts' => [
        ['YES_TIERS', ['Oui, avec paliers', 'Yes, with tiers', 'Ja, mit Staffeln', '是，按阶梯']],
        ['NO', $no],
    ],
    'quote_response_time' => [
        ['LT_24H', ['Moins de 24 h', 'Less than 24 h', 'Weniger als 24 Std.', '24 小时内']],
        ['DAYS_1_3', ['1 à 3 jours', '1 to 3 days', '1 bis 3 Tage', '1 至 3 天']],
        ['DAYS_3_7', ['3 à 7 jours', '3 to 7 days', '3 bis 7 Tage', '3 至 7 天']],
        ['GT_7_DAYS', ['Plus de 7 jours', 'More than 7 days', 'Mehr als 7 Tage', '7 天以上']],
    ],
    'incoterms' => [
        ['EXW', ['EXW', 'EXW', 'EXW', 'EXW']],
        ['FOB', ['FOB', 'FOB', 'FOB', 'FOB']],
        ['CIF', ['CIF', 'CIF', 'CIF', 'CIF']],
        ['DAP', ['DAP', 'DAP', 'DAP', 'DAP']],
        ['DDP', ['DDP', 'DDP', 'DDP', 'DDP']],
        ['OTHER', $other, 'other'],
    ],
    'transport_responsibility' => [
        ['SUPPLIER', ['Fournisseur', 'Supplier', 'Lieferant', '供应商']],
        ['BUYER', ['Acheteur', 'Buyer', 'Käufer', '买家']],
        ['DEPENDS_ON_AGREEMENT', ['Selon l\'accord', 'Depends on the agreement', 'Je nach Vereinbarung', '视协议而定']],
    ],
    'collaboration_type' => [
        ['PRODUCT_LISTING', ['Référencement de vos produits', 'Listing of your products', 'Listung Ihrer Produkte', '产品上架展示']],
        ['LEAD_GENERATION', ['Génération de leads', 'Lead generation', 'Lead-Generierung', '获取潜在客户']],
        ['QUOTE_REQUESTS', ['Demandes de devis', 'Quote requests', 'Angebotsanfragen', '询价请求']],
        ['INTERNATIONAL_GROWTH', ['Développement à l\'international', 'International growth', 'Internationale Expansion', '国际化发展']],
        ['NEW_MARKETS', ['Accès à de nouveaux marchés', 'Access to new markets', 'Zugang zu neuen Märkten', '开拓新市场']],
        ['LONG_TERM_PARTNERSHIP', ['Partenariat long terme', 'Long-term partnership', 'Langfristige Partnerschaft', '长期合作']],
        ['OTHER', $other, 'other'],
    ],
    'price_display' => [
        ['INDICATIVE_PRICES', ['Prix indicatifs affichés', 'Indicative prices displayed', 'Richtpreise anzeigen', '显示参考价格']],
        ['PRICE_RANGE', ['Fourchette de prix affichée', 'Price range displayed', 'Preisspanne anzeigen', '显示价格区间']],
        ['QUOTE_ONLY', ['Sur devis uniquement', 'On quotation only', 'Nur auf Anfrage', '仅限询价']],
    ],
];
