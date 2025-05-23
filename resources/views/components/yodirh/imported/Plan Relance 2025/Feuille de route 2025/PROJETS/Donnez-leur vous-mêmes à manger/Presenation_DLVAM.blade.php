<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donnez-leur vous-mêmes à manger - Projet 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css">
    <style>
        :root {
            --color-primary: #5D4037;
            --color-secondary: #5DAEDF;
            --color-accent: #C78A44;
            --color-light: #F5F0E8;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-light);
            color: var(--color-primary);
            line-height: 1.6;
        }
        
        .bg-primary {
            background-color: var(--color-primary);
        }
        
        .bg-secondary {
            background-color: var(--color-secondary);
        }
        
        .bg-accent {
            background-color: var(--color-accent);
        }
        
        .bg-light {
            background-color: var(--color-light);
        }
        
        .text-primary {
            color: var(--color-primary);
        }
        
        .text-secondary {
            color: var(--color-secondary);
        }
        
        .text-accent {
            color: var(--color-accent);
        }
        
        .border-primary {
            border-color: var(--color-primary);
        }
        
        .border-secondary {
            border-color: var(--color-secondary);
        }
        
        .border-accent {
            border-color: var(--color-accent);
        }
        
        .section {
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
        }
        
        .quote {
            font-style: italic;
            border-left: 4px solid var(--color-accent);
            padding-left: 1rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--color-primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #4D3429;
        }
        
        .btn-accent {
            background-color: var(--color-accent);
            color: white;
        }
        
        .btn-accent:hover {
            background-color: #B67A34;
        }
        
        .step-item {
            position: relative;
            padding-left: 2.5rem;
            margin-bottom: 1.5rem;
        }
        
        .step-number {
            position: absolute;
            left: 0;
            top: 0;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: var(--color-accent);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
        
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 2rem;
            border-left: 2px solid var(--color-accent);
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 0.25rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background-color: var(--color-accent);
        }
        
        .timeline-item:last-child {
            border-left: 2px solid transparent;
        }
        
        @media print {
            .page-break {
                display: none;
            }
            
            body {
                background-color: white;
            }
            
            .section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <header class="bg-primary text-white py-8 px-4 text-center">
        <h1 class="text-4xl font-bold mb-2">"Donnez-leur vous-mêmes à manger"</h1>
        <h2 class="text-2xl">Campagne de levée de fonds - Juin 2025</h2>
        <div class="mt-4">
            <p class="text-lg">NÉHÉMIE International</p>
            <p class="italic">"Levons-nous et bâtissons!"</p>
        </div>
    </header>
    
    <main class="container mx-auto px-4 py-8">
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Introduction</h2>
            <p class="mb-4">
                Chers membres de l'équipe de pilotage, cette présentation vous expose notre projet phare pour 2025 : 
                la campagne "Donnez-leur vous-mêmes à manger". Cette initiative s'inscrit pleinement dans notre 
                mission d'évangélisation par les actes et représente une opportunité unique de manifester concrètement 
                notre engagement auprès des plus vulnérables.
            </p>
            <p class="mb-4">
                Notre approche vise à collecter des fonds en juin pour organiser une grande distribution alimentaire en juillet, 
                selon notre principe fondateur : <strong class="text-primary">collectez en juin, distribuez en juillet</strong>.
            </p>
            <div class="quote p-4 bg-light rounded mb-6">
                <p class="text-lg">"Une invitation à partager non pas votre superflu, mais une part de votre nécessaire."</p>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Contexte et Inspiration Biblique</h2>
            <div class="flex flex-col md:flex-row">
                <div class="md:w-2/3 pr-0 md:pr-6">
                    <h3 class="text-2xl font-semibold mb-3 text-primary">"Donnez-leur vous-mêmes à manger"</h3>
                    <p class="mb-4">
                        Ces paroles de Jésus-Christ, prononcées face à une foule affamée il y a deux millénaires, 
                        résonnent encore avec force aujourd'hui. Dans l'Évangile selon Marc (6:37), lorsque les disciples 
                        suggèrent de renvoyer la foule pour qu'elle trouve elle-même de quoi se nourrir, Jésus leur lance 
                        ce défi extraordinaire : <em>"Donnez-leur vous-mêmes à manger"</em>.
                    </p>
                    <p class="mb-4">
                        Face à l'immensité des besoins, il serait facile pour nous aussi de nous sentir 
                        impuissants, comme les disciples qui ne voyaient que cinq pains et deux poissons 
                        pour des milliers de personnes.
                    </p>
                    <p class="mb-4">
                        Mais l'acte de foi consiste précisément à offrir ce que nous avons entre nos mains, 
                        aussi modeste soit-il, pour qu'il se multiplie et nourrisse bien au-delà de ce que 
                        nous pouvions imaginer.
                    </p>
                </div>
                <div class="md:w-1/3 mt-4 md:mt-0 bg-light rounded-lg p-4">
                    <div class="quote py-4 px-2">
                        <p class="text-lg text-primary font-bold mb-2">Un appel qui résonne depuis 2000 ans</p>
                        <p>
                            "Jésus leur répondit : Donnez-leur vous-mêmes à manger. Mais ils lui dirent : 
                            Nous n'avons que cinq pains et deux poissons, à moins que nous n'allions 
                            nous-mêmes acheter des vivres pour tout ce peuple."
                        </p>
                        <p class="text-right mt-2">- Marc 6:37</p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Vision : Moins pour moi, la vie pour eux</h2>
            <div class="p-6 border-l-4 border-accent bg-light rounded mb-6">
                <p class="mb-4">
                    Imaginez un père qui, chaque mois, remet à son fils une somme pour ses besoins personnels. Un jour, il lui dit : 
                    "À partir d'aujourd'hui, tu recevras un peu moins, car j'ai décidé que nous aiderions ensemble une famille 
                    qui n'a pas de quoi manger. Ce petit sacrifice te permettra de comprendre que notre abondance, 
                    même relative, peut devenir la survie d'un autre."
                </p>
                <p class="text-lg font-bold text-primary">
                    Ce geste simple enseigne une vérité profonde : vivre avec un peu moins pour que d'autres puissent simplement vivre.
                </p>
            </div>
            <div class="grid md:grid-cols-2 gap-8 mt-8">
                <div class="card bg-light">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-primary">Vision Spirituelle</h3>
                        <p>
                            Notre initiative ne vise pas seulement à nourrir des corps, mais aussi des âmes. 
                            En suivant l'exemple du Christ qui multipliait les pains tout en partageant sa Parole, 
                            nous cherchons à être des canaux de la grâce divine, manifestant l'amour de Dieu 
                            de façon tangible et transformatrice.
                        </p>
                    </div>
                </div>
                <div class="card bg-light">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-primary">Vision Sociale</h3>
                        <p>
                            Notre action répond à un besoin concret et urgent dans notre communauté. 
                            Au-delà de l'aide immédiate, nous visons à restaurer la dignité des bénéficiaires, 
                            à créer des liens de solidarité durables et à sensibiliser la société à la 
                            responsabilité collective face aux inégalités.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Objectifs du Projet</h2>
            <div class="mb-6">
                <h3 class="text-2xl font-semibold mb-3 text-primary">Objectifs Généraux</h3>
                <ul class="list-disc pl-6 mb-4">
                    <li class="mb-2">Mobiliser notre communauté autour d'un acte concret de générosité sacrificielle</li>
                    <li class="mb-2">Apporter une aide alimentaire substantielle aux familles les plus vulnérables</li>
                    <li class="mb-2">Témoigner concrètement de l'amour du Christ à travers nos actions</li>
                    <li class="mb-2">Renforcer notre témoignage d'évangélisation par les actes</li>
                </ul>
            </div>
            <div>
                <h3 class="text-2xl font-semibold mb-3 text-primary">Objectifs Spécifiques</h3>
                <ul class="list-disc pl-6">
                    <li class="mb-2">Collecter suffisamment de fonds en juin pour soutenir au moins 100 familles</li>
                    <li class="mb-2">Organiser une distribution alimentaire complète en juillet 2025</li>
                    <li class="mb-2">Mobiliser au moins 30 bénévoles pour la préparation et la distribution</li>
                    <li class="mb-2">Créer des moments d'échange spirituel significatifs lors de la distribution</li>
                    <li class="mb-2">Établir un suivi des bénéficiaires pour un accompagnement à plus long terme</li>
                </ul>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Valeurs Fondamentales</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="card bg-light">
                    <div class="p-5">
                        <h3 class="text-xl font-bold mb-3 text-primary flex items-center">
                            <span class="bg-accent text-white w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-heart"></i>
                            </span>
                            Foi
                        </h3>
                        <p>
                            Croire que nos modestes moyens, lorsqu'ils sont confiés à Dieu, peuvent produire 
                            un impact bien au-delà de nos attentes, comme les cinq pains et deux poissons qui ont 
                            nourri une multitude.
                        </p>
                    </div>
                </div>
                <div class="card bg-light">
                    <div class="p-5">
                        <h3 class="text-xl font-bold mb-3 text-primary flex items-center">
                            <span class="bg-accent text-white w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-hands-helping"></i>
                            </span>
                            Compassion
                        </h3>
                        <p>
                            Être touché par la souffrance d'autrui au point d'agir concrètement, 
                            comme Jésus qui, "ému de compassion pour cette foule", a cherché à répondre 
                            à leurs besoins les plus pressants.
                        </p>
                    </div>
                </div>
                <div class="card bg-light">
                    <div class="p-5">
                        <h3 class="text-xl font-bold mb-3 text-primary flex items-center">
                            <span class="bg-accent text-white w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-hand-holding-heart"></i>
                            </span>
                            Partage
                        </h3>
                        <p>
                            Cultiver un esprit de générosité qui dépasse le simple don de notre superflu 
                            pour embrasser le partage authentique, celui qui implique un coût personnel 
                            et une part de notre nécessaire.
                        </p>
                    </div>
                </div>
                <div class="card bg-light">
                    <div class="p-5">
                        <h3 class="text-xl font-bold mb-3 text-primary flex items-center">
                            <span class="bg-accent text-white w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-dove"></i>
                            </span>
                            Sacrifice Volontaire
                        </h3>
                        <p>
                            Embrasser librement la discipline du renoncement à certains conforts personnels 
                            pour contribuer au bien-être d'autrui, à l'image du Christ qui s'est donné lui-même.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Principes Clés du Projet</h2>
            <div class="mb-6">
                <h3 class="text-2xl font-semibold mb-3 text-primary">Don d'une part de sa subsistance</h3>
                <p class="mb-4">
                    Notre campagne ne sollicite pas le superflu, mais invite les donateurs à partager une part 
                    de ce qui leur est nécessaire. C'est dans ce sacrifice volontaire que réside la puissance 
                    transformatrice du don, tant pour celui qui donne que pour celui qui reçoit.
                </p>
                <div class="quote p-4 bg-light rounded mb-2">
                    <p>
                        "Il y a plus de bonheur à donner qu'à recevoir."
                    </p>
                    <p class="text-right">- Actes 20:35</p>
                </div>
            </div>
            <div class="mb-6">
                <h3 class="text-2xl font-semibold mb-3 text-primary">Bénédiction du donateur</h3>
                <p>
                    Cette sagesse ancienne cache un paradoxe merveilleux : en donnant, nous recevons bien plus encore. 
                    Pas nécessairement en biens matériels, mais en richesse intérieure, en sens de la vie, 
                    en gratitude profonde. Chaque acte de générosité nous transforme et nous rapproche 
                    de notre véritable nature. En partageant notre pain, nous nourrissons aussi notre âme.
                </p>
            </div>
            <div>
                <h3 class="text-2xl font-semibold mb-3 text-primary">Solidarité concrète</h3>
                <p>
                    Quand vous donnez à une personne dans le besoin, vous ne lui offrez pas seulement de la nourriture – 
                    vous lui offrez la preuve qu'elle compte, qu'elle est digne d'attention, qu'elle n'est pas seule 
                    dans sa lutte quotidienne. Cette reconnaissance de la dignité de l'autre est au cœur de notre mission.
                </p>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Juin pour donner, Juillet pour nourrir</h2>
            <p class="mb-6">
                Durant tout le mois de juin, NÉHÉMIE International invite notre communauté à faire partie de ce miracle quotidien. 
                Chaque don collecté permettra d'organiser en juillet une grande distribution alimentaire pour les familles 
                les plus vulnérables de notre région.
            </p>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="card">
                    <div class="bg-primary text-white p-4">
                        <h3 class="text-xl font-bold">JUIN : Mois de la Collecte</h3>
                    </div>
                    <div class="p-5 bg-light">
                        <div class="timeline-item">
                            <h4 class="font-bold mb-1">Semaine 1</h4>
                            <p>Lancement de la campagne et communication</p>
                        </div>
                        <div class="timeline-item">
                            <h4 class="font-bold mb-1">Semaine 2</h4>
                            <p>Intensification des appels aux dons</p>
                        </div>
                        <div class="timeline-item">
                            <h4 class="font-bold mb-1">Semaine 3</h4>
                            <p>Suivi des promesses de dons et relances</p>
                        </div>
                        <div class="timeline-item">
                            <h4 class="font-bold mb-1">Semaine 4</h4>
                            <p>Clôture de la collecte et bilan</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="bg-accent text-white p-4">
                        <h3 class="text-xl font-bold">JUILLET : Mois de la Distribution</h3>
                    </div>
                    <div class="p-5 bg-light">
                        <div class="timeline-item">
                            <h4 class="font-bold mb-1">Semaine 1</h4>
                            <p>Achat des denrées et préparation logistique</p>
                        </div>
                        <div class="timeline-item">
                            <h4 class="font-bold mb-1">Semaine 2</h4>
                            <p>Préparation des kits alimentaires</p>
                        </div>
                        <div class="timeline-item">
                            <h4 class="font-bold mb-1">Semaine 3</h4>
                            <p>Grande distribution publique</p>
                        </div>
                        <div class="timeline-item">
                            <h4 class="font-bold mb-1">Semaine 4</h4>
                            <p>Évaluation et rapport d'impact</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Enjeux pour l'ONG et les Bénéficiaires</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-2xl font-semibold mb-3 text-primary">Pour NÉHÉMIE International</h3>
                    <ul class="list-disc pl-6">
                        <li class="mb-2">Concrétiser notre mission d'évangélisation par les actes</li>
                        <li class="mb-2">Renforcer notre crédibilité auprès des communautés locales</li>
                        <li class="mb-2">Mobiliser notre réseau de soutien autour d'un projet fédérateur</li>
                        <li class="mb-2">Témoigner concrètement des valeurs chrétiennes que nous professons</li>
                        <li class="mb-2">Identifier de nouveaux besoins pour des interventions futures</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-2xl font-semibold mb-3 text-primary">Pour les Bénéficiaires</h3>
                    <ul class="list-disc pl-6">
                        <li class="mb-2">Recevoir une aide alimentaire substantielle</li>
                        <li class="mb-2">Expérimenter un accueil empreint de dignité et de respect</li>
                        <li class="mb-2">Être exposés au témoignage chrétien à travers des actes concrets</li>
                        <li class="mb-2">Sentir qu'ils comptent et sont valorisés par la communauté</li>
                        <li class="mb-2">Potentiellement s'intégrer dans d'autres programmes de l'ONG</li>
                    </ul>
                </div>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Rôle de l'Équipe de Pilotage</h2>
            <p class="mb-6">
                En tant qu'équipe de pilotage, votre leadership sera déterminant pour la réussite de ce projet. 
                Voici les responsabilités spécifiques qui vous incombent :
            </p>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h3 class="font-bold text-primary mb-2">Planification stratégique</h3>
                    <p>Définir les objectifs précis, établir le budget, identifier les ressources nécessaires et élaborer le calendrier détaillé.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h3 class="font-bold text-primary mb-2">Communication interne et externe</h3>
                    <p>Concevoir les messages clés, superviser la création des supports de communication et coordonner la diffusion.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h3 class="font-bold text-primary mb-2">Mobilisation des ressources</h3>
                    <p>Identifier et solliciter les donateurs potentiels, mobiliser les bénévoles et sécuriser les ressources matérielles.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">4</div>
                    <h3 class="font-bold text-primary mb-2">Coordination logistique</h3>
                    <p>Superviser l'achat des denrées, la préparation des kits et l'organisation de la distribution.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">5</div>
                    <h3 class="font-bold text-primary mb-2">Accompagnement spirituel</h3>
                    <p>Veiller à l'intégration de la dimension spirituelle dans toutes les étapes, depuis la sensibilisation jusqu'à la distribution.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">6</div>
                    <h3 class="font-bold text-primary mb-2">Suivi et évaluation</h3>
                    <p>Mesurer les résultats, recueillir les témoignages et rédiger le rapport d'impact pour les parties prenantes.</p>
                </div>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Comment Participer</h2>
            <div class="grid md:grid-cols-2 gap-8 mb-6">
                <div class="card bg-light">
                    <div class="p-5">
                        <h3 class="text-xl font-bold mb-3 text-primary flex items-center">
                            <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-gift"></i>
                            </span>
                            Donnez
                        </h3>
                        <p>
                            Faites un don, petit ou grand. Ce n'est pas le montant qui compte, mais l'intention du cœur.
                            Encouragez votre entourage professionnel et personnel à contribuer.
                        </p>
                    </div>
                </div>
                <div class="card bg-light">
                    <div class="p-5">
                        <h3 class="text-xl font-bold mb-3 text-primary flex items-center">
                            <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-bullhorn"></i>
                            </span>
                            Partagez
                        </h3>
                        <p>
                            Faites connaître cette campagne autour de vous, dans vos familles,
                            vos églises, vos lieux de travail. Devenez ambassadeur du projet.
                        </p>
                    </div>
                </div>
                <div class="card bg-light">
                    <div class="p-5">
                        <h3 class="text-xl font-bold mb-3 text-primary flex items-center">
                            <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-pray"></i>
                            </span>
                            Priez
                        </h3>
                        <p>
                            Soutenez cette initiative par vos prières, pour que chaque ressource soit multipliée
                            et atteigne ceux qui en ont le plus besoin.
                        </p>
                    </div>
                </div>
                <div class="card bg-light">
                    <div class="p-5">
                        <h3 class="text-xl font-bold mb-3 text-primary flex items-center">
                            <span class="bg-primary text-white w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-hands-helping"></i>
                            </span>
                            Rejoignez-nous
                        </h3>
                        <p>
                            Devenez bénévole pour la distribution de juillet et voyez par vous-même
                            le fruit de la générosité collective. Engagez-vous dans la préparation.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="section bg-white mb-8">
            <h2 class="text-3xl font-bold mb-4 text-accent">Ensemble, nous pouvons nourrir des corps et des âmes</h2>
            <div class="p-6 border border-accent rounded-lg bg-light mb-6">
                <p class="text-lg italic">
                    "Que ton pain soit partagé avec celui qui a faim, et tes vêtements avec celui qui est nu... 
                    Alors ta lumière jaillira comme l'aurore."
                </p>
                <p class="text-right">- Ésaïe 58:7-8</p>
            </div>
            <p class="mb-6 text-lg">
                Tous les dons collectés en juin serviront à l'organisation d'une distribution alimentaire en juillet 2025.
                En tant qu'équipe de pilotage, votre engagement déterminera l'ampleur et l'impact de ce projet.
            </p>
            <div class="text-center">
                <p class="text-xl font-bold text-primary">Votre don est une semence d'espoir.</p>
                <p class="text-lg mb-8">
                    Quand nous donnons à une personne dans le besoin, nous ne lui offrons pas seulement de la nourriture –
                    nous lui offrons la preuve qu'elle compte, qu'elle est digne d'attention,
                    qu'elle n'est pas seule dans sa lutte quotidienne.
                </p>
                <a href="#" class="btn btn-accent inline-block">Rejoindre le projet</a>
            </div>
        </section>
    </main>
    
    <footer class="bg-primary text-white py-6 px-4 text-center">
        <p class="mb-2 text-xl font-bold">"Levons-nous et bâtissons !"</p>
        <p>NÉHÉMIE INTERNATIONAL</p>
        <p class="text-sm mt-4">© 2025 NÉHÉMIE International. Tous droits réservés.</p>
    </footer>
</body>
</html>
