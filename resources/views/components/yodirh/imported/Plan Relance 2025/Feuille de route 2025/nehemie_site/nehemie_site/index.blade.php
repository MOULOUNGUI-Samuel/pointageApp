<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NÉHÉMIE International - Levons-nous et bâtissons!</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Overlay de préchargement -->
    <div id="preloader">
        <div class="loader">
            <div class="loader-inner"></div>
        </div>
        <div class="loader-text">NÉHÉMIE International</div>
    </div>

    <!-- En-tête et navigation -->
    <header class="header" id="header">
        <div class="container">
            <div class="header-wrapper">
                <div class="logo">
                    <a href="#accueil">
                        <img src="https://nehemie-international.com/wp-content/uploads/2024/01/LOGO-NEHEMIE-carre-e1704837809114.png" alt="Logo NÉHÉMIE International">
                        <span>NÉHÉMIE International</span>
                    </a>
                </div>
                
                <nav class="main-nav">
                    <ul class="nav-list">
                        <li><a href="#accueil" class="nav-link active">Accueil</a></li>
                        <li class="dropdown">
                            <a href="#qui-sommes-nous" class="nav-link">Qui sommes-nous <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="#notre-histoire">Notre Histoire</a></li>
                                <li><a href="#vision-mission">Vision & Mission</a></li>
                                <li><a href="#nos-valeurs">Nos Valeurs</a></li>
                                <li><a href="#notre-equipe">Notre Équipe</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#nos-programmes" class="nav-link">Nos programmes <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="#programme-timothee">TIMOTHÉE</a></li>
                                <li><a href="#programme-philippe">PHILIPPE</a></li>
                                <li><a href="#programme-dorcas">DORCAS</a></li>
                                <li><a href="#programme-bethanie">BÉTHANIE</a></li>
                                <li><a href="#programme-daniel">DANIEL</a></li>
                            </ul>
                        </li>
                        <li><a href="#actualites" class="nav-link">Actualités</a></li>
                        <li><a href="#temoignages" class="nav-link">Témoignages</a></li>
                        <li><a href="#contact" class="nav-link">Contact</a></li>
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <a href="#faire-un-don" class="btn btn-primary">Faire un don</a>
                    <button class="menu-toggle" aria-label="Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Menu mobile -->
    <div class="mobile-menu">
        <div class="container">
            <ul class="mobile-nav-list">
                <li><a href="#accueil" class="mobile-nav-link">Accueil</a></li>
                <li class="mobile-dropdown">
                    <a href="#qui-sommes-nous" class="mobile-nav-link">Qui sommes-nous <i class="fas fa-chevron-down"></i></a>
                    <ul class="mobile-dropdown-menu">
                        <li><a href="#notre-histoire">Notre Histoire</a></li>
                        <li><a href="#vision-mission">Vision & Mission</a></li>
                        <li><a href="#nos-valeurs">Nos Valeurs</a></li>
                        <li><a href="#notre-equipe">Notre Équipe</a></li>
                    </ul>
                </li>
                <li class="mobile-dropdown">
                    <a href="#nos-programmes" class="mobile-nav-link">Nos programmes <i class="fas fa-chevron-down"></i></a>
                    <ul class="mobile-dropdown-menu">
                        <li><a href="#programme-timothee">TIMOTHÉE</a></li>
                        <li><a href="#programme-philippe">PHILIPPE</a></li>
                        <li><a href="#programme-dorcas">DORCAS</a></li>
                        <li><a href="#programme-bethanie">BÉTHANIE</a></li>
                        <li><a href="#programme-daniel">DANIEL</a></li>
                    </ul>
                </li>
                <li><a href="#actualites" class="mobile-nav-link">Actualités</a></li>
                <li><a href="#temoignages" class="mobile-nav-link">Témoignages</a></li>
                <li><a href="#contact" class="mobile-nav-link">Contact</a></li>
                <li><a href="#faire-un-don" class="btn btn-primary mobile-btn">Faire un don</a></li>
            </ul>
        </div>
    </div>

    <!-- Section Héro avec slider -->
    <section id="accueil" class="hero-section">
        <div class="hero-slider">
            <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')">
                <div class="container">
                    <div class="hero-content">
                        <h1 class="hero-title" data-aos="fade-up">"Levons-nous et bâtissons!"</h1>
                        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">Une ONG chrétienne engagée auprès des vulnérables</p>
                        <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
                            <a href="#qui-sommes-nous" class="btn btn-primary">Découvrir notre mission</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')">
                <div class="container">
                    <div class="hero-content">
                        <h1 class="hero-title">Donnez-leur vous-mêmes à manger</h1>
                        <p class="hero-subtitle">Campagne de levée de fonds - Juin 2025</p>
                        <div class="hero-buttons">
                            <a href="#faire-un-don" class="btn btn-primary">Participez maintenant</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1542810634-71277d95dcbb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')">
                <div class="container">
                    <div class="hero-content">
                        <h1 class="hero-title">L'Évangélisation par les actes</h1>
                        <p class="hero-subtitle">Notre engagement pour la transformation sociale</p>
                        <div class="hero-buttons">
                            <a href="#nos-programmes" class="btn btn-primary">Découvrir nos programmes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-controls">
            <button class="hero-control prev" aria-label="Slide précédent"><i class="fas fa-chevron-left"></i></button>
            <div class="hero-dots">
                <button class="hero-dot active" aria-label="Slide 1"></button>
                <button class="hero-dot" aria-label="Slide 2"></button>
                <button class="hero-dot" aria-label="Slide 3"></button>
            </div>
            <button class="hero-control next" aria-label="Slide suivant"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>

    <!-- Section Message de bienvenue -->
    <section class="welcome-section">
        <div class="container">
            <div class="welcome-wrapper">
                <div class="welcome-image" data-aos="fade-right">
                    <img src="https://nehemie-international.com/wp-content/uploads/2024/01/President-Davy-NGUELENGOGO.jpg" alt="NGUEL'ENGOGO Davy, Président">
                    <div class="welcome-image-info">
                        <h3>NGUEL'ENGOGO Davy</h3>
                        <p>Président, NÉHÉMIE International</p>
                    </div>
                </div>
                <div class="welcome-content" data-aos="fade-left">
                    <div class="section-heading">
                        <h2>Message de bienvenue</h2>
                        <div class="heading-line"></div>
                    </div>
                    <div class="welcome-text">
                        <p>"C'est avec un profond sentiment d'espoir et une vision claire que je vous accueille sur notre site. NÉHÉMIE International est née d'une conviction profonde : la foi authentique s'exprime naturellement par des actes concrets d'amour et de service qui restaurent la dignité humaine."</p>
                        <p>"À l'heure où le Gabon fait face à des défis sociaux considérables, nous sommes persuadés que les valeurs de compassion, de service et d'intégrité peuvent constituer le socle d'un développement durable et équitable."</p>
                        <p>"Je vous invite à découvrir notre mission et à vous joindre à nous dans cette œuvre de reconstruction et d'espérance."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Notre Mission -->
    <section id="qui-sommes-nous" class="mission-section">
        <div class="container">
            <div class="section-heading text-center" data-aos="fade-up">
                <h2>Notre mission</h2>
                <div class="heading-line center"></div>
                <p class="section-subtitle">NÉHÉMIE International existe pour exprimer sa foi par son engagement en faveur de la restauration, de la solidarité et de l'autonomie des personnes vulnérables et fragilisées.</p>
            </div>
            
            <div class="mission-cards">
                <div class="mission-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="mission-card-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Aide & Soutien</h3>
                    <p>Répondre aux besoins essentiels des populations les plus défavorisées.</p>
                </div>
                <div class="mission-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="mission-card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3>Formation</h3>
                    <p>Former et accompagner les personnes vers l'autonomie économique et sociale.</p>
                </div>
                <div class="mission-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="mission-card-icon">
                        <i class="fas fa-pray"></i>
                    </div>
                    <h3>Accompagnement Spirituel</h3>
                    <p>Offrir un accompagnement spirituel qui respecte la liberté de conscience.</p>
                </div>
            </div>
            
            <div class="mission-approach" data-aos="fade-up">
                <div class="section-heading text-center">
                    <h3>Notre approche : L'évangélisation par les actes</h3>
                    <div class="heading-line center"></div>
                </div>
                <div class="mission-quote">
                    <i class="fas fa-quote-left"></i>
                    <blockquote>
                        À quoi bon dire qu'on a la foi, si l'on n'a pas les œuvres ? La foi peut-elle sauver, si elle n'a pas les œuvres ? Si un frère ou une sœur sont nus, s'ils manquent de leur nourriture quotidienne, et que l'un d'entre vous leur dise : Allez en paix, chauffez-vous et rassasiez-vous ! sans leur donner ce qui est nécessaire au corps, à quoi cela sert-il ?
                        <cite>Jacques 2:14-17</cite>
                    </blockquote>
                </div>
            </div>
            
            <div class="text-center" data-aos="fade-up">
                <a href="#nos-programmes" class="btn btn-secondary">Découvrir nos programmes</a>
            </div>
        </div>
    </section>

    <!-- Section Campagne en cours -->
    <section class="campaign-section">
        <div class="container">
            <div class="campaign-wrapper">
                <div class="campaign-image" data-aos="fade-right">
                    <img src="https://nehemie-international.com/wp-content/uploads/2024/05/CAMPAGNE-DLVAM-JUIN-2024-1024x576.jpg" alt="Campagne Donnez-leur vous-mêmes à manger">
                </div>
                <div class="campaign-content" data-aos="fade-left">
                    <div class="campaign-badge">Campagne en cours</div>
                    <h2>Donnez-leur vous-mêmes à manger</h2>
                    <p>Durant tout le mois de juin, NÉHÉMIE International vous invite à faire partie d'un miracle quotidien. Chaque don collecté permettra d'organiser en juillet une grande distribution alimentaire pour les familles les plus vulnérables.</p>
                    
                    <div class="campaign-progress">
                        <div class="progress-info">
                            <span>Objectif: 10 000 000 FCFA</span>
                            <span>65% atteint</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 65%"></div>
                        </div>
                    </div>
                    
                    <div class="campaign-actions">
                        <a href="#faire-un-don" class="btn btn-primary">Faire un don</a>
                        <a href="#" class="btn btn-outline">En savoir plus</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Nos Programmes -->
    <section id="nos-programmes" class="programs-section">
        <div class="container">
            <div class="section-heading text-center" data-aos="fade-up">
                <h2>Nos programmes</h2>
                <div class="heading-line center"></div>
                <p class="section-subtitle">Découvrez nos cinq programmes fondamentaux, chacun répondant à une dimension spécifique de notre mission globale.</p>
            </div>
            
            <div class="programs-tabs" data-aos="fade-up">
                <div class="programs-tabs-nav">
                    <button class="program-tab active" data-program="timothee">
                        <i class="fas fa-graduation-cap"></i>
                        <span>TIMOTHÉE</span>
                    </button>
                    <button class="program-tab" data-program="philippe">
                        <i class="fas fa-bullhorn"></i>
                        <span>PHILIPPE</span>
                    </button>
                    <button class="program-tab" data-program="dorcas">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>DORCAS</span>
                    </button>
                    <button class="program-tab" data-program="bethanie">
                        <i class="fas fa-users"></i>
                        <span>BÉTHANIE</span>
                    </button>
                    <button class="program-tab" data-program="daniel">
                        <i class="fas fa-pray"></i>
                        <span>DANIEL</span>
                    </button>
                </div>
                
                <div class="programs-tabs-content">
                    <div class="program-content active" id="program-timothee">
                        <div class="program-content-inner">
                            <div class="program-image">
                                <img src="https://images.unsplash.com/photo-1522661067900-ab829854a57f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Programme TIMOTHÉE">
                            </div>
                            <div class="program-details">
                                <h3>Programme TIMOTHÉE</h3>
                                <p class="program-tagline">Formation et développement des compétences</p>
                                <p>Le programme TIMOTHÉE vise à équiper les jeunes et les adultes avec des compétences pratiques et des connaissances qui favorisent leur autonomie économique et leur développement personnel.</p>
                                <h4>Nos actions :</h4>
                                <ul class="program-actions">
                                    <li><i class="fas fa-check"></i> Formations professionnelles (bureautique, langues, entrepreneuriat)</li>
                                    <li><i class="fas fa-check"></i> Accompagnement à l'insertion professionnelle</li>
                                    <li><i class="fas fa-check"></i> Mentorat et coaching personnalisé</li>
                                    <li><i class="fas fa-check"></i> Ateliers de développement personnel</li>
                                </ul>
                                <a href="#" class="btn btn-secondary">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-content" id="program-philippe">
                        <div class="program-content-inner">
                            <div class="program-image">
                                <img src="https://images.unsplash.com/photo-1531545514256-b1400bc00f31?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Programme PHILIPPE">
                            </div>
                            <div class="program-details">
                                <h3>Programme PHILIPPE</h3>
                                <p class="program-tagline">Sensibilisation et partage de valeurs</p>
                                <p>Le programme PHILIPPE se consacre à la sensibilisation et au partage des valeurs chrétiennes à travers des actions concrètes qui témoignent de notre foi par les actes.</p>
                                <h4>Nos actions :</h4>
                                <ul class="program-actions">
                                    <li><i class="fas fa-check"></i> Campagnes de sensibilisation communautaire</li>
                                    <li><i class="fas fa-check"></i> Production de contenus inspirants</li>
                                    <li><i class="fas fa-check"></i> Organisation d'événements thématiques</li>
                                    <li><i class="fas fa-check"></i> Partage de témoignages transformateurs</li>
                                </ul>
                                <a href="#" class="btn btn-secondary">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-content" id="program-dorcas">
                        <div class="program-content-inner">
                            <div class="program-image">
                                <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Programme DORCAS">
                            </div>
                            <div class="program-details">
                                <h3>Programme DORCAS</h3>
                                <p class="program-tagline">Action sociale et humanitaire</p>
                                <p>Le programme DORCAS répond aux besoins essentiels des populations vulnérables à travers des actions humanitaires et sociales qui témoignent de notre compassion en actes.</p>
                                <h4>Nos actions :</h4>
                                <ul class="program-actions">
                                    <li><i class="fas fa-check"></i> Distribution de denrées alimentaires</li>
                                    <li><i class="fas fa-check"></i> Fourniture de produits d'hygiène et de première nécessité</li>
                                    <li><i class="fas fa-check"></i> Soutien aux familles en situation précaire</li>
                                    <li><i class="fas fa-check"></i> Visites aux personnes isolées</li>
                                </ul>
                                <a href="#" class="btn btn-secondary">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-content" id="program-bethanie">
                        <div class="program-content-inner">
                            <div class="program-image">
                                <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Programme BÉTHANIE">
                            </div>
                            <div class="program-details">
                                <h3>Programme BÉTHANIE</h3>
                                <p class="program-tagline">Communion fraternelle et soutien communautaire</p>
                                <p>Le programme BÉTHANIE crée des espaces de communion fraternelle et de soutien mutuel, favorisant les liens communautaires et l'entraide entre les personnes.</p>
                                <h4>Nos actions :</h4>
                                <ul class="program-actions">
                                    <li><i class="fas fa-check"></i> Organisation de rencontres communautaires</li>
                                    <li><i class="fas fa-check"></i> Création de groupes de soutien</li>
                                    <li><i class="fas fa-check"></i> Activités intergénérationnelles</li>
                                    <li><i class="fas fa-check"></i> Projets collaboratifs locaux</li>
                                </ul>
                                <a href="#" class="btn btn-secondary">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="program-content" id="program-daniel">
                        <div class="program-content-inner">
                            <div class="program-image">
                                <img src="https://images.unsplash.com/photo-1507692049790-de58290a4334?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Programme DANIEL">
                            </div>
                            <div class="program-details">
                                <h3>Programme DANIEL</h3>
                                <p class="program-tagline">Soutien spirituel et accompagnement</p>
                                <p>Le programme DANIEL offre un accompagnement spirituel et un soutien pastoral aux personnes en quête de sens, dans le respect de leur liberté de conscience.</p>
                                <h4>Nos actions :</h4>
                                <ul class="program-actions">
                                    <li><i class="fas fa-check"></i> Accompagnement pastoral personnalisé</li>
                                    <li><i class="fas fa-check"></i> Groupes de prière et de partage</li>
                                    <li><i class="fas fa-check"></i> Conseil spirituel</li>
                                    <li><i class="fas fa-check"></i> Ressources pour la croissance spirituelle</li>
                                </ul>
                                <a href="#" class="btn btn-secondary">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Impact -->
    <section class="impact-section">
        <div class="container">
            <div class="section-heading text-center" data-aos="fade-up">
                <h2>Notre impact</h2>
                <div class="heading-line center"></div>
                <p class="section-subtitle">Les chiffres qui témoignent de notre action depuis 2020</p>
            </div>
            
            <div class="impact-counters" data-aos="fade-up">
                <div class="impact-counter">
                    <div class="counter-value" data-count="1250">0</div>
                    <div class="counter-label">Familles soutenues</div>
                </div>
                <div class="impact-counter">
                    <div class="counter-value" data-count="350">0</div>
                    <div class="counter-label">Jeunes formés</div>
                </div>
                <div class="impact-counter">
                    <div class="counter-value" data-count="25">0</div>
                    <div class="counter-label">Projets communautaires</div>
                </div>
                <div class="impact-counter">
                    <div class="counter-value" data-count="50">0</div>
                    <div class="counter-label">Bénévoles actifs</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Témoignages -->
    <section id="temoignages" class="testimonials-section">
        <div class="container">
            <div class="section-heading text-center" data-aos="fade-up">
                <h2>Témoignages</h2>
                <div class="heading-line center"></div>
                <p class="section-subtitle">Ce que nos bénéficiaires et partenaires disent de nous</p>
            </div>
            
            <div class="testimonials-slider" data-aos="fade-up">
                <div class="testimonials-wrapper">
                    <div class="testimonial active">
                        <div class="testimonial-content">
                            <div class="testimonial-quote">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <p>Grâce à la formation en entrepreneuriat de NÉHÉMIE, j'ai pu lancer ma petite entreprise et subvenir aux besoins de ma famille. C'est bien plus qu'une aide, c'est une transformation complète de ma vie.</p>
                            <div class="testimonial-author">
                                <img src="https://via.placeholder.com/80/CBD5E0/FFFFFF?Text=MK" alt="Marie Koumba">
                                <div class="author-info">
                                    <h4>Marie Koumba</h4>
                                    <p>Bénéficiaire du Programme TIMOTHÉE</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <div class="testimonial-quote">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <p>L'accompagnement spirituel reçu au travers du programme DANIEL m'a redonné espoir et force dans une période difficile. Je suis reconnaissant pour leur écoute et leurs prières.</p>
                            <div class="testimonial-author">
                                <img src="https://via.placeholder.com/80/A3BFFA/FFFFFF?Text=PN" alt="Paul Ntoutoume">
                                <div class="author-info">
                                    <h4>Paul Ntoutoume</h4>
                                    <p>Participant au Programme DANIEL</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <div class="testimonial-quote">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <p>En tant que partenaire, nous sommes impressionnés par le dévouement et la transparence de NÉHÉMIE International. Leur impact sur la communauté est tangible.</p>
                            <div class="testimonial-author">
                                <img src="https://via.placeholder.com/80/FBBF24/FFFFFF?Text=DI" alt="M. Diallo Ibrahim">
                                <div class="author-info">
                                    <h4>M. Diallo Ibrahim</h4>
                                    <p>Directeur, Fondation Espoir</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonials-controls">
                    <button class="testimonial-control prev" aria-label="Témoignage précédent"><i class="fas fa-chevron-left"></i></button>
                    <div class="testimonial-dots">
                        <button class="testimonial-dot active" aria-label="Témoignage 1"></button>
                        <button class="testimonial-dot" aria-label="Témoignage 2"></button>
                        <button class="testimonial-dot" aria-label="Témoignage 3"></button>
                    </div>
                    <button class="testimonial-control next" aria-label="Témoignage suivant"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Actualités -->
    <section id="actualites" class="news-section">
        <div class="container">
            <div class="section-heading text-center" data-aos="fade-up">
                <h2>Actualités & Événements</h2>
                <div class="heading-line center"></div>
                <p class="section-subtitle">Restez informé de nos dernières actions et des événements à venir</p>
            </div>
            
            <div class="news-grid" data-aos="fade-up">
                <div class="news-card">
                    <div class="news-image">
                        <img src="https://nehemie-international.com/wp-content/uploads/2024/05/Formation-Club-Anglais-Mai-2024-768x432.jpg" alt="Lancement du Club d'Anglais">
                        <div class="news-date">
                            <span class="day">15</span>
                            <span class="month">Mai</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <h3>Lancement du Club d'Anglais pour les jeunes</h3>
                        <p>NÉHÉMIE International a inauguré son club d'anglais visant à renforcer les compétences linguistiques des jeunes...</p>
                        <a href="#" class="news-link">Lire la suite <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="news-card">
                    <div class="news-image">
                        <img src="https://nehemie-international.com/wp-content/uploads/2024/04/Distribution-DORCAS-Avril-2024-768x432.jpg" alt="Distribution alimentaire">
                        <div class="news-date">
                            <span class="day">22</span>
                            <span class="month">Avr</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <h3>Grande distribution alimentaire à Akébé</h3>
                        <p>Dans le cadre du programme DORCAS, une importante distribution de vivres a eu lieu dans le quartier Akébé...</p>
                        <a href="#" class="news-link">Lire la suite <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="news-card">
                    <div class="news-image">
                        <img src="https://nehemie-international.com/wp-content/uploads/2024/02/Visite-terrain-Nzeng-Ayong-768x432.jpeg" alt="Visite de terrain">
                        <div class="news-date">
                            <span class="day">10</span>
                            <span class="month">Fév</span>
                        </div>
                    </div>
                    <div class="news-content">
                        <h3>Visite de terrain et évaluation des besoins</h3>
                        <p>L'équipe de NÉHÉMIE s'est rendue à Nzeng-Ayong pour une évaluation des besoins des familles vulnérables...</p>
                        <a href="#" class="news-link">Lire la suite <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="news-events-wrapper" data-aos="fade-up">
                <div class="upcoming-events">
                    <h3>Prochains Événements</h3>
                    
                    <div class="event-item">
                        <div class="event-date">
                            <span class="event-day">10</span>
                            <span class="event-month">JUIN</span>
                        </div>
                        <div class="event-details">
                            <h4>Lancement campagne "Donnez-leur..."</h4>
                            <p><i class="fas fa-map-marker-alt"></i> Centre communautaire, Libreville</p>
                            <p><i class="fas fa-clock"></i> 18h00</p>
                        </div>
                    </div>
                    
                    <div class="event-item">
                        <div class="event-date">
                            <span class="event-day">15</span>
                            <span class="event-month">JUIN</span>
                        </div>
                        <div class="event-details">
                            <h4>Atelier Formation Club d'Anglais</h4>
                            <p><i class="fas fa-map-marker-alt"></i> Local NÉHÉMIE</p>
                            <p><i class="fas fa-clock"></i> 14h00 - 16h00</p>
                        </div>
                    </div>
                </div>
                
                <div class="newsletter-signup">
                    <h3>Rejoignez notre newsletter</h3>
                    <p>Recevez nos actualités et restez informé de nos actions sur le terrain.</p>
                    
                    <form class="newsletter-form">
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" id="name" placeholder="Votre nom">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" placeholder="Votre email">
                        </div>
                        <button type="submit" class="btn btn-primary">S'abonner</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Faire un Don -->
    <section id="faire-un-don" class="donation-section">
        <div class="container">
            <div class="section-heading text-center" data-aos="fade-up">
                <h2>S'impliquer & Faire un Don</h2>
                <div class="heading-line center"></div>
                <p class="section-subtitle">Votre soutien est précieux pour continuer notre mission</p>
            </div>
            
            <div class="donation-wrapper" data-aos="fade-up">
                <div class="donation-options">
                    <h3>Faire un Don</h3>
                    <p>Chaque contribution, petite ou grande, fait une différence significative dans la vie des personnes que nous servons. Voici comment vous pouvez donner :</p>
                    
                    <div class="donation-method">
                        <div class="method-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="method-details">
                            <h4>Virement Bancaire</h4>
                            <p><strong>Banque :</strong> BGFI Bank Gabon<br>
                            <strong>Titulaire :</strong> ONG NEHEMIE INTERNATIONAL<br>
                            <strong>N° Compte :</strong> XXXXXXXXXXXX<br>
                            <strong>Code SWIFT/BIC :</strong> BGFXXXXX</p>
                        </div>
                    </div>
                    
                    <div class="donation-method">
                        <div class="method-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="method-details">
                            <h4>Mobile Money</h4>
                            <p><strong>Airtel Money :</strong> +241 XX XX XX XX<br>
                            <strong>Moov Money :</strong> +241 XX XX XX XX</p>
                        </div>
                    </div>
                    
                    <div class="donation-method">
                        <div class="method-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="method-details">
                            <h4>Carte Bancaire</h4>
                            <p>Faites un don sécurisé en ligne via notre plateforme de paiement.</p>
                            <a href="#" class="btn btn-primary">Donner maintenant</a>
                        </div>
                    </div>
                </div>
                
                <div class="volunteer-options">
                    <h3>Devenir Bénévole</h3>
                    <p>Vous souhaitez vous engager à nos côtés ? Nous avons besoin de bénévoles dans différents domaines :</p>
                    
                    <ul class="volunteer-list">
                        <li><i class="fas fa-check-circle"></i> Distribution alimentaire</li>
                        <li><i class="fas fa-check-circle"></i> Animation d'ateliers</li>
                        <li><i class="fas fa-check-circle"></i> Soutien administratif</li>
                        <li><i class="fas fa-check-circle"></i> Communication</li>
                        <li><i class="fas fa-check-circle"></i> Logistique</li>
                    </ul>
                    
                    <a href="#contact" class="btn btn-secondary">Nous rejoindre</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-heading text-center" data-aos="fade-up">
                <h2>Contactez-nous</h2>
                <div class="heading-line center"></div>
                <p class="section-subtitle">Nous sommes à votre écoute pour toute question ou proposition</p>
            </div>
            
            <div class="contact-wrapper" data-aos="fade-up">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Adresse</h4>
                            <p>Quartier Nzeng-Ayong<br>Libreville, Gabon</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Téléphone</h4>
                            <p>+241 XX XX XX XX</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Email</h4>
                            <p>contact@nehemie-international.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-social">
                        <h4>Suivez-nous</h4>
                        <div class="social-links">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <form id="contactForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contactName">Nom</label>
                                <input type="text" id="contactName" placeholder="Votre nom" required>
                            </div>
                            <div class="form-group">
                                <label for="contactEmail">Email</label>
                                <input type="email" id="contactEmail" placeholder="Votre email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contactSubject">Sujet</label>
                            <input type="text" id="contactSubject" placeholder="Sujet de votre message" required>
                        </div>
                        <div class="form-group">
                            <label for="contactMessage">Message</label>
                            <textarea id="contactMessage" placeholder="Votre message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Carte -->
    <section class="map-section">
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127504.42550263697!2d9.395304899999999!3d0.3927889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x107f3b8a5e8c7f2d%3A0xb9a8e4b25b9fee0c!2sLibreville%2C%20Gabon!5e0!3m2!1sfr!2sfr!4v1621345678901!5m2!1sfr!2sfr" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <!-- Pied de page -->
    <footer class="footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-logo">
                    <img src="https://nehemie-international.com/wp-content/uploads/2024/01/LOGO-NEHEMIE-carre-e1704837809114.png" alt="Logo NÉHÉMIE International">
                    <h3>NÉHÉMIE International</h3>
                    <p>"Levons-nous et bâtissons!"</p>
                </div>
                
                <div class="footer-links">
                    <div class="footer-links-column">
                        <h4>Navigation</h4>
                        <ul>
                            <li><a href="#accueil">Accueil</a></li>
                            <li><a href="#qui-sommes-nous">Qui sommes-nous</a></li>
                            <li><a href="#nos-programmes">Nos programmes</a></li>
                            <li><a href="#actualites">Actualités</a></li>
                            <li><a href="#temoignages">Témoignages</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links-column">
                        <h4>Nos programmes</h4>
                        <ul>
                            <li><a href="#programme-timothee">TIMOTHÉE</a></li>
                            <li><a href="#programme-philippe">PHILIPPE</a></li>
                            <li><a href="#programme-dorcas">DORCAS</a></li>
                            <li><a href="#programme-bethanie">BÉTHANIE</a></li>
                            <li><a href="#programme-daniel">DANIEL</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links-column">
                        <h4>Légal</h4>
                        <ul>
                            <li><a href="#">Mentions légales</a></li>
                            <li><a href="#">Politique de confidentialité</a></li>
                            <li><a href="#">Conditions d'utilisation</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 NÉHÉMIE International. Tous droits réservés.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bouton retour en haut -->
    <button id="backToTop" aria-label="Retour en haut">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>
</html>
