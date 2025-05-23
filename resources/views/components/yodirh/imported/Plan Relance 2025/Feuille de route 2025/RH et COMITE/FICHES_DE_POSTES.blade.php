<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FICHES DE POSTES - ONG NÉHÉMIE INTERNATIONAL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Variables basées sur la charte graphique de nehemie-international.com */
        :root {
            --primary-blue: #003366; /* Bleu foncé */
            --secondary-blue: #0077cc; /* Bleu plus vif */
            --light-grey: #f4f4f4; /* Fond léger */
            --text-color: #333; /* Gris foncé pour le texte */
            --border-color: #ddd; /* Couleur de bordure */
            --title-color: #1a365d; /* Un bleu foncé légèrement différent trouvé dans votre HTML source - peut être remplacé par --primary-blue si désiré */
        }

        html {
            scroll-behavior: smooth; /* Permet le défilement fluide */
        }

        body {
            font-family: 'Open Sans', 'Lato', Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            background-color: #f8f8f8; /* Léger gris de fond */
        }

        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 0 20px;
        }

        header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        header h1 {
            color: var(--primary-blue);
            font-family: 'Lato', sans-serif;
            font-size: 2.8em;
            margin-bottom: 5px;
            font-weight: 700;
        }

        header h2 {
            color: var(--secondary-blue);
            font-size: 1.8em;
            margin-top: 0;
            font-weight: 600;
            font-style: italic;
        }

        header h3 {
             color: var(--text-color);
             font-size: 1.5em;
             margin-top: 20px;
             margin-bottom: 10px;
             font-weight: 700;
        }

        .header-divider {
            border-top: 2px solid var(--border-color);
            border-bottom: 2px solid var(--border-color);
            padding: 10px 0;
            margin: 20px 0;
        }

        .introduction, .footer {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .introduction h2, .footer h2 {
            color: var(--primary-blue);
            font-size: 1.8em;
            margin-top: 0;
            margin-bottom: 15px;
             font-weight: 700;
        }
         .introduction p {
             margin-bottom: 15px;
         }

        .program-tags {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .program-tag {
            background-color: var(--secondary-blue);
            color: white;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.85em;
            font-weight: 600;
        }

        /* Navigation */
        .job-navigation {
            background-color: var(--light-grey);
            padding: 15px 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .job-navigation h3 {
            color: var(--primary-blue);
            font-size: 1.3em;
            margin-top: 0;
            margin-bottom: 10px;
             font-weight: 700;
        }

        .job-navigation ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .job-navigation li {
            margin: 0;
        }

        .job-navigation a {
            display: block;
            padding: 8px 15px;
            background-color: #fff;
            color: var(--primary-blue);
            text-decoration: none;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
            font-weight: 600;
            font-size: 0.95em;
        }

        .job-navigation a:hover, .job-navigation a:focus {
            background-color: var(--secondary-blue);
            color: #fff;
            border-color: var(--secondary-blue);
        }

        /* Job Card Styles */
        .job-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); /* Légère ombre plus prononcée */
            overflow: hidden;
            margin-bottom: 30px;
        }

        .job-title {
            background-color: var(--title-color); /* Utilise le bleu foncé de votre source */
            color: white;
            padding: 15px 25px;
        }

        .job-title h2 {
            margin: 0;
            font-size: 1.6em;
            font-weight: 700;
            font-family: 'Lato', sans-serif;
        }

        .job-title p {
            margin-top: 5px;
            margin-bottom: 0;
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.8);
        }

        .job-content {
            padding: 25px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 1.3em;
            font-weight: 700;
            color: var(--primary-blue);
            padding-bottom: 8px;
            border-bottom: 2px solid var(--secondary-blue); /* Utilise le bleu vif pour la ligne */
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 1em; /* Espacement entre les paragraphes */
        }


        .list-custom {
            list-style: none; /* Supprime les puces par défaut */
            padding-left: 0;
            margin-top: 10px;
        }

        .list-custom li {
            position: relative;
            padding-left: 25px; /* Espace pour la puce custom */
            margin-bottom: 10px; /* Espacement entre les éléments de liste */
        }

        .list-custom li::before {
            content: '\2022'; /* Caractère puce (Unicode) */
            position: absolute;
            left: 0;
            color: var(--secondary-blue); /* Couleur de la puce */
            font-weight: bold;
             font-size: 1.2em;
             line-height: 1; /* Alignement vertical */
        }
         .list-custom li strong {
             color: var(--primary-blue); /* Mettre en évidence les noms/rôles dans les listes */
         }

        .tag {
            background-color: var(--light-grey); /* Couleur de fond tag */
            color: var(--text-color);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.85em;
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
            font-weight: 600;
        }


        /* Footer */
        footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 0.9em;
        }

        footer p {
            margin: 5px 0;
        }
        footer p.text-sm {
            font-size: 0.8em;
            color: #555;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            header h1 {
                font-size: 2em;
            }
             header h2 {
                font-size: 1.4em;
            }
            .job-title h2 {
                font-size: 1.4em;
            }
            .job-content {
                padding: 20px;
            }
            .section-title {
                font-size: 1.2em;
            }
             .job-navigation ul {
                 flex-direction: column;
                 gap: 5px;
             }
              .job-navigation a {
                  padding: 6px 10px;
                  text-align: center;
              }
             .list-custom li {
                 padding-left: 20px;
             }
             .list-custom li::before {
                 font-size: 1em;
             }

        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <header>
            <h1 class="text-blue-900">ONG NÉHÉMIE INTERNATIONAL</h1>
            <h2 class="text-blue-700 italic">"Levons-nous et bâtissons!"</h2>
            <div class="header-divider">
                <h3 class="text-gray-800">FICHES DE POSTES</h3>
                <p class="text-gray-600">Document interne - Direction des Ressources Humaines</p>
            </div>
        </header>

        <!-- Introduction -->
        <div class="introduction">
            <h2 class="text-blue-800">Introduction</h2>
            <p>Ce document présente les fiches de postes détaillées pour l'équipe dirigeante de NÉHÉMIE International, ONG chrétienne gabonaise qui met en œuvre des actions concrètes d'aide aux populations vulnérables, de formation professionnelle et d'accompagnement spirituel.</p>
            <p>Chaque fiche définit le périmètre d'action, les responsabilités et les interactions entre les différentes fonctions de l'ONG, permettant ainsi une meilleure coordination des efforts pour accomplir notre mission d'évangélisation par les actes.</p>
            <p>Ces fiches de postes s'inscrivent dans le cadre de nos cinq programmes fondamentaux :</p>
            <div class="program-tags">
                <span class="program-tag">TIMOTHÉE</span>
                <span class="program-tag">PHILIPPE</span>
                <span class="program-tag">DORCAS</span>
                <span class="program-tag">BÉTHANIE</span>
                <span class="program-tag">DANIEL</span>
            </div>
        </div>

        <!-- Navigation rapide -->
        <nav class="job-navigation">
            <h3>Navigation rapide par rôle :</h3>
            <ul>
                <li><a href="#president">Président</a></li>
                <li><a href="#secretaire-general">Secrétaire Général</a></li>
                <li><a href="#partenariat">Chargée du partenariat</a></li>
                <li><a href="#assistante-direction">Assistante de direction</a></li>
                <li><a href="#raf">RAF</a></li>
                <li><a href="#moyens-generaux">Moyens Généraux</a></li>
                <li><a href="#juridique">Juridique & Adjoint RAF</a></li>
                <li><a href="#benevoles">Gestion des bénévoles</a></li>
                <li><a href="#it-chargée">Chargée de l'informatique</a></li>
                <li><a href="#it-assistant">Assistant informatique</a></li>
            </ul>
        </nav>

        <!-- Fiches de postes -->
        <div class="job-cards-container">
            <!-- Président -->
            <section id="president" class="job-card">
                <div class="job-title">
                    <h2>PRÉSIDENT DE L'ONG</h2>
                    <p>M. NGUEL</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p>Garant de la vision et de la mission de NÉHÉMIE International, le Président assure la direction stratégique de l'ONG, représente l'organisation auprès des instances officielles et partenaires, et veille à l'accomplissement des objectifs conformément aux valeurs chrétiennes fondatrices.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                        <ul class="list-custom">
                            <li>Définir et porter la vision stratégique de l'ONG en accord avec sa mission d'évangélisation par les actes</li>
                            <li>Présider le Conseil d'Administration et assurer la gouvernance de l'organisation</li>
                            <li>Représenter l'ONG auprès des autorités, des partenaires et du public</li>
                            <li>Superviser l'ensemble des programmes et projets majeurs</li>
                            <li>Garantir l'alignement des actions avec les valeurs chrétiennes de l'organisation</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Responsabilités spécifiques</h3>
                        <ul class="list-custom">
                            <li>Conduire l'élaboration et la validation du plan stratégique pluriannuel</li>
                            <li>Officialiser les partenariats stratégiques et institutionnels</li>
                            <li>Prendre les décisions finales concernant les orientations majeures de l'ONG</li>
                            <li>Assurer la communication publique sur la vision et les réalisations de l'ONG</li>
                            <li>Superviser les campagnes majeures comme "Donnez-leur vous-mêmes à manger"</li>
                            <li>Animer les réunions de direction et les assemblées générales</li>
                            <li>Valider les budgets annuels et les rapports d'activité</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Conseil d'Administration</p>
                        <p><span class="font-semibold">Supervise directement :</span> Secrétaire Général, Responsable administratif et financier, Chargée du partenariat</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Membres du Conseil d'Administration, Partenaires institutionnels, Autorités religieuses et administratives</p>
                    </div>
                     <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Plan stratégique quinquennal</li>
                            <li>Rapport annuel d'activité présenté au Conseil d'Administration</li>
                            <li>Nombre de partenariats stratégiques établis</li>
                            <li>Impact global des actions de l'ONG par rapport aux objectifs définis</li>
                            <li>Témoignages d'évangélisation par les actes (histoires transformationnelles)</li>
                            <li>Croissance et rayonnement de l'ONG au Gabon et au-delà</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Secrétaire Général -->
            <section id="secretaire-general" class="job-card">
                 <div class="job-title">
                    <h2>SECRÉTAIRE GÉNÉRAL</h2>
                    <p>M. Patrick</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p>Le Secrétaire Général est responsable de la coordination opérationnelle quotidienne de l'ONG, assurant la mise en œuvre cohérente des décisions stratégiques et le bon fonctionnement des cinq programmes fondamentaux de l'organisation.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                         <ul class="list-custom">
                            <li>Assurer la coordination opérationnelle entre les différents programmes et départements</li>
                            <li>Mettre en œuvre les décisions du Président et du Conseil d'Administration</li>
                            <li>Superviser le fonctionnement administratif quotidien de l'organisation</li>
                            <li>Coordonner la planification et l'exécution des projets conformément à la vision</li>
                            <li>Faciliter la communication interne et la cohésion entre les équipes</li>
                        </ul>
                    </div>
                    <div class="section">
                         <h3 class="section-title">Responsabilités spécifiques</h3>
                         <ul class="list-custom">
                            <li>Élaborer et suivre les plans d'action annuels pour chaque programme</li>
                            <li>Organiser et animer les réunions de coordination hebdomadaires</li>
                            <li>Superviser la documentation administrative des activités de l'ONG</li>
                            <li>Assurer le suivi de la mise en œuvre des projets et leur évaluation</li>
                            <li>Coordonner la rédaction des rapports d'activité périodiques</li>
                            <li>Représenter l'ONG en l'absence du Président</li>
                            <li>Superviser la gestion des ressources humaines</li>
                            <li>Veiller à l'application des procédures internes</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Président de l'ONG</p>
                        <p><span class="font-semibold">Supervise directement :</span> Assistante de direction, Responsables des programmes (TIMOTHÉE, PHILIPPE, DORCAS, BÉTHANIE et DANIEL), Responsable des moyens généraux</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Responsable administratif et financier, Chargée de la gestion des bénévoles, Chargée du partenariat</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Plans d'action annuels et tableaux de bord de suivi opérationnel</li>
                            <li>Comptes-rendus des réunions de coordination</li>
                            <li>Rapports d'activité trimestriels</li>
                            <li>Taux de réalisation des objectifs des cinq programmes</li>
                            <li>Évaluations des projets menés</li>
                            <li>Niveau de satisfaction des équipes (climat interne)</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Chargée du partenariat -->
            <section id="partenariat" class="job-card">
                 <div class="job-title">
                    <h2>CHARGÉE DU PARTENARIAT</h2>
                    <p>Mme Dominique</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p>La Chargée du partenariat est responsable de l'élaboration et de la mise en œuvre de la stratégie de partenariat de l'ONG, en identifiant et développant des relations de collaboration avec différents types de partenaires (financiers, techniques, institutionnels) pour soutenir la mission et les projets de l'organisation.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                         <ul class="list-custom">
                            <li>Élaborer et mettre en œuvre la stratégie de partenariat de l'ONG</li>
                            <li>Identifier et approcher des partenaires potentiels (fondations, entreprises, ONG)</li>
                            <li>Concevoir et présenter des dossiers de demande de soutien adaptés</li>
                            <li>Entretenir des relations durables avec les partenaires existants</li>
                            <li>Assurer le suivi et l'évaluation des partenariats établis</li>
                        </ul>
                    </div>
                    <div class="section">
                         <h3 class="section-title">Responsabilités spécifiques</h3>
                         <ul class="list-custom">
                            <li>Réaliser une veille sur les opportunités de partenariat et de financement</li>
                            <li>Rédiger des propositions de projet et des demandes de financement</li>
                            <li>Négocier les termes des partenariats et conventions</li>
                            <li>Organiser des événements de rencontre et de fidélisation des partenaires</li>
                            <li>Élaborer des rapports narratifs et financiers pour les partenaires</li>
                            <li>Coordonner la campagne "Donnez-leur vous-mêmes à manger" avec les partenaires</li>
                            <li>Assurer la visibilité des partenaires selon les engagements pris</li>
                            <li>Développer des outils de communication spécifiques aux partenariats</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Président de l'ONG</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Secrétaire Général, Responsable administratif et financier, Chargée de l'informatique (pour les contenus liés aux partenaires), Responsables des programmes pour les projets à financer</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Stratégie de partenariat et plan d'action annuel</li>
                            <li>Nombre de nouveaux partenariats établis par trimestre</li>
                            <li>Montant des financements mobilisés</li>
                            <li>Taux de renouvellement des partenariats existants</li>
                            <li>Qualité et délai des rapports aux partenaires</li>
                            <li>Base de données des partenaires actualisée</li>
                            <li>Dossiers de présentation adaptés à différents types de partenaires</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Assistante de direction -->
            <section id="assistante-direction" class="job-card">
                 <div class="job-title">
                    <h2>ASSISTANTE DE DIRECTION</h2>
                    <p>Mme Cassandra</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p>L'Assistante de direction apporte un soutien administratif et organisationnel aux dirigeants de l'ONG, en particulier au Président et au Secrétaire Général, facilitant ainsi l'efficacité de la gouvernance et de la coordination opérationnelle.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                         <ul class="list-custom">
                            <li>Assurer le secrétariat et l'appui administratif de la direction</li>
                            <li>Gérer l'agenda et les communications des dirigeants</li>
                            <li>Organiser les réunions et événements institutionnels</li>
                            <li>Traiter et suivre les correspondances officielles</li>
                            <li>Assurer la circulation efficace des informations au sein de l'ONG</li>
                        </ul>
                    </div>
                    <div class="section">
                         <h3 class="section-title">Responsabilités spécifiques</h3>
                         <ul class="list-custom">
                            <li>Gérer l'agenda du Président et du Secrétaire Général</li>
                            <li>Préparer les documents pour les réunions (ordre du jour, supports)</li>
                            <li>Rédiger les comptes-rendus des réunions de direction</li>
                            <li>Organiser les déplacements professionnels des dirigeants</li>
                            <li>Gérer la correspondance écrite et électronique de la direction</li>
                            <li>Accueillir les visiteurs et partenaires importants</li>
                            <li>Assurer la gestion documentaire et l'archivage</li>
                            <li>Coordonner la logistique des événements institutionnels</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Secrétaire Général (principalement), Président de l'ONG</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Tous les responsables de départements, Chargée de la gestion des bénévoles, Responsable des moyens généraux</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Qualité et ponctualité des documents produits</li>
                            <li>Respect des délais dans le traitement des correspondances</li>
                            <li>Efficacité de la gestion des agendas (absence de conflits)</li>
                            <li>Organisation logistique des réunions et événements</li>
                            <li>Qualité des comptes-rendus et procès-verbaux</li>
                            <li>Fiabilité du système d'archivage et de classement</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Responsable administratif et financier -->
            <section id="raf" class="job-card">
                 <div class="job-title">
                    <h2>RESPONSABLE ADMINISTRATIF ET FINANCIER</h2>
                    <p>M. Tauken</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p>Le Responsable administratif et financier assure la gestion rigoureuse des ressources financières et des processus administratifs de l'ONG, garantissant l'intégrité, la transparence et l'efficacité dans l'utilisation des fonds conformément aux valeurs chrétiennes d'intendance fidèle.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                         <ul class="list-custom">
                            <li>Gérer les ressources financières de l'ONG avec rigueur et transparence</li>
                            <li>Élaborer et suivre le budget global et les budgets par programme</li>
                            <li>Développer et faire respecter les procédures administratives et financières</li>
                            <li>Assurer la conformité légale et fiscale de l'organisation</li>
                            <li>Superviser la comptabilité et produire les états financiers</li>
                        </ul>
                    </div>
                    <div class="section">
                         <h3 class="section-title">Responsabilités spécifiques</h3>
                         <ul class="list-custom">
                            <li>Élaborer le budget annuel en collaboration avec les responsables de programme</li>
                            <li>Assurer le suivi budgétaire et les réallocations nécessaires</li>
                            <li>Superviser les opérations comptables et la tenue des livres</li>
                            <li>Préparer les rapports financiers pour la direction et les partenaires</li>
                            <li>Gérer la trésorerie et les flux financiers</li>
                            <li>Superviser les achats et les procédures d'approvisionnement</li>
                            <li>Coordonner les audits internes et externes</li>
                            <li>Gérer les aspects administratifs des ressources humaines</li>
                            <li>Assurer la conformité avec les réglementations gabonaises</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Président de l'ONG</p>
                        <p><span class="font-semibold">Supervise directement :</span> Responsable juridique (adjoint RAF), Personnel comptable et administratif</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Secrétaire Général, Chargée du partenariat, Responsable des moyens généraux, Responsables des programmes</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Budget annuel et suivi budgétaire mensuel</li>
                            <li>États financiers trimestriels et annuels</li>
                            <li>Rapports financiers pour les bailleurs de fonds</li>
                            <li>Manuel de procédures administratives et financières</li>
                            <li>Taux d'exécution budgétaire</li>
                            <li>Conformité avec les exigences légales et fiscales</li>
                            <li>Résultats des audits</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Responsable des moyens généraux -->
            <section id="moyens-generaux" class="job-card">
                 <div class="job-title">
                    <h2>RESPONSABLE DES MOYENS GÉNÉRAUX</h2>
                    <p>M. Paterne</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p>Le Responsable des moyens généraux assure la gestion optimale des infrastructures, équipements et services support nécessaires au bon fonctionnement de l'ONG, en garantissant des conditions matérielles propices à la réalisation de sa mission.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                         <ul class="list-custom">
                            <li>Gérer et maintenir les infrastructures et équipements de l'ONG</li>
                            <li>Assurer la logistique des activités et projets sur le terrain</li>
                            <li>Superviser les achats de matériel et fournitures</li>
                            <li>Coordonner les services généraux (entretien, sécurité, transport)</li>
                            <li>Optimiser l'utilisation des ressources matérielles</li>
                        </ul>
                    </div>
                    <div class="section">
                         <h3 class="section-title">Responsabilités spécifiques</h3>
                         <ul class="list-custom">
                            <li>Gérer l'entretien et la maintenance des locaux et équipements</li>
                            <li>Organiser la logistique des distributions et événements (ex: campagne "Donnez-leur vous-mêmes à manger")</li>
                            <li>Superviser l'inventaire du matériel et gérer les stocks</li>
                            <li>Coordonner les prestataires externes (entretien, gardiennage)</li>
                            <li>Gérer le parc automobile et les déplacements</li>
                            <li>Mettre en place des procédures d'utilisation des équipements</li>
                            <li>Assurer le respect des normes de sécurité et d'hygiène</li>
                            <li>Optimiser les coûts liés aux moyens généraux</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Secrétaire Général</p>
                        <p><span class="font-semibold">Supervise directement :</span> Agents d'entretien, Chauffeurs, Agents de sécurité</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Responsable administratif et financier, Responsables des programmes pour les besoins logistiques, Chargée de la gestion des bénévoles pour les besoins matériels</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Plan de maintenance des infrastructures et équipements</li>
                            <li>Inventaire actualisé des biens et matériels</li>
                            <li>Rapports mensuels sur l'état des infrastructures et des véhicules</li>
                            <li>Plannings logistiques des événements et activités</li>
                            <li>Budget prévisionnel des moyens généraux</li>
                            <li>Taux de disponibilité des équipements et véhicules</li>
                            <li>Qualité logistique des événements organisés</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Responsable juridique et adjoint du RAF -->
            <section id="juridique" class="job-card">
                 <div class="job-title">
                    <h2>RESPONSABLE JURIDIQUE ET ADJOINT DU RAF</h2>
                    <p>M. Maurice</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p>Le Responsable juridique et adjoint du RAF assure la conformité légale de toutes les activités de l'ONG et seconde le Responsable administratif et financier dans ses fonctions, contribuant ainsi à une gestion rigoureuse et éthique des ressources.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                         <ul class="list-custom">
                            <li>Garantir la conformité juridique de l'ONG et de ses activités</li>
                            <li>Assister le RAF dans la gestion administrative et financière</li>
                            <li>Sécuriser les relations contractuelles avec les partenaires et prestataires</li>
                            <li>Assurer la veille juridique et réglementaire</li>
                            <li>Conseiller la direction sur les aspects juridiques des décisions</li>
                        </ul>
                    </div>
                    <div class="section">
                         <h3 class="section-title">Responsabilités spécifiques</h3>
                         <ul class="list-custom">
                            <li>Élaborer et réviser les contrats et conventions de partenariat</li>
                            <li>Superviser les déclarations légales et administratives</li>
                            <li>Gérer les procédures d'appel d'offres et d'achats</li>
                            <li>Assurer le suivi des dossiers d'assurance et de contentieux</li>
                            <li>Participer à l'élaboration des procédures administratives et financières</li>
                            <li>Appuyer le RAF pour le suivi budgétaire et la gestion comptable</li>
                            <li>Coordonner les audits et contrôles internes</li>
                            <li>Former le personnel sur les aspects juridiques pertinents</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Responsable administratif et financier</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Secrétaire Général, Chargée du partenariat, Responsable des moyens généraux, Prestataires juridiques externes</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Base documentaire juridique à jour</li>
                            <li>Registres légaux conformes aux exigences</li>
                            <li>Veille juridique trimestrielle</li>
                            <li>Contrats et conventions sécurisés</li>
                            <li>Rapports d'audit interne</li>
                            <li>Taux de conformité légale et réglementaire</li>
                            <li>Absence ou résolution efficace des litiges juridiques</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Chargée de la gestion des bénévoles -->
            <section id="benevoles" class="job-card">
                 <div class="job-title">
                    <h2>CHARGÉE DE LA GESTION DES BÉNÉVOLES</h2>
                    <p>Mme Leila</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p>La Chargée de la gestion des bénévoles coordonne le recrutement, la formation, l'affectation et le suivi des bénévoles, en veillant à leur intégration harmonieuse dans l'ONG et à l'alignement de leur contribution avec les valeurs et la mission de NÉHÉMIE International.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                         <ul class="list-custom">
                            <li>Développer et mettre en œuvre la stratégie de bénévolat de l'ONG</li>
                            <li>Recruter, former et accompagner les bénévoles</li>
                            <li>Coordonner l'affectation des bénévoles selon les besoins des programmes</li>
                            <li>Assurer la reconnaissance et la fidélisation des bénévoles</li>
                            <li>Veiller à l'intégration des valeurs chrétiennes dans l'expérience bénévole</li>
                        </ul>
                    </div>
                    <div class="section">
                         <h3 class="section-title">Responsabilités spécifiques</h3>
                         <ul class="list-custom">
                            <li>Élaborer les procédures d'accueil et d'intégration des bénévoles</li>
                            <li>Identifier et évaluer les candidats bénévoles</li>
                            <li>Organiser et animer les sessions de formation initiale et continue</li>
                            <li>Créer et gérer la base de données des bénévoles</li>
                            <li>Planifier les affectations en fonction des compétences et disponibilités</li>
                            <li>Assurer le suivi et l'évaluation des activités bénévoles</li>
                            <li>Organiser des événements de reconnaissance et de cohésion</li>
                            <li>Développer des outils de communication dédiés aux bénévoles</li>
                            <li>Gérer les conflits et difficultés potentiels</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Secrétaire Général</p>
                        <p><span class="font-semibold">Supervise directement :</span> Référents bénévoles par programme</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Assistante de direction, Responsables des programmes, Responsable des moyens généraux, Chargée de l'informatique (pour la communication avec les bénévoles)</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Plan annuel de gestion des bénévoles</li>
                            <li>Programme de formation initial et continu</li>
                            <li>Base de données des bénévoles à jour</li>
                            <li>Tableaux d'affectation des bénévoles par activité</li>
                            <li>Rapports trimestriels sur l'engagement bénévole</li>
                            <li>Taux de satisfaction des bénévoles</li>
                            <li>Taux de rétention des bénévoles</li>
                            <li>Nombre d'heures bénévoles par programme</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Chargée de l'informatique (Nesta) -->
            <section id="it-chargée" class="job-card">
                 <div class="job-title">
                    <h2>CHARGÉE DE L'INFORMATIQUE</h2>
                    <p>Mme Nesta</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p>La Chargée de l'informatique est responsable de la gestion des systèmes d'information et des outils numériques de l'ONG, avec une attention particulière pour le site internet, assurant ainsi la visibilité digitale et l'efficacité opérationnelle de NÉHÉMIE International.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                         <ul class="list-custom">
                            <li>Gérer et développer le site internet de l'ONG</li>
                            <li>Assurer le bon fonctionnement des systèmes informatiques</li>
                            <li>Développer la stratégie de présence numérique de l'organisation</li>
                            <li>Former et accompagner les utilisateurs aux outils numériques</li>
                            <li>Garantir la sécurité des données et des systèmes</li>
                        </ul>
                    </div>
                    <div class="section">
                         <h3 class="section-title">Responsabilités spécifiques</h3>
                         <ul class="list-custom">
                            <li>Administrer et mettre à jour régulièrement le site internet</li>
                            <li>Gérer la présence sur les réseaux sociaux en lien avec la communication</li>
                            <li>Superviser la mise en œuvre du cahier des charges du site avec les prestataires</li>
                            <li>Assurer la maintenance du parc informatique</li>
                            <li>Gérer les accès et droits utilisateurs aux systèmes</li>
                            <li>Mettre en place des procédures de sauvegarde et de sécurité</li>
                            <li>Développer des solutions numériques adaptées aux besoins des programmes</li>
                            <li>Former le personnel à l'utilisation des outils informatiques</li>
                            <li>Analyser les statistiques de fréquentation du site et réseaux sociaux</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Président</p>
                        <p><span class="font-semibold">Supervise directement :</span> Assistant informatique, Prestataires externes (développeurs, hébergeurs)</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Chargée du partenariat (pour la communication externe), Tous les départements pour leurs besoins informatiques, Responsable administratif et financier (pour les budgets IT)</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Site internet fonctionnel et régulièrement mis à jour</li>
                            <li>Rapport mensuel sur l'activité des plateformes numériques</li>
                            <li>Plan de maintenance informatique</li>
                            <li>Procédures de sécurité et de sauvegarde</li>
                            <li>Statistiques de fréquentation du site et réseaux sociaux</li>
                            <li>Taux de disponibilité des systèmes informatiques</li>
                            <li>Délai de résolution des incidents informatiques</li>
                            <li>Satisfaction des utilisateurs internes vis-à-vis des outils</li>
                        </ul>
                    </div>
                </div>
            </section>

             <!-- Assistant informatique (Bienvenue) -->
            <section id="it-assistant" class="job-card">
                 <div class="job-title">
                    <h2>ASSISTANT INFORMATIQUE</h2>
                    <p>M. Bienvenue</p>
                </div>
                <div class="job-content">
                    <div class="section">
                        <h3 class="section-title">Description du poste</h3>
                        <p> En charge de la gestion et de la maintenance de l'infrastructure informatique générale de l'ONG. Il est le garant du bon fonctionnement des systèmes (réseau, serveurs, matériel, logiciels de base), de leur sécurité, et apporte le support technique de niveau supérieur pour l'ensemble de l'organisation. Il définit les standards et les politiques informatiques.</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Missions principales</h3>
                         <ul class="list-custom">
                            <li>Déployer, gérer et maintenir l'infrastructure informatique globale de l'ONG (matériel, logiciels essentiels, réseau)</li>
                            <li>Assurer la sécurité des systèmes d'information et la protection des données</li>
                            <li>Contribuer à la gestion et mise à jour du site internet</li>
                            <li>Définir et superviser l'application des politiques et procédures d'utilisation des ressources IT</li>
                            <li>Aider à la formation des utilisateurs sur les outils numériques</li>
                        </ul>
                    </div>
                    <div class="section">
                         <h3 class="section-title">Responsabilités spécifiques</h3>
                         <ul class="list-custom">
                            <li>Diagnostiquer et résoudre les problèmes informatiques courants</li>
                            <li>Installer et configurer les logiciels et matériels</li>
                            <li>Mettre à jour les contenus du site internet sous supervision</li>
                            <li>Gérer les sauvegardes quotidiennes des données</li>
                            <li>Maintenir l'inventaire du matériel informatique</li>
                            <li>Documenter les procédures informatiques</li>
                            <li>Apporter un soutien technique lors des événements</li>
                            <li>Participer à la publication sur les réseaux sociaux</li>
                            <li>Assurer une veille technologique</li>
                        </ul>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Liens fonctionnels</h3>
                        <p><span class="font-semibold">Rapporte à :</span> Au Président</p>
                        <p><span class="font-semibold">Collabore étroitement avec :</span> Ensemble du personnel pour le support informatique, Assistante de direction pour les besoins bureautiques, Responsable des moyens généraux (pour les équipements)</p>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Livrables attendus / Indicateurs de suivi</h3>
                        <ul class="list-custom">
                            <li>Journal des incidents et interventions techniques</li>
                            <li>Rapports hebdomadaires des activités informatiques</li>
                            <li>Inventaire à jour du matériel informatique</li>
                            <li>Délai moyen de résolution des incidents</li>
                            <li>Documentation technique des procédures</li>
                            <li>Taux de satisfaction des utilisateurs sur le support</li>
                            <li>Régularité des mises à jour du site et des réseaux sociaux</li>
                        </ul>
                    </div>
                </div>
            </section>


        </div>

        <!-- Footer -->
        <footer class="footer">
            <p>Document confidentiel à usage interne - NÉHÉMIE International © 2025</p>
            <p class="text-sm">Siège social : Quartier Charbonnages à Libreville</p>
            <p class="text-sm">Récépissé définitif ONG NÉHÉMIE N° 000253/MI/SG/BMB</p>
            <div class="mt-4">
                <p>+241 62 39 31 04 / 74 17 77 49</p>
                <p>info@nehemie-international.com</p>
            </div>
        </footer>
    </div>
</body>
</html>