<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les 5 Programmes Stratégiques de l'ONG pour influencer les 7 Montagnes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1f2937; /* gray-800 */
            line-height: 1.6;
            scroll-behavior: smooth;
        }
        .header-bg {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%); /* blue-700 to blue-600 */
        }
        .program-card {
            border-left-width: 5px; /* slightly thicker border */
            transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
        }
        .program-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.15);
        }
        .mountain-card {
            transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
        }
        .mountain-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 20px -5px rgba(0,0,0,0.1);
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 14px; /* increased padding */
            text-align: left;
            border: 1px solid #e5e7eb; /* gray-200 */
        }
        th {
            background-color: #374151; /* gray-700 */
            color: #ffffff;
            font-weight: 600;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb; /* gray-50 */
        }
        tbody tr:hover {
            background-color: #f3f4f6; /* gray-100 */
        }
        .salomon-color { border-color: #3b82f6; } /* blue-500 */
        .joseph-color { border-color: #10b981; } /* green-500 */
        .david-color { border-color: #f59e0b; } /* amber-500 */
        .daniel-color { border-color: #8b5cf6; } /* violet-500 */
        
        /* Burgundy Palette for Priscille & Aquila */
        .bg-burgundy-50 { background-color: #FAF0F3; } /* Very light pinkish-burgundy */
        .bg-burgundy-600 { background-color: #800020; } /* True Burgundy */

        .text-burgundy-100 { color: #F5DCE5; } /* Lighter text */
        .text-burgundy-200 { color: #E9B3C4; } /* Icon color */
        .text-burgundy-300 { color: #D38A9F; } /* Quote icon color */
        .text-burgundy-500 { color: #A03454; } /* Icon/Table icon color */
        .text-burgundy-600 { color: #800020; } /* H3 title in card */
        .text-burgundy-700 { color: #600018; } /* H3 title in content, H4 titles */
        .text-burgundy-800 { color: #400010; } /* Bold text, Quote text */

        .border-burgundy-200 { border-color: #E9B3C4; } /* Mountain card border */
        .border-burgundy-400 { border-color: #B95F79; } /* Quote border */
        .priscille-color { border-color: #A03454; } /* Program card left border (burgundy-500) */


        /* Custom scrollbar for a more modern feel (optional) */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #a0aec0; /* gray-500 */
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #718096; /* gray-600 */
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="header-bg text-white py-10 px-4 sm:px-6 lg:px-8 mb-10 shadow-lg">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold mb-3 tracking-tight">Rapport Stratégique</h1>
            <h2 class="text-2xl sm:text-3xl font-semibold text-blue-200">Les 5 Programmes de l'ONG pour influencer les 7 Montagnes</h2>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <section class="mb-16 bg-white rounded-xl shadow-xl p-6 sm:p-8">
            <h2 class="text-3xl font-bold mb-6 text-gray-800">Introduction aux 7 Montagnes d'Influence</h2>
            <p class="mb-6 text-lg text-gray-700">Le concept des "7 montagnes d'influence" identifie les sept domaines clés de la société que les chrétiens sont appelés à influencer pour transformer la culture selon des valeurs bibliques. Ces sept montagnes sont :</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="mountain-card bg-blue-50 p-5 rounded-lg border border-blue-200 shadow-sm">
                    <h3 class="font-bold text-xl text-blue-700 flex items-center"><i class="fas fa-pray fa-fw mr-3 text-blue-500"></i>Religion/Spiritualité</h3>
                    <p class="text-md text-gray-600 mt-1">Les systèmes de croyance et institutions religieuses.</p>
                </div>
                <div class="mountain-card bg-burgundy-50 p-5 rounded-lg border border-burgundy-200 shadow-sm">
                    <h3 class="font-bold text-xl text-burgundy-700 flex items-center"><i class="fas fa-home fa-fw mr-3 text-burgundy-500"></i>Famille</h3>
                    <p class="text-md text-gray-600 mt-1">Le fondement de la structure sociale.</p>
                </div>
                <div class="mountain-card bg-yellow-50 p-5 rounded-lg border border-yellow-200 shadow-sm">
                    <h3 class="font-bold text-xl text-yellow-700 flex items-center"><i class="fas fa-graduation-cap fa-fw mr-3 text-yellow-500"></i>Éducation</h3>
                    <p class="text-md text-gray-600 mt-1">Les systèmes d'apprentissage et de formation.</p>
                </div>
                <div class="mountain-card bg-purple-50 p-5 rounded-lg border border-purple-200 shadow-sm">
                    <h3 class="font-bold text-xl text-purple-700 flex items-center"><i class="fas fa-landmark fa-fw mr-3 text-purple-500"></i>Gouvernement/Politique</h3>
                    <p class="text-md text-gray-600 mt-1">Les systèmes de gouvernance et d'autorité.</p>
                </div>
                <div class="mountain-card bg-indigo-50 p-5 rounded-lg border border-indigo-200 shadow-sm">
                    <h3 class="font-bold text-xl text-indigo-700 flex items-center"><i class="fas fa-tv fa-fw mr-3 text-indigo-500"></i>Médias/Communications</h3>
                    <p class="text-md text-gray-600 mt-1">Les canaux d'information et d'influence.</p>
                </div>
                <div class="mountain-card bg-pink-50 p-5 rounded-lg border border-pink-200 shadow-sm">
                    <h3 class="font-bold text-xl text-pink-700 flex items-center"><i class="fas fa-music fa-fw mr-3 text-pink-500"></i>Arts/Divertissement</h3>
                    <p class="text-md text-gray-600 mt-1">Les expressions créatives et culturelles.</p>
                </div>
                <div class="mountain-card bg-green-50 p-5 rounded-lg border border-green-200 shadow-sm md:col-span-2 lg:col-span-1">
                    <h3 class="font-bold text-xl text-green-700 flex items-center"><i class="fas fa-chart-line fa-fw mr-3 text-green-500"></i>Économie/Affaires</h3>
                    <p class="text-md text-gray-600 mt-1">Les systèmes économiques et commerciaux.</p>
                </div>
            </div>

            <h3 class="text-2xl font-bold mb-3 text-gray-800">Notre Approche Stratégique</h3>
            <p class="mb-6 text-lg text-gray-700">Pour optimiser notre impact, nous avons développé une structure de 5 programmes stratégiques qui couvrent efficacement les 7 montagnes d'influence. Chaque programme est nommé d'après une figure biblique exemplaire qui incarne les valeurs et la mission du programme.</p>
            
            <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-500 mt-6 shadow-sm">
                <p class="text-md italic text-blue-800">
                    <i class="fas fa-quote-left text-blue-400 mr-2 fa-lg"></i>
                    Notre vision est de développer 5 programmes bibliquement fondés qui, ensemble, transforment les 7 sphères d'influence de la société, chaque programme étant dirigé par les principes incarnés par les figures bibliques correspondantes.
                    <i class="fas fa-quote-right text-blue-400 ml-2 fa-lg"></i>
                </p>
            </div>
        </section>

        <section class="mb-16">
            <h2 class="text-3xl font-bold mb-8 text-gray-800 text-center">Vue d'overview des 5 Programmes</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <div class="program-card salomon-color bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-2xl text-blue-600 mb-2">Programme SALOMON</h3>
                    <p class="text-gray-700 mb-1 flex items-center"><i class="fas fa-landmark fa-fw mr-2 text-blue-500"></i> Gouvernement/Politique</p>
                    <p class="text-gray-700 flex items-center"><i class="fas fa-graduation-cap fa-fw mr-2 text-blue-500"></i> Éducation</p>
                </div>
                
                <div class="program-card joseph-color bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-2xl text-green-600 mb-2">Programme JOSEPH</h3>
                    <p class="text-gray-700 mb-1 flex items-center"><i class="fas fa-chart-line fa-fw mr-2 text-green-500"></i> Économie/Affaires</p>
                    <p class="text-gray-500 flex items-center"><i class="fas fa-landmark fa-fw mr-2 text-gray-400"></i> Gouvernement (secondaire)</p>
                </div>
                
                <div class="program-card david-color bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-2xl text-yellow-600 mb-2">Programme DAVID</h3>
                    <p class="text-gray-700 mb-1 flex items-center"><i class="fas fa-music fa-fw mr-2 text-yellow-500"></i> Arts/Divertissement</p>
                    <p class="text-gray-700 flex items-center"><i class="fas fa-tv fa-fw mr-2 text-yellow-500"></i> Médias/Communications</p>
                </div>
                
                <div class="program-card daniel-color bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="font-bold text-2xl text-purple-600 mb-2">Programme DANIEL</h3>
                    <p class="text-gray-700 mb-1 flex items-center"><i class="fas fa-pray fa-fw mr-2 text-purple-500"></i> Religion/Spiritualité</p>
                    <p class="text-500 flex items-center"><i class="fas fa-landmark fa-fw mr-2 text-gray-400"></i> Gouvernement (secondaire)</p>
                </div>
                
                <div class="program-card priscille-color bg-white p-6 rounded-lg shadow-lg md:col-span-2 lg:col-span-1">
                    <h3 class="font-bold text-2xl text-burgundy-600 mb-2">Programme PRISCILLE & AQUILA</h3>
                    <p class="text-gray-700 mb-1 flex items-center"><i class="fas fa-home fa-fw mr-2 text-burgundy-500"></i> Famille</p>
                     <p class="text-gray-700 mb-1 flex items-center"><i class="fas fa-hands-helping fa-fw mr-2 text-burgundy-500"></i> Action Sociale Communautaire</p>
                    <p class="text-gray-500 flex items-center"><i class="fas fa-graduation-cap fa-fw mr-2 text-gray-400"></i> Éducation (secondaire)</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <h3 class="bg-gray-100 p-5 font-bold text-xl text-gray-700 border-b border-gray-200">Couverture des 7 Montagnes par les 5 Programmes</h3>
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th class="w-1/3">Montagne d'Influence</th>
                                <th class="w-1/3">Programme Principal</th>
                                <th class="w-1/3">Programme(s) Secondaire(s) et Connexions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="flex items-center"><i class="fas fa-pray text-blue-500 mr-3"></i>Religion/Spiritualité</td>
                                <td class="font-medium text-purple-700">DANIEL</td>
                                <td class="text-gray-600">DAVID (expression), PRISCILLE & AQUILA (vie communautaire)</td>
                            </tr>
                            <tr>
                                <td class="flex items-center"><i class="fas fa-home text-burgundy-500 mr-3"></i>Famille</td>
                                <td class="font-medium text-burgundy-700">PRISCILLE & AQUILA</td>
                                <td class="text-gray-600">DANIEL (valeurs éthiques/spirituelles), Éducation via SALOMON</td>
                            </tr>
                             <tr>
                                <td class="flex items-center"><i class="fas fa-hands-helping text-burgundy-500 mr-3"></i>Action Sociale Communautaire <span class="text-xs italic text-gray-500 ml-2">(Transversale)</span></td>
                                <td class="font-medium text-burgundy-700">PRISCILLE & AQUILA <span class="text-xs italic text-gray-500 ml-1">(Focus direct)</span></td>
                                <td class="text-gray-600">JOSEPH (pauvreté/emploi), DANIEL (compassion/valeurs), SALOMON (politiques sociales), DAVID (sensibilisation)</td>
                            </tr>
                            <tr>
                                <td class="flex items-center"><i class="fas fa-graduation-cap text-yellow-500 mr-3"></i>Éducation</td>
                                <td class="font-medium text-blue-700">SALOMON</td>
                                <td class="text-gray-600">PRISCILLE & AQUILA (éducation familiale/mentorat)</td>
                            </tr>
                            <tr>
                                <td class="flex items-center"><i class="fas fa-landmark text-purple-500 mr-3"></i>Gouvernement/Politique</td>
                                <td class="font-medium text-blue-700">SALOMON</td>
                                <td class="text-gray-600">DANIEL (éthique/conseil), JOSEPH (économie/politiques)</td>
                            </tr>
                            <tr>
                                <td class="flex items-center"><i class="fas fa-tv text-indigo-500 mr-3"></i>Médias/Communications</td>
                                <td class="font-medium text-yellow-700">DAVID</td>
                                <td class="text-gray-600">Tous (outil de diffusion)</td>
                            </tr>
                            <tr>
                                <td class="flex items-center"><i class="fas fa-music text-pink-500 mr-3"></i>Arts/Divertissement</td>
                                <td class="font-medium text-yellow-700">DAVID</td>
                                <td class="text-gray-600">Tous (expression culturelle)</td>
                            </tr>
                            <tr>
                                <td class="flex items-center"><i class="fas fa-chart-line text-green-500 mr-3"></i>Économie/Affaires</td>
                                <td class="font-medium text-green-700">JOSEPH</td>
                                <td class="text-gray-600">PRISCILLE & AQUILA (micro-entreprises/soutien à l'emploi), SALOMON (régulation)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Programme SALOMON -->
        <section id="programme-salomon" class="mb-16 bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="bg-blue-600 text-white p-8">
                <h2 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-crown fa-fw mr-4 text-yellow-300 text-4xl"></i>
                    Programme SALOMON
                </h2>
                <p class="text-blue-100 text-lg mt-1">Gouvernement/Politique + Éducation</p>
            </div>
            
            <div class="p-6 sm:p-8">
                <h3 class="text-2xl font-semibold mb-4 text-blue-700">Justification du Nom</h3>
                <div class="bg-blue-50 p-5 rounded-lg border-l-4 border-blue-400 mb-6 shadow-sm">
                    <p class="text-md text-blue-800">
                        <i class="fas fa-quote-left text-blue-300 mr-2 fa-lg"></i>
                        Salomon symbolise la sagesse divine appliquée au gouvernement et à l'éducation. Il représente l'alliance parfaite entre la gouvernance éclairée et la connaissance.
                        <i class="fas fa-quote-right text-blue-300 ml-2 fa-lg"></i>
                    </p>
                </div>
                
                <p class="mb-4 text-gray-700">
                    <strong>Fondement biblique :</strong> Salomon est reconnu comme le roi le plus sage de l'histoire d'Israël. Lors de son accession au trône, il demanda à Dieu de lui accorder "un cœur intelligent pour juger ton peuple, pour discerner le bien et le mal" (1 Rois 3:9).
                </p>
                
                <p class="mb-6 text-gray-700">
                    Cette sagesse divine lui permit de gouverner avec justice et discernement, faisant de son règne une période de paix et de prospérité sans précédent. Sa renommée était telle que des dirigeants comme la reine de Saba venaient de loin pour apprendre de lui. Avec ses 3000 proverbes et 1005 cantiques, il fut également un grand éducateur de son temps.
                </p>
                
                <h3 class="text-2xl font-semibold mb-4 text-blue-700">Essence du Programme</h3>
                <p class="mb-6 text-gray-700">
                    Former et équiper des leaders de gouvernance éthique et sage, influencer les systèmes politiques avec des principes bibliques, et développer des systèmes éducatifs qui intègrent sagesse et connaissance pour transformer la société.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-blue-700">Objectifs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Former des leaders politiques et gouvernementaux fondés sur des principes bibliques.</li>
                            <li>Développer et promouvoir des politiques publiques justes et sages.</li>
                            <li>Créer des modèles éducatifs qui intègrent connaissance académique et sagesse pratique.</li>
                            <li>Influencer les systèmes éducatifs pour qu'ils valorisent l'intégrité et l'excellence.</li>
                            <li>Établir des ponts entre les sphères politique et éducative.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-blue-700">KPIs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Nombre de leaders formés occupant des postes d'influence.</li>
                            <li>Politiques publiques élaborées ou influencées par le programme.</li>
                            <li>Nombre d'institutions éducatives adoptant les modèles développés.</li>
                            <li>Amélioration des indicateurs de gouvernance dans les communautés ciblées.</li>
                            <li>Évaluations d'impact sur les systèmes éducatifs.</li>
                        </ul>
                    </div>
                </div>
                
                <h4 class="text-xl font-semibold mb-4 text-blue-700">Projets Potentiels à Développer</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-blue-600 mb-1">Académie de Leadership Salomon</h5>
                        <p class="text-sm text-gray-600">Formation intensive pour futurs leaders politiques et éducatifs.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-blue-600 mb-1">Think Tank "Sagesse en Action"</h5>
                        <p class="text-sm text-gray-600">Élaboration de politiques publiques basées sur des principes bibliques.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-blue-600 mb-1">École Modèle d'Excellence</h5>
                        <p class="text-sm text-gray-600">Établissement pilote démontrant l'intégration sagesse-connaissance.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-blue-600 mb-1">Forum "Gouvernance & Éducation"</h5>
                        <p class="text-sm text-gray-600">Événements connectant décideurs politiques et éducateurs.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Programme JOSEPH -->
        <section id="programme-joseph" class="mb-16 bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="bg-green-600 text-white p-8">
                <h2 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-seedling fa-fw mr-4 text-yellow-300 text-4xl"></i>
                    Programme JOSEPH
                </h2>
                <p class="text-green-100 text-lg mt-1">Économie/Affaires</p>
            </div>
            
            <div class="p-6 sm:p-8">
                <h3 class="text-2xl font-semibold mb-4 text-green-700">Justification du Nom</h3>
                <div class="bg-green-50 p-5 rounded-lg border-l-4 border-green-400 mb-6 shadow-sm">
                    <p class="text-md text-green-800">
                        <i class="fas fa-quote-left text-green-300 mr-2 fa-lg"></i>
                        Joseph incarne l'excellence dans la gestion des ressources économiques, l'intégrité dans le monde des affaires et la vision prophétique qui permet non seulement de survivre aux crises, mais de prospérer à travers elles.
                        <i class="fas fa-quote-right text-green-300 ml-2 fa-lg"></i>
                    </p>
                </div>
                
                <p class="mb-4 text-gray-700">
                    <strong>Fondement biblique :</strong> Joseph, fils de Jacob, s'éleva d'esclave à premier ministre d'Égypte grâce à son intégrité, sa sagesse et ses compétences exceptionnelles en gestion. Il interpréta prophétiquement les rêves de Pharaon concernant sept années d'abondance suivies de sept années de famine.
                </p>
                
                <p class="mb-6 text-gray-700">
                    Sa gestion stratégique des ressources pendant les années d'abondance permit non seulement à l'Égypte de survivre à la famine, mais aussi de devenir la puissance économique dominant toute la région. Son modèle d'administration visionnaire illustre comment les principes divins peuvent transformer l'économie et comment la fidélité dans l'épreuve mène à l'élévation et à l'influence.
                </p>
                
                <h3 class="text-2xl font-semibold mb-4 text-green-700">Essence du Programme</h3>
                <p class="mb-6 text-gray-700">
                    Transformer la sphère économique selon des principes bibliques, former des entrepreneurs et dirigeants d'entreprise éthiques, développer des modèles d'affaires durables et équitables, et utiliser les ressources économiques pour avoir un impact social positif.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-green-700">Objectifs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Former une nouvelle génération d'entrepreneurs et dirigeants intègres.</li>
                            <li>Développer des modèles d'affaires qui allient profitabilité et impact social.</li>
                            <li>Promouvoir une gestion sage et prévoyante des ressources.</li>
                            <li>Établir des réseaux d'entreprises engagées dans l'économie du Royaume.</li>
                            <li>Accompagner les entreprises existantes dans leur transformation éthique.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-green-700">KPIs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Nombre d'entreprises créées/accompagnées.</li>
                            <li>Croissance du chiffre d'affaires des entreprises partenaires.</li>
                            <li>Indice de satisfaction des employés dans les entreprises partenaires.</li>
                            <li>Montant des investissements éthiques facilités.</li>
                            <li>Nombre d'emplois créés par les entreprises du programme.</li>
                        </ul>
                    </div>
                </div>
                
                <h4 class="text-xl font-semibold mb-4 text-green-700">Projets Potentiels à Développer</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-green-600 mb-1">Incubateur d'Entreprises Joseph</h5>
                        <p class="text-sm text-gray-600">Soutien et mentorat pour startups à impact.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-green-600 mb-1">Fonds d'Investissement Éthique</h5>
                        <p class="text-sm text-gray-600">Financement pour projets alignés sur les valeurs du Royaume.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-green-600 mb-1">Réseau d'Affaires "Prospérité Partagée"</h5>
                        <p class="text-sm text-gray-600">Plateforme de collaboration et de partage de bonnes pratiques.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-green-600 mb-1">Programme de Formation en Gestion Intègre</h5>
                        <p class="text-sm text-gray-600">Ateliers sur l'éthique, la RSE et la gouvernance d'entreprise.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Programme DAVID -->
        <section id="programme-david" class="mb-16 bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="bg-yellow-500 text-white p-8"> <!-- Changed to amber/yellow -->
                <h2 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-guitar fa-fw mr-4 text-yellow-200 text-4xl"></i> <!-- Updated icon and color -->
                    Programme DAVID
                </h2>
                <p class="text-yellow-100 text-lg mt-1">Arts/Divertissement + Médias/Communications</p>
            </div>
            <div class="p-6 sm:p-8">
                <!-- Contenu spécifique au Programme DAVID ici -->
                 <h3 class="text-2xl font-semibold mb-4 text-yellow-700">Justification du Nom</h3>
                 <div class="bg-yellow-50 p-5 rounded-lg border-l-4 border-yellow-400 mb-6 shadow-sm">
                    <p class="text-md text-yellow-800">
                        <i class="fas fa-quote-left text-yellow-300 mr-2 fa-lg"></i>
                        David, le roi-poète et musicien, symbolise la puissance de l'art et de la communication pour toucher les cœurs, exprimer la foi et influencer la culture.
                        <i class="fas fa-quote-right text-yellow-300 ml-2 fa-lg"></i>
                    </p>
                </div>
                <p class="mb-6 text-gray-700">
                    <strong>Fondement biblique :</strong> Le roi David était non seulement un leader politique et militaire, mais aussi un artiste accompli, auteur de nombreux Psaumes. Sa musique apaisait l'âme tourmentée du roi Saül, et ses écrits continuent d'inspirer des millions de personnes. Il a utilisé ses dons artistiques pour adorer Dieu, exprimer toute la gamme des émotions humaines et transmettre des vérités spirituelles.
                </p>
                 <h3 class="text-2xl font-semibold mb-4 text-yellow-700">Essence du Programme</h3>
                <p class="mb-6 text-gray-700">
                    Former et promouvoir des artistes et communicateurs chrétiens qui créent des œuvres d'excellence porteuses de sens, de beauté et de vérité. Influencer les sphères des arts, du divertissement et des médias avec des contenus qui élèvent l'esprit et reflètent les valeurs du Royaume.
                </p>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-yellow-700">Objectifs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Identifier, former et soutenir les talents chrétiens dans les arts et les médias.</li>
                            <li>Produire et diffuser des contenus culturels et médiatiques inspirants et pertinents.</li>
                            <li>Sensibiliser le public aux valeurs éthiques et spirituelles par l'art et la communication.</li>
                            <li>Créer des plateformes pour l'expression artistique chrétienne de qualité.</li>
                            <li>Influencer les tendances culturelles et médiatiques.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-yellow-700">KPIs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Nombre d'artistes/communicateurs formés ou soutenus.</li>
                            <li>Portée et engagement des contenus produits (vues, partages, etc.).</li>
                            <li>Nombre d'événements artistiques ou médiatiques organisés/impactés.</li>
                            <li>Mentions ou reconnaissances dans les médias grand public.</li>
                            <li>Sondages sur la perception de l'influence culturelle.</li>
                        </ul>
                    </div>
                </div>
                 <h4 class="text-xl font-semibold mb-4 text-yellow-700">Projets Potentiels à Développer</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-yellow-600 mb-1">Studio de Création Davidique</h5>
                        <p class="text-sm text-gray-600">Production de musique, films courts, podcasts, contenus numériques.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-yellow-600 mb-1">Plateforme Média "Echo du Royaume"</h5>
                        <p class="text-sm text-gray-600">Diffusion de contenus, blog, magazine en ligne, réseau social.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-yellow-600 mb-1">Ateliers "Art et Sens"</h5>
                        <p class="text-sm text-gray-600">Formation et coaching pour artistes chrétiens.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-yellow-600 mb-1">Festival Culturel "Harmonie Divine"</h5>
                        <p class="text-sm text-gray-600">Événements mettant en valeur l'art et la musique inspirés.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Programme DANIEL -->
        <section id="programme-daniel" class="mb-16 bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="bg-purple-600 text-white p-8"> <!-- Changed to violet/purple -->
                <h2 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-scroll fa-fw mr-4 text-purple-200 text-4xl"></i> <!-- Updated icon and color -->
                    Programme DANIEL
                </h2>
                <p class="text-purple-100 text-lg mt-1">Religion/Spiritualité + Gouvernement (secondaire)</p>
            </div>
             <div class="p-6 sm:p-8">
                <!-- Contenu spécifique au Programme DANIEL ici -->
                 <h3 class="text-2xl font-semibold mb-4 text-purple-700">Justification du Nom</h3>
                 <div class="bg-purple-50 p-5 rounded-lg border-l-4 border-purple-400 mb-6 shadow-sm">
                    <p class="text-md text-purple-800">
                        <i class="fas fa-quote-left text-purple-300 mr-2 fa-lg"></i>
                        Daniel incarne l'intégrité inébranlable, la sagesse divine et l'influence spirituelle au sein même des plus hautes sphères du pouvoir séculier, tout en maintenant une dévotion profonde.
                        <i class="fas fa-quote-right text-purple-300 ml-2 fa-lg"></i>
                    </p>
                </div>
                 <p class="mb-6 text-gray-700">
                    <strong>Fondement biblique :</strong> Daniel, exilé à Babylone, a servi plusieurs rois païens avec excellence et fidélité à Dieu. Malgré les pressions et les menaces, il n'a jamais compromis sa foi. Sa sagesse, sa capacité à interpréter les songes et sa vie de prière lui ont valu le respect et lui ont permis d'influencer positivement les décisions des empires.
                </p>
                 <h3 class="text-2xl font-semibold mb-4 text-purple-700">Essence du Programme</h3>
                <p class="mb-6 text-gray-700">
                    Renforcer la montagne de la religion et de la spiritualité en équipant les leaders religieux et les croyants pour un impact sociétal pertinent. Former des individus capables d'opérer avec sagesse et intégrité dans des contextes séculiers, en particulier gouvernementaux, tout en étant des témoins vivants de leur foi.
                </p>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-purple-700">Objectifs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Équiper les leaders religieux pour une influence éthique et sociale.</li>
                            <li>Promouvoir le dialogue interconfessionnel et la liberté religieuse.</li>
                            <li>Former les croyants à intégrer leur foi dans tous les domaines de leur vie.</li>
                            <li>Servir de conseillers spirituels et éthiques dans les sphères de décision.</li>
                            <li>Développer des ressources pour l'éducation théologique et biblique appliquée.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-purple-700">KPIs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Nombre de leaders religieux formés/impactés.</li>
                            <li>Initiatives de dialogue interconfessionnel lancées.</li>
                             <li>Nombre de croyants équipés pour un témoignage public.</li>
                            <li>Opportunités de conseil obtenues auprès d'institutions séculières.</li>
                            <li>Diffusion des ressources théologiques développées.</li>
                        </ul>
                    </div>
                </div>
                 <h4 class="text-xl font-semibold mb-4 text-purple-700">Projets Potentiels à Développer</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-purple-600 mb-1">Institut Daniel de Sagesse Appliquée</h5>
                        <p class="text-sm text-gray-600">Formation pour croyants dans des sphères non religieuses (politique, administration).</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-purple-600 mb-1">Plateforme "Foi & Société"</h5>
                        <p class="text-sm text-gray-600">Espace de dialogue, de ressources et de mise en réseau pour l'engagement public.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-purple-600 mb-1">Programme de Mentorat Spirituel</h5>
                        <p class="text-sm text-gray-600">Accompagnement d'individus dans leur croissance et leur impact.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-purple-600 mb-1">Conférences "Éthique et Foi"</h5>
                        <p class="text-sm text-gray-600">Événements abordant les défis sociétaux sous un angle biblique.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Programme PRISCILLE & AQUILA -->
        <section id="programme-priscille" class="mb-16 bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="bg-burgundy-600 text-white p-8">
                <h2 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-users fa-fw mr-4 text-burgundy-200 text-4xl"></i>
                    Programme PRISCILLE & AQUILA
                </h2>
                <p class="text-burgundy-100 text-lg mt-1">Famille + Action Sociale Communautaire + Éducation (secondaire)</p>
            </div>
             <div class="p-6 sm:p-8">
                <h3 class="text-2xl font-semibold mb-4 text-burgundy-700">Justification du Nom</h3>
                <div class="bg-burgundy-50 p-5 rounded-lg border-l-4 border-burgundy-400 mb-6 shadow-sm">
                    <p class="text-md text-burgundy-800">
                        <i class="fas fa-quote-left text-burgundy-300 mr-2 fa-lg"></i>
                        Priscille et Aquila représentent le partenariat conjugal dans le ministère, l'hospitalité, le mentorat et l'impact économique au service de l'Évangile. Leur maison était un centre vivant de foi, d'éducation et de soutien, incarnant une vie de famille solide <strong class="text-burgundy-800">qui déborde vers le service et l'aide concrète à autrui et à la communauté</strong>.
                        <i class="fas fa-quote-right text-burgundy-300 ml-2 fa-lg"></i>
                    </p>
                </div>
                <p class="mb-6 text-gray-700">
                    <strong>Fondement biblique :</strong> Ce couple du Nouveau Testament est un exemple de collaboration dans la vie, le travail (fabricants de tentes) et le ministère. Ils ont ouvert leur maison pour l'église, ont voyagé avec l'apôtre Paul, et ont instruit Apollos plus précisément dans la voie de Dieu. Leur vie illustre l'importance de la famille comme base pour l'éducation, le service et l'influence, montrant comment un foyer peut devenir une source de bénédiction et d'action sociale pour son entourage.
                </p>
                 <h3 class="text-2xl font-semibold mb-4 text-burgundy-700">Essence du Programme</h3>
                <p class="mb-6 text-gray-700">
                    Soutenir et renforcer les familles comme fondement de la société et piliers de la communauté, en promouvant des modèles basés sur des principes bibliques. Le programme vise à équiper les foyers pour être des centres d'influence positive, de soin et d'éducation, s'étendant vers la communauté environnante par l'hospitalité, le mentorat <strong class="text-burgundy-800">et des actions sociales concrètes de soutien aux personnes et familles vulnérables</strong>.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-burgundy-700">Objectifs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Renforcer les liens familiaux et conjugaux selon les principes bibliques.</li>
                            <li>Équiper les parents pour l'éducation et le développement de leurs enfants.</li>
                            <li>Promouvoir l'hospitalité et l'entraide au sein des communautés.</li>
                            <li><strong class="text-burgundy-800">Développer des programmes d'action sociale ciblés pour les familles et individus vulnérables (aide, accompagnement).</strong></li>
                            <li><strong class="text-burgundy-800">Mobiliser les familles et individus pour s'engager dans le service communautaire.</strong></li>
                             <li>Soutenir le mentorat et la transmission de compétences.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-burgundy-700">KPIs Généraux</h4>
                        <ul class="list-disc list-inside pl-2 space-y-2 text-gray-700">
                            <li>Nombre de familles ayant participé aux programmes de renforcement.</li>
                            <li>Nombre d'actions d'hospitalité ou d'entraide documentées.</li>
                            <li><strong class="text-burgundy-800">Nombre de personnes/familles vulnérables ayant bénéficié d'une aide concrète.</strong></li>
                            <li><strong class="text-burgundy-800">Nombre de bénévoles engagés dans des actions sociales via le programme.</strong></li>
                             <li>Évaluations de l'impact sur le bien-être familial et communautaire.</li>
                             <li>Nombre de relations de mentorat établies.</li>
                        </ul>
                    </div>
                </div>
                 <h4 class="text-xl font-semibold mb-4 text-burgundy-700">Projets Potentiels à Développer</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-burgundy-600 mb-1">Ateliers "Famille Solide"</h5>
                        <p class="text-sm text-gray-600">Formation sur le mariage, la parentalité, la communication.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-burgundy-600 mb-1">Réseau d'Hospitalité et d'Entraide</h5>
                        <p class="text-sm text-gray-600">Mise en relation pour le partage, le soutien mutuel, l'accueil.</p>
                    </div>
                     <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-burgundy-600 mb-1"><strong class="text-burgundy-800">Programme de Soutien aux Familles Vulnérables</strong></h5>
                        <p class="text-sm text-gray-600"><strong class="text-burgundy-800">Aide alimentaire, vestimentaire, accompagnement administratif, conseil budgétaire.</strong></p>
                    </div>
                     <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-burgundy-600 mb-1"><strong class="text-burgundy-800">Initiatives Communautaires de Service</strong></h5>
                        <p class="text-sm text-gray-600"><strong class="text-burgundy-800">Organisation d'événements de service (nettoyage de quartier, aide aux seniors), banques de bénévoles.</strong></p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-burgundy-600 mb-1">Programme de Mentorat Transgénérationnel</h5>
                        <p class="text-sm text-gray-600">Connexion entre générations pour la transmission de sagesse et de compétences.</p>
                    </div>
                     <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                        <h5 class="font-bold text-lg text-burgundy-600 mb-1">Groupe de Support pour Parents Isolé(e)s</h5>
                        <p class="text-sm text-gray-600">Espace d'écoute, de partage et de soutien pratique pour parents seuls.</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="bg-gray-800 text-gray-300 py-8 text-center">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <p>&copy; <span id="currentYear"></span> Votre ONG. Tous droits réservés.</p>
            <p class="text-sm mt-1">Conçu pour inspirer et transformer.</p>
        </div>
    </footer>

    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>

</body>
</html>