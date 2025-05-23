<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donnez-leur vous-mêmes à manger - NÉHÉMIE International</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #F5F0E8;
            color: #333333;
        }
        .serif {
            font-family: 'Lora', serif;
        }
        .primary-color {
            color: #5D4037;
        }
        .secondary-color {
            color: #5DAEDF;
        }
        .accent-color {
            color: #C78A44;
        }
        .primary-bg {
            background-color: #5D4037;
        }
        .secondary-bg {
            background-color: #5DAEDF;
        }
        .accent-bg {
            background-color: #C78A44;
        }
        .light-bg {
            background-color: #F5F0E8;
        }
        .quote-box {
            border-left: 4px solid #C78A44;
            padding-left: 1rem;
            font-style: italic;
        }
        .divider {
            height: 3px;
            background: linear-gradient(to right, #5D4037, #5DAEDF, #C78A44);
        }
        .hero-pattern {
            background-color: #5D4037;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .btn-primary {
            background-color: #5D4037;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #4A332C;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background-color: #5DAEDF;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #4A9BCC;
            transform: translateY(-2px);
        }
        .btn-accent {
            background-color: #C78A44;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-accent:hover {
            background-color: #B47935;
            transform: translateY(-2px);
        }
        .section-title {
            position: relative;
            display: inline-block;
        }
        .section-title:after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60%;
            height: 3px;
            background: #C78A44;
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        @media print {
            .container {
                max-width: none;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="primary-bg text-white py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold">NÉHÉMIE INTERNATIONAL</h1>
            </div>
            <p class="italic">"Levons-nous et bâtissons !"</p>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-pattern text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 serif">Donnez-leur vous-mêmes à manger</h1>
            <p class="text-xl md:text-2xl mb-8">Campagne de levée de fonds - Juin 2025</p>
            <div class="divider w-1/4 mx-auto mb-8"></div>
            <p class="text-lg md:text-xl max-w-3xl mx-auto">
                Collectez en juin, distribuez en juillet. Une invitation à partager non pas votre superflu, mais une part de votre nécessaire.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-10">
        <!-- Intro Section -->
        <section class="mb-16">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold primary-color section-title mb-8">MOINS POUR MOI, LA VIE POUR EUX</h2>
                <p class="text-lg mb-6">
                    Imaginez un père qui, chaque mois, remet à son fils une somme pour ses besoins personnels. 
                    Un jour, il lui dit : "À partir d'aujourd'hui, tu recevras un peu moins, car j'ai décidé que 
                    nous aiderions ensemble une famille qui n'a pas de quoi manger. Ce petit sacrifice te permettra 
                    de comprendre que notre abondance, même relative, peut devenir la survie d'un autre."
                </p>
                <p class="text-lg mb-6">
                    Ce geste simple enseigne une vérité profonde : <strong class="accent-color">vivre avec un peu moins pour que d'autres puissent simplement vivre.</strong>
                </p>
            </div>
        </section>

        <!-- Biblical Call Section -->
        <section class="mb-16 bg-white py-10 px-8 rounded-lg shadow-lg">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold primary-color section-title mb-8">UN APPEL QUI RÉSONNE DEPUIS 2000 ANS</h2>
                <div class="quote-box mb-6">
                    <p class="text-xl serif mb-2">"Donnez-leur vous-mêmes à manger."</p>
                </div>
                <p class="text-lg mb-6">
                    Ces paroles prononcées il y a deux millénaires face à une foule affamée résonnent encore aujourd'hui. 
                    Face à l'immensité des besoins, il serait facile de nous sentir impuissants, comme les disciples 
                    qui ne voyaient que cinq pains et deux poissons pour des milliers de personnes.
                </p>
                <p class="text-lg mb-6">
                    Mais l'acte de foi consiste précisément à offrir ce que nous avons entre nos mains, aussi modeste 
                    soit-il, pour qu'il se multiplie et nourrisse bien au-delà de ce que nous pouvions imaginer.
                </p>
            </div>
        </section>

        <!-- Campaign Timing -->
        <section class="mb-16">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold primary-color section-title mb-8">JUIN POUR DONNER, JUILLET POUR NOURRIR</h2>
                <p class="text-lg mb-6">
                    Durant tout le mois de juin, NÉHÉMIE International vous invite à faire partie de ce miracle quotidien. 
                    Chaque don collecté en juin permettra d'organiser en juillet une grande distribution alimentaire 
                    pour les familles les plus vulnérables de notre région.
                </p>
                <p class="text-lg mb-6">
                    Il ne s'agit pas de donner votre superflu. Il s'agit de partager consciemment une part de ce qui vous 
                    fait vivre, de faire l'expérience transformatrice du don véritable – celui qui coûte quelque chose, 
                    mais qui remplit le cœur d'une joie incomparable.
                </p>
            </div>
        </section>

        <!-- Giving Section -->
        <section class="mb-16 bg-white py-10 px-8 rounded-lg shadow-lg">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold primary-color section-title mb-8">DONNER, C'EST RECEVOIR DEUX FOIS</h2>
                <div class="quote-box mb-6">
                    <p class="text-xl serif mb-2">"Il y a plus de bonheur à donner qu'à recevoir."</p>
                </div>
                <p class="text-lg mb-6">
                    Cette sagesse ancienne cache un paradoxe merveilleux : en donnant, nous recevons bien plus encore. 
                    Pas nécessairement en biens matériels, mais en richesse intérieure, en sens de la vie, en gratitude profonde.
                </p>
                <p class="text-lg mb-6">
                    Chaque acte de générosité nous transforme et nous rapproche de notre véritable nature. 
                    En partageant notre pain, nous nourrissons aussi notre âme.
                </p>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="mb-16">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold primary-color section-title mb-10">COMMENT PARTICIPER ?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="card bg-white p-6 rounded-lg shadow-md">
                        <div class="text-4xl primary-color mb-4">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 primary-color">Donnez</h3>
                        <p>Faites un don, petit ou grand. Ce n'est pas le montant qui compte, mais l'intention du cœur.</p>
                    </div>
                    <div class="card bg-white p-6 rounded-lg shadow-md">
                        <div class="text-4xl secondary-color mb-4">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 secondary-color">Partagez</h3>
                        <p>Faites connaître cette campagne autour de vous, dans vos familles, vos églises, vos lieux de travail.</p>
                    </div>
                    <div class="card bg-white p-6 rounded-lg shadow-md">
                        <div class="text-4xl accent-color mb-4">
                            <i class="fas fa-pray"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 accent-color">Priez</h3>
                        <p>Soutenez cette initiative par vos prières, pour que chaque ressource soit multipliée et atteigne ceux qui en ont le plus besoin.</p>
                    </div>
                    <div class="card bg-white p-6 rounded-lg shadow-md">
                        <div class="text-4xl primary-color mb-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 primary-color">Rejoignez-nous</h3>
                        <p>Devenez bénévole pour la distribution de juillet et voyez par vous-même le fruit de la générosité collective.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Impact Section -->
        <section class="mb-16">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold primary-color section-title mb-8">VOTRE DON EST UNE SEMENCE D'ESPOIR</h2>
                <p class="text-lg mb-6">
                    Quand vous donnez à une personne dans le besoin, vous ne lui offrez pas seulement de la nourriture – 
                    vous lui offrez la preuve qu'elle compte, qu'elle est digne d'attention, qu'elle n'est pas seule dans sa lutte quotidienne.
                </p>
                <p class="text-lg mb-6">
                    En ce mois de juin, osez le geste qui transforme : celui de partager votre pain pour que d'autres puissent vivre. 
                    Votre générosité sera, pour beaucoup, le signe tangible que l'amour existe encore dans notre monde.
                </p>
            </div>
        </section>

        <!-- Conclusion Section -->
        <section class="mb-16 text-center">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold primary-color mb-8">ENSEMBLE, NOUS POUVONS NOURRIR DES CORPS ET DES ÂMES</h2>
                <div class="quote-box mx-auto text-center mb-8 max-w-2xl">
                    <p class="text-xl serif">"Que ton pain soit partagé avec celui qui a faim, et tes vêtements avec celui qui est nu... Alors ta lumière jaillira comme l'aurore."</p>
                </div>
                <div class="divider w-1/4 mx-auto mb-8"></div>
                <div class="mt-10">
                    <a href="#" class="btn-primary text-lg font-bold py-3 px-8 rounded-full inline-block mb-4 md:mb-0 md:mr-4">Faire un don maintenant</a>
                    <a href="#" class="btn-accent text-lg font-bold py-3 px-8 rounded-full inline-block">Devenir bénévole</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Action Banner -->
    <section class="secondary-bg py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <p class="text-xl mb-4">Tous les dons collectés en juin serviront à l'organisation d'une distribution alimentaire en juillet 2025.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="primary-bg text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-6 md:mb-0">
                    <h3 class="text-xl font-bold mb-4">NÉHÉMIE INTERNATIONAL</h3>
                    <p class="italic">"Levons-nous et bâtissons !"</p>
                </div>
                <div class="mb-6 md:mb-0">
                    <h4 class="font-bold mb-3">Contact</h4>
                    <p><i class="fas fa-envelope mr-2"></i> contact@nehemie-international.org</p>
                    <p><i class="fas fa-phone mr-2"></i> +XXX XXX XXX XXX</p>
                    <p><i class="fas fa-map-marker-alt mr-2"></i> Libreville, Gabon</p>
                </div>
                <div>
                    <h4 class="font-bold mb-3">Suivez-nous</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-accent-color text-2xl"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white hover:text-accent-color text-2xl"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white hover:text-accent-color text-2xl"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="divider w-full my-6"></div>
            <div class="text-center">
                <p>&copy; 2025 NÉHÉMIE International. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>