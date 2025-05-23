<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stratégie Globale - NÉHÉMIE INTERNATIONAL</title>
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
            --font-size-small: 0.9rem;
            --font-size-large: 1.1rem;
            
            --line-height-base: 1.7;
            --line-height-headings: 1.4;

            --spacing-unit: 1rem; 
            --container-max-width: 1200px;
            --border-radius: 5px;
            --box-shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.08);
            --box-shadow-medium: 0 6px 15px rgba(0, 0, 0, 0.1);

            --title-pane-width: 220px; 
            --menu-pane-width: 280px;  
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: var(--font-size-base); scroll-behavior: smooth; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        body { 
            font-family: var(--font-family-base); 
            line-height: var(--line-height-base); 
            color: var(--color-text-dark); 
            background-color: var(--color-background-light);
            display: flex; 
            min-height: 100vh;
            overflow-x: hidden; 
        }

        /* --- Structure des 3 volets --- */
        .site-title-pane {
            width: var(--title-pane-width);
            background-color: var(--color-primary-dark);
            color: var(--color-text-light);
            padding: calc(var(--spacing-unit) * 2);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center; 
            text-align: center;
            z-index: 200;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .site-title-pane h1 {
            font-family: var(--font-family-headings);
            font-size: 1.6rem; 
            line-height: 1.3;
            font-weight: 700;
            color: var(--color-text-light);
            word-wrap: break-word; 
        }
        .site-title-pane .logo-placeholder { 
            width: 80px;
            height: 80px;
            background-color: var(--color-accent);
            border-radius: 50%;
            margin-bottom: var(--spacing-unit);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--color-primary-dark);
        }


        .navigation-pane {
            width: var(--menu-pane-width);
            background-color: var(--color-background-section);
            padding: calc(var(--spacing-unit) * 1.5);
            position: fixed;
            left: var(--title-pane-width); 
            top: 0;
            bottom: 0;
            overflow-y: auto; 
            z-index: 150;
            border-right: 1px solid var(--color-border-light);
            box-shadow: 1px 0 5px rgba(0,0,0,0.05);
        }
        .navigation-pane h2 {
            font-size: 1.3rem;
            color: var(--color-primary-dark);
            margin-bottom: var(--spacing-unit);
            padding-bottom: calc(var(--spacing-unit) * 0.5);
            border-bottom: 2px solid var(--color-accent);
            text-align: center;
        }
        #main-navigation { margin-top: var(--spacing-unit); }
        #main-navigation ul { list-style-type: none; padding-left: 0; }
        #main-navigation > ul > li { margin-bottom: calc(var(--spacing-unit) * 0.8); }
        #main-navigation a { 
            display: block; 
            padding: calc(var(--spacing-unit) * 0.5) calc(var(--spacing-unit) * 0.75);
            border-radius: var(--border-radius);
            color: var(--color-primary-medium);
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        #main-navigation > ul > li > a { font-weight: 700; font-size: 1rem; color: var(--color-primary-dark); }
        #main-navigation ul ul { padding-left: var(--spacing-unit); margin-top: calc(var(--spacing-unit) * 0.3); }
        #main-navigation ul ul a { font-size: var(--font-size-small); font-weight: 400; }
        #main-navigation a:hover { background-color: #e9ecef; color: var(--color-accent); }
        #main-navigation a.active-menu-item { background-color: var(--color-accent); color: var(--color-text-light) !important; font-weight: 700; }
        #main-navigation a.active-menu-item-parent { background-color: var(--color-primary-medium); color: var(--color-text-light) !important; }


        .content-pane {
            margin-left: calc(var(--title-pane-width) + var(--menu-pane-width)); 
            flex-grow: 1; 
            padding: calc(var(--spacing-unit) * 2);
            overflow-y: auto; 
            background-color: var(--color-background-light);
            height: 100vh; /* Important pour que le scroll des boutons haut/bas fonctionne sur ce volet */
        }
        .container-content { 
            max-width: var(--container-max-width);
            margin: 0 auto;
        }

        .content-page {
            display: none; 
            animation: fadeIn 0.5s ease-in-out;
        }
        .content-page.active { display: block;  }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        hr.section-divider { border: 0; height: 1px; background-image: linear-gradient(to right, rgba(0, 0, 0, 0), var(--color-border-medium), rgba(0, 0, 0, 0)); margin: calc(var(--spacing-unit) * 3) 0; }
        .content-page h2 { font-size: 2rem; color: var(--color-primary-dark); margin-bottom: calc(var(--spacing-unit) * 1.5); padding-bottom: calc(var(--spacing-unit) * 0.5); border-bottom: 2px solid var(--color-accent); display: inline-block; }
        .content-page h3 { font-size: 1.6rem; color: var(--color-primary-medium); margin-top: calc(var(--spacing-unit) * 2); margin-bottom: var(--spacing-unit); font-weight: 500; }
        .content-page h4 { font-size: 1.25rem; color: var(--color-primary-dark); margin-top: calc(var(--spacing-unit) * 1.5); margin-bottom: calc(var(--spacing-unit) * 0.75); font-weight: 700; }
        .plan-action-item h4 { margin-top: 0; }
        .content-page p { margin-bottom: var(--spacing-unit); color: var(--color-text-medium); }
        
        .content-page a { /* Style pour les liens DANS le contenu, différent du menu */
            color: var(--color-accent); 
            text-decoration: none; 
            font-weight: 700; 
            transition: color 0.2s ease-in-out; 
        }
        .content-page a:hover, .content-page a:focus { 
            color: #c08100; 
            text-decoration: underline; 
        }

        strong, b { font-weight: 700; color: var(--color-text-dark); }
        em, i { font-style: italic; color: var(--color-primary-medium); }
        /* S'assurer que les listes du menu ne sont pas affectées par les styles globaux de listes */
        .content-page ul, .content-page ol { 
            margin-bottom: var(--spacing-unit); 
            padding-left: calc(var(--spacing-unit) * 2); 
            color: var(--color-text-medium); 
        }
        .content-page li { margin-bottom: calc(var(--spacing-unit) * 0.5); }
        .content-page ul { list-style-type: disc; }
        .content-page ol { list-style-type: decimal; }

        blockquote { margin: var(--spacing-unit) 0 calc(var(--spacing-unit) * 1.5) 0; padding: var(--spacing-unit) calc(var(--spacing-unit) * 1.5); border-left: 4px solid var(--color-accent); background-color: var(--color-background-section); font-style: italic; color: var(--color-text-medium); border-radius: 0 var(--border-radius) var(--border-radius) 0; }
        blockquote p { margin-bottom: 0; }
        .plan-action-item { background-color: var(--color-background-light); padding: calc(var(--spacing-unit) * 1.5); margin-bottom: calc(var(--spacing-unit) * 2); border-left: 5px solid var(--color-accent); border-radius: 0 var(--border-radius) var(--border-radius) 0; }
        .plan-action-item p, .plan-action-item ul { font-size: var(--font-size-base); color: var(--color-text-medium); }
        .plan-action-item strong { font-family: var(--font-family-headings); font-weight: 700; color: var(--color-primary-medium); display: block; margin-bottom: calc(var(--spacing-unit) * 0.25); }
        .plan-action-item ul { padding-left: var(--spacing-unit); }
        .plan-action-item ul li { margin-bottom: calc(var(--spacing-unit) * 0.3); }

        .site-footer {
            background-color: var(--color-text-dark);
            color: var(--color-border-light);
            padding: calc(var(--spacing-unit) * 1.5) 0;
            text-align: center;
            font-size: var(--font-size-small);
            margin-top: calc(var(--spacing-unit) * 2); 
        }
        .site-footer p { margin-bottom: calc(var(--spacing-unit) * 0.5); }
        .site-footer a { color: var(--color-accent); text-decoration: none; }
        .site-footer a:hover { text-decoration: underline; }
        
        #scrollToTopBtn, #scrollToBottomBtn { 
            display: none; 
            position: fixed; 
            right: calc(var(--spacing-unit) * 1.5); 
            background-color: var(--color-accent); 
            color: var(--color-text-light); 
            border: none; 
            border-radius: var(--border-radius); 
            padding: 10px 15px; 
            font-size: 1.2rem; 
            font-weight: bold; 
            cursor: pointer; 
            z-index: 1000; 
            text-decoration: none; 
            box-shadow: var(--box-shadow-medium); 
            transition: background-color 0.3s, opacity 0.3s, display 0.3s; 
        }
        #scrollToTopBtn { bottom: 75px; }
        #scrollToTopBtn:hover { background-color: #c08100; }
        #scrollToBottomBtn { bottom: 25px; display: block; }
        #scrollToBottomBtn:hover { background-color: #c08100; }

        @media (max-width: 992px) {
            body { flex-direction: column; } 
            .site-title-pane {
                width: 100%;
                position: static; 
                flex-direction: row;
                justify-content: center;
                padding: var(--spacing-unit);
                height: auto;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .site-title-pane h1 { font-size: 1.5rem; }
            .site-title-pane .logo-placeholder { display: none; } 

            .navigation-pane {
                width: 100%;
                position: static; 
                max-height: 300px; 
                border-right: none;
                border-bottom: 1px solid var(--color-border-light);
                box-shadow: none;
            }
             #main-navigation { column-count: 2; } 

            .content-pane {
                margin-left: 0; 
                padding: var(--spacing-unit);
                height: auto; /* Permettre au contenu de déterminer la hauteur */
            }
        }
        @media (max-width: 576px) {
            #main-navigation { column-count: 1; }
            .site-title-pane h1 { font-size: 1.3rem; }
        }
    </style>
</head>
<body>

    <div class="site-title-pane" id="title-pane">
        <!-- <div class="logo-placeholder">N</div> -->
        <h1>NÉHÉMIE<br>INTERNATIONAL<br><span style="font-size:0.8em; font-weight:400;">Stratégie 2025</span></h1>
    </div>

    <nav class="navigation-pane" id="menu-pane">
        <h2>Navigation</h2>
        <div id="main-navigation">
            <ul>
                <li><a href="#preambule">PRÉAMBULE</a></li>
                <li>
                    <a href="#axe1">AXE 1 : RAYONNEMENT</a>
                    <ul>
                        <li><a href="#axe1_plan1">1. Identité Visuelle</a></li>
                        <li><a href="#axe1_plan2">2. Digital Stratégique</a></li>
                        <li><a href="#axe1_plan3">3. Audiovisuels</a></li>
                        <li><a href="#axe1_plan4">4. Relations & Partenariats</a></li>
                        <li><a href="#axe1_plan5">5. Rencontres Thématiques</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#axe2">AXE 2 : GOUVERNANCE</a>
                    <ul>
                         <li><a href="#axe2_plan1">1. Juridique & Conformité</a></li>
                         <li><a href="#axe2_plan2">2. Structure Gouvernance</a></li>
                         <li><a href="#axe2_plan3">3. Gestion Financière</a></li>
                         <li><a href="#axe2_plan4">4. Équipe Opérationnelle</a></li>
                         <li><a href="#axe2_plan5">5. Mobilisation Ressources</a></li>
                    </ul>
                </li>
                 <li>
                    <a href="#axe3">AXE 3 : COMMUNAUTÉ</a>
                    <ul>
                        <li><a href="#axe3_plan1">1. Intégration Membres</a></li>
                        <li><a href="#axe3_plan2">2. Valorisation Compétences</a></li>
                        <li><a href="#axe3_plan3">3. Cellules Communautaires</a></li>
                        <li><a href="#axe3_plan4">4. Ambassadeurs</a></li>
                        <li><a href="#axe3_plan5">5. Événements Fédérateurs</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#axe4">AXE 4 : FORMATION</a>
                    <ul>
                        <li><a href="#axe4_plan1">1. Compétences Pro</a></li>
                        <li><a href="#axe4_plan2">2. Soutien Entrepreneurs</a></li>
                        <li><a href="#axe4_plan3">3. Anglais Pro</a></li>
                        <li><a href="#axe4_plan4">4. Sensibilisation Entrepreneuriat</a></li>
                        <li><a href="#axe4_plan5">5. Incubateur Micro-Projets</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#axe5">AXE 5 : SPIRITUEL</a>
                    <ul>
                        <li><a href="#axe5_plan1">1. Enseignement Biblique</a></li>
                        <li><a href="#axe5_plan2">2. Intercession</a></li>
                        <li><a href="#axe5_plan3">3. Leadership & Mentorat</a></li>
                        <li><a href="#axe5_plan4">4. Accompagnement Spirituel</a></li>
                        <li><a href="#axe5_plan5">5. Impact par la Foi</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#integration">INTÉGRATION & ŒUVRE</a>
                    <ul>
                        <li><a href="#integration_chrono">Chronologie</a></li>
                        <li><a href="#integration_coord">Coordination</a></li>
                        <li><a href="#integration_suivi">Suivi-Évaluation</a></li>
                        <li><a href="#integration_changement">Gestion Changement</a></li>
                    </ul>
                </li>
                <li><a href="#conclusion">CONCLUSION</a></li>
            </ul>
        </div>
    </nav>

    <main class="content-pane" id="main-content">
        <div class="container-content">

            <section id="preambule" class="content-page">
                <h2>PRÉAMBULE : VISION ET FONDEMENT BIBLIQUE</h2>
                <p>À l'image de Néhémie qui a rebâti les murs de Jérusalem avec une vision claire, une stratégie organisée et une foi inébranlable, NÉHÉMIE International est appelée à être un instrument de reconstruction et de transformation pour le Gabon. Cette stratégie pour la période de Mai à Décembre 2025 s'appuie sur les fondations déjà posées et les initiatives en cours, visant à structurer et amplifier notre impact. Comme il est écrit : <em>"Je leur dis : Vous voyez la détresse où nous sommes [...] Venez, rebâtissons la muraille, et nous ne serons plus dans l'opprobre"</em> (Néhémie 2:17).</p>
                <p>Cette stratégie s'inspire de cinq principes bibliques fondamentaux :</p>
                <ol>
                    <li><strong>Vision claire</strong> - "Écris la vision, grave-la sur des tablettes, afin qu'on puisse la lire couramment" (Habakuk 2:2)</li>
                    <li><strong>Fondations solides</strong> - "Semblable à un homme qui a bâti une maison sur le roc" (Matthieu 7:24)</li>
                    <li><strong>Multiplication des talents</strong> - "À celui qui a, on donnera encore" (Matthieu 25:29)</li>
                    <li><strong>Transformation intégrale</strong> - "Je suis venu afin que les brebis aient la vie, et qu'elles l'aient en abondance" (Jean 10:10)</li>
                    <li><strong>Impact communautaire</strong> - "Cherchez le bien de la ville... et priez l'Éternel en sa faveur" (Jérémie 29:7)</li>
                </ol>
            </section>

            <section id="axe1" class="content-page">
                <h2>AXE 1 : RAYONNEMENT ET VISIBILITÉ STRATÉGIQUE</h2>
                <h3>Explication de la stratégie globale de l'axe</h3>
                <p>Le rayonnement et la visibilité constituent le fondement de notre capacité à attirer partenaires, donateurs et bénéficiaires. À l'instar de la "ville située sur une montagne" (Matthieu 5:14), NÉHÉMIE International doit amplifier sa lumière pour maximiser son impact. Cet axe vise à établir une présence forte et cohérente au Gabon et à l'international, en créant une identité distinctive ancrée dans notre mission chrétienne et en s'appuyant sur notre charte graphique existante.</p>
                <p><strong>Objectifs spécifiques :</strong></p>
                <ul>
                    <li>Positionner NÉHÉMIE International comme une ONG chrétienne de référence au Gabon.</li>
                    <li>Développer une visibilité pour attirer des partenaires et mobiliser la communauté.</li>
                    <li>Créer un écosystème de communication intégré et professionnel.</li>
                    <li>Établir une réputation d'excellence et d'intégrité.</li>
                </ul>
                <p><strong>Bénéfices attendus :</strong></p>
                <ul>
                    <li>Opportunités accrues de partenariats et de soutien.</li>
                    <li>Crédibilité renforcée auprès des autorités et des communautés.</li>
                    <li>Attraction de nouveaux membres et bénéficiaires.</li>
                    <li>Effet multiplicateur sur l'impact des autres programmes.</li>
                </ul>
                <h3>Plan d'action structuré</h3>
                <div class="plan-action-item" id="axe1_plan1">
                    <h4>1. Déploiement de l'Identité Visuelle Institutionnelle</h4>
                    <p><strong>Descriptif :</strong> Utilisation et déploiement cohérents du logo et de la charte graphique existants de NÉHÉMIE International. Création et standardisation des supports institutionnels essentiels (modèles de documents, présentations, visuels pour les médias sociaux) pour assurer une reconnaissance et une image professionnelle. Ce déploiement inclura la finalisation et la validation de supports existants, comme la plaquette du projet « Donnez-leur vous-même à manger » qui est en attente de discussion pour amélioration, en assurant son alignement avec la charte globale.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Équipe interne, mobilisation de bénévoles avec des compétences en graphisme pour la création de supports.</li>
                        <li>Matérielles : Charte graphique existante, outils de conception graphique et bureautique.</li>
                        <li>Financières : Mobilisation de ressources pour l'impression de supports essentiels.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Cohérence visuelle sur tous les supports.</li>
                        <li>Supports institutionnels de base disponibles et utilisés, y compris la plaquette "Donnez-leur vous-même à manger" améliorée.</li>
                        <li>Renforcement de la reconnaissance de la marque.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Disponibilité et utilisation des modèles de supports.</li>
                        <li>Validation et diffusion de la plaquette améliorée.</li>
                        <li>Retours qualitatifs sur la perception de l'identité visuelle.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Compilation et validation des éléments graphiques : Mai 2025</li>
                        <li>Création/Amélioration des modèles de supports (incluant plaquette) : Mai - Juin 2025</li>
                        <li>Déploiement progressif : À partir de Juin 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe1_plan2">
                    <h4>2. Développement d'un Écosystème Digital Stratégique</h4>
                    <p><strong>Descriptif :</strong> Optimisation et animation d'une présence numérique performante, incluant le site web institutionnel (ou sa création/amélioration) et les comptes sur les réseaux sociaux les plus pertinents pour notre audience. Ce plan concrétise la stratégie de communication globale de NÉHÉMIE, dont l'élaboration est en cours, en assurant notamment une articulation claire entre nos différents programmes (par exemple, le lien entre le programme 'GLOIRE' et l'initiative 'Donnez-leur vous-même à manger') et en capitalisant sur les réflexions antérieures pour sa finalisation. Développement d'une stratégie de contenu engageante et régulière.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Responsable de communication (potentiellement bénévole ou membre de l'équipe), community manager (bénévole ou temps partiel), contributeurs de contenu.</li>
                        <li>Matérielles : Accès à internet, outils de gestion de réseaux sociaux.</li>
                        <li>Financières : Frais d'hébergement web et nom de domaine.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Site web informatif et fonctionnel.</li>
                        <li>Présence active et engageante sur les plateformes sociales clés.</li>
                        <li>Diffusion régulière de contenus de qualité, reflétant la stratégie de communication globale.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Croissance de l'audience et de l'engagement en ligne.</li>
                        <li>Qualité et fréquence des publications.</li>
                        <li>Augmentation des interactions et des demandes d'information.</li>
                        <li>Adoption de la stratégie de communication interne et externe.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Finalisation de la stratégie de communication globale : Mai - Juin 2025</li>
                        <li>Audit et optimisation de la présence existante : Mai - Juin 2025</li>
                        <li>Développement/amélioration du site web : Juin - Juillet 2025</li>
                        <li>Lancement de la stratégie de contenu : À partir de Juillet 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe1_plan3">
                    <h4>3. Création et Diffusion de Contenus Audiovisuels Impactants</h4>
                    <p><strong>Descriptif :</strong> Production régulière de contenus audiovisuels (vidéos courtes, témoignages, interviews, podcasts) pour illustrer la mission, les actions et l'impact de NÉHÉMIE International, en s'appuyant sur les succès initiaux comme la mini-vidéo interne déjà réalisée pour les contributeurs et en développant des formats récurrents tels que l'émission 'À l'image de Christ', actuellement en cours de développement (incluant les aménagements mobiliers nécessaires). Diffusion sur le site web, les réseaux sociaux et via des plateformes de partage.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Équipe de création de contenu (bénévoles avec compétences en prise de vue/son, montage).</li>
                        <li>Matérielles : Équipement de base pour la prise de vue et de son (smartphones de bonne qualité, microphones), logiciels de montage accessibles, aménagements pour l'émission.</li>
                        <li>Financières : Mobilisation de ressources pour l'acquisition de petit matériel et les aménagements si nécessaire.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Bibliothèque de contenus audiovisuels valorisant l'ONG, incluant l'émission 'À l'image de Christ' structurée.</li>
                        <li>Engagement accru grâce à des formats dynamiques.</li>
                        <li>Meilleure illustration des témoignages et des projets.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de vues et partages des contenus.</li>
                        <li>Fréquence et qualité de l'émission 'À l'image de Christ'.</li>
                        <li>Diversité des formats produits.</li>
                        <li>Retours qualitatifs de l'audience.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Planification éditoriale et formation/consolidation de l'équipe : Juin 2025</li>
                        <li>Intensification de la production de contenu existant et lancement de nouveaux formats (dont l'émission structurée) : Juillet 2025</li>
                        <li>Diffusion régulière : À partir de Juillet 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe1_plan4">
                    <h4>4. Relations Institutionnelles et Partenariats Ciblés</h4>
                    <p><strong>Descriptif :</strong> En capitalisant sur les succès déjà obtenus dans la sécurisation d'engagements (par exemple, avec les entreprises donatrices dont les fiches d'engagement ont été signées), développement d'un réseau de relations avec des institutions clés, des leaders d'opinion, des médias locaux et des organisations partenaires potentielles. L'objectif est de faire connaître NÉHÉMIE International et de créer des synergies.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Responsables de l'ONG, équipe dédiée aux relations externes (bénévoles).</li>
                        <li>Matérielles : Base de données de contacts, supports de présentation.</li>
                        <li>Financières : Prise en charge des frais de représentation minimes.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Visibilité accrue auprès des décideurs et influenceurs.</li>
                        <li>Établissement de relations de confiance.</li>
                        <li>Identification d'opportunités de collaboration, en s'appuyant sur les partenariats existants.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de contacts établis et de rencontres effectuées.</li>
                        <li>Mentions médiatiques obtenues.</li>
                        <li>Partenariats initiés ou consolidés.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Cartographie des acteurs clés : Mai - Juin 2025</li>
                        <li>Premières prises de contact : À partir de Juillet 2025</li>
                        <li>Suivi et développement des relations : Continu</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe1_plan5">
                    <h4>5. Organisation de Rencontres Thématiques Stratégiques</h4>
                    <p><strong>Descriptif :</strong> Planification et organisation de rencontres thématiques (ateliers, séminaires de sensibilisation, tables rondes) sur des sujets liés aux axes d'intervention de NÉHÉMIE International. Ces événements viseront à partager l'expertise, à mobiliser la communauté et à renforcer la visibilité.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Équipe d'organisation (bénévoles), intervenants internes ou partenaires.</li>
                        <li>Matérielles : Lieux de rencontre (privilégier les partenariats pour des mises à disposition gratuites), supports de communication.</li>
                        <li>Financières : Mobilisation de ressources pour la logistique des événements.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Renforcement de l'image d'expert de NÉHÉMIE International sur ses thématiques.</li>
                        <li>Mobilisation et engagement des publics cibles.</li>
                        <li>Création d'opportunités de réseautage.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre et qualité des événements organisés.</li>
                        <li>Taux de participation et satisfaction des participants.</li>
                        <li>Retombées et actions concrètes issues des rencontres.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Identification des thèmes et planification : Juin - Juillet 2025</li>
                        <li>Organisation du premier événement : Septembre/Octobre 2025</li>
                        <li>Rythme régulier par la suite.</li>
                    </ul>
                </div>
            </section>

            <section id="axe2" class="content-page">
                <h2>AXE 2 : GOUVERNANCE ET STRUCTURATION ORGANISATIONNELLE</h2>
                <h3>Explication de la stratégie globale de l'axe</h3>
                <p>La structuration organisationnelle est le fondement sur lequel NÉHÉMIE International pourra bâtir durablement son impact. Comme les bases solides évoquées dans la parabole du constructeur sage (Matthieu 7:24-27), une organisation bien structurée garantira la pérennité de notre mission. Cette gouvernance exemplaire sera aussi témoignage de notre intégrité chrétienne.</p>
                <p><strong>Objectifs spécifiques :</strong></p>
                <ul>
                    <li>Établir une structure juridique et administrative rigoureuse.</li>
                    <li>Développer un modèle de gouvernance transparent et efficace.</li>
                    <li>Mettre en place des systèmes de gestion financière clairs.</li>
                    <li>Constituer une équipe opérationnelle compétente et alignée sur la vision.</li>
                </ul>
                <p><strong>Bénéfices attendus :</strong></p>
                <ul>
                    <li>Crédibilité renforcée auprès des partenaires et des bénéficiaires.</li>
                    <li>Efficience accrue dans l'exécution des programmes.</li>
                    <li>Fondation solide pour le développement des activités.</li>
                </ul>
                <h3>Plan d'action structuré</h3>
                <div class="plan-action-item" id="axe2_plan1">
                    <h4>1. Consolidation du Cadre Juridique et Conformité Administrative</h4>
                    <p><strong>Descriptif :</strong> Vérification et mise à jour du cadre juridique de l'ONG (statuts, règlement intérieur) et assurance de la conformité avec les enregistrements officiels et autorisations administratives requises au Gabon. Cela inclut la finalisation de la régularisation contractuelle pour Néhémie (ex: obtention du bail, personnalisation du local dont les démarches sont en cours) et l'établissement des textes fondateurs pour des initiatives majeures comme le projet de création d'école et l'implantation de l'Église, dont les réflexions et rédactions sont en cours.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Responsables de l'ONG, consultation de conseillers juridiques (si possible bénévoles ou via des réseaux partenaires).</li>
                        <li>Matérielles : Documents juridiques existants, accès aux informations réglementaires.</li>
                        <li>Financières : Prise en charge des frais administratifs de mise en conformité.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Documents juridiques à jour et conformes, incluant bail et textes fondateurs pour l'école et l'église.</li>
                        <li>Situation administrative régularisée.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Validation des documents par les instances compétentes.</li>
                        <li>Obtention des attestations de conformité, du bail et finalisation des textes fondateurs.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Audit juridique interne : Mai 2025</li>
                        <li>Révision et actualisation des documents (y compris finalisation bail, textes école/église) : Juin - Juillet 2025</li>
                        <li>Démarches administratives : Juillet - Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe2_plan2">
                    <h4>2. Mise en Place d'une Structure de Gouvernance Claire et Participative</h4>
                    <p><strong>Descriptif :</strong> Définition et mise en œuvre d'une structure de gouvernance incluant un conseil d'administration actif et des comités de travail thématiques. Clarification des rôles, responsabilités et mécanismes de prise de décision.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Membres fondateurs, membres du conseil d'administration, responsables de comités (bénévoles).</li>
                        <li>Matérielles : Outils de communication et de partage de documents.</li>
                        <li>Financières : Non applicable directement, focus sur l'organisation interne.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Organes de gouvernance fonctionnels.</li>
                        <li>Processus de décision transparents.</li>
                        <li>Charte de gouvernance simple et adoptée.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Régularité et productivité des réunions des instances.</li>
                        <li>Clarté des mandats et des responsabilités.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Définition de la structure et des rôles : Mai - Juin 2025</li>
                        <li>Mise en place des instances : Juillet 2025</li>
                        <li>Opérationnalisation : À partir d'Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe2_plan3">
                    <h4>3. Instauration d'un Système de Gestion Financière Rigoureux</h4>
                    <p><strong>Descriptif :</strong> Mise en place de procédures de gestion financière claires et traçables pour les recettes et les dépenses, en s'appuyant sur les compétences du financier récemment recruté. Utilisation d'outils de suivi adaptés (tableurs ou logiciels de comptabilité simples) et élaboration de rapports financiers périodiques. Cela comprendra la recherche de partenaires financiers, l'optimisation de la gestion et du recouvrement.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Trésorier ou responsable financier (déjà recruté), soutien potentiel d'un comptable bénévole.</li>
                        <li>Matérielles : Outils bureautiques, modèles de suivi financier.</li>
                        <li>Financières : Non applicable directement, focus sur les processus.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Suivi précis des flux financiers.</li>
                        <li>Transparence dans la gestion des fonds.</li>
                        <li>Rapports financiers réguliers et fiables.</li>
                        <li>Partenariats financiers explorés.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Qualité et ponctualité des rapports financiers.</li>
                        <li>Respect des procédures établies.</li>
                        <li>Progrès dans la recherche de partenaires financiers.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Définition des procédures et choix des outils : Mai - Juin 2025</li>
                        <li>Mise en œuvre du système : Juillet 2025</li>
                        <li>Premier rapport financier : Septembre 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe2_plan4">
                    <h4>4. Développement et Animation d'une Équipe Opérationnelle Engagée</h4>
                    <p><strong>Descriptif :</strong> Identification des besoins en ressources humaines pour la mise en œuvre des programmes et mobilisation d'une équipe de responsables et de bénévoles, en poursuivant le processus de réunion et de responsabilisation des membres clés déjà initié. Mise en place d'un organigramme clair, de descriptifs de rôles et d'un système d'animation et de reconnaissance de l'engagement.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Responsables de l'ONG, coordinateurs de projets (bénévoles ou permanents selon les ressources), membres clés déjà identifiés.</li>
                        <li>Matérielles : Outils de communication interne.</li>
                        <li>Financières : Mobilisation de ressources pour soutenir l'équipe si nécessaire et possible.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Équipe de base structurée et motivée, avec des membres clés responsabilisés.</li>
                        <li>Rôles et responsabilités clairement définis.</li>
                        <li>Forte culture d'engagement et de service.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de bénévoles actifs et réguliers.</li>
                        <li>Satisfaction et motivation de l'équipe.</li>
                        <li>Atteinte des objectifs par les équipes projets.</li>
                        <li>Niveau d'implication des membres clés.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Définition des besoins et des rôles (complément) : Mai 2025</li>
                        <li>Campagne de mobilisation des bénévoles : Juin - Juillet 2025</li>
                        <li>Structuration et animation de l'équipe (incluant suivi responsabilisation membres clés) : À partir de Juillet 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe2_plan5">
                    <h4>5. Élaboration d'une Stratégie de Mobilisation de Ressources Diversifiée</h4>
                    <p><strong>Descriptif :</strong> Développement d'une stratégie pour assurer le financement des activités, en explorant diverses sources : dons individuels locaux, contributions de la communauté et d'églises partenaires, recherche de petits soutiens auprès d'entreprises locales (en s'appuyant sur les engagements déjà sécurisés), et préparation pour des appels à projets futurs.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Équipe dédiée à la mobilisation de ressources (bénévoles).</li>
                        <li>Matérielles : Supports de présentation de l'ONG et de ses projets.</li>
                        <li>Financières : Investissement initial dans la création de supports de collecte.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Identification de sources de financement potentielles.</li>
                        <li>Premières actions de collecte de fonds initiées.</li>
                        <li>Augmentation progressive des ressources disponibles.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de donateurs et partenaires financiers.</li>
                        <li>Montants collectés.</li>
                        <li>Diversification des sources de revenus.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Élaboration de la stratégie et des outils : Juin - Juillet 2025</li>
                        <li>Lancement des premières initiatives : Août 2025</li>
                        <li>Développement continu : À partir de Septembre 2025</li>
                    </ul>
                </div>
            </section>

            <section id="axe3" class="content-page">
                <h2>AXE 3 : DÉVELOPPEMENT COMMUNAUTAIRE ET ENGAGEMENT DES MEMBRES</h2>
                <h3>Explication de la stratégie globale de l'axe</h3>
                <p>Le développement d'une communauté forte de membres engagés est essentiel pour incarner le modèle biblique du Corps du Christ (1 Corinthiens 12). Cette approche communautaire permettra de créer un mouvement transformationnel durable.</p>
                <p><strong>Objectifs spécifiques :</strong></p>
                <ul>
                    <li>Créer une expérience d'appartenance significative pour les membres.</li>
                    <li>Développer des parcours d'engagement progressifs.</li>
                    <li>Mobiliser efficacement les compétences et ressources de la communauté.</li>
                    <li>Établir un modèle de croissance organique par l'influence et le témoignage.</li>
                </ul>
                <p><strong>Bénéfices attendus :</strong></p>
                <ul>
                    <li>Multiplication de l'impact par l'effet de levier communautaire.</li>
                    <li>Ressource durable de bénévoles compétents.</li>
                    <li>Témoignage vivant de transformation dans la société.</li>
                </ul>
                <h3>Plan d'action structuré</h3>
                <div class="plan-action-item" id="axe3_plan1">
                    <h4>1. Programme d'Intégration et Parcours d'Engagement des Membres</h4>
                    <p><strong>Descriptif :</strong> Création d'un parcours structuré pour accueillir, informer et intégrer les nouveaux membres, en leur présentant la vision, les valeurs et les opportunités d'engagement au sein de NÉHÉMIE International.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Équipe d'accueil et d'intégration (bénévoles).</li>
                        <li>Matérielles : Supports d'information (numériques ou imprimés), espace pour les rencontres d'accueil.</li>
                        <li>Financières : Mobilisation de ressources pour la création des supports.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Processus d'intégration clair et chaleureux.</li>
                        <li>Membres bien informés et motivés à s'engager.</li>
                        <li>Augmentation du nombre de membres actifs.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de nouveaux membres intégrés.</li>
                        <li>Taux de participation aux activités proposées.</li>
                        <li>Retours qualitatifs des nouveaux membres.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Conception du programme et des supports : Mai - Juin 2025</li>
                        <li>Formation de l'équipe d'accueil : Juillet 2025</li>
                        <li>Lancement : Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe3_plan2">
                    <h4>2. Système de Valorisation et Mobilisation des Compétences</h4>
                    <p><strong>Descriptif :</strong> Mise en place d'un système pour identifier, recenser et valoriser les talents, compétences et dons des membres, afin de les mobiliser de manière pertinente dans les différents projets et activités de l'ONG.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Coordinateur des bénévoles (membre de l'équipe ou bénévole).</li>
                        <li>Matérielles : Outil de recensement (ex: formulaire en ligne, base de données simple).</li>
                        <li>Financières : Non applicable directement.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Base de données des compétences des membres.</li>
                        <li>Adéquation entre les besoins des projets et les talents disponibles.</li>
                        <li>Forte implication des membres selon leurs dons.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de membres ayant déclaré leurs compétences.</li>
                        <li>Taux de mobilisation des compétences sur les projets.</li>
                        <li>Satisfaction des bénévoles quant à l'utilisation de leurs talents.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Conception du système de recensement : Juin 2025</li>
                        <li>Lancement du recensement : Juillet - Août 2025</li>
                        <li>Premières affectations basées sur les compétences : Septembre 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe3_plan3">
                    <h4>3. Création de Cellules Communautaires Dynamiques</h4>
                    <p><strong>Descriptif :</strong> Encouragement et accompagnement à la création de petits groupes (cellules) thématiques ou géographiques, favorisant le partage, le soutien mutuel, la croissance spirituelle et l'action locale.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Coordinateurs de cellules, leaders de cellules formés et accompagnés (bénévoles).</li>
                        <li>Matérielles : Guides d'animation et supports thématiques (numériques ou imprimés).</li>
                        <li>Financières : Mobilisation de ressources pour la formation des leaders et la création de supports.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Réseau de cellules actives et en croissance.</li>
                        <li>Renforcement des liens communautaires.</li>
                        <li>Initiatives locales émanant des cellules.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de cellules créées et de participants.</li>
                        <li>Régularité des rencontres.</li>
                        <li>Témoignages d'impact au sein des cellules.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Conception du modèle et des supports : Juin 2025</li>
                        <li>Formation des premiers leaders : Juillet 2025</li>
                        <li>Lancement des cellules pilotes : Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe3_plan4">
                    <h4>4. Programme d'Ambassadeurs de NÉHÉMIE</h4>
                    <p><strong>Descriptif :</strong> Identification, formation et outillage de membres engagés pour devenir des ambassadeurs de NÉHÉMIE International, capables de présenter l'organisation, de partager sa vision et de mobiliser de nouveaux soutiens dans leurs réseaux respectifs.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Coordinateur du programme (bénévole), formateurs.</li>
                        <li>Matérielles : Kit de présentation pour ambassadeurs (numérique).</li>
                        <li>Financières : Mobilisation de ressources pour la formation.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Réseau d'ambassadeurs actifs et représentatifs.</li>
                        <li>Visibilité accrue de l'ONG dans divers milieux.</li>
                        <li>Génération de nouveaux contacts et opportunités.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre d'ambassadeurs formés et actifs.</li>
                        <li>Nombre de présentations réalisées par les ambassadeurs.</li>
                        <li>Nouveaux membres ou partenaires recrutés via les ambassadeurs.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Conception du programme et des supports : Juillet 2025</li>
                        <li>Identification et formation des ambassadeurs : Août - Septembre 2025</li>
                        <li>Lancement des actions des ambassadeurs : Octobre 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe3_plan5">
                    <h4>5. Organisation d'Événements Communautaires Fédérateurs</h4>
                    <p><strong>Descriptif :</strong> Planification et organisation régulière d'événements visant à rassembler la communauté de NÉHÉMIE International (membres, bénéficiaires, sympathisants) pour célébrer, partager, apprendre et servir ensemble.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Équipe d'organisation événementielle (bénévoles).</li>
                        <li>Matérielles : Lieux de rencontre (privilégier les partenariats), contributions en nature pour la logistique.</li>
                        <li>Financières : Mobilisation de ressources et contributions pour couvrir les frais.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Renforcement du sentiment d'appartenance.</li>
                        <li>Mobilisation collective pour des actions d'impact.</li>
                        <li>Visibilité positive de la communauté.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Fréquence et participation aux événements.</li>
                        <li>Satisfaction des participants.</li>
                        <li>Engagement suscité par les événements.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Planification du calendrier des événements : Juin 2025</li>
                        <li>Premier événement communautaire : Août/Septembre 2025</li>
                        <li>Rythme régulier par la suite.</li>
                    </ul>
                </div>
            </section>

            <section id="axe4" class="content-page">
                <h2>AXE 4 : FORMATION ET AUTONOMISATION ÉCONOMIQUE</h2>
                <h3>Explication de la stratégie globale de l'axe</h3>
                <p>L'autonomisation économique est au cœur de la mission transformationnelle de NÉHÉMIE International, en lien avec le principe biblique du travail comme source de dignité. Cet axe vise à équiper les individus avec des compétences et un état d'esprit entrepreneurial fondé sur des valeurs chrétiennes.</p>
                <p><strong>Objectifs spécifiques :</strong></p>
                <ul>
                    <li>Offrir des opportunités de développement de compétences pratiques et entrepreneuriales.</li>
                    <li>Créer des parcours d'accompagnement adaptés aux réalités locales.</li>
                    <li>Faciliter l'émergence d'initiatives économiques.</li>
                    <li>Promouvoir un modèle de développement économique fondé sur les valeurs chrétiennes.</li>
                </ul>
                <p><strong>Bénéfices attendus :</strong></p>
                <ul>
                    <li>Amélioration des perspectives économiques des bénéficiaires.</li>
                    <li>Témoignage de l'impact de la foi sur le développement personnel et économique.</li>
                    <li>Création d'un réseau de soutien pour les porteurs de projets.</li>
                </ul>
                <h3>Plan d'action structuré</h3>
                <div class="plan-action-item" id="axe4_plan1">
                    <h4>1. Ateliers de Développement de Compétences Professionnelles</h4>
                    <p><strong>Descriptif :</strong> Organisation d'ateliers pratiques axés sur des compétences techniques et transversales demandées sur le marché du travail ou utiles pour de petites activités génératrices de revenus. Animation par des professionnels bénévoles ou des membres compétents. Ce plan prendra en compte la réflexion déjà menée sur les cibles, les thèmes et l'aménagement du cadre pour les "Formations NÉHÉMIE", qui n'ont pas encore été effectuées.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Coordinateurs des ateliers, formateurs bénévoles qualifiés.</li>
                        <li>Matérielles : Espaces de formation (mis à disposition par des partenaires ou membres), matériel pédagogique de base.</li>
                        <li>Financières : Mobilisation de ressources pour l'acquisition de matériel pédagogique spécifique si besoin.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Offre régulière d'ateliers thématiques, structurée à partir des réflexions sur les "Formations NÉHÉMIE".</li>
                        <li>Acquisition de nouvelles compétences par les participants.</li>
                        <li>Augmentation de l'employabilité ou de la capacité à entreprendre.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre d'ateliers organisés et de participants.</li>
                        <li>Compétences acquises (évaluations, auto-déclarations).</li>
                        <li>Initiatives concrètes issues des ateliers.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Finalisation de la planification des Formations NÉHÉMIE (cibles, thèmes, cadre) : Mai - Juin 2025</li>
                        <li>Identification des formateurs : Juin 2025</li>
                        <li>Lancement des premiers ateliers : Juillet - Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe4_plan2">
                    <h4>2. Espace de Soutien et de Réseautage pour Entrepreneurs Chrétiens</h4>
                    <p><strong>Descriptif :</strong> Création d'un cadre de rencontres, d'échanges et de soutien mutuel pour les membres porteurs de projets entrepreneuriaux ou déjà engagés dans de petites activités économiques, intégrant une perspective chrétienne de l'entreprise.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Animateur du groupe (bénévole), intervenants entrepreneurs expérimentés (bénévoles).</li>
                        <li>Matérielles : Lieu de rencontre convivial.</li>
                        <li>Financières : Non applicable directement.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Communauté d'entrepreneurs solidaires et en croissance.</li>
                        <li>Partage de bonnes pratiques et de défis.</li>
                        <li>Encouragement et développement de projets.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de participants réguliers au groupe.</li>
                        <li>Qualité des échanges et du soutien mutuel.</li>
                        <li>Projets entrepreneuriaux initiés ou consolidés.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Conception du format et mobilisation des premiers participants : Juin - Juillet 2025</li>
                        <li>Lancement des rencontres : Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe4_plan3">
                    <h4>3. Renforcement du Programme d'Apprentissage de l'Anglais Professionnel</h4>
                    <p><strong>Descriptif :</strong> Structuration et dynamisation du programme d'anglais, en vue du lancement du Club d'Anglais qui est "à structurer" et n'a pas encore été effectué. L'orientation sera vers des besoins professionnels, en s'appuyant sur des animateurs compétents et des méthodes interactives.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Animateurs/formateurs d'anglais (bénévoles ou membres qualifiés).</li>
                        <li>Matérielles : Supports pédagogiques (existants ou créés/trouvés gratuitement), salle de cours.</li>
                        <li>Financières : Mobilisation de ressources pour l'acquisition de supports si nécessaire.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Amélioration des compétences en anglais des participants.</li>
                        <li>Programme attractif et adapté aux besoins, Club d'Anglais lancé.</li>
                        <li>Meilleure préparation des participants au marché du travail international.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre d'inscrits et taux d'assiduité au Club d'Anglais.</li>
                        <li>Progression des niveaux de compétence.</li>
                        <li>Satisfaction des apprenants.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Évaluation et structuration du programme et du Club d'Anglais : Mai - Juin 2025</li>
                        <li>Recrutement/confirmation des animateurs : Juillet 2025</li>
                        <li>Lancement du Club d'Anglais renforcé : Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe4_plan4">
                    <h4>4. Sensibilisation à l'Entrepreneuriat et aux Principes de Gestion</h4>
                    <p><strong>Descriptif :</strong> Organisation de sessions de sensibilisation aux fondamentaux de la création et de la gestion d'entreprise, intégrant les principes bibliques de bonne intendance, d'éthique et de service.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Intervenants qualifiés (entrepreneurs, gestionnaires – bénévoles).</li>
                        <li>Matérielles : Supports de présentation, études de cas simples.</li>
                        <li>Financières : Non applicable directement.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Intérêt accru pour l'entrepreneuriat.</li>
                        <li>Compréhension des bases de la gestion d'une activité.</li>
                        <li>Intégration des valeurs chrétiennes dans la vision entrepreneuriale.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de participants aux sessions.</li>
                        <li>Qualité des interactions et questions posées.</li>
                        <li>Émergence de nouvelles idées de projets.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Développement du contenu des sessions : Juin - Juillet 2025</li>
                        <li>Planification et organisation des premières sessions : Septembre 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe4_plan5">
                    <h4>5. Incubateur de Micro-Projets Communautaires</h4>
                    <p><strong>Descriptif :</strong> Création d'un dispositif d'accompagnement pour l'identification, le développement et la mise en œuvre de micro-projets économiques collectifs portés par des groupes de membres, en s'inspirant et en intégrant des initiatives en cours telles que le projet « Oser bâtir » dont les démarches (y compris avec une banque pour appui) sont en cours. Cet incubateur offrira du mentorat, des formations ciblées, un accès à des ressources et un suivi personnalisé pour transformer des idées en initiatives viables et génératrices de revenus.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Coordinateur de l'incubateur, mentors expérimentés en entrepreneuriat (bénévoles), experts techniques selon les projets.</li>
                        <li>Matérielles : Espace de travail partagé (potentiellement mis à disposition par des partenaires), outils de base pour le prototypage.</li>
                        <li>Financières : Création d'un petit fonds d'amorçage pour les projets sélectionnés, mobilisation de ressources pour les formations, suivi des appuis bancaires pour "Oser bâtir".</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Émergence d'initiatives économiques collectives viables, y compris la consolidation du projet "Oser bâtir".</li>
                        <li>Développement de compétences entrepreneuriales par la pratique.</li>
                        <li>Renforcement de la solidarité et de la collaboration entre membres.</li>
                        <li>Création de modèles d'entreprise inspirés de valeurs chrétiennes.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de micro-projets accompagnés et lancés.</li>
                        <li>Avancement et concrétisation de l'appui au projet "Oser bâtir".</li>
                        <li>Viabilité économique des initiatives après 6 mois.</li>
                        <li>Compétences acquises par les porteurs de projets.</li>
                        <li>Revenus générés et nombre de personnes bénéficiant des retombées.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Conception du programme d'incubation (intégrant "Oser bâtir") : Mai - Juin 2025</li>
                        <li>Formation de l'équipe d'accompagnement : Juillet 2025</li>
                        <li>Appel à projets et sélection des premières initiatives / Consolidation "Oser bâtir" : Août 2025</li>
                        <li>Lancement de l'accompagnement : Septembre 2025</li>
                    </ul>
                </div>
            </section>

            <section id="axe5" class="content-page">
                <h2>AXE 5 : FONDATION ET TRANSFORMATION SPIRITUELLES</h2>
                <h3>Explication de la stratégie globale de l'axe</h3>
                <p>Le fondement spirituel est la pierre angulaire de NÉHÉMIE International. Comme le rappelle Zacharie 4:6, <em>"Ce n'est ni par la puissance ni par la force, mais c'est par mon esprit, dit l'Éternel"</em>. Cet axe vise à développer un impact spirituel profond et transformationnel.</p>
                <p><strong>Objectifs spécifiques :</strong></p>
                <ul>
                    <li>Établir un ministère d'enseignement biblique solide et contextuel.</li>
                    <li>Développer une dimension puissante d'intercession et de prière.</li>
                    <li>Former des leaders spirituels équilibrés et influents.</li>
                    <li>Faciliter des expériences authentiques de transformation spirituelle.</li>
                </ul>
                <p><strong>Bénéfices attendus :</strong></p>
                <ul>
                    <li>Transformation profonde et durable des vies.</li>
                    <li>Témoignage visible de l'action de Dieu.</li>
                    <li>Fondement spirituel pour tous les autres programmes.</li>
                </ul>
                <h3>Plan d'action structuré</h3>
                <div class="plan-action-item" id="axe5_plan1">
                    <h4>1. Programme d'Enseignement Biblique Pratique et Contextuel</h4>
                    <p><strong>Descriptif :</strong> Mise en place de modules d'enseignement biblique accessibles et progressifs, axés sur l'application pratique des principes bibliques dans la vie quotidienne et dans le contexte gabonais, pouvant s'articuler avec des initiatives d'éducation plus larges comme le projet de création d'école, dont la rédaction des textes fondateurs est en cours.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Enseignants bibliques qualifiés (membres de l'équipe, pasteurs partenaires, bénévoles).</li>
                        <li>Matérielles : Bibles, supports d'étude (numériques ou imprimés), lieux de rencontre.</li>
                        <li>Financières : Mobilisation de ressources pour la création et l'impression des supports.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Participation régulière aux enseignements.</li>
                        <li>Croissance dans la connaissance et l'application de la Parole.</li>
                        <li>Témoignages de transformation personnelle.</li>
                        <li>Articulation avec le projet d'école clarifiée.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de participants et assiduité.</li>
                        <li>Qualité des échanges et des apprentissages.</li>
                        <li>Application concrète des enseignements dans la vie des participants.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Élaboration ou sélection du curriculum (en lien avec projet école) : Mai - Juin 2025</li>
                        <li>Formation/briefing des enseignants : Juillet 2025</li>
                        <li>Lancement des premiers modules : Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe5_plan2">
                    <h4>2. Structuration du Ministère d'Intercession</h4>
                    <p><strong>Descriptif :</strong> Organisation et animation d'un ministère d'intercession dynamique, avec des équipes de prière, des temps de prière collectifs et des initiatives de prière ciblées. Cela comprendra la finalisation des travaux et aménagements nécessaires pour la salle de prière (actuellement non effectués) et la redynamisation ou la reprise d'initiatives telles que 'La Prière du Bâtisseur', actuellement suspendue et en attente de discussion pour reprise. Utilisation et optimisation de la salle de prière une fois finalisée.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Coordinateur de l'intercession, leaders d'équipes de prière (bénévoles).</li>
                        <li>Matérielles : Salle de prière (à finaliser), supports pour guider la prière.</li>
                        <li>Financières : Mobilisation de ressources pour la finalisation de l'aménagement et l'équipement de la salle de prière.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Salle de prière finalisée et fonctionnelle.</li>
                        <li>Vie de prière dynamique au sein de l'ONG, incluant 'La Prière du Bâtisseur' redynamisée.</li>
                        <li>Couverture de prière pour toutes les activités.</li>
                        <li>Témoignages d'exaucements et d'intervention divine.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Achèvement des travaux de la salle de prière.</li>
                        <li>Reprise effective de 'La Prière du Bâtisseur'.</li>
                        <li>Nombre de participants aux activités de prière.</li>
                        <li>Régularité des temps de prière.</li>
                        <li>Impact ressenti de la prière sur les projets et les vies.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Planification de la finalisation de la salle de prière et discussion pour reprise 'Prière du Bâtisseur' : Mai 2025</li>
                        <li>Travaux de finalisation de la salle de prière : Juin - Juillet 2025</li>
                        <li>Lancement officiel du ministère et reprise des initiatives : Juillet - Août 2025</li>
                        <li>Animation continue : À partir d'Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe5_plan3">
                    <h4>3. Développement du Leadership Spirituel par le Mentorat</h4>
                    <p><strong>Descriptif :</strong> Identification et accompagnement de leaders potentiels au sein de la communauté par un système de mentorat avec des leaders spirituels plus expérimentés. L'objectif est de former des responsables ancrés dans les valeurs chrétiennes et capables d'influencer positivement leur entourage.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Mentors expérimentés et disponibles (bénévoles).</li>
                        <li>Matérielles : Guides de discussion pour le mentorat, espaces de rencontre discrets.</li>
                        <li>Financières : Mobilisation de ressources pour la formation des mentors.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Émergence de nouveaux leaders spirituels.</li>
                        <li>Transmission des valeurs et des principes de leadership chrétien.</li>
                        <li>Renforcement des capacités de leadership au sein de l'ONG.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de relations de mentorat établies.</li>
                        <li>Progression des mentorés dans leur leadership.</li>
                        <li>Impact des leaders formés dans leurs sphères d'influence.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Identification des mentors et conception du cadre : Juin - Juillet 2025</li>
                        <li>Lancement des premières relations de mentorat : Août 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe5_plan4">
                    <h4>4. Accompagnement Spirituel Personnalisé</h4>
                    <p><strong>Descriptif :</strong> Mise en place d'un service d'écoute et d'accompagnement spirituel individualisé pour les membres et bénéficiaires qui en expriment le besoin, offert par des personnes formées et matures dans la foi.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Équipe d'accompagnateurs spirituels formés (bénévoles).</li>
                        <li>Matérielles : Espaces confidentiels pour les entretiens.</li>
                        <li>Financières : Mobilisation de ressources pour la formation des accompagnateurs.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Soutien spirituel adapté aux besoins individuels.</li>
                        <li>Croissance personnelle et résolution de difficultés.</li>
                        <li>Renforcement de la foi et de la résilience.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre de personnes accompagnées.</li>
                        <li>Satisfaction des personnes suivies.</li>
                        <li>Témoignages de progression spirituelle.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Formation des accompagnateurs : Juillet - Août 2025</li>
                        <li>Mise en place du service : Septembre 2025</li>
                    </ul>
                </div>
                <div class="plan-action-item" id="axe5_plan5">
                    <h4>5. Initiatives d'Impact Communautaire fondées sur la Foi</h4>
                    <p><strong>Descriptif :</strong> Encouragement et soutien d'initiatives locales d'action sociale et de témoignage chrétien, portées par les membres et les cellules communautaires, pour répondre à des besoins concrets dans leur environnement (aide aux plus démunis, actions de réconciliation, projets environnementaux, etc.), avec un accent particulier sur le soutien au projet d'implantation de l'Église, dont la rédaction des textes est en cours, et qui servira de catalyseur pour de nombreuses actions communautaires.</p>
                    <p><strong>Ressources nécessaires :</strong></p>
                    <ul>
                        <li>Humaines : Coordinateurs de projets communautaires, équipes de bénévoles.</li>
                        <li>Matérielles : Mobilisation de ressources en nature ou matérielles spécifiques aux projets.</li>
                        <li>Financières : Mobilisation de petits fonds pour soutenir les initiatives.</li>
                    </ul>
                    <p><strong>Résultats attendus :</strong></p>
                    <ul>
                        <li>Manifestation concrète de l'amour chrétien en action.</li>
                        <li>Réponse à des besoins sociaux identifiés.</li>
                        <li>Impact positif et visible de l'ONG dans la communauté, y compris à travers l'avancement du projet d'implantation de l'Église.</li>
                    </ul>
                    <p><strong>Indicateurs de suivi :</strong></p>
                    <ul>
                        <li>Nombre et nature des initiatives lancées.</li>
                        <li>Progrès du projet d'implantation de l'Église.</li>
                        <li>Nombre de bénéficiaires et d'impacts mesurables.</li>
                        <li>Perception de l'ONG par la communauté locale.</li>
                    </ul>
                    <p><strong>Calendrier :</strong></p>
                    <ul>
                        <li>Identification des besoins et des idées de projets (incluant l'Église) : Mai - Juillet 2025</li>
                        <li>Lancement des premières initiatives : Août - Septembre 2025</li>
                    </ul>
                </div>
            </section>

            <section id="integration" class="content-page">
                <h2>INTÉGRATION ET MISE EN ŒUVRE</h2>
                <h3 id="integration_chrono">Chronologie et priorisation des actions</h3>
                <p><strong>Phase 1 : Fondations et Mobilisation (Mai-Juin 2025)</strong></p>
                <ul>
                    <li>Consolidation du cadre juridique (y compris la régularisation contractuelle en cours, la finalisation du bail et des textes pour l'école/église) et de la gouvernance.</li>
                    <li>Déploiement de l'identité visuelle institutionnelle et création/amélioration des supports de base (incluant plaquette).</li>
                    <li>Structuration de la gestion financière, s'appuyant sur le financier recruté et la recherche de partenaires.</li>
                    <li>Recensement des compétences et mobilisation/responsabilisation continue des équipes de bénévoles et membres clés.</li>
                    <li>Conception/Finalisation des programmes clés (intégration, enseignement en lien avec projet école, cellules, Formations NÉHÉMIE, Club d'Anglais, salle de prière, Prière du Bâtisseur).</li>
                    <li>Finalisation de la stratégie de communication globale.</li>
                </ul>
                <p><strong>Phase 2 : Lancement et Premiers Engagements (Juillet-Août 2025)</strong></p>
                <ul>
                    <li>Lancement du programme d'intégration des membres et des premières cellules.</li>
                    <li>Démarrage des enseignements bibliques et structuration/redynamisation de l'intercession (salle de prière finalisée, Prière du Bâtisseur relancée).</li>
                    <li>Mise en place de l'écosystème digital (site web, réseaux sociaux) et intensification de la production des premiers contenus (incluant émission 'À l'image de Christ').</li>
                    <li>Lancement des premiers ateliers de compétences (Formations NÉHÉMIE) et du Club d'Anglais.</li>
                    <li>Initiation des rencontres de l'espace de soutien aux entrepreneurs et consolidation du projet "Oser bâtir".</li>
                </ul>
                <p><strong>Phase 3 : Développement et Rayonnement Initial (Septembre-Octobre 2025)</strong></p>
                <ul>
                    <li>Déploiement complet des programmes de formation et d'accompagnement.</li>
                    <li>Intensification de la production de contenu et des actions de communication.</li>
                    <li>Organisation des premiers événements communautaires et rencontres thématiques.</li>
                    <li>Lancement du programme d'ambassadeurs et des premières actions sociales communautaires (y compris avancées sur l'implantation de l'Église).</li>
                    <li>Développement des relations institutionnelles.</li>
                </ul>
                <p><strong>Phase 4 : Consolidation et Perspectives (Novembre-Décembre 2025)</strong></p>
                <ul>
                    <li>Évaluation des premières actions et collecte des témoignages d'impact.</li>
                    <li>Ajustement des programmes en fonction des retours et des résultats.</li>
                    <li>Célébration des premières réussites et reconnaissance de l'engagement.</li>
                    <li>Planification stratégique pour l'année 2026, en capitalisant sur les acquis.</li>
                </ul>

                <h3 id="integration_coord">Mécanismes de coordination entre les axes</h3>
                <ol>
                    <li><strong>Comité de Pilotage Stratégique :</strong> Composé des responsables des axes et de la direction, se réunissant régulièrement pour assurer la cohérence, le suivi des progrès et la résolution des défis.</li>
                    <li><strong>Communication Interne Fluide :</strong> Utilisation d'outils collaboratifs pour le partage d'informations, la coordination des activités et la gestion de projet.</li>
                    <li><strong>Réunions Transversales :</strong> Organisation de réunions spécifiques pour les projets impliquant plusieurs axes afin d'assurer une synergie et une optimisation des ressources.</li>
                </ol>

                <h3 id="integration_suivi">Système de suivi-évaluation global</h3>
                <ol>
                    <li><strong>Indicateurs Clés :</strong> Définition d'indicateurs de performance qualitatifs et quantitatifs pour chaque action, axés sur l'engagement, la participation et l'impact, en intégrant ceux des projets déjà en cours.</li>
                    <li><strong>Collecte de Données :</strong> Mise en place de mécanismes simples de collecte (rapports d'activité, fiches de suivi, enquêtes de satisfaction, collecte de témoignages).</li>
                    <li><strong>Revues Périodiques :</strong> Analyse mensuelle des progrès et évaluation trimestrielle plus approfondie pour identifier les succès, les défis et les ajustements nécessaires.</li>
                </ol>

                <h3 id="integration_changement">Plan de gestion du changement</h3>
                <ol>
                    <li><strong>Communication Claire de la Vision :</strong> Partage régulier et inspirant de la vision, des objectifs et des progrès de la stratégie avec tous les membres et parties prenantes.</li>
                    <li><strong>Implication et Participation :</strong> Encourager la participation active des membres dans la planification et la mise en œuvre des actions, favorisant l'appropriation.</li>
                    <li><strong>Valorisation et Reconnaissance :</strong> Mettre en lumière les contributions de chacun et célébrer les étapes franchies pour maintenir la motivation et l'engagement.</li>
                    <li><strong>Flexibilité et Apprentissage Continu :</strong> Adopter une culture d'apprentissage, en étant prêt à ajuster les plans en fonction des réalités du terrain et des leçons apprises.</li>
                </ol>
            </section>

            <section id="conclusion" class="content-page">
                <h2>CONCLUSION</h2>
                <p>Cette stratégie globale initiale pour NÉHÉMIE International (Mai - Décembre 2025) établit une feuille de route claire et mobilisatrice. Elle est conçue pour poser des fondations solides, capitaliser sur les actions déjà entreprises, maximiser l'impact avec les ressources disponibles et initier un mouvement de transformation durable au Gabon, fidèle à notre vision et à nos fondements bibliques.</p>
                <p>Nous sommes convaincus qu'en mettant en œuvre ces actions avec diligence, foi et l'engagement de toute notre communauté, NÉHÉMIE International deviendra un instrument puissant de reconstruction et d'espérance. Nous avançons avec la certitude que <em>"celui qui a commencé en vous cette bonne œuvre la rendra parfaite pour le jour de Jésus-Christ"</em> (Philippiens 1:6).</p>
            </section>

            <footer class="site-footer" id="page-content-bottom">
                <p>&copy; 2025 NÉHÉMIE INTERNATIONAL. Tous droits réservés.</p>
                <p>Stratégie Globale de Développement et Transformation (Mai - Décembre 2025)</p>
            </footer>
        </div> 
    </main>

    <a href="#" id="scrollToTopBtn" title="Remonter en haut">↑</a>
    <a href="#" id="scrollToBottomBtn" title="Aller en bas">↓</a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainNavLinks = document.querySelectorAll('#main-navigation a');
            const contentPages = document.querySelectorAll('.content-page');
            const mainContentPane = document.getElementById('main-content'); 
            // const titlePaneWidth = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--title-pane-width'));
            // const menuPaneWidth = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--menu-pane-width'));

            function showPage(pageId, isSubLinkClick = false, subElementId = null) {
                let pageFound = false;
                contentPages.forEach(page => {
                    if (page.id === pageId) {
                        page.classList.add('active');
                        pageFound = true;
                    } else {
                        page.classList.remove('active');
                    }
                });

                if (!pageFound) { 
                    console.warn("Page principale non trouvée pour l'ID:", pageId, "Affichage du Préambule.");
                    const preambulePage = document.getElementById('preambule');
                    if (preambulePage) preambulePage.classList.add('active');
                    pageId = 'preambule'; 
                }
                
                mainNavLinks.forEach(link => {
                    link.classList.remove('active-menu-item', 'active-menu-item-parent');
                    const linkHref = link.getAttribute('href').substring(1);
                    if (linkHref === pageId || (subElementId && linkHref === subElementId)) {
                        link.classList.add('active-menu-item');
                        const parentLi = link.closest('ul')?.closest('li');
                        if (parentLi && parentLi.querySelector('a') !== link) {
                           parentLi.querySelector('a')?.classList.add('active-menu-item-parent');
                        }
                    } else if (subElementId && subElementId.startsWith(linkHref) && linkHref !== subElementId.split('_')[0] && linkHref.startsWith(pageId.split('_')[0])) { 
                         // Cas où le lien est un parent de l'élément actif, mais pas la page principale elle-même
                        const parentCandidate = link.closest('ul')?.closest('li')?.querySelector('a');
                        if(parentCandidate && subElementId.startsWith(parentCandidate.getAttribute('href').substring(1))){
                            link.classList.add('active-menu-item-parent');
                        }
                    } else if (pageId.startsWith(linkHref) && linkHref !== pageId && !subElementId) {
                         link.classList.add('active-menu-item-parent');
                    }


                });
                
                if (!isSubLinkClick && !subElementId) { 
                    mainContentPane.scrollTop = 0; 
                }
            }

            mainNavLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    const targetId = this.getAttribute('href').substring(1);
                    let pageToShowId = targetId;
                    let subElementToScrollTo = null;

                    if (targetId.includes('_plan') || (targetId.startsWith('integration_') && targetId !== 'integration')) { 
                        pageToShowId = targetId.split('_')[0]; 
                        if(pageToShowId === "integration" && targetId !== "integration"){
                           pageToShowId = "integration"; 
                        }
                        subElementToScrollTo = targetId;
                    }
                    
                    const pageElement = document.getElementById(pageToShowId);
                    if (pageElement && pageElement.classList.contains('content-page')) {
                        event.preventDefault();
                        history.pushState({page: targetId}, '', '#' + targetId); 
                        showPage(pageToShowId, false, subElementToScrollTo); 

                        if (subElementToScrollTo) {
                           setTimeout(() => { 
                                const subElement = document.getElementById(subElementToScrollTo);
                                if (subElement) {
                                    const offsetTop = subElement.offsetTop - 20; 
                                    mainContentPane.scrollTo({
                                        top: offsetTop,
                                        behavior: 'smooth'
                                    });
                                }
                           }, 50); 
                        }
                    } else if (document.getElementById(targetId) && document.getElementById(targetId).classList.contains('content-page')) {
                        event.preventDefault();
                        history.pushState({page: targetId}, '', '#' + targetId);
                        showPage(targetId);
                    }
                });
            });

            function handleLocationChange(statePage) {
                let targetPageId = window.location.hash ? window.location.hash.substring(1) : 'preambule';
                if (statePage) targetPageId = statePage; // Prioriser l'état de l'historique

                let pageToShowId = targetPageId;
                let subElementToScrollTo = null;

                if (targetPageId.includes('_plan') || (targetPageId.startsWith('integration_') && targetPageId !== 'integration')) {
                    pageToShowId = targetPageId.split('_')[0];
                     if(pageToShowId === "integration" && targetPageId !== "integration"){
                           pageToShowId = "integration";
                        }
                    subElementToScrollTo = targetPageId;
                }
                
                showPage(pageToShowId, false, subElementToScrollTo);
                
                if (subElementToScrollTo) {
                     setTimeout(() => {
                        const subElement = document.getElementById(subElementToScrollTo);
                        if (subElement) {
                             const offsetTop = subElement.offsetTop - 20;
                             mainContentPane.scrollTo({ top: offsetTop, behavior: 'smooth' });
                        }
                    }, 100); 
                } else {
                    mainContentPane.scrollTop = 0; 
                }
            }

            window.addEventListener('popstate', function(event) {
                handleLocationChange(event.state ? event.state.page : null);
            });
            handleLocationChange(); 

            let scrollTopBtn = document.getElementById("scrollToTopBtn");
            let scrollBottomBtn = document.getElementById("scrollToBottomBtn");
            
            mainContentPane.onscroll = function() { scrollNavButtons(); }; 

            function scrollNavButtons() {
                if (mainContentPane.scrollTop > 100) {
                    scrollTopBtn.style.display = "block";
                } else {
                    scrollTopBtn.style.display = "none";
                }
                if ((mainContentPane.offsetHeight + mainContentPane.scrollTop) >= mainContentPane.scrollHeight - 50) { 
                    scrollBottomBtn.style.display = "none"; 
                } else {
                    scrollBottomBtn.style.display = "block"; 
                }
            }
            scrollNavButtons(); 

            scrollTopBtn.addEventListener('click', function(e) {
                e.preventDefault(); 
                mainContentPane.scrollTo({ top: 0, behavior: 'smooth' });
            });
            scrollBottomBtn.addEventListener('click', function(e) {
                e.preventDefault(); 
                mainContentPane.scrollTo({ top: mainContentPane.scrollHeight, behavior: 'smooth' });
            });
        });
    </script>

</body>
</html>