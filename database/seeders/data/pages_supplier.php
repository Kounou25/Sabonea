<?php

/*
 * Pages of the supplier forms and legal pages. Translatable values are [fr, en, de, zh].
 * Legal texts are provisional: Sabonea replaces them from the back-office.
 */

$provisional = fn (string $fr, string $en, string $de, string $zh): array => [
    "<p><em>{$fr}</em></p><p>Texte provisoire : la version définitive, en cours de rédaction, sera publiée prochainement.</p>",
    "<p><em>{$en}</em></p><p>Provisional text: the final version is being drafted and will be published shortly.</p>",
    "<p><em>{$de}</em></p><p>Vorläufiger Text: Die endgültige Fassung wird derzeit erstellt und in Kürze veröffentlicht.</p>",
    "<p><em>{$zh}</em></p><p>临时文本：正式版本正在起草中，即将发布。</p>",
];

return [

    /* ------------------------------------------------------------------ */
    'devenir-fournisseur' => [
        'name' => 'Devenir fournisseur (formulaire 1)',
        'meta_title' => ['Devenir fournisseur   Sabonea', 'Become a supplier – Sabonea', 'Lieferant werden – Sabonea', '成为供应商 – Sabonea'],
        'meta_description' => [
            'Fabricant ou distributeur d\'équipements professionnels de nettoyage, d\'entretien et de maintenance ? Présentez votre entreprise à Sabonea et accédez à des acheteurs professionnels du monde entier.',
            'Manufacturer or distributor of professional cleaning, upkeep and maintenance equipment? Introduce your company to Sabonea and reach professional buyers worldwide.',
            'Hersteller oder Händler professioneller Reinigungs-, Pflege- und Wartungsausrüstung? Stellen Sie Ihr Unternehmen bei Sabonea vor und erreichen Sie professionelle Einkäufer weltweit.',
            '您是专业清洁、保养与维护设备的制造商或经销商吗？向 Sabonea 介绍您的企业，触达全球专业买家。',
        ],
        'header_title' => ['Devenir fournisseur', 'Become a supplier', 'Lieferant werden', '成为供应商'],
        'breadcrumb' => ['Devenir fournisseur', 'Become a supplier', 'Lieferant werden', '成为供应商'],
        'sections' => [
            [
                'key' => 'intro', 'name' => 'Introduction', 'fields' => ['eyebrow', 'title', 'body'],
                'eyebrow' => ['Rejoindre Sabonea', 'Join Sabonea', 'Sabonea beitreten', '加入 Sabonea'],
                'title' => ['Présentez votre entreprise en quelques minutes', 'Introduce your company in a few minutes', 'Stellen Sie Ihr Unternehmen in wenigen Minuten vor', '几分钟内介绍您的企业'],
                'body' => [
                    'Ce premier formulaire nous permet de mieux vous connaître. Notre équipe l\'étudie et revient vers vous sous 48 h. Si votre profil correspond, vous recevrez un lien privé pour compléter votre dossier d\'intégration : vos réponses y seront déjà reprises.',
                    'This first form helps us get to know you. Our team reviews it and gets back to you within 48 hours. If your profile is a good fit, you will receive a private link to complete your onboarding file, with your answers already filled in.',
                    'Mit diesem ersten Formular lernen wir Sie besser kennen. Unser Team prüft es und meldet sich innerhalb von 48 Stunden bei Ihnen. Passt Ihr Profil, erhalten Sie einen privaten Link zu Ihren Aufnahmeunterlagen – Ihre Antworten sind dort bereits übernommen.',
                    '通过这份初步表单，我们可以更好地了解您。我们的团队会在 48 小时内审核并与您联系。如果您的情况符合要求，您将收到一个专属链接，用于完善入驻资料，您已填写的回答会自动带入。',
                ],
            ],
            [
                'key' => 'benefits', 'name' => 'Pourquoi nous rejoindre', 'fields' => [], 'item_fields' => ['title', 'text', 'icon'],
                'items' => [
                    ['icon' => 'fa fa-globe-europe', 'title' => ['Une vitrine internationale', 'An international showcase', 'Ein internationales Schaufenster', '国际展示窗口'], 'text' => [
                        'Présentez vos équipements à des acheteurs professionnels du monde entier.',
                        'Present your equipment to professional buyers all over the world.',
                        'Präsentieren Sie Ihre Ausrüstung professionellen Einkäufern weltweit.',
                        '向全球专业买家展示您的设备。',
                    ]],
                    ['icon' => 'fa fa-bullseye', 'title' => ['Des demandes qualifiées', 'Qualified requests', 'Qualifizierte Anfragen', '优质询盘'], 'text' => [
                        'Chaque demande est analysée par notre équipe avant de vous être transmise.',
                        'Every request is analysed by our team before being passed on to you.',
                        'Jede Anfrage wird von unserem Team geprüft, bevor sie an Sie weitergeleitet wird.',
                        '每一项需求都经我们团队分析后再转交给您。',
                    ]],
                    ['icon' => 'fa fa-clock', 'title' => ['Une réponse sous 48 h', 'An answer within 48 hours', 'Antwort innerhalb von 48 Std.', '48 小时内回复'], 'text' => [
                        'Notre équipe étudie votre profil et revient vers vous rapidement.',
                        'Our team reviews your profile and gets back to you quickly.',
                        'Unser Team prüft Ihr Profil und meldet sich schnell bei Ihnen.',
                        '我们的团队会审核您的资料并尽快与您联系。',
                    ]],
                ],
            ],
            [
                'key' => 'thanks', 'name' => 'Message de fin', 'fields' => ['title', 'body'],
                'title' => ['Merci !', 'Thank you!', 'Vielen Dank!', '谢谢！'],
                'body' => [
                    'Merci d\'avoir pris le temps de répondre. Notre équipe vous recontactera sous 48 h pour échanger sur la suite.',
                    'Thank you for taking the time to answer. Our team will get back to you within 48 hours to discuss the next steps.',
                    'Vielen Dank, dass Sie sich die Zeit für Ihre Antworten genommen haben. Unser Team meldet sich innerhalb von 48 Stunden bei Ihnen, um die nächsten Schritte zu besprechen.',
                    '感谢您抽出时间填写。我们的团队将在 48 小时内与您联系，商讨后续事宜。',
                ],
            ],
            [
                'key' => 'next_steps', 'name' => 'Et maintenant ? (après l\'envoi)', 'fields' => ['title'], 'item_fields' => ['title', 'text'],
                'title' => ['Et maintenant ?', 'What happens next?', 'Wie geht es weiter?', '接下来'],
                'items' => [
                    ['title' => ['Étude de votre profil', 'Review of your profile', 'Prüfung Ihres Profils', '审核您的资料'], 'text' => [
                        'Notre équipe analyse vos réponses sous 48 h.',
                        'Our team reviews your answers within 48 hours.',
                        'Unser Team prüft Ihre Antworten innerhalb von 48 Stunden.',
                        '我们的团队将在 48 小时内审核您的回答。',
                    ]],
                    ['title' => ['Votre dossier d\'intégration', 'Your onboarding file', 'Ihre Aufnahmeunterlagen', '您的入驻资料'], 'text' => [
                        'Si votre profil correspond, vous recevez un lien privé, déjà pré-rempli avec vos réponses.',
                        'If your profile is a good fit, you receive a private link, already pre-filled with your answers.',
                        'Passt Ihr Profil, erhalten Sie einen privaten Link, bereits mit Ihren Antworten vorausgefüllt.',
                        '如果您的资料符合要求，您将收到一个已预填您回答的专属链接。',
                    ]],
                    ['title' => ['Mise en ligne de votre vitrine', 'Your showcase goes live', 'Ihr Schaufenster geht online', '展示页上线'], 'text' => [
                        'Votre vitrine est publiée et vous recevez des demandes de devis qualifiées.',
                        'Your showcase is published and you receive qualified quote requests.',
                        'Ihr Schaufenster wird veröffentlicht und Sie erhalten qualifizierte Angebotsanfragen.',
                        '您的展示页将发布上线，并开始接收优质询价。',
                    ]],
                ],
            ],
            [
                'key' => 'closed', 'name' => 'Formulaire pas encore ouvert', 'fields' => ['title', 'body', 'cta'],
                'title' => ['Formulaire bientôt disponible', 'Form coming soon', 'Formular in Kürze verfügbar', '表单即将开放'],
                'body' => [
                    'Le formulaire d\'inscription des fournisseurs ouvrira très prochainement. En attendant, vous pouvez nous écrire depuis la page Contact.',
                    'The supplier registration form will open very soon. In the meantime, you can write to us from the Contact page.',
                    'Das Anmeldeformular für Lieferanten wird in Kürze freigeschaltet. Bis dahin können Sie uns über die Kontaktseite schreiben.',
                    '供应商注册表单即将开放。在此期间，您可以通过联系页面与我们联系。',
                ],
                'cta_label' => ['Nous contacter', 'Contact us', 'Kontakt aufnehmen', '联系我们'], 'cta_url' => 'contact',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'integration-fournisseur' => [
        'name' => 'Dossier d\'intégration fournisseur (formulaire 2)',
        'noindex' => true,
        'meta_title' => ['Dossier d\'intégration fournisseur   Sabonea', 'Supplier onboarding file – Sabonea', 'Aufnahmeunterlagen für Lieferanten – Sabonea', '供应商入驻资料 – Sabonea'],
        'meta_description' => [
            'Espace privé de constitution du dossier d\'intégration des fournisseurs Sabonea.',
            'Private area for Sabonea suppliers to complete their onboarding file.',
            'Privater Bereich für die Aufnahmeunterlagen der Sabonea-Lieferanten.',
            'Sabonea 供应商填写入驻资料的专属页面。',
        ],
        'header_title' => ['Dossier d\'intégration fournisseur', 'Supplier onboarding file', 'Aufnahmeunterlagen für Lieferanten', '供应商入驻资料'],
        'breadcrumb' => ['Dossier d\'intégration', 'Onboarding file', 'Aufnahmeunterlagen', '入驻资料'],
        'sections' => [
            [
                'key' => 'intro', 'name' => 'Introduction', 'fields' => ['eyebrow', 'title', 'body'],
                'eyebrow' => ['Espace fournisseur', 'Supplier area', 'Lieferantenbereich', '供应商专区'],
                'title' => ['Complétez votre dossier d\'intégration', 'Complete your onboarding file', 'Vervollständigen Sie Ihre Aufnahmeunterlagen', '完善您的入驻资料'],
                'body' => [
                    'Certaines réponses sont déjà reprises de votre premier formulaire : vérifiez-les et corrigez-les si besoin. Vos réponses sont enregistrées au fur et à mesure.',
                    'Some answers are taken from your first form: check them and correct them if needed. Your answers are saved as you go.',
                    'Einige Antworten wurden aus Ihrem ersten Formular übernommen: Prüfen und korrigieren Sie sie bei Bedarf. Ihre Antworten werden fortlaufend gespeichert.',
                    '部分回答已从您的第一份表单中带入，请核对并按需修改。您的回答会随时自动保存。',
                ],
            ],
            [
                'key' => 'thanks', 'name' => 'Message de fin', 'fields' => ['title', 'body'],
                'title' => ['Merci pour votre confiance', 'Thank you for your trust', 'Vielen Dank für Ihr Vertrauen', '感谢您的信任'],
                'body' => [
                    'Merci pour la confiance accordée. L\'équipe Sabonea étudiera votre dossier et vous recontactera pour finaliser votre intégration.',
                    'Thank you for your trust. The Sabonea team will review your file and get back to you to finalise your onboarding.',
                    'Vielen Dank für Ihr Vertrauen. Das Sabonea-Team prüft Ihre Unterlagen und meldet sich, um Ihre Aufnahme abzuschließen.',
                    '感谢您的信任。Sabonea 团队将审核您的资料，并与您联系以完成入驻。',
                ],
            ],
            [
                'key' => 'next_steps', 'name' => 'Et maintenant ? (après l\'envoi)', 'fields' => ['title'], 'item_fields' => ['title', 'text'],
                'title' => ['Et maintenant ?', 'What happens next?', 'Wie geht es weiter?', '接下来'],
                'items' => [
                    ['title' => ['Étude de votre dossier', 'Review of your file', 'Prüfung Ihrer Unterlagen', '审核您的资料'], 'text' => [
                        'L\'équipe Sabonea vérifie vos informations et vos documents.',
                        'The Sabonea team checks your information and documents.',
                        'Das Sabonea-Team prüft Ihre Angaben und Unterlagen.',
                        'Sabonea 团队将核实您的信息和文件。',
                    ]],
                    ['title' => ['Validation de votre intégration', 'Approval of your onboarding', 'Bestätigung Ihrer Aufnahme', '确认入驻'], 'text' => [
                        'Nous revenons vers vous pour finaliser les conditions de partenariat.',
                        'We get back to you to finalise the partnership terms.',
                        'Wir melden uns bei Ihnen, um die Partnerschaftsbedingungen abzuschließen.',
                        '我们将与您联系，确定合作条款。',
                    ]],
                    ['title' => ['Publication de votre vitrine', 'Your showcase goes live', 'Ihr Schaufenster geht online', '展示页上线'], 'text' => [
                        'Vos produits deviennent visibles par les acheteurs professionnels.',
                        'Your products become visible to professional buyers.',
                        'Ihre Produkte werden für professionelle Einkäufer sichtbar.',
                        '您的产品将展示给专业买家。',
                    ]],
                ],
            ],
            [
                'key' => 'invalid', 'name' => 'Lien invalide ou expiré', 'fields' => ['title', 'body', 'cta'],
                'title' => ['Lien invalide ou expiré', 'Invalid or expired link', 'Ungültiger oder abgelaufener Link', '链接无效或已过期'],
                'body' => [
                    'Ce lien n\'est plus valable. Contactez l\'équipe Sabonea pour recevoir un nouveau lien vers votre dossier.',
                    'This link is no longer valid. Contact the Sabonea team to receive a new link to your file.',
                    'Dieser Link ist nicht mehr gültig. Wenden Sie sich an das Sabonea-Team, um einen neuen Link zu Ihren Unterlagen zu erhalten.',
                    '该链接已失效。请联系 Sabonea 团队获取新的资料链接。',
                ],
                'cta_label' => ['Nous contacter', 'Contact us', 'Kontakt aufnehmen', '联系我们'], 'cta_url' => 'contact',
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'politique-de-confidentialite' => [
        'name' => 'Politique de confidentialité',
        'meta_title' => ['Politique de confidentialité   Sabonea', 'Privacy policy – Sabonea', 'Datenschutzerklärung – Sabonea', '隐私政策 – Sabonea'],
        'header_title' => ['Politique de confidentialité', 'Privacy policy', 'Datenschutzerklärung', '隐私政策'],
        'breadcrumb' => ['Politique de confidentialité', 'Privacy policy', 'Datenschutzerklärung', '隐私政策'],
        'sections' => [
            [
                'key' => 'content', 'name' => 'Texte de la page', 'fields' => ['content'],
                'body' => $provisional(
                    'Cette page présentera la manière dont Sabonea collecte, utilise et protège les données personnelles transmises via le site et ses formulaires.',
                    'This page will explain how Sabonea collects, uses and protects the personal data submitted through the website and its forms.',
                    'Diese Seite wird erläutern, wie Sabonea die über die Website und ihre Formulare übermittelten personenbezogenen Daten erhebt, verwendet und schützt.',
                    '本页面将说明 Sabonea 如何收集、使用和保护通过网站及表单提交的个人数据。',
                ),
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'conditions-fournisseurs' => [
        'name' => 'Conditions de partenariat fournisseur',
        'meta_title' => ['Conditions de partenariat fournisseur   Sabonea', 'Supplier partnership terms – Sabonea', 'Partnerschaftsbedingungen für Lieferanten – Sabonea', '供应商合作条款 – Sabonea'],
        'header_title' => ['Conditions de partenariat fournisseur', 'Supplier partnership terms', 'Partnerschaftsbedingungen für Lieferanten', '供应商合作条款'],
        'breadcrumb' => ['Conditions fournisseurs', 'Supplier terms', 'Lieferantenbedingungen', '供应商条款'],
        'sections' => [
            [
                'key' => 'content', 'name' => 'Texte de la page', 'fields' => ['content'],
                'body' => $provisional(
                    'Cette page présentera les conditions de partenariat entre Sabonea et ses fournisseurs, dont les modalités d\'abonnement à la page vitrine.',
                    'This page will set out the partnership terms between Sabonea and its suppliers, including the subscription terms of the showcase page.',
                    'Diese Seite wird die Partnerschaftsbedingungen zwischen Sabonea und seinen Lieferanten darlegen, einschließlich der Abonnementbedingungen für das Schaufenster.',
                    '本页面将说明 Sabonea 与供应商之间的合作条款，包括展示页的订阅条款。',
                ),
            ],
        ],
    ],

    /* ------------------------------------------------------------------ */
    'mentions-legales' => [
        'name' => 'Mentions légales',
        'meta_title' => ['Mentions légales   Sabonea', 'Legal notice – Sabonea', 'Impressum – Sabonea', '法律声明 – Sabonea'],
        'header_title' => ['Mentions légales', 'Legal notice', 'Impressum', '法律声明'],
        'breadcrumb' => ['Mentions légales', 'Legal notice', 'Impressum', '法律声明'],
        'sections' => [
            [
                'key' => 'content', 'name' => 'Texte de la page', 'fields' => ['content'],
                'body' => $provisional(
                    'Cette page présentera l\'éditeur du site, son hébergeur et les informations légales de Sabonea.',
                    'This page will present the publisher of the website, its host and Sabonea\'s legal information.',
                    'Diese Seite wird den Herausgeber der Website, den Hosting-Anbieter und die rechtlichen Angaben zu Sabonea enthalten.',
                    '本页面将介绍网站发布者、托管服务商以及 Sabonea 的法律信息。',
                ),
            ],
        ],
    ],
];
