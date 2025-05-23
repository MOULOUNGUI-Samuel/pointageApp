<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feuille de Route Opérationnelle - ONG NÉHÉMIE International</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #5D4037;
            --secondary-color: #5DAEDF;
            --accent-color: #C78A44;
            --light-bg: #F5F0E8;
            --white: #FFFFFF;
            --dark-text: #333333;
            --medium-text: #666666;
            --light-text: #999999;
            --success: #4CAF50;
            --warning: #FF9800;
            --danger: #F44336;
            --info: #2196F3;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--dark-text);
            background-color: var(--light-bg);
            padding: 0;
            margin: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 2rem 0;
            text-align: center;
            position: relative;
        }
        
        header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color), var(--accent-color));
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        h2 {
            font-size: 2rem;
            border-bottom: 3px solid var(--accent-color);
            padding-bottom: 0.5rem;
            margin-top: 2.5rem;
            margin-bottom: 1.5rem;
        }
        
        h3 {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-top: 2rem;
        }
        
        p {
            margin-bottom: 1.5rem;
        }
        
        .tagline {
            font-family: 'Lora', serif;
            font-style: italic;
            font-size: 1.2rem;
            margin-top: 0.5rem;
        }
        
        .intro {
            background-color: var(--white);
            padding: 2rem;
            margin: 2rem 0;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .phase-section {
            background-color: var(--white);
            padding: 2rem;
            margin: 2rem 0;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            position: relative;
            overflow: hidden;
        }
        
        .phase-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }
        
        .phase-preliminary::before { background-color: #9C27B0; }
        .phase-1::before { background-color: var(--primary-color); }
        .phase-2::before { background-color: var(--secondary-color); }
        .phase-3::before { background-color: var(--accent-color); }
        .phase-4::before { background-color: #4CAF50; }
        .phase-5::before { background-color: #FF5722; }
        
        .phase-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .phase-title {
            margin: 0;
            flex-grow: 1;
        }
        
        .phase-period {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            background-color: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }
        
        th {
            background-color: var(--primary-color);
            color: var(--white);
            text-align: left;
            padding: 1rem;
            font-weight: 600;
        }
        
        td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background-color: rgba(93, 64, 55, 0.05);
        }
        
        .status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-align: center;
        }
        
        .status-todo {
            background-color: var(--light-bg);
            color: var(--medium-text);
        }
        
        .status-progress {
            background-color: var(--warning);
            color: var(--white);
        }
        
        .status-done {
            background-color: var(--success);
            color: var(--white);
        }
        
        .coordination-section, .risks-section {
            background-color: var(--white);
            padding: 2rem;
            margin: 2rem 0;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .risk-table td:nth-child(2), 
        .risk-table td:nth-child(3) {
            text-align: center;
        }
        
        .probability-high, .impact-high {
            color: var(--danger);
            font-weight: 600;
        }
        
        .probability-medium, .impact-medium {
            color: var(--warning);
            font-weight: 600;
        }
        
        .probability-low, .impact-low {
            color: var(--success);
            font-weight: 600;
        }
        
        .conclusion {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 2rem;
            margin: 2rem 0;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .conclusion h2 {
            color: var(--white);
            border-bottom-color: var(--accent-color);
        }
        
        .bible-quote {
            font-family: 'Lora', serif;
            font-style: italic;
            text-align: center;
            margin: 2rem 0;
            font-size: 1.2rem;
            color: var(--accent-color);
        }
        
        .bible-source {
            display: block;
            text-align: right;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }
        
        footer {
            background-color: var(--primary-color);
            color: var(--white);
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .footer-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            h3 {
                font-size: 1.2rem;
            }
            
            .phase-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .phase-period {
                margin-top: 0.5rem;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 0.75rem;
            }
        }
        
        /* Print styles */
        @media print {
            body {
                background-color: white;
                color: black;
            }
            
            .container {
                max-width: 100%;
                padding: 0;
            }
            
            header, footer, .conclusion {
                background-color: white !important;
                color: black !important;
            }
            
            .phase-section, .coordination-section, .risks-section, .intro {
                box-shadow: none;
                margin: 1rem 0;
                page-break-inside: avoid;
            }
            
            table {
                box-shadow: none;
                page-break-inside: avoid;
            }
            
            th {
                background-color: #f0f0f0 !important;
                color: black !important;
            }
            
            .status {
                border: 1px solid #ccc;
            }
            
            h2, h3 {
                page-break-after: avoid;
            }
            
            h2 {
                color: black;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Feuille de Route Opérationnelle</h1>
            <h2 style="color: white; border-bottom: none;">ONG NÉHÉMIE International</h2>
            <p>Période : 1er juin - 31 décembre 2025</p>
            <p class="tagline">"Levons-nous et bâtissons !"</p>
        </div>
    </header>
    
    <div class="container">
        <section class="intro">
            <h2>Introduction</h2>
            <p>Ce document présente la feuille de route opérationnelle pour le lancement des activités de l'ONG NÉHÉMIE International sur la période du 1er juin au 31 décembre 2025. Il est conçu comme un outil de pilotage interne pour l'équipe de coordination, détaillant les étapes clés, les responsabilités, les ressources nécessaires et les échéances à respecter.</p>
            
            <p>La structure chronologique intègre les échéances prioritaires suivantes :</p>
            <ul>
                <li>Lancement de la campagne de levée de fonds "Donnez-leur vous-mêmes à manger" le 1er juin 2025</li>
                <li>Démarrage des démarches de partenariat auprès des institutions internationales à partir du 15 juin 2025</li>
            </ul>
        </section>
        
        <section>
            <h2>Étapes Clés du Déploiement</h2>
            
            <div class="phase-section phase-preliminary">
                <div class="phase-header">
                    <h3 class="phase-title">PHASE PRÉLIMINAIRE</h3>
                    <span class="phase-period">15-31 mai 2025</span>
                </div>
                <ul>
                    <li>Finalisation des préparatifs pour le lancement de la campagne "Donnez-leur vous-mêmes à manger"</li>
                    <li>Constitution des équipes opérationnelles initiales</li>
                    <li>Préparation des supports de communication pour la campagne</li>
                </ul>
            </div>
            
            <div class="phase-section phase-1">
                <div class="phase-header">
                    <h3 class="phase-title">PHASE 1 : LANCEMENT INITIAL</h3>
                    <span class="phase-period">1er-15 juin 2025</span>
                </div>
                <ul>
                    <li>Lancement officiel de la campagne "Donnez-leur vous-mêmes à manger" (1er juin)</li>
                    <li>Mise en place des structures de gouvernance opérationnelle</li>
                    <li>Déploiement de la communication digitale initiale</li>
                </ul>
            </div>
            
            <div class="phase-section phase-2">
                <div class="phase-header">
                    <h3 class="phase-title">PHASE 2 : EXPANSION DES PARTENARIATS</h3>
                    <span class="phase-period">15 juin-15 juillet 2025</span>
                </div>
                <ul>
                    <li>Lancement des démarches de partenariat auprès des institutions internationales (15 juin)</li>
                    <li>Intensification de la campagne de levée de fonds</li>
                    <li>Structuration administrative et juridique complète</li>
                </ul>
            </div>
            
            <div class="phase-section phase-3">
                <div class="phase-header">
                    <h3 class="phase-title">PHASE 3 : DÉPLOIEMENT DES PROGRAMMES</h3>
                    <span class="phase-period">15 juillet-31 août 2025</span>
                </div>
                <ul>
                    <li>Lancement des programmes TIMOTHÉE (formation) et DANIEL (prière)</li>
                    <li>Première distribution alimentaire majeure (campagne DLVAM)</li>
                    <li>Mise en place des outils de suivi-évaluation</li>
                </ul>
            </div>
            
            <div class="phase-section phase-4">
                <div class="phase-header">
                    <h3 class="phase-title">PHASE 4 : CONSOLIDATION ET DÉVELOPPEMENT</h3>
                    <span class="phase-period">1er septembre-31 octobre 2025</span>
                </div>
                <ul>
                    <li>Lancement des programmes PHILIPPE (évangélisation) et BÉTHANIE (communion)</li>
                    <li>Évaluation intermédiaire et ajustements</li>
                    <li>Renforcement des partenariats et diversification des sources de financement</li>
                </ul>
            </div>
            
            <div class="phase-section phase-5">
                <div class="phase-header">
                    <h3 class="phase-title">PHASE 5 : EXPANSION ET PRÉPARATION 2026</h3>
                    <span class="phase-period">1er novembre-31 décembre 2025</span>
                </div>
                <ul>
                    <li>Lancement du programme ÉLITE et préparation du Dîner de Gala "GLOIRE"</li>
                    <li>Évaluation annuelle et planification 2026</li>
                    <li>Célébration des réussites et reconnaissance des contributeurs</li>
                </ul>
            </div>
        </section>
        
        <section>
            <h2>Table des Tâches Détaillée</h2>
            
            <h3>PHASE PRÉLIMINAIRE (15-31 mai 2025)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Intitulé de l'action</th>
                        <th>Description</th>
                        <th>Responsable/Pôle</th>
                        <th>Ressources nécessaires</th>
                        <th>Date/Période</th>
                        <th>Statut</th>
                        <th>Observations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Finalisation du plan de campagne DLVAM</td>
                        <td>Validation du plan d'action, des messages clés et du calendrier de la campagne</td>
                        <td>Responsable Communication / Responsable Projet Social</td>
                        <td>Budget communication, expertise marketing, supports de communication</td>
                        <td>15-25 mai 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Coordination nécessaire entre les pôles Communication et Projet Social</td>
                    </tr>
                    <tr>
                        <td>Préparation des supports de communication</td>
                        <td>Création des visuels, textes, vidéos et contenus web pour la campagne DLVAM</td>
                        <td>Équipe Communication</td>
                        <td>Outils de design, équipement audiovisuel, budget production</td>
                        <td>15-28 mai 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Veiller à respecter la charte graphique et les messages validés</td>
                    </tr>
                    <tr>
                        <td>Configuration des canaux de don</td>
                        <td>Mise en place des systèmes de collecte en ligne et physique pour la campagne</td>
                        <td>Responsable Financier / Équipe Communication</td>
                        <td>Plateforme de paiement en ligne, comptes bancaires dédiés, formulaires de don</td>
                        <td>15-30 mai 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>S'assurer de la sécurité des transactions et de la conformité légale</td>
                    </tr>
                    <tr>
                        <td>Recrutement des premiers responsables</td>
                        <td>Finalisation du recrutement des responsables de pôles prioritaires</td>
                        <td>Président / Secrétaire Général</td>
                        <td>Budget RH, fiches de postes, processus d'entretien</td>
                        <td>15-31 mai 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Prioriser les postes essentiels au lancement de la campagne</td>
                    </tr>
                    <tr>
                        <td>Préparation logistique initiale</td>
                        <td>Organisation des espaces de travail et inventaire des ressources disponibles</td>
                        <td>Responsable Moyens Généraux</td>
                        <td>Espace de bureau, équipements de base, outils d'inventaire</td>
                        <td>15-31 mai 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Optimiser l'utilisation des ressources existantes</td>
                    </tr>
                </tbody>
            </table>
            
            <h3>PHASE 1 : LANCEMENT INITIAL (1er-15 juin 2025)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Intitulé de l'action</th>
                        <th>Description</th>
                        <th>Responsable/Pôle</th>
                        <th>Ressources nécessaires</th>
                        <th>Date/Période</th>
                        <th>Statut</th>
                        <th>Observations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lancement de la campagne DLVAM</td>
                        <td>Événement de lancement et activation de tous les canaux de communication</td>
                        <td>Responsable Communication / Direction</td>
                        <td>Budget événementiel, équipe d'organisation, supports de communication</td>
                        <td>1er juin 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Événement crucial nécessitant une préparation minutieuse</td>
                    </tr>
                    <tr>
                        <td>Première session "La Prière du Bâtisseur"</td>
                        <td>Organisation de la première session mensuelle de prière communautaire</td>
                        <td>Responsable Programme DANIEL</td>
                        <td>Salle de prière, supports de prière, équipe d'animation</td>
                        <td>1er juin 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Synchroniser avec le lancement de la campagne pour un soutien spirituel</td>
                    </tr>
                    <tr>
                        <td>Constitution du Comité Opérationnel de Relance</td>
                        <td>Formalisation de l'équipe de coordination et définition des processus de travail</td>
                        <td>Président / Secrétaire Général</td>
                        <td>Termes de référence, calendrier de réunions, outils de collaboration</td>
                        <td>1er-10 juin 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Essentiel pour la coordination efficace des activités</td>
                    </tr>
                    <tr>
                        <td>Déploiement de la présence digitale</td>
                        <td>Activation des comptes sur réseaux sociaux et lancement du site web</td>
                        <td>Responsable Communication Digitale</td>
                        <td>Contenus digitaux, calendrier éditorial, budget sponsoring</td>
                        <td>1er-15 juin 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Crucial pour la visibilité de la campagne DLVAM</td>
                    </tr>
                    <tr>
                        <td>Élaboration des procédures financières</td>
                        <td>Mise en place des procédures de gestion des dons et de reporting financier</td>
                        <td>Responsable Financier / Trésorier</td>
                        <td>Expertise comptable, logiciels de gestion, modèles de rapports</td>
                        <td>1er-15 juin 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Garantir la transparence et la traçabilité des fonds collectés</td>
                    </tr>
                </tbody>
            </table>
            
            <h3>PHASE 2 : EXPANSION DES PARTENARIATS (15 juin-15 juillet 2025)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Intitulé de l'action</th>
                        <th>Description</th>
                        <th>Responsable/Pôle</th>
                        <th>Ressources nécessaires</th>
                        <th>Date/Période</th>
                        <th>Statut</th>
                        <th>Observations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lancement des démarches de partenariat international</td>
                        <td>Identification et premier contact avec les institutions internationales ciblées</td>
                        <td>Responsable Partenariats / Direction</td>
                        <td>Dossier de présentation, base de données contacts, budget relations externes</td>
                        <td>15-30 juin 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Échéance prioritaire fixée par la direction</td>
                    </tr>
                    <tr>
                        <td>Régularisation administrative et juridique</td>
                        <td>Mise à jour des statuts, règlement intérieur et documents légaux</td>
                        <td>Responsable Administratif et Juridique</td>
                        <td>Expertise juridique, documentation administrative, frais administratifs</td>
                        <td>15 juin-10 juillet 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Préalable nécessaire aux partenariats formels</td>
                    </tr>
                    <tr>
                        <td>Intensification de la campagne DLVAM</td>
                        <td>Renforcement des actions de communication et de collecte</td>
                        <td>Équipe Communication / Responsable Levée de Fonds</td>
                        <td>Budget marketing, canaux de diffusion élargis, équipe de bénévoles</td>
                        <td>15 juin-15 juillet 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Maintenir la dynamique après le lancement initial</td>
                    </tr>
                    <tr>
                        <td>Recrutement des bénévoles</td>
                        <td>Campagne de recrutement et formation initiale des premiers bénévoles</td>
                        <td>Responsable Mobilisation / Équipe BÉTHANIE</td>
                        <td>Supports de recrutement, programme de formation, espace de formation</td>
                        <td>20 juin-15 juillet 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Constituer un vivier de bénévoles pour les actions terrain</td>
                    </tr>
                    <tr>
                        <td>Préparation logistique de la distribution</td>
                        <td>Organisation de la chaîne logistique pour la première distribution alimentaire</td>
                        <td>Responsable Moyens Généraux / Équipe DORCAS</td>
                        <td>Espace de stockage, moyens de transport, système de distribution</td>
                        <td>25 juin-15 juillet 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Anticiper tous les aspects pratiques de la distribution</td>
                    </tr>
                </tbody>
            </table>
            
            <h3>PHASE 3 : DÉPLOIEMENT DES PROGRAMMES (15 juillet-31 août 2025)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Intitulé de l'action</th>
                        <th>Description</th>
                        <th>Responsable/Pôle</th>
                        <th>Ressources nécessaires</th>
                        <th>Date/Période</th>
                        <th>Statut</th>
                        <th>Observations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Première distribution alimentaire majeure</td>
                        <td>Organisation et réalisation de la première distribution à grande échelle</td>
                        <td>Responsable Projet Social / Équipe DORCAS</td>
                        <td>Denrées alimentaires, équipe de bénévoles, logistique complète</td>
                        <td>20-25 juillet 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Premier test majeur de la capacité opérationnelle</td>
                    </tr>
                    <tr>
                        <td>Lancement du programme TIMOTHÉE</td>
                        <td>Démarrage des premières formations professionnelles</td>
                        <td>Responsable Programme TIMOTHÉE</td>
                        <td>Matériel pédagogique, formateurs, salle équipée</td>
                        <td>15 juillet-10 août 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Commencer par les formations les plus demandées</td>
                    </tr>
                    <tr>
                        <td>Aménagement complet de la salle de prière</td>
                        <td>Finalisation des travaux et équipement de l'espace dédié</td>
                        <td>Responsable Programme DANIEL / Responsable Logistique</td>
                        <td>Budget travaux, matériel d'aménagement, équipements spécifiques</td>
                        <td>15-31 juillet 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Créer un espace propice à la prière et au recueillement</td>
                    </tr>
                    <tr>
                        <td>Lancement de l'émission "La Voix du Bâtisseur"</td>
                        <td>Production et diffusion des premiers épisodes</td>
                        <td>Responsable Communication / Équipe PHILIPPE</td>
                        <td>Équipement audiovisuel, équipe de production, plateforme de diffusion</td>
                        <td>1er-15 août 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Établir un calendrier régulier de production</td>
                    </tr>
                    <tr>
                        <td>Mise en place du système de suivi-évaluation</td>
                        <td>Définition des indicateurs et création des outils de collecte de données</td>
                        <td>Secrétaire Général / Responsables de programmes</td>
                        <td>Expertise en évaluation, outils de suivi, formation des équipes</td>
                        <td>15 juillet-31 août 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Essentiel pour mesurer l'impact et ajuster les actions</td>
                    </tr>
                </tbody>
            </table>
            
            <h3>PHASE 4 : CONSOLIDATION ET DÉVELOPPEMENT (1er septembre-31 octobre 2025)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Intitulé de l'action</th>
                        <th>Description</th>
                        <th>Responsable/Pôle</th>
                        <th>Ressources nécessaires</th>
                        <th>Date/Période</th>
                        <th>Statut</th>
                        <th>Observations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lancement du Club d'Anglais</td>
                        <td>Mise en place des premiers cours et ateliers d'anglais</td>
                        <td>Coordinateur Club d'Anglais / Équipe TIMOTHÉE</td>
                        <td>Matériel pédagogique, enseignants, salle dédiée</td>
                        <td>1er-15 septembre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Répondre à un besoin d'employabilité identifié</td>
                    </tr>
                    <tr>
                        <td>Évaluation intermédiaire des programmes</td>
                        <td>Analyse des premiers résultats et identification des ajustements nécessaires</td>
                        <td>Secrétaire Général / Responsables de programmes</td>
                        <td>Données de suivi, réunions d'évaluation, expertise analytique</td>
                        <td>1er-15 octobre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Moment clé pour réorienter certaines actions si nécessaire</td>
                    </tr>
                    <tr>
                        <td>Renforcement des partenariats</td>
                        <td>Suivi des contacts initiaux et formalisation des premiers accords</td>
                        <td>Responsable Partenariats</td>
                        <td>Conventions types, réunions de travail, budget déplacements</td>
                        <td>Septembre-Octobre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Capitaliser sur les démarches initiées en juin</td>
                    </tr>
                    <tr>
                        <td>Seconde campagne de levée de fonds</td>
                        <td>Lancement d'une nouvelle phase de collecte ciblée</td>
                        <td>Responsable Levée de Fonds / Équipe Communication</td>
                        <td>Nouveaux supports, stratégie de relance, canaux diversifiés</td>
                        <td>15 septembre-15 octobre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Diversifier les sources de financement</td>
                    </tr>
                    <tr>
                        <td>Organisation d'un événement communautaire</td>
                        <td>Rassemblement des membres et sympathisants pour renforcer les liens</td>
                        <td>Responsable Programme BÉTHANIE</td>
                        <td>Budget événementiel, lieu, équipe d'animation</td>
                        <td>15-30 octobre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Renforcer le sentiment d'appartenance à la communauté</td>
                    </tr>
                </tbody>
            </table>
            
            <h3>PHASE 5 : EXPANSION ET PRÉPARATION 2026 (1er novembre-31 décembre 2025)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Intitulé de l'action</th>
                        <th>Description</th>
                        <th>Responsable/Pôle</th>
                        <th>Ressources nécessaires</th>
                        <th>Date/Période</th>
                        <th>Statut</th>
                        <th>Observations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lancement du programme ÉLITE</td>
                        <td>Démarrage du programme de leadership et mentorat</td>
                        <td>Responsable Programme TIMOTHÉE</td>
                        <td>Curriculum, mentors qualifiés, espace de formation</td>
                        <td>1er-15 novembre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Programme phare pour le développement du leadership</td>
                    </tr>
                    <tr>
                        <td>Organisation du Dîner de Gala "GLOIRE"</td>
                        <td>Préparation et tenue de l'événement de levée de fonds</td>
                        <td>Comité d'organisation dédié</td>
                        <td>Budget événementiel, lieu prestigieux, logistique complète</td>
                        <td>29 novembre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Événement majeur de collecte et de visibilité</td>
                    </tr>
                    <tr>
                        <td>Lancement du projet "Oser Bâtir"</td>
                        <td>Mise en place du programme d'accompagnement entrepreneurial</td>
                        <td>Équipe TIMOTHÉE</td>
                        <td>Matériel pédagogique, mentors entrepreneurs, financement initial</td>
                        <td>1er-15 décembre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Soutenir l'entrepreneuriat comme voie d'autonomisation</td>
                    </tr>
                    <tr>
                        <td>Évaluation annuelle et planification 2026</td>
                        <td>Bilan complet des réalisations et élaboration du plan pour l'année suivante</td>
                        <td>Comité Opérationnel de Relance / CA</td>
                        <td>Données consolidées, sessions de planification, expertise en évaluation</td>
                        <td>1er-20 décembre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Tirer les leçons de 2025 pour optimiser 2026</td>
                    </tr>
                    <tr>
                        <td>Célébration des réussites</td>
                        <td>Organisation d'un événement de reconnaissance pour les équipes et partenaires</td>
                        <td>Direction / Équipe BÉTHANIE</td>
                        <td>Budget événementiel, supports de communication des résultats</td>
                        <td>20-31 décembre 2025</td>
                        <td><span class="status status-todo">À faire</span></td>
                        <td>Valoriser les contributions et renforcer la motivation</td>
                    </tr>
                </tbody>
            </table>
        </section>
        
        <section class="coordination-section">
            <h2>Mécanismes de Coordination et de Suivi</h2>
            
            <p>Pour garantir une mise en œuvre efficace de cette feuille de route, les mécanismes suivants seront mis en place :</p>
            
            <h3>Réunions de Coordination</h3>
            <ul>
                <li><strong>Réunion hebdomadaire du Comité Opérationnel de Relance</strong> : Tous les lundis, 9h-11h</li>
                <li><strong>Point d'avancement bi-mensuel avec les responsables de programmes</strong> : 2ème et 4ème jeudi du mois, 14h-16h</li>
                <li><strong>Réunion mensuelle avec le Conseil d'Administration</strong> : Dernier vendredi du mois, 16h-18h</li>
            </ul>
            
            <h3>Outils de Suivi</h3>
            <ul>
                <li><strong>Tableau de bord de suivi des tâches</strong> : Mis à jour en temps réel, accessible à tous les responsables</li>
                <li><strong>Rapports d'avancement standardisés</strong> : Soumis par chaque responsable de pôle le 25 de chaque mois</li>
                <li><strong>Système d'alerte précoce</strong> : Pour signaler les retards ou difficultés nécessitant une intervention</li>
            </ul>
            
            <h3>Processus d'Ajustement</h3>
            <ul>
                <li><strong>Revue mensuelle des priorités</strong> : Ajustement des ressources et des délais si nécessaire</li>
                <li><strong>Procédure de gestion des changements</strong> : Pour les modifications substantielles du plan initial</li>
                <li><strong>Mécanisme de décision rapide</strong> : Pour les situations urgentes nécessitant une réaction immédiate</li>
            </ul>
        </section>
        
        <section class="risks-section">
            <h2>Gestion des Risques</h2>
            
            <p>Un registre des risques sera maintenu et mis à jour régulièrement par le Secrétaire Général, avec les éléments suivants pour chaque risque identifié :</p>
            
            <table class="risk-table">
                <thead>
                    <tr>
                        <th>Risque</th>
                        <th>Probabilité</th>
                        <th>Impact</th>
                        <th>Mesures préventives</th>
                        <th>Plan de contingence</th>
                        <th>Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Insuffisance des ressources financières</td>
                        <td class="probability-medium">Moyenne</td>
                        <td class="impact-high">Élevé</td>
                        <td>Diversification des sources de financement, suivi budgétaire rigoureux</td>
                        <td>Priorisation des activités essentielles, recherche de financements d'urgence</td>
                        <td>Responsable Financier</td>
                    </tr>
                    <tr>
                        <td>Difficultés de recrutement des profils clés</td>
                        <td class="probability-medium">Moyenne</td>
                        <td class="impact-high">Élevé</td>
                        <td>Anticipation des besoins RH, réseau de recrutement élargi</td>
                        <td>Solutions intérimaires, redistribution temporaire des responsabilités</td>
                        <td>Secrétaire Général</td>
                    </tr>
                    <tr>
                        <td>Retards dans les autorisations administratives</td>
                        <td class="probability-high">Élevée</td>
                        <td class="impact-medium">Moyen</td>
                        <td>Démarches anticipées, relations proactives avec les autorités</td>
                        <td>Activités alternatives ne nécessitant pas d'autorisation spécifique</td>
                        <td>Responsable Administratif</td>
                    </tr>
                    <tr>
                        <td>Faible mobilisation des bénévoles</td>
                        <td class="probability-medium">Moyenne</td>
                        <td class="impact-medium">Moyen</td>
                        <td>Stratégie d'engagement attractive, reconnaissance régulière</td>
                        <td>Recours à des prestataires ponctuels, redimensionnement des actions</td>
                        <td>Responsable Mobilisation</td>
                    </tr>
                    <tr>
                        <td>Problèmes logistiques dans la distribution</td>
                        <td class="probability-medium">Moyenne</td>
                        <td class="impact-high">Élevé</td>
                        <td>Planification détaillée, tests préalables, équipe de secours</td>
                        <td>Solutions alternatives de distribution, partenariats logistiques</td>
                        <td>Responsable Logistique</td>
                    </tr>
                </tbody>
            </table>
        </section>
        
        <section class="conclusion">
            <h2>Conclusion</h2>
            
            <p>Cette feuille de route opérationnelle constitue le cadre de référence pour le lancement et le déploiement des activités de l'ONG NÉHÉMIE International en 2025. Sa mise en œuvre réussie reposera sur :</p>
            
            <ul>
                <li>L'engagement collectif de toutes les parties prenantes</li>
                <li>Une communication fluide et transparente entre les différents pôles</li>
                <li>Un suivi rigoureux et des ajustements réguliers</li>
                <li>Une gestion proactive des risques identifiés</li>
            </ul>
            
            <p>Le respect des échéances clés, notamment le lancement de la campagne "Donnez-leur vous-mêmes à manger" le 1er juin et le démarrage des démarches de partenariat international le 15 juin, sera déterminant pour la dynamique globale du plan.</p>
            
            <p>Ce document est conçu comme un outil vivant qui évoluera en fonction des réalités du terrain et des opportunités qui se présenteront, tout en maintenant le cap sur la mission fondamentale de l'ONG : exprimer sa foi par son engagement en faveur de la restauration, de la solidarité et de l'autonomie des personnes vulnérables.</p>
        </section>
        
        <div class="bible-quote">
            "Celui qui est fidèle dans les moindres choses l'est aussi dans les grandes."
            <span class="bible-source">- Luc 16:10</span>
        </div>
    </div>
    
    <footer>
        <div class="footer-content">
            <p>ONG NÉHÉMIE International &copy; 2025</p>
            <p class="tagline">"Levons-nous et bâtissons !"</p>
        </div>
    </footer>
</body>
</html>
