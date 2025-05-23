<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan d'Action Détaillé - Stratégie NÉHÉMIE INTERNATIONAL 2025</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary-dark: #1A2A40;
            --color-primary-medium: #2c5d92;
            --color-accent: #F1A100;
            --color-text-dark: #333333;
            --color-text-medium: #555555;
            --color-text-light: #FFFFFF;
            --color-background-light: #FFFFFF;
            --color-background-section: #f8f9fa;
            --color-border-light: #dee2e6;
            --color-border-medium: #ced4da;

            --font-family-base: 'Lato', Arial, sans-serif;
            --font-family-headings: 'Montserrat', Arial, sans-serif;
            
            --font-size-base: 16px;
            --spacing-unit: 1rem;
            --border-radius: 5px;
            --box-shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: var(--font-family-base);
            line-height: 1.6;
            color: var(--color-text-dark);
            background-color: var(--color-background-light);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: calc(var(--spacing-unit) * 2) var(--spacing-unit); 
        }

        header {
            background-color: var(--color-primary-dark);
            color: var(--color-text-light);
            padding: calc(var(--spacing-unit) * 2) 0;
            text-align: center;
            border-bottom: 5px solid var(--color-accent);
            margin-bottom: calc(var(--spacing-unit) * 2);
        }
        header h1 {
            font-family: var(--font-family-headings);
            font-size: 2.2rem;
            margin: 0;
        }
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: calc(var(--spacing-unit) * 0.5);
        }

        .axis-section {
            margin-bottom: calc(var(--spacing-unit) * 3);
            padding: calc(var(--spacing-unit) * 1.5);
            background-color: var(--color-background-section);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-soft);
        }
        .axis-title {
            font-family: var(--font-family-headings);
            font-size: 1.8rem;
            color: var(--color-primary-dark);
            margin-top: 0; 
            margin-bottom: calc(var(--spacing-unit) * 1.5);
            padding-bottom: calc(var(--spacing-unit) * 0.5);
            border-bottom: 2px solid var(--color-accent);
        }

        .action-plan-card {
            background-color: var(--color-background-light);
            border: 1px solid var(--color-border-light);
            border-left: 5px solid var(--color-primary-medium);
            border-radius: var(--border-radius);
            padding: calc(var(--spacing-unit) * 1.5);
            margin-bottom: calc(var(--spacing-unit) * 1.5);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .action-plan-title {
            font-family: var(--font-family-headings);
            font-size: 1.4rem;
            color: var(--color-primary-medium);
            margin-top: 0;
            margin-bottom: var(--spacing-unit);
        }

        .task-list {
            list-style-type: none;
            padding-left: 0;
            margin-top: 0; 
        }
        .task-item {
            display: flex;
            align-items: flex-start; 
            padding: calc(var(--spacing-unit) * 0.5) 0;
            border-bottom: 1px dashed var(--color-border-light);
            font-size: 0.95rem;
            color: var(--color-text-medium);
        }
        .task-item:last-child {
            border-bottom: none;
        }
        .task-icon {
            margin-right: calc(var(--spacing-unit) * 0.75);
            color: var(--color-accent);
            font-size: 1.1em; 
            line-height: 1.5; 
            flex-shrink: 0; 
            width: 1.5em; /* Pour aligner les textes même avec des emojis différents */
            text-align: center;
        }
        .task-content {
            flex-grow: 1;
        }
        .task-id {
            font-weight: 700;
            color: var(--color-primary-dark);
            margin-right: calc(var(--spacing-unit) * 0.5);
        }

        .global-prerequisites .action-plan-card { 
            background-color: #eef7ff; 
            border-left-color: var(--color-accent);
        }
        .global-prerequisites .action-plan-title {
            color: var(--color-accent);
        }

        .integration-section .action-plan-card {
            border-left-color: #28a745; 
        }
        .integration-section .action-plan-title {
            color: #28a745;
        }
        .integration-section .task-icon {
            color: #1e7e34; 
        }

        .action-plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 320px), 1fr)); 
            gap: calc(var(--spacing-unit) * 1.5);
        }
         @media (max-width: 767px) { 
            .action-plans-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Plan d'Action Détaillé</h1>
        <p>Stratégie NÉHÉMIE INTERNATIONAL (Mai - Décembre 2025)</p>
    </header>

    <div class="container">

        <section class="axis-section global-prerequisites">
            <h2 class="axis-title">PRÉ-REQUIS GLOBAUX / TRANSVERSAUX</h2>
            <div class="action-plans-grid">
                <div class="action-plan-card">
                    <h3 class="action-plan-title">Mise en place du Comité de Pilotage Stratégique</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">👥</span><div class="task-content"><span class="task-id">G1:</span> Définir les membres du comité.</div></li>
                        <li class="task-item"><span class="task-icon">🗓️</span><div class="task-content"><span class="task-id">G2:</span> Établir la fréquence et le format des réunions.</div></li>
                        <li class="task-item"><span class="task-icon">🛠️</span><div class="task-content"><span class="task-id">G3:</span> Mettre en place les outils de communication et de suivi.</div></li>
                    </ul>
                </div>
                <div class="action-plan-card">
                    <h3 class="action-plan-title">Choix et déploiement des Outils Collaboratifs</h3>
                     <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🖱️</span><div class="task-content"><span class="task-id">G4:</span> Sélectionner une plateforme de gestion de projet/tâches (ex: Trello, Asana version gratuite).</div></li>
                        <li class="task-item"><span class="task-icon">💾</span><div class="task-content"><span class="task-id">G5:</span> Mettre en place un espace de stockage partagé pour les documents (ex: Google Drive).</div></li>
                        <li class="task-item"><span class="task-icon">🎓</span><div class="task-content"><span class="task-id">G6:</span> Former l'équipe à l'utilisation de ces outils.</div></li>
                    </ul>
                </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">Communication Interne sur la Stratégie</h3>
                    <ul class="task-list">
                         <li class="task-item"><span class="task-icon">📢</span><div class="task-content"><span class="task-id">G7:</span> Organiser une réunion de lancement avec l'équipe et les bénévoles clés.</div></li>
                         <li class="task-item"><span class="task-icon">📖</span><div class="task-content"><span class="task-id">G8:</span> Mettre à disposition le document de stratégie finalisé pour tous.</div></li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- AXE 1: RAYONNEMENT ET VISIBILITÉ STRATÉGIQUE -->
        <section class="axis-section">
            <h2 class="axis-title">AXE 1: RAYONNEMENT ET VISIBILITÉ STRATÉGIQUE</h2>
            <div class="action-plans-grid">
                
                <div class="action-plan-card">
                    <h3 class="action-plan-title">1.1. Déploiement de l'Identité Visuelle</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🎨</span><div class="task-content"><span class="task-id">T1.1.1:</span> Collecter et valider tous les éléments de la charte graphique existante (logo, couleurs, typographies).</div></li>
                        <li class="task-item"><span class="task-icon">🤝</span><div class="task-content"><span class="task-id">T1.1.2:</span> Organiser une réunion de validation pour la plaquette "Donnez-leur vous-même à manger" (Projet 8).</div></li>
                        <li class="task-item"><span class="task-icon">✍️</span><div class="task-content"><span class="task-id">T1.1.3:</span> Mettre à jour la plaquette selon les retours de la réunion.</div></li>
                        <li class="task-item"><span class="task-icon">📝</span><div class="task-content"><span class="task-id">T1.1.4:</span> Concevoir des modèles de documents standards (Word, PowerPoint, en-têtes de lettre).</div></li>
                        <li class="task-item"><span class="task-icon">🖼️</span><div class="task-content"><span class="task-id">T1.1.5:</span> Créer des gabarits pour les visuels des réseaux sociaux.</div></li>
                        <li class="task-item"><span class="task-icon">📖</span><div class="task-content"><span class="task-id">T1.1.6:</span> Préparer un guide simple d'utilisation de la charte graphique.</div></li>
                        <li class="task-item"><span class="task-icon">🖨️</span><div class="task-content"><span class="task-id">T1.1.7:</span> Planifier l'impression des supports essentiels.</div></li>
                    </ul>
                </div>

                <div class="action-plan-card">
                    <h3 class="action-plan-title">1.2. Développement d'un Écosystème Digital</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🌐</span><div class="task-content"><span class="task-id">T1.2.1:</span> Finaliser la stratégie de communication globale (Projet 9) en intégrant les liens entre programmes.</div></li>
                        <li class="task-item"><span class="task-icon">🔍</span><div class="task-content"><span class="task-id">T1.2.2:</span> Auditer la présence digitale existante (site web, réseaux sociaux).</div></li>
                        <li class="task-item"><span class="task-icon">📋</span><div class="task-content"><span class="task-id">T1.2.3:</span> Définir les spécifications pour l'amélioration/création du site web.</div></li>
                        <li class="task-item"><span class="task-icon">👨‍💻</span><div class="task-content"><span class="task-id">T1.2.4:</span> Sélectionner une équipe interne/bénévole pour le développement du site.</div></li>
                        <li class="task-item"><span class="task-icon">🚀</span><div class="task-content"><span class="task-id">T1.2.5:</span> Développer ou améliorer le site web.</div></li>
                        <li class="task-item"><span class="task-icon">🖋️</span><div class="task-content"><span class="task-id">T1.2.6:</span> Rédiger et intégrer les contenus initiaux.</div></li>
                        <li class="task-item"><span class="task-icon">📱</span><div class="task-content"><span class="task-id">T1.2.7:</span> Créer/optimiser les profils sur les réseaux sociaux pertinents.</div></li>
                        <li class="task-item"><span class="task-icon">🗓️</span><div class="task-content"><span class="task-id">T1.2.8:</span> Élaborer un calendrier éditorial pour les réseaux sociaux.</div></li>
                        <li class="task-item"><span class="task-icon">🎓</span><div class="task-content"><span class="task-id">T1.2.9:</span> Former l'équipe à la gestion des réseaux sociaux.</div></li>
                    </ul>
                </div>
                
                <div class="action-plan-card">
                    <h3 class="action-plan-title">1.3. Création et Diffusion de Contenus Audiovisuels</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🎬</span><div class="task-content"><span class="task-id">T1.3.1:</span> Évaluer les équipements audiovisuels disponibles et identifier les besoins.</div></li>
                        <li class="task-item"><span class="task-icon">👥</span><div class="task-content"><span class="task-id">T1.3.2:</span> Recruter/mobiliser une équipe de bénévoles pour la création de contenu.</div></li>
                        <li class="task-item"><span class="task-icon">🛋️</span><div class="task-content"><span class="task-id">T1.3.3:</span> Planifier les aménagements pour l'émission "À l'image de Christ" (Projet 16).</div></li>
                        <li class="task-item"><span class="task-icon">💡</span><div class="task-content"><span class="task-id">T1.3.4:</span> Structurer le format et le contenu de l'émission.</div></li>
                        <li class="task-item"><span class="task-icon">▶️</span><div class="task-content"><span class="task-id">T1.3.5:</span> Produire un épisode pilote.</div></li>
                        <li class="task-item"><span class="task-icon">🎯</span><div class="task-content"><span class="task-id">T1.3.6:</span> Identifier des sujets pour des vidéos courtes (témoignages, présentation de projets).</div></li>
                        <li class="task-item"><span class="task-icon">✍️</span><div class="task-content"><span class="task-id">T1.3.7:</span> Scénariser et planifier les tournages.</div></li>
                        <li class="task-item"><span class="task-icon">🌟</span><div class="task-content"><span class="task-id">T1.3.8:</span> Capitaliser sur la mini-vidéo interne déjà réalisée (Projet 7).</div></li>
                    </ul>
                </div>
                
                <div class="action-plan-card">
                    <h3 class="action-plan-title">1.4. Relations Institutionnelles et Partenariats</h3>
                     <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🗺️</span><div class="task-content"><span class="task-id">T1.4.1:</span> Cartographier les partenaires potentiels (institutions, leaders d'opinion, médias).</div></li>
                        <li class="task-item"><span class="task-icon">📁</span><div class="task-content"><span class="task-id">T1.4.2:</span> Préparer un dossier de présentation de NÉHÉMIE International.</div></li>
                        <li class="task-item"><span class="task-icon">📞</span><div class="task-content"><span class="task-id">T1.4.3:</span> Initier les prises de contact (emails, appels, demandes de rendez-vous).</div></li>
                        <li class="task-item"><span class="task-icon">🤝</span><div class="task-content"><span class="task-id">T1.4.4:</span> Organiser et réaliser les rencontres avec les acteurs clés.</div></li>
                        <li class="task-item"><span class="task-icon">📈</span><div class="task-content"><span class="task-id">T1.4.5:</span> Assurer le suivi des relations établies.</div></li>
                        <li class="task-item"><span class="task-icon">💼</span><div class="task-content"><span class="task-id">T1.4.6:</span> Documenter et valoriser les engagements des entreprises donatrices (Projet 1).</div></li>
                    </ul>
                </div>

                <div class="action-plan-card">
                    <h3 class="action-plan-title">1.5. Organisation de Rencontres Thématiques</h3>
                     <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🎯</span><div class="task-content"><span class="task-id">T1.5.1:</span> Identifier 2-3 thèmes prioritaires pour les rencontres.</div></li>
                        <li class="task-item"><span class="task-icon">🗣️</span><div class="task-content"><span class="task-id">T1.5.2:</span> Définir le format, les objectifs et le public cible.</div></li>
                        <li class="task-item"><span class="task-icon">🎤</span><div class="task-content"><span class="task-id">T1.5.3:</span> Identifier et solliciter des intervenants.</div></li>
                        <li class="task-item"><span class="task-icon">📍</span><div class="task-content"><span class="task-id">T1.5.4:</span> Rechercher et réserver des lieux (privilégier partenariats gratuits).</div></li>
                        <li class="task-item"><span class="task-icon">📢</span><div class="task-content"><span class="task-id">T1.5.5:</span> Élaborer un plan de communication pour chaque événement.</div></li>
                        <li class="task-item"><span class="task-icon">🛠️</span><div class="task-content"><span class="task-id">T1.5.6:</span> Gérer la logistique des événements.</div></li>
                    </ul>
                </div>

            </div> 
        </section> 

        <!-- AXE 2: GOUVERNANCE ET STRUCTURATION ORGANISATIONNELLE -->
        <section class="axis-section">
            <h2 class="axis-title">AXE 2: GOUVERNANCE ET STRUCTURATION ORGANISATIONNELLE</h2>
            <div class="action-plans-grid">
                <div class="action-plan-card">
                    <h3 class="action-plan-title">2.1. Consolidation du Cadre Juridique</h3>
                     <ul class="task-list">
                        <li class="task-item"><span class="task-icon">⚖️</span><div class="task-content"><span class="task-id">T2.1.1:</span> Réaliser un audit juridique interne des documents existants.</div></li>
                        <li class="task-item"><span class="task-icon">🧐</span><div class="task-content"><span class="task-id">T2.1.2:</span> Identifier les besoins de mise à jour des statuts et du règlement intérieur.</div></li>
                        <li class="task-item"><span class="task-icon">🔄</span><div class="task-content"><span class="task-id">T2.1.3:</span> Suivre et finaliser la régularisation contractuelle (obtention bail, personnalisation local - Projet 5).</div></li>
                        <li class="task-item"><span class="task-icon">🏫</span><div class="task-content"><span class="task-id">T2.1.4:</span> Finaliser la rédaction des textes fondateurs pour la création de l'école (Projet 11).</div></li>
                        <li class="task-item"><span class="task-icon">⛪</span><div class="task-content"><span class="task-id">T2.1.5:</span> Finaliser la rédaction des textes fondateurs pour l'implantation de l'Église (Projet 12).</div></li>
                        <li class="task-item"><span class="task-icon">✅</span><div class="task-content"><span class="task-id">T2.1.6:</span> Effectuer les démarches administratives nécessaires.</div></li>
                    </ul>
                </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">2.2. Structure de Gouvernance Participative</h3>
                     <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🏛️</span><div class="task-content"><span class="task-id">T2.2.1:</span> Définir clairement les rôles et responsabilités du Conseil d'Administration.</div></li>
                        <li class="task-item"><span class="task-icon">🧩</span><div class="task-content"><span class="task-id">T2.2.2:</span> Identifier les besoins en comités de travail thématiques.</div></li>
                        <li class="task-item"><span class="task-icon">📝</span><div class="task-content"><span class="task-id">T2.2.3:</span> Rédiger les mandats pour chaque comité et identifier des responsables.</div></li>
                        <li class="task-item"><span class="task-icon">📅</span><div class="task-content"><span class="task-id">T2.2.4:</span> Organiser les premières réunions des instances de gouvernance.</div></li>
                        <li class="task-item"><span class="task-icon">📜</span><div class="task-content"><span class="task-id">T2.2.5:</span> Rédiger une charte de gouvernance simple.</div></li>
                        <li class="task-item"><span class="task-icon">📊</span><div class="task-content"><span class="task-id">T2.2.6:</span> Mettre en place un système de compte-rendu et de suivi des décisions.</div></li>
                    </ul>
                </div>
                <div class="action-plan-card">
                    <h3 class="action-plan-title">2.3. Système de Gestion Financière</h3>
                     <ul class="task-list">
                        <li class="task-item"><span class="task-icon">💰</span><div class="task-content"><span class="task-id">T2.3.1:</span> Définir les procédures de gestion avec le financier recruté (Projet 6).</div></li>
                        <li class="task-item"><span class="task-icon">💻</span><div class="task-content"><span class="task-id">T2.3.2:</span> Choisir/configurer les outils de suivi financier.</div></li>
                        <li class="task-item"><span class="task-icon">🎓</span><div class="task-content"><span class="task-id">T2.3.3:</span> Former l'équipe aux nouvelles procédures.</div></li>
                        <li class="task-item"><span class="task-icon">🗂️</span><div class="task-content"><span class="task-id">T2.3.4:</span> Mettre en place un système de classement des pièces comptables.</div></li>
                        <li class="task-item"><span class="task-icon">🗓️</span><div class="task-content"><span class="task-id">T2.3.5:</span> Établir un calendrier pour les rapports financiers.</div></li>
                        <li class="task-item"><span class="task-icon">🤝</span><div class="task-content"><span class="task-id">T2.3.6:</span> Explorer des pistes de partenariats financiers et optimiser le recouvrement.</div></li>
                    </ul>
                </div>
                <div class="action-plan-card">
                    <h3 class="action-plan-title">2.4. Équipe Opérationnelle Engagée</h3>
                     <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🎯</span><div class="task-content"><span class="task-id">T2.4.1:</span> Préciser les besoins en ressources humaines par projet/axe.</div></li>
                        <li class="task-item"><span class="task-icon">📋</span><div class="task-content"><span class="task-id">T2.4.2:</span> Rédiger des descriptifs de rôles clairs.</div></li>
                        <li class="task-item"><span class="task-icon">📢</span><div class="task-content"><span class="task-id">T2.4.3:</span> Lancer une campagne de mobilisation des bénévoles.</div></li>
                        <li class="task-item"><span class="task-icon">🔑</span><div class="task-content"><span class="task-id">T2.4.4:</span> Finaliser la responsabilisation des membres clés identifiés (Projet 4).</div></li>
                        <li class="task-item"><span class="task-icon">📊</span><div class="task-content"><span class="task-id">T2.4.5:</span> Élaborer un organigramme fonctionnel.</div></li>
                        <li class="task-item"><span class="task-icon">🗣️</span><div class="task-content"><span class="task-id">T2.4.6:</span> Mettre en place des réunions d'équipe régulières.</div></li>
                        <li class="task-item"><span class="task-icon">⭐</span><div class="task-content"><span class="task-id">T2.4.7:</span> Développer un système de reconnaissance de l'engagement.</div></li>
                    </ul>
                </div>
                <div class="action-plan-card">
                    <h3 class="action-plan-title">2.5. Stratégie de Mobilisation de Ressources</h3>
                     <ul class="task-list">
                        <li class="task-item"><span class="task-icon">📈</span><div class="task-content"><span class="task-id">T2.5.1:</span> Analyser les besoins financiers globaux de la stratégie.</div></li>
                        <li class="task-item"><span class="task-icon">🔍</span><div class="task-content"><span class="task-id">T2.5.2:</span> Identifier les différentes sources de financement potentielles.</div></li>
                        <li class="task-item"><span class="task-icon">📝</span><div class="task-content"><span class="task-id">T2.5.3:</span> Élaborer des argumentaires et des supports pour la collecte de fonds.</div></li>
                        <li class="task-item"><span class="task-icon">📋</span><div class="task-content"><span class="task-id">T2.5.4:</span> Mettre en place un système de suivi des donateurs et des dons.</div></li>
                        <li class="task-item"><span class="task-icon">🚀</span><div class="task-content"><span class="task-id">T2.5.5:</span> Lancer des premières initiatives de collecte.</div></li>
                        <li class="task-item"><span class="task-icon">🎓</span><div class="task-content"><span class="task-id">T2.5.6:</span> Former une petite équipe de bénévoles à la mobilisation de ressources.</div></li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- AXE 3: DÉVELOPPEMENT COMMUNAUTAIRE ET ENGAGEMENT -->
         <section class="axis-section">
            <h2 class="axis-title">AXE 3: DÉVELOPPEMENT COMMUNAUTAIRE ET ENGAGEMENT</h2>
            <div class="action-plans-grid">
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">3.1. Programme d'Intégration des Membres</h3>
                     <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🧩</span><div class="task-content"><span class="task-id">T3.1.1:</span> Concevoir le contenu et le format du parcours d'intégration.</div></li>
                        <li class="task-item"><span class="task-icon">📖</span><div class="task-content"><span class="task-id">T3.1.2:</span> Créer les supports d'information (livret d'accueil, présentation).</div></li>
                        <li class="task-item"><span class="task-icon">🎓</span><div class="task-content"><span class="task-id">T3.1.3:</span> Former l'équipe d'accueil et d'intégration.</div></li>
                        <li class="task-item"><span class="task-icon">🎉</span><div class="task-content"><span class="task-id">T3.1.4:</span> Organiser les premières sessions d'accueil.</div></li>
                        <li class="task-item"><span class="task-icon">📊</span><div class="task-content"><span class="task-id">T3.1.5:</span> Mettre en place un système de suivi des nouveaux membres.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">3.2. Valorisation et Mobilisation des Compétences</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">📝</span><div class="task-content"><span class="task-id">T3.2.1:</span> Concevoir un formulaire de recensement des compétences et talents.</div></li>
                        <li class="task-item"><span class="task-icon">📢</span><div class="task-content"><span class="task-id">T3.2.2:</span> Communiquer et lancer la campagne de recensement.</div></li>
                        <li class="task-item"><span class="task-icon">💾</span><div class="task-content"><span class="task-id">T3.2.3:</span> Compiler les informations dans une base de données.</div></li>
                        <li class="task-item"><span class="task-icon">🔗</span><div class="task-content"><span class="task-id">T3.2.4:</span> Mettre en relation les besoins des projets avec les compétences identifiées.</div></li>
                        <li class="task-item"><span class="task-icon">🤝</span><div class="task-content"><span class="task-id">T3.2.5:</span> Organiser des rencontres entre responsables de projets et bénévoles.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">3.3. Cellules Communautaires Dynamiques</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">💡</span><div class="task-content"><span class="task-id">T3.3.1:</span> Définir le modèle des cellules (thématiques, géographiques, objectifs).</div></li>
                        <li class="task-item"><span class="task-icon">📖</span><div class="task-content"><span class="task-id">T3.3.2:</span> Créer des guides d'animation et supports thématiques.</div></li>
                        <li class="task-item"><span class="task-icon">🎓</span><div class="task-content"><span class="task-id">T3.3.3:</span> Identifier et former les premiers leaders de cellules.</div></li>
                        <li class="task-item"><span class="task-icon">🚀</span><div class="task-content"><span class="task-id">T3.3.4:</span> Lancer des cellules pilotes et accompagner leur démarrage.</div></li>
                        <li class="task-item"><span class="task-icon">📊</span><div class="task-content"><span class="task-id">T3.3.5:</span> Mettre en place un système de suivi pour les leaders.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">3.4. Programme d'Ambassadeurs</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">⭐</span><div class="task-content"><span class="task-id">T3.4.1:</span> Définir les critères de sélection et le rôle des ambassadeurs.</div></li>
                        <li class="task-item"><span class="task-icon">📁</span><div class="task-content"><span class="task-id">T3.4.2:</span> Créer un kit de présentation (argumentaire, supports visuels).</div></li>
                        <li class="task-item"><span class="task-icon">🔍</span><div class="task-content"><span class="task-id">T3.4.3:</span> Identifier et recruter les premiers ambassadeurs.</div></li>
                        <li class="task-item"><span class="task-icon">🎓</span><div class="task-content"><span class="task-id">T3.4.4:</span> Organiser une session de formation.</div></li>
                        <li class="task-item"><span class="task-icon">📈</span><div class="task-content"><span class="task-id">T3.4.5:</span> Coordonner et suivre les actions des ambassadeurs.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">3.5. Événements Communautaires Fédérateurs</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🗓️</span><div class="task-content"><span class="task-id">T3.5.1:</span> Planifier un calendrier d'événements communautaires.</div></li>
                        <li class="task-item"><span class="task-icon">👥</span><div class="task-content"><span class="task-id">T3.5.2:</span> Constituer des équipes d'organisation pour chaque événement.</div></li>
                        <li class="task-item"><span class="task-icon">💰</span><div class="task-content"><span class="task-id">T3.5.3:</span> Définir le budget et rechercher des moyens de financement.</div></li>
                        <li class="task-item"><span class="task-icon">📢</span><div class="task-content"><span class="task-id">T3.5.4:</span> Gérer la communication et la promotion.</div></li>
                        <li class="task-item"><span class="task-icon">🛠️</span><div class="task-content"><span class="task-id">T3.5.5:</span> Coordonner la logistique et l'animation.</div></li>
                        <li class="task-item"><span class="task-icon">📊</span><div class="task-content"><span class="task-id">T3.5.6:</span> Évaluer l'impact et la satisfaction des participants.</div></li>
                    </ul>
                 </div>
            </div>
        </section>

        <!-- AXE 4: FORMATION ET AUTONOMISATION ÉCONOMIQUE -->
        <section class="axis-section">
            <h2 class="axis-title">AXE 4: FORMATION ET AUTONOMISATION ÉCONOMIQUE</h2>
            <div class="action-plans-grid">
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">4.1. Ateliers de Compétences Professionnelles</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🤔</span><div class="task-content"><span class="task-id">T4.1.1:</span> Finaliser la réflexion sur les "Formations NÉHÉMIE" (Projet 13).</div></li>
                        <li class="task-item"><span class="task-icon">🎯</span><div class="task-content"><span class="task-id">T4.1.2:</span> Identifier les besoins en formation prioritaires.</div></li>
                        <li class="task-item"><span class="task-icon">📚</span><div class="task-content"><span class="task-id">T4.1.3:</span> Développer le contenu pédagogique pour les premiers ateliers.</div></li>
                        <li class="task-item"><span class="task-icon">👨‍🏫</span><div class="task-content"><span class="task-id">T4.1.4:</span> Recruter des formateurs bénévoles qualifiés.</div></li>
                        <li class="task-item"><span class="task-icon">🗓️</span><div class="task-content"><span class="task-id">T4.1.5:</span> Planifier le calendrier et organiser la logistique.</div></li>
                        <li class="task-item"><span class="task-icon">📢</span><div class="task-content"><span class="task-id">T4.1.6:</span> Promouvoir les ateliers et gérer les inscriptions.</div></li>
                        <li class="task-item"><span class="task-icon">▶️</span><div class="task-content"><span class="task-id">T4.1.7:</span> Animer les ateliers et évaluer les acquis.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">4.2. Soutien aux Entrepreneurs Chrétiens</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">⚙️</span><div class="task-content"><span class="task-id">T4.2.1:</span> Définir le format et la fréquence des rencontres.</div></li>
                        <li class="task-item"><span class="task-icon">💡</span><div class="task-content"><span class="task-id">T4.2.2:</span> Identifier des thèmes de discussion et des intervenants.</div></li>
                        <li class="task-item"><span class="task-icon">👥</span><div class="task-content"><span class="task-id">T4.2.3:</span> Mobiliser les premiers participants (porteurs de projets, entrepreneurs).</div></li>
                        <li class="task-item"><span class="task-icon">🗣️</span><div class="task-content"><span class="task-id">T4.2.4:</span> Animer les rencontres et faciliter les échanges.</div></li>
                        <li class="task-item"><span class="task-icon">🔗</span><div class="task-content"><span class="task-id">T4.2.5:</span> Mettre en place un système de partage de ressources.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">4.3. Programme d'Anglais Professionnel</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🔍</span><div class="task-content"><span class="task-id">T4.3.1:</span> Évaluer le programme existant et identifier les besoins.</div></li>
                        <li class="task-item"><span class="task-icon">🇬🇧</span><div class="task-content"><span class="task-id">T4.3.2:</span> Structurer le curriculum du Club d'Anglais (Projet 14).</div></li>
                        <li class="task-item"><span class="task-icon">📚</span><div class="task-content"><span class="task-id">T4.3.3:</span> Sélectionner/créer les supports pédagogiques.</div></li>
                        <li class="task-item"><span class="task-icon">👨‍🏫</span><div class="task-content"><span class="task-id">T4.3.4:</span> Recruter et former les animateurs/formateurs.</div></li>
                        <li class="task-item"><span class="task-icon">📝</span><div class="task-content"><span class="task-id">T4.3.5:</span> Organiser les inscriptions et les tests de niveau.</div></li>
                        <li class="task-item"><span class="task-icon">🚀</span><div class="task-content"><span class="task-id">T4.3.6:</span> Lancer les cours du Club d'Anglais.</div></li>
                        <li class="task-item"><span class="task-icon">📊</span><div class="task-content"><span class="task-id">T4.3.7:</span> Suivre la progression des apprenants.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">4.4. Sensibilisation à l'Entrepreneuriat</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">📚</span><div class="task-content"><span class="task-id">T4.4.1:</span> Développer le contenu des sessions (modules, études de cas).</div></li>
                        <li class="task-item"><span class="task-icon">🎤</span><div class="task-content"><span class="task-id">T4.4.2:</span> Identifier des intervenants qualifiés.</div></li>
                        <li class="task-item"><span class="task-icon">🗓️</span><div class="task-content"><span class="task-id">T4.4.3:</span> Planifier et promouvoir les sessions.</div></li>
                        <li class="task-item"><span class="task-icon">▶️</span><div class="task-content"><span class="task-id">T4.4.4:</span> Animer les sessions et encourager l'émergence d'idées.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">4.5. Incubateur de Micro-Projets</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">⚙️</span><div class="task-content"><span class="task-id">T4.5.1:</span> Concevoir le programme d'incubation (critères, étapes, services).</div></li>
                        <li class="task-item"><span class="task-icon">🏗️</span><div class="task-content"><span class="task-id">T4.5.2:</span> Intégrer et structurer l'accompagnement du projet "Oser bâtir" (Projet 10).</div></li>
                        <li class="task-item"><span class="task-icon">👥</span><div class="task-content"><span class="task-id">T4.5.3:</span> Recruter et former l'équipe d'accompagnement.</div></li>
                        <li class="task-item"><span class="task-icon">📢</span><div class="task-content"><span class="task-id">T4.5.4:</span> Lancer un appel à projets ou identifier des initiatives existantes.</div></li>
                        <li class="task-item"><span class="task-icon">✅</span><div class="task-content"><span class="task-id">T4.5.5:</span> Sélectionner les micro-projets à accompagner.</div></li>
                        <li class="task-item"><span class="task-icon">🤝</span><div class="task-content"><span class="task-id">T4.5.6:</span> Mettre en œuvre l'accompagnement (mentorat, formations).</div></li>
                        <li class="task-item"><span class="task-icon">📈</span><div class="task-content"><span class="task-id">T4.5.7:</span> Suivre la progression des projets.</div></li>
                    </ul>
                 </div>
            </div>
        </section>

        <!-- AXE 5: FONDATION ET TRANSFORMATION SPIRITUELLES -->
        <section class="axis-section">
            <h2 class="axis-title">AXE 5: FONDATION ET TRANSFORMATION SPIRITUELLES</h2>
            <div class="action-plans-grid">
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">5.1. Enseignement Biblique Pratique</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">✝️</span><div class="task-content"><span class="task-id">T5.1.1:</span> Élaborer le curriculum d'enseignement, en articulation avec le projet d'école.</div></li>
                        <li class="task-item"><span class="task-icon">📚</span><div class="task-content"><span class="task-id">T5.1.2:</span> Préparer les supports d'étude.</div></li>
                        <li class="task-item"><span class="task-icon">👨‍🏫</span><div class="task-content"><span class="task-id">T5.1.3:</span> Identifier et former les enseignants.</div></li>
                        <li class="task-item"><span class="task-icon">🗓️</span><div class="task-content"><span class="task-id">T5.1.4:</span> Planifier le calendrier et la logistique.</div></li>
                        <li class="task-item"><span class="task-icon">🚀</span><div class="task-content"><span class="task-id">T5.1.5:</span> Lancer les modules d'enseignement.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">5.2. Ministère d'Intercession</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🛠️</span><div class="task-content"><span class="task-id">T5.2.1:</span> Finaliser les travaux de la salle de prière (Projet 2).</div></li>
                        <li class="task-item"><span class="task-icon">🔄</span><div class="task-content"><span class="task-id">T5.2.2:</span> Planifier la reprise de "La Prière du Bâtisseur" (Projet 15).</div></li>
                        <li class="task-item"><span class="task-icon">👥</span><div class="task-content"><span class="task-id">T5.2.3:</span> Constituer et former des équipes de prière.</div></li>
                        <li class="task-item"><span class="task-icon">🗓️</span><div class="task-content"><span class="task-id">T5.2.4:</span> Établir un calendrier pour les temps de prière collectifs.</div></li>
                        <li class="task-item"><span class="task-icon">📋</span><div class="task-content"><span class="task-id">T5.2.5:</span> Mettre en place un système de recueil des sujets et témoignages.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">5.3. Leadership Spirituel par le Mentorat</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🧭</span><div class="task-content"><span class="task-id">T5.3.1:</span> Définir le cadre du programme (objectifs, attentes, durée).</div></li>
                        <li class="task-item"><span class="task-icon">🔍</span><div class="task-content"><span class="task-id">T5.3.2:</span> Identifier et recruter des mentors expérimentés.</div></li>
                        <li class="task-item"><span class="task-icon">🧑‍🎓</span><div class="task-content"><span class="task-id">T5.3.3:</span> Identifier des mentorés potentiels.</div></li>
                        <li class="task-item"><span class="task-icon">🎓</span><div class="task-content"><span class="task-id">T5.3.4:</span> Organiser une formation pour les mentors.</div></li>
                        <li class="task-item"><span class="task-icon">🤝</span><div class="task-content"><span class="task-id">T5.3.5:</span> Faciliter la mise en relation mentor-mentoré.</div></li>
                        <li class="task-item"><span class="task-icon">📖</span><div class="task-content"><span class="task-id">T5.3.6:</span> Proposer des ressources et guides de discussion.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">5.4. Accompagnement Spirituel Personnalisé</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">📜</span><div class="task-content"><span class="task-id">T5.4.1:</span> Définir les principes et le cadre de l'accompagnement.</div></li>
                        <li class="task-item"><span class="task-icon">👥</span><div class="task-content"><span class="task-id">T5.4.2:</span> Recruter et former une équipe d'accompagnateurs.</div></li>
                        <li class="task-item"><span class="task-icon">🔒</span><div class="task-content"><span class="task-id">T5.4.3:</span> Mettre en place un système confidentiel de prise de rendez-vous.</div></li>
                        <li class="task-item"><span class="task-icon">🚪</span><div class="task-content"><span class="task-id">T5.4.4:</span> Assurer la disponibilité d'espaces discrets.</div></li>
                        <li class="task-item"><span class="task-icon">📢</span><div class="task-content"><span class="task-id">T5.4.5:</span> Communiquer sur la disponibilité de ce service.</div></li>
                    </ul>
                 </div>
                 <div class="action-plan-card">
                    <h3 class="action-plan-title">5.5. Initiatives d'Impact Communautaire</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">🎯</span><div class="task-content"><span class="task-id">T5.5.1:</span> Identifier des besoins sociaux dans l'environnement.</div></li>
                        <li class="task-item"><span class="task-icon">⛪</span><div class="task-content"><span class="task-id">T5.5.2:</span> Accompagner le développement du projet d'implantation de l'Église (Projet 12).</div></li>
                        <li class="task-item"><span class="task-icon">⚙️</span><div class="task-content"><span class="task-id">T5.5.3:</span> Aider à la structuration des initiatives (objectifs, plan d'action).</div></li>
                        <li class="task-item"><span class="task-icon">🤝</span><div class="task-content"><span class="task-id">T5.5.4:</span> Faciliter la mobilisation de ressources pour ces initiatives.</div></li>
                        <li class="task-item"><span class="task-icon">✨</span><div class="task-content"><span class="task-id">T5.5.5:</span> Suivre et valoriser l'impact des actions menées.</div></li>
                    </ul>
                 </div>
            </div>
        </section>

        <!-- INTÉGRATION ET MISE EN ŒUVRE -->
        <section class="axis-section integration-section">
            <h2 class="axis-title">INTÉGRATION ET MISE EN ŒUVRE</h2>
            <div class="action-plans-grid">
                <div class="action-plan-card">
                    <h3 class="action-plan-title">Suivi et Coordination</h3>
                    <ul class="task-list">
                        <li class="task-item"><span class="task-icon">✅</span><div class="task-content"><span class="task-id">I.1:</span> Valider la chronologie et les priorités des phases.</div></li>
                        <li class="task-item"><span class="task-icon">👤</span><div class="task-content"><span class="task-id">I.2:</span> Assigner des responsables pour chaque plan d'action.</div></li>
                        <li class="task-item"><span class="task-icon">🏁</span><div class="task-content"><span class="task-id">I.3:</span> Définir des jalons clés pour chaque phase.</div></li>
                        <li class="task-item"><span class="task-icon">🗣️</span><div class="task-content"><span class="task-id">I.4:</span> Organiser des réunions de coordination régulières.</div></li>
                        <li class="task-item"><span class="task-icon">📊</span><div class="task-content"><span class="task-id">I.5:</span> Finaliser les indicateurs de performance pour chaque action.</div></li>
                        <li class="task-item"><span class="task-icon">📋</span><div class="task-content"><span class="task-id">I.6:</span> Mettre en place les outils de collecte de données.</div></li>
                        <li class="task-item"><span class="task-icon">🗓️</span><div class="task-content"><span class="task-id">I.7:</span> Planifier les revues mensuelles et les évaluations trimestrielles.</div></li>
                        <li class="task-item"><span class="task-icon">📢</span><div class="task-content"><span class="task-id">I.8:</span> Préparer des communications régulières sur les avancées.</div></li>
                        <li class="task-item"><span class="task-icon">🎉</span><div class="task-content"><span class="task-id">I.9:</span> Planifier des moments de célébration des réussites.</div></li>
                    </ul>
                </div>
            </div>
        </section>

    </div> 

</body>
</html>