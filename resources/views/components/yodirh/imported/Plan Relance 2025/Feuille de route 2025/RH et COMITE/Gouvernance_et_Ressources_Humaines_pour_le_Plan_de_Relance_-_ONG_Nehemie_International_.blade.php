<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gouvernance et Ressources Humaines pour le Plan de Relance - ONG Néhémie International</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Si vous n'utilisez pas FontAwesome, vous pouvez supprimer la ligne suivante -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css"> -->
    <style>
        :root {
            --brown-deep: #5D4037;
            /* --red: #D13B18;  Couleur rouge supprimée des utilisations directes */
            --orange: #FF8C26;
            --blue-sky: #5DAEDF; /* Attention: contraste faible avec texte blanc */
            --beige: #F5F0E8;
            --blue-dark: #2B5C8E;
            --grey: #666666;
            --ochre: #C78A44;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            background-color: var(--beige);
            color: #333;
            line-height: 1.6;
        }
        /* Utilisation des balises sémantiques pour les styles principaux */
        main.container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header.page-header { /* Renommé pour éviter conflit avec <header> HTML */
            background-color: var(--brown-deep);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .section-title {
            background-color: var(--brown-deep);
            color: white;
            padding: 10px 15px;
            margin: 30px 0 20px;
            border-radius: 5px;
        }
        article.card, article.committee { /* Appliqué à article */
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            overflow: hidden;
        }
        .card-header, .committee-header {
            padding: 15px 20px;
            color: white;
            font-weight: bold;
        }
        /* Couleurs des en-têtes de carte */
        .card-header-1 { background-color: var(--brown-deep); } /* Rouge remplacé par brown-deep */
        .card-header-2 { background-color: var(--orange); }
        .card-header-3 { background-color: var(--blue-sky); } /* Texte blanc ici a un faible contraste */
        .card-header-4 { background-color: var(--blue-dark); }
        .card-header-5 { background-color: var(--ochre); }
        .card-header-6 { background-color: var(--grey); }
        
        .card-body, .committee-body { padding: 20px; }

        .card-subheader, .committee-subheader {
            font-weight: bold;
            color: var(--brown-deep);
            margin-top: 15px;
            margin-bottom: 5px;
            border-bottom: 2px solid var(--brown-deep);
            padding-bottom: 5px;
        }
        /* .committee-subheader n'avait pas de bordure, ajout si voulu, sinon garder séparé */
        .committee-subheader {
             border-bottom: none; /* Ou ajustez selon le design souhaité */
        }

        blockquote.quote {
            background-color: var(--beige);
            border-left: 5px solid var(--brown-deep);
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
        }
        blockquote.quote footer {
            font-style: normal;
            text-align: right;
            margin-top: 10px;
            font-size: 0.9em;
            color: var(--grey);
        }
        blockquote.quote footer cite {
            font-style: italic;
        }
        
        /* Couleurs des en-têtes de comité */
        .committee-header-1 { background-color: var(--brown-deep); } /* Rouge remplacé par brown-deep */
        .committee-header-2 { background-color: var(--orange); }
        .committee-header-3 { background-color: var(--blue-sky); } /* Texte blanc ici a un faible contraste */
        .committee-header-4 { background-color: var(--blue-dark); }
        .committee-header-5 { background-color: var(--ochre); }

        .coordination {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .coordination-title {
            color: var(--brown-deep);
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 2px solid var(--brown-deep);
            padding-bottom: 5px;
        }
        footer.page-footer { /* Renommé pour éviter conflit avec <footer> HTML */
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            border-top: 1px solid var(--brown-deep);
            color: var(--grey);
        }
        section.conclusion {
            background-color: var(--beige);
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            border: 1px solid var(--brown-deep);
        }
        ul {
            list-style-type: disc;
            padding-left: 20px;
            margin-bottom: 10px;
        }
        ul li {
            margin-bottom: 5px;
        }
        .bible-verse {
            text-align: center;
            font-style: italic;
            margin: 30px 0;
            color: var(--brown-deep);
        }
    </style>
</head>
<body>
    <header class="page-header">
        <h1 class="text-3xl font-bold">NÉHÉMIE International</h1>
        <h2 class="text-2xl mt-2">Gouvernance et Ressources Humaines</h2>
        <p class="mt-2">Plan de Relance - ONG Néhémie International</p>
        <p class="text-sm mt-4">Document préparé pour la période de relance: 1er juin - 31 décembre 2025</p>
    </header>

    <main class="container">
        <section aria-labelledby="introduction-title">
            <h2 id="introduction-title" class="text-2xl font-bold text-gray-800">Introduction</h2>
            <p class="my-4">Ce document présente la structure de gouvernance et les ressources humaines nécessaires pour assurer le succès du plan de relance de l'ONG Néhémie International. Face aux défis actuels, la mise en place d'une équipe compétente et d'une gouvernance rigoureuse est essentielle pour redresser la situation et revitaliser notre mission.</p>
            
            <blockquote class="quote">
                <p>"Levons-nous et bâtissons!"</p>
                <footer><cite>Néhémie 2:18</cite></footer>
            </blockquote>
            
            <p class="my-4">Les profils et comités décrits ci-dessous ont été spécifiquement conçus pour répondre aux besoins actuels de l'ONG, en combinant expertise professionnelle et ancrage dans les valeurs chrétiennes qui fondent notre identité.</p>
        </section>

        <section aria-labelledby="profils-types-title">
            <h2 id="profils-types-title" class="section-title text-xl">I. PROFILS TYPES DES RESPONSABLES D'AXES DU PLAN DE RELANCE</h2>
            
            <article class="card">
                <div class="card-header card-header-1">1. Responsable Financier</div>
                <div class="card-body">
                    <h3 class="card-subheader">Formation minimale requise</h3>
                    <p>Master en finances, comptabilité ou gestion. Une certification en comptabilité (DESCOGEF, DESCF, CPA) ou diplôme d'expertise comptable serait un atout. Minimum 5 ans d'expérience professionnelle dont 2 ans dans le secteur associatif ou à but non lucratif.</p>
                    
                    <h3 class="card-subheader">Mission principale</h3>
                    <p>Superviser l'assainissement financier et mettre en place un nouveau modèle économique viable.</p>
                    
                    <h3 class="card-subheader">Compétences clés</h3>
                    <ul>
                        <li>Formation en finances, comptabilité ou gestion</li>
                        <li>Expérience en redressement d'organisations en difficulté</li>
                        <li>Connaissance des spécificités fiscales des ONG</li>
                        <li>Maîtrise des techniques de collecte de fonds et de diversification des ressources</li>
                    </ul>
                    
                    <h3 class="card-subheader">Responsabilités spécifiques</h3>
                    <ul>
                        <li>Établir un diagnostic financier complet et un plan d'apurement des dettes</li>
                        <li>Instaurer des procédures de gestion financière transparentes</li>
                        <li>Superviser la mise en place de nouvelles stratégies de collecte de fonds</li>
                        <li>Assurer les relations avec les institutions financières et bailleurs de fonds</li>
                    </ul>
                    
                    <h3 class="card-subheader">Qualités personnelles</h3>
                    <ul>
                        <li>Rigueur et intégrité exemplaires</li>
                        <li>Capacité à prendre des décisions difficiles</li>
                        <li>Aptitude à vulgariser les enjeux financiers auprès des non-spécialistes</li>
                        <li>Sensibilité aux valeurs chrétiennes et à la mission de l'ONG</li>
                    </ul>
                </div>
            </article>

            <article class="card">
                <div class="card-header card-header-2">2. Responsable Administrative et Juridique</div>
                <div class="card-body">
                    <h3 class="card-subheader">Formation minimale requise</h3>
                    <p>Licence en droit, idéalement avec une spécialisation en droit des associations ou des organisations à but non lucratif. Une formation complémentaire en gestion administrative serait appréciée. Minimum 3 ans d'expérience en gestion administrative d'organisations.</p>
                    
                    <h3 class="card-subheader">Mission principale</h3>
                    <p>Régulariser la situation administrative et optimiser les processus organisationnels.</p>
                    
                    <h3 class="card-subheader">Compétences clés</h3>
                    <ul>
                        <li>Formation juridique ou en administration des organisations</li>
                        <li>Connaissance du cadre légal régissant les ONG au Gabon</li>
                        <li>Expérience en gestion documentaire et archivage</li>
                        <li>Pratique des démarches administratives liées aux associations</li>
                    </ul>
                    
                    <h3 class="card-subheader">Responsabilités spécifiques</h3>
                    <ul>
                        <li>Mettre à jour tous les documents légaux (statuts, règlement intérieur)</li>
                        <li>Régulariser les relations avec les autorités de tutelle</li>
                        <li>Concevoir et déployer un système d'archivage efficace</li>
                        <li>Élaborer les procédures internes et le manuel de gouvernance</li>
                    </ul>
                    
                    <h3 class="card-subheader">Qualités personnelles</h3>
                    <ul>
                        <li>Méthodisme et sens de l'organisation</li>
                        <li>Souci du détail et persévérance</li>
                        <li>Diplomatie dans les relations institutionnelles</li>
                        <li>Adhésion aux valeurs de transparence et de redevabilité</li>
                    </ul>
                </div>
            </article>

            <article class="card">
                <div class="card-header card-header-3">3. Responsable de la Communication et des Relations Externes</div>
                <div class="card-body">
                    <h3 class="card-subheader">Formation minimale requise</h3>
                    <p>Licence ou Master en communication, marketing, relations publiques ou journalisme. Une formation complémentaire en communication digitale est fortement recommandée. Minimum 3 ans d'expérience en communication, idéalement dans le secteur associatif ou humanitaire.</p>
                    
                    <h3 class="card-subheader">Mission principale</h3>
                    <p>Reconstruire l'image de l'ONG et développer une stratégie de communication efficace.</p>
                    
                    <h3 class="card-subheader">Compétences clés</h3>
                    <ul>
                        <li>Formation en communication, relations publiques ou marketing digital</li>
                        <li>Maîtrise des outils de communication traditionnels et digitaux</li>
                        <li>Expérience en gestion de crise et communication sensible</li>
                        <li>Capacité à élaborer et déployer une stratégie de contenu</li>
                    </ul>
                    
                    <h3 class="card-subheader">Responsabilités spécifiques</h3>
                    <ul>
                        <li>Concevoir et mettre en œuvre le plan de communication de relance</li>
                        <li>Gérer la présence digitale de l'ONG (site web, réseaux sociaux)</li>
                        <li>Développer les relations avec les médias et les influenceurs</li>
                        <li>Produire des supports de communication conformes à la charte graphique</li>
                    </ul>
                    
                    <h3 class="card-subheader">Qualités personnelles</h3>
                    <ul>
                        <li>Créativité et sens de l'innovation</li>
                        <li>Excellentes capacités rédactionnelles</li>
                        <li>Aisance relationnelle et sens de la diplomatie</li>
                        <li>Capacité à communiquer les valeurs chrétiennes de manière inclusive</li>
                    </ul>
                </div>
            </article>

            <article class="card">
                <div class="card-header card-header-4">4. Responsable Projet Social et Impact Communautaire</div>
                <div class="card-body">
                    <h3 class="card-subheader">Formation minimale requise</h3>
                    <p>Licence ou Master en développement communautaire, action sociale, gestion de projets humanitaires ou domaine connexe. Une certification en gestion de projet (PMP, Prince2) serait un atout. Minimum 3 ans d'expérience dans la gestion de projets sociaux ou humanitaires.</p>
                    
                    <h3 class="card-subheader">Mission principale</h3>
                    <p>Relancer et coordonner les projets d'impact social et spirituel sur le terrain.</p>
                    
                    <h3 class="card-subheader">Compétences clés</h3>
                    <ul>
                        <li>Expérience en gestion de projets sociaux ou humanitaires</li>
                        <li>Connaissance des méthodes d'évaluation d'impact</li>
                        <li>Pratique du travail communautaire et de l'animation de groupes</li>
                        <li>Compréhension des besoins des populations vulnérables</li>
                    </ul>
                    
                    <h3 class="card-subheader">Responsabilités spécifiques</h3>
                    <ul>
                        <li>Redémarrer et superviser les projets prioritaires ("Donnez-leur vous-même à manger")</li>
                        <li>Établir des systèmes de suivi et d'évaluation des projets</li>
                        <li>Coordonner les équipes terrain et les bénévoles</li>
                        <li>Documenter l'impact des activités et préparer les rapports de projets</li>
                    </ul>
                    
                    <h3 class="card-subheader">Qualités personnelles</h3>
                    <ul>
                        <li>Empathie et sensibilité aux besoins des bénéficiaires</li>
                        <li>Leadership mobilisateur et esprit d'équipe</li>
                        <li>Adaptabilité et résilience face aux contraintes</li>
                        <li>Engagement spirituel et sens du service</li>
                    </ul>
                </div>
            </article>

            <article class="card">
                <div class="card-header card-header-5">5. Responsable du Développement des Ressources Humaines et du Bénévolat</div>
                <div class="card-body">
                    <h3 class="card-subheader">Formation minimale requise</h3>
                    <p>Licence en ressources humaines, psychologie, sciences sociales ou domaine connexe. Une formation complémentaire en gestion du bénévolat serait un atout. Minimum 3 ans d'expérience en gestion d'équipes, idéalement avec une composante bénévole.</p>
                    
                    <h3 class="card-subheader">Mission principale</h3>
                    <p>Mobiliser, former et fidéliser les équipes nécessaires à la relance de l'ONG.</p>
                    
                    <h3 class="card-subheader">Compétences clés</h3>
                    <ul>
                        <li>Formation ou expérience en gestion des ressources humaines</li>
                        <li>Connaissance des spécificités du management des bénévoles</li>
                        <li>Compétences en formation et développement des talents</li>
                        <li>Pratique de l'accompagnement individuel et collectif</li>
                    </ul>
                    
                    <h3 class="card-subheader">Responsabilités spécifiques</h3>
                    <ul>
                        <li>Recruter et intégrer de nouveaux bénévoles et membres actifs</li>
                        <li>Concevoir et animer des programmes de formation adaptés</li>
                        <li>Mettre en place des systèmes de reconnaissance et de valorisation</li>
                        <li>Développer une culture organisationnelle positive et engageante</li>
                    </ul>
                    
                    <h3 class="card-subheader">Qualités personnelles</h3>
                    <ul>
                        <li>Capacité à inspirer et à motiver</li>
                        <li>Écoute active et intelligence émotionnelle</li>
                        <li>Sens de la médiation et gestion des conflits</li>
                        <li>Connaissance du leadership serviteur chrétien</li>
                    </ul>
                </div>
            </article>

            <article class="card">
                <div class="card-header card-header-6">6. Conseiller(ère) en Développement Spirituel</div>
                <div class="card-body">
                    <h3 class="card-subheader">Formation minimale requise</h3>
                    <p>Formation théologique de niveau Licence ou équivalent (séminaire biblique, institut biblique). Une formation pastorale ou en accompagnement spirituel serait un atout majeur. Minimum 5 ans d'expérience dans un ministère chrétien ou dans l'accompagnement spirituel.</p>
                    
                    <h3 class="card-subheader">Mission principale</h3>
                    <p>Assurer l'alignement des actions avec la vision spirituelle et les valeurs fondamentales de l'ONG.</p>
                    
                    <h3 class="card-subheader">Compétences clés</h3>
                    <ul>
                        <li>Formation théologique solide</li>
                        <li>Expérience en accompagnement spirituel et discipulat</li>
                        <li>Connaissance des principes bibliques appliqués à la gouvernance</li>
                        <li>Capacité à intégrer foi et action sociale</li>
                    </ul>
                    
                    <h3 class="card-subheader">Responsabilités spécifiques</h3>
                    <ul>
                        <li>Relancer les programmes d'accompagnement spirituel ("La Prière du Bâtisseur")</li>
                        <li>Animer des temps de réflexion et de discernement pour l'équipe</li>
                        <li>Veiller à l'intégration des principes bibliques dans les décisions et actions</li>
                        <li>Développer des outils de croissance spirituelle adaptés au contexte</li>
                    </ul>
                    
                    <h3 class="card-subheader">Qualités personnelles</h3>
                    <ul>
                        <li>Maturité spirituelle et discernement</li>
                        <li>Humilité et esprit de service</li>
                        <li>Capacité à inspirer et à encourager</li>
                        <li>Aptitude à faire des ponts entre théologie et pratique</li>
                    </ul>
                </div>
            </article>
        </section>

        <section aria-labelledby="comites-pilotage-title">
            <h2 id="comites-pilotage-title" class="section-title text-xl">II. COMITÉS DE PILOTAGE ET GOUVERNANCE DU PLAN DE RELANCE</h2>

            <article class="committee">
                <div class="committee-header committee-header-1">1. Comité Stratégique de Relance (CSR)</div>
                <div class="committee-body">
                    <h3 class="committee-subheader">Rôle</h3>
                    <p>Instance décisionnelle suprême pour le pilotage global du plan de relance.</p>
                    
                    <h3 class="committee-subheader">Composition idéale (7-9 personnes)</h3>
                    <ul>
                        <li>Président(e) du Conseil d'Administration</li>
                        <li>Secrétaire Général de l'ONG</li>
                        <li>Les 3-4 responsables d'axes clés (Finance, Administration, Projets, Communication)</li>
                        <li>2-3 membres externes apportant une expertise spécifique (consultant en gouvernance, expert financier, leader d'église)</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Missions principales</h3>
                    <ul>
                        <li>Valider les orientations stratégiques et les ajustements majeurs</li>
                        <li>Suivre les indicateurs de performance globaux du plan de relance</li>
                        <li>Arbitrer les décisions importantes et l'allocation des ressources</li>
                        <li>Représenter l'ONG auprès des parties prenantes stratégiques</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Modalités de fonctionnement</h3>
                    <ul>
                        <li>Réunion mensuelle formelle (dernier vendredi du mois)</li>
                        <li>Sessions extraordinaires en cas de crise ou décision urgente</li>
                        <li>Préparation par un ordre du jour précis avec documents préparatoires</li>
                        <li>Compte-rendu détaillé diffusé dans les 48h après la réunion</li>
                    </ul>
                </div>
            </article>

            <article class="committee">
                <div class="committee-header committee-header-2">2. Comité Opérationnel de Relance (COR)</div>
                <div class="committee-body">
                    <h3 class="committee-subheader">Rôle</h3>
                    <p>Assurer la mise en œuvre quotidienne du plan de relance et la coordination entre les différents axes.</p>
                    
                    <h3 class="committee-subheader">Composition idéale (5-7 personnes)</h3>
                    <ul>
                        <li>Président de l'ONG</li>
                        <li>Secrétaire Général</li>
                        <li>Tous les responsables d'axes du plan de relance</li>
                        <li>Représentant des bénévoles</li>
                        <li>Chargé(e) du suivi-évaluation</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Missions principales</h3>
                    <ul>
                        <li>Coordonner l'exécution des tâches du plan de relance</li>
                        <li>Résoudre les problèmes opérationnels et lever les blocages</li>
                        <li>Ajuster les plannings et la mobilisation des ressources</li>
                        <li>Préparer les rapports d'avancement pour le Comité Stratégique</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Modalités de fonctionnement</h3>
                    <ul>
                        <li>Réunion hebdomadaire courte (1h30 maximum, le lundi matin)</li>
                        <li>Tableau de bord opérationnel mis à jour en temps réel</li>
                        <li>Point d'avancement standardisé par responsable d'axe</li>
                        <li>Identification collective des blocages et solutions</li>
                    </ul>
                </div>
            </article>

            <article class="committee">
                <div class="committee-header committee-header-3">3. Comité Financier et de Mobilisation des Ressources (CFMR)</div>
                <div class="committee-body">
                    <h3 class="committee-subheader">Rôle</h3>
                    <p>Superviser l'assainissement financier et développer la stratégie de collecte de fonds.</p>
                    
                    <h3 class="committee-subheader">Composition idéale (5-6 personnes)</h3>
                    <ul>
                        <li>Responsable Financier (président du comité)</li>
                        <li>Trésorier(ère) de l'ONG</li>
                        <li>Responsable de la Communication</li>
                        <li>1-2 experts externes en fundraising ou finances associatives</li>
                        <li>Comptable ou responsable administratif et financier</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Missions principales</h3>
                    <ul>
                        <li>Suivre l'exécution du plan d'assainissement financier</li>
                        <li>Valider les stratégies de collecte et les objectifs de financement</li>
                        <li>Analyser les résultats des campagnes de collecte</li>
                        <li>Examiner la conformité des dépenses et la gestion budgétaire</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Modalités de fonctionnement</h3>
                    <ul>
                        <li>Réunion bimensuelle (1er et 3ème mardi du mois)</li>
                        <li>Tableau de bord financier actualisé avant chaque réunion</li>
                        <li>Revue des flux de trésorerie et projections actualisées</li>
                        <li>Compte-rendu financier mensuel pour le Comité Stratégique</li>
                    </ul>
                </div>
            </article>

            <article class="committee">
                <div class="committee-header committee-header-4">4. Comité des Projets et de l'Impact Social (CPIS)</div>
                <div class="committee-body">
                    <h3 class="committee-subheader">Rôle</h3>
                    <p>Piloter la mise en œuvre des projets terrain et maximiser leur impact.</p>
                    
                    <h3 class="committee-subheader">Composition idéale (6-8 personnes)</h3>
                    <ul>
                        <li>Responsable Projet Social (président du comité)</li>
                        <li>Responsables des projets prioritaires</li>
                        <li>Conseiller(ère) en Développement Spirituel</li>
                        <li>1-2 représentants des bénéficiaires</li>
                        <li>Expert(e) en évaluation d'impact</li>
                        <li>Responsable du bénévolat</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Missions principales</h3>
                    <ul>
                        <li>Planifier et superviser le déploiement des projets prioritaires</li>
                        <li>Valider les méthodologies d'intervention et les outils de suivi</li>
                        <li>Analyser les retours d'expérience et les résultats obtenus</li>
                        <li>Proposer des ajustements pour maximiser l'impact</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Modalités de fonctionnement</h3>
                    <ul>
                        <li>Réunion bimensuelle (2ème et 4ème mercredi du mois)</li>
                        <li>Session sur le terrain une fois par mois (visite de projet)</li>
                        <li>Tableau de bord des projets actualisé avant chaque réunion</li>
                        <li>Partage de témoignages et études de cas</li>
                    </ul>
                </div>
            </article>

            <article class="committee">
                <div class="committee-header committee-header-5">5. Comité Communication et Partenariats (CCP)</div>
                <div class="committee-body">
                    <h3 class="committee-subheader">Rôle</h3>
                    <p>Superviser la stratégie de communication et développer les relations externes.</p>
                    
                    <h3 class="committee-subheader">Composition idéale (5-6 personnes)</h3>
                    <ul>
                        <li>Responsable de la Communication (président du comité)</li>
                        <li>Responsable des relations avec les églises</li>
                        <li>Chargé(e) des médias sociaux</li>
                        <li>1-2 partenaires externes ou ambassadeurs de l'ONG</li>
                        <li>Expert(e) en communication chrétienne</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Missions principales</h3>
                    <ul>
                        <li>Valider le plan de communication et son déploiement</li>
                        <li>Suivre l'évolution de l'image de l'ONG et les indicateurs de visibilité</li>
                        <li>Approuver les principaux contenus et supports de communication</li>
                        <li>Développer la stratégie de partenariats et d'alliances</li>
                    </ul>
                    
                    <h3 class="committee-subheader">Modalités de fonctionnement</h3>
                    <ul>
                        <li>Réunion bimensuelle (1er et 3ème jeudi du mois)</li>
                        <li>Revue des statistiques de communication (engagement, portée)</li>
                        <li>Planification éditoriale pour les deux semaines à venir</li>
                        <li>Analyse des opportunités de partenariats</li>
                    </ul>
                </div>
            </article>
        </section>

        <section aria-labelledby="coordination-interactions-title">
            <h2 id="coordination-interactions-title" class="section-title text-xl">III. COORDINATION ET INTERACTIONS ENTRE LES COMITÉS</h2>

            <div class="coordination">
                <h3 class="coordination-title">1. Système de reporting et de communication</h3>
                <ul>
                    <li><strong>Reporting ascendant</strong> : Chaque comité thématique produit un rapport synthétique mensuel pour le Comité Opérationnel, qui consolide l'information pour le Comité Stratégique.</li>
                    <li><strong>Communication descendante</strong> : Les décisions du Comité Stratégique sont communiquées par le Directeur Exécutif lors des réunions du Comité Opérationnel, puis relayées aux comités thématiques.</li>
                    <li><strong>Plateforme collaborative</strong> : Mise en place d'un outil de gestion de projet (type Asana ou Trello) accessible à tous les membres des comités pour le suivi en temps réel.</li>
                    <li><strong>Tableau de bord intégré</strong> : Création d'un tableau de bord unifié regroupant les indicateurs clés de tous les axes, mis à jour hebdomadairement.</li>
                </ul>
            </div>

            <div class="coordination">
                <h3 class="coordination-title">2. Moments de coordination clés</h3>
                <ul>
                    <li><strong>Réunion mensuelle de synchronisation</strong> : Session de 2h regroupant les présidents de tous les comités pour assurer la cohérence des actions.</li>
                    <li><strong>Rituels de partage d'information</strong> : Email hebdomadaire résumant les avancées et points de blocage majeurs de chaque comité.</li>
                    <li><strong>Atelier trimestriel d'évaluation et recalage</strong> : Journée complète réunissant tous les comités pour évaluer les progrès globaux et ajuster le plan.</li>
                    <li><strong>Temps de prière commun</strong> : Session mensuelle de prière et discernement spirituel pour l'ensemble des comités (avant la réunion du Comité Stratégique).</li>
                </ul>
            </div>

            <div class="coordination">
                <h3 class="coordination-title">3. Principes de gouvernance partagée</h3>
                <ul>
                    <li><strong>Subsidiarité</strong> : Les décisions sont prises au niveau le plus proche de l'action, avec escalade au niveau supérieur seulement si nécessaire.</li>
                    <li><strong>Transparence</strong> : Tous les comptes-rendus et tableaux de bord sont accessibles à l'ensemble des membres des comités.</li>
                    <li><strong>Redevabilité mutuelle</strong> : Chaque comité présente régulièrement ses avancées aux autres comités.</li>
                    <li><strong>Leadership serviteur</strong> : Les présidents de comités sont au service de leurs équipes et non dans une posture hiérarchique.</li>
                    <li><strong>Discernement spirituel</strong> : Les décisions importantes sont précédées d'un temps de prière et d'écoute spirituelle.</li>
                </ul>
            </div>

            <div class="coordination">
                <h3 class="coordination-title">4. Gestion des conflits et arbitrages</h3>
                <ul>
                    <li><strong>Processus d'escalade clair</strong> : Définition précise des sujets nécessitant arbitrage et du circuit de décision.</li>
                    <li><strong>Mécanisme de médiation</strong> : Désignation d'un médiateur neutre (ex: Conseiller en Développement Spirituel) pour les tensions entre comités.</li>
                    <li><strong>Critères de décision explicites</strong> : Établissement de principes d'arbitrage basés sur la mission, les valeurs et les priorités du plan de relance.</li>
                    <li><strong>Révision régulière du fonctionnement</strong> : Évaluation trimestrielle de l'efficacité du système de gouvernance avec ajustements si nécessaire.</li>
                </ul>
            </div>
        </section>

        <section class="conclusion" aria-labelledby="conclusion-heading">
            <h2 id="conclusion-heading" class="text-xl font-bold mb-4 text-center" style="color: var(--brown-deep);">CONCLUSION</h2>
            <p class="mb-4">Cette structure de gouvernance et ces profils de responsables sont spécifiquement conçus pour piloter efficacement la relance de l'ONG Néhémie International dans sa période critique (juin-décembre 2025). Ils allient la rigueur professionnelle nécessaire au redressement avec les valeurs chrétiennes qui fondent l'identité de l'organisation.</p>
            
            <p class="mb-4">La multiplication des comités pourrait sembler lourde, mais elle répond à un besoin temporaire de structuration forte pendant la phase de relance. À mesure que la situation se stabilisera, cette gouvernance pourra être simplifiée pour adopter un fonctionnement plus fluide et moins formalisé.</p>
            
            <p>Le succès de cette organisation reposera largement sur la qualité des personnes recrutées pour les postes clés, leur adhésion aux valeurs de l'ONG et leur capacité à travailler ensemble dans un esprit d'unité et de service.</p>
        </section>

        <div class="bible-verse">
            <p>"Voici, oh ! qu'il est agréable, qu'il est doux pour des frères de demeurer ensemble !" (Psaume 133:1)</p>
        </div>
    </main>

    <footer class="page-footer">
        <p>Document préparé pour l'ONG Néhémie International</p>
        <p class="mt-2">© 2025 - "Levons-nous et bâtissons !"</p>
    </footer>
</body>
</html>