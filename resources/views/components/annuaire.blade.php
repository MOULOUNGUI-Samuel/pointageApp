<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annuaire Inter-Entreprise - BFEV</title>
    <style>
        /* ====================================================== */
        /* ==                 VARIABLES & IMPORTS              == */
        /* ====================================================== */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

        :root {
            --font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
            --primary-color: #05436b;
            --primary-light: #0a5c8f;
            --primary-dark: #032d4c;
            --secondary-color: #F8F9FA;
            --accent-color: #FF6B35;
            --text-primary: #2C3E50;
            --text-secondary: #6C757D;
            --text-light: #ADB5BD;
            --border-color: #DEE2E6;
            --success-color: #28A745;
            --warning-color: #FFC107;
            --danger-color: #DC3545;
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        /* ====================================================== */
        /* ==                  BASE STYLES                     == */
        /* ====================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ====================================================== */
        /* ==                    HEADER                         == */
        /* ====================================================== */
        .header {
            background: white;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 3px solid var(--primary-color);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            height: 50px;
            width: auto;
            object-fit: contain;
        }

        .app-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .app-subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* ====================================================== */
        /* ==                  MAIN CONTAINER                  == */
        /* ====================================================== */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
            min-height: calc(100vh - 120px);
        }

        /* ====================================================== */
        /* ==                   SIDEBAR                         == */
        /* ====================================================== */
        .sidebar {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            padding: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 140px;
        }

        .search-section {
            margin-bottom: 2rem;
        }

        .search-box {
            position: relative;
            margin-bottom: 1rem;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--secondary-color);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 89, 77, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .filter-section {
            margin-bottom: 1.5rem;
        }

        .filter-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group {
            margin-bottom: 1rem;
        }

        .filter-select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            background: white;
            transition: var(--transition);
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .stats-section {
            background: var(--secondary-color);
            padding: 1rem;
            border-radius: var(--border-radius);
            text-align: center;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
        }

        .stats-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        /* ====================================================== */
        /* ==                  MAIN CONTENT                    == */
        /* ====================================================== */
        .main-content {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .content-header {
            background: var(--primary-color);
            color: white;
            padding: 1.5rem 2rem 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .content-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        /* Onglets */
        .tabs-container {
            display: flex;
            gap: 0;
        }

        .tab-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: rgba(255, 255, 255, 0.7);
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.95rem;
            font-weight: 500;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            border-bottom: 2px solid transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
        }

        .tab-btn.active {
            background: white;
            color: var(--primary-color);
            border-bottom-color: white;
        }

        .content-body {
            padding: 0;
        }

        /* Contenu des onglets */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
            min-height: 400px;
        }

        .tab-content.active {
            display: block;
        }

        /* S'assurer que la vue liste est visible */
        #listTabContent .employees-list {
            display: block !important;
            width: 100%;
        }

        /* ====================================================== */
        /* ==                  EMPLOYEE GRID                   == */
        /* ====================================================== */
        .employees-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }

        .employee-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .employee-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
            border-color: var(--primary-color);
        }

        .employee-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-light) 100%);
        }

        .employee-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .employee-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            overflow: hidden;
        }

        .employee-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .employee-basic {
            flex: 1;
        }

        .employee-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .employee-title {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .employee-company {
            font-size: 0.8rem;
            color: var(--primary-color);
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            background: rgba(0, 89, 77, 0.1);
            border-radius: 12px;
            display: inline-block;
        }

        .employee-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .detail-icon {
            color: var(--primary-color);
            width: 16px;
            text-align: center;
        }

        .detail-text {
            color: var(--text-secondary);
        }

        .employee-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .action-btn {
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            padding: 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* ====================================================== */
        /* ==                  EMPLOYEE LIST                   == */
        /* ====================================================== */
        .employees-list {
            display: block;
        }

        .list-header {
            background: var(--secondary-color);
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: grid;
            grid-template-columns: 2fr 1.5fr 1fr 1fr 100px;
            gap: 1rem;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .list-item {
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: grid;
            grid-template-columns: 2fr 1.5fr 1fr 1fr 100px;
            gap: 1rem;
            align-items: center;
            transition: var(--transition);
        }

        .list-item:hover {
            background: var(--secondary-color);
        }

        .list-employee {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .list-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .list-info h4 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .list-info p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .list-company {
            color: var(--primary-color);
            font-weight: 500;
        }

        .list-contact {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .list-department {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .list-actions {
            display: flex;
            gap: 0.25rem;
        }

        /* ====================================================== */
        /* ==                   RESPONSIVE                      == */
        /* ====================================================== */
        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
            }

            .sidebar {
                position: static;
                margin-bottom: 1rem;
            }

            .employees-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1rem;
                padding: 1rem;
            }

            .list-header,
            .list-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                text-align: left;
                padding: 1rem;
            }

            .list-employee {
                margin-bottom: 0.5rem;
            }

            .list-actions {
                justify-content: flex-start;
                margin-top: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .content-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 1rem;
            }

            .tabs-container {
                width: 100%;
                justify-content: center;
            }

            .tab-btn {
                flex: 1;
                text-align: center;
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
            }

            .employees-grid {
                grid-template-columns: 1fr;
            }

            .employee-details {
                grid-template-columns: 1fr;
            }
        }

        /* ====================================================== */
        /* ==                   ANIMATIONS                      == */
        /* ====================================================== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .employee-card {
            animation: fadeIn 0.5s ease-out;
        }

        .employee-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .employee-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .employee-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .employee-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .employee-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .employee-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        /* ====================================================== */
        /* ==                    UTILITIES                      == */
        /* ====================================================== */
        .text-center {
            text-align: center;
        }

        .text-muted {
            color: var(--text-secondary);
        }

        .font-weight-bold {
            font-weight: 600;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-1 {
            margin-top: 0.5rem;
        }

        /* Status badges */
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-online {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .status-away {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .status-offline {
            background: rgba(108, 117, 125, 0.1);
            color: var(--text-light);
        }

        /* Style pour l'arrière-plan de la modale */
        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(3px);
        }

        /* Style pour la boîte de dialogue elle-même */
        #annuaireDialog {
            width: 450px;
            height: 550px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 0;
            overflow: hidden;
        }

        .dialog-content {
            width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Style pour l'iframe qui contient votre page */
        #annuaireDialog iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Style pour le bouton de fermeture */
        .dialog-close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            background: transparent;
            border: none;
            font-size: 2rem;
            color: #6c757d;
            cursor: pointer;
            line-height: 1;
            z-index: 10;
        }

        .dialog-close-btn:hover {
            color: #000;
        }
    </style>
</head>

<body>
    <!-- MAIN CONTAINER -->
    <div class="container">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="search-section">
                <h3 class="filter-title">
                    <i class="fas fa-search"></i>
                    Recherche
                </h3>
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Nom, fonction, entreprise..."
                        id="searchInput">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>

            <div class="filter-section">
                <h3 class="filter-title">
                    <i class="fas fa-filter"></i>
                    Filtres
                </h3>
                <div class="filter-group">
                    <select class="filter-select" id="companyFilter">
                        <option value="">Toutes les entreprises</option>
                        @foreach ($entreprises as $entreprise)
                            <option value="{{ $entreprise->code_entreprise }}">{{ $entreprise->nom_entreprise }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <select class="filter-select" id="departmentFilter">
                        <option value="">Tous les départements</option>
                        @php($displayedNames = [])

                        @foreach ($services as $service)
                            {{-- On vérifie si le nom du service n'est PAS DÉJÀ dans notre tableau --}}
                            @if (!in_array($service->nom_service, $displayedNames))
                                {{-- Si ce n'est pas le cas, on affiche l'option --}}
                                <option value="{{ $service->nom_service }}">{{ $service->nom_service }}</option>

                                {{-- Et on ajoute le nom au tableau pour ne plus le réafficher --}}
                                @php($displayedNames[] = $service->nom_service)
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <select class="filter-select" id="statusFilter">
                        <option value="">Tous les statuts</option>
                        <option value="online">En ligne</option>
                        <option value="away">Absent</option>
                        <option value="offline">Hors ligne</option>
                    </select>
                </div>
            </div>

            <div class="stats-section">
                <span class="stats-number" id="totalCount">127</span>
                <span class="stats-label">Contacts total</span>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <div class="content-header">
                <h2 class="content-title">Personnel Inter-Entreprise</h2>
                <div class="tabs-container">
                    <button class="tab-btn active" id="gridTabBtn">
                        <i class="fas fa-th"></i>
                        Vue Cartes
                    </button>
                    <button class="tab-btn" id="listTabBtn">
                        <i class="fas fa-list"></i>
                        Vue Liste
                    </button>
                </div>
            </div>

            <div class="content-body">
                <!-- TAB CONTENT - GRID VIEW -->
                <div class="tab-content active" id="gridTabContent">
                    <div class="employees-grid">
                        <!-- Employee Card 1 -->
                        @foreach ($utilisateurs as $user)
                            <div class="employee-card" data-company="{{ $user->entreprise->code_entreprise }}"
                                data-department="{{ $user->service->nom_service }}" data-status="online">
                                <div class="employee-header">
                                    <div class="employee-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="employee-basic">
                                        <h3 class="employee-name">{{ $user->nom }} {{ $user->prenom }}</h3>
                                        <p class="employee-title">{{ $user->fonction ?? '------' }}</p>
                                        <span
                                            class="employee-company">{{ $user->entreprise->nom_entreprise ?? '------' }}</span>
                                    </div>
                                </div>
                                <div class="employee-details">
                                    <div class="detail-item">
                                        <i class="fas fa-phone detail-icon"></i>
                                        <span class="detail-text">+241 {{ $user->telephone ?? '------' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-envelope detail-icon"></i>
                                        <span class="detail-text">{{ $user->email_professionnel ?? '------' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-building detail-icon"></i>
                                        <span class="detail-text">{{ $user->service->nom_service }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-circle detail-icon" style="color: var(--success-color);"></i>
                                        <span class="status-badge status-online">En ligne</span>
                                    </div>
                                </div>
                                <div class="employee-actions">
                                    <button class="action-btn" title="Appeler">
                                        <i class="fas fa-phone"></i>
                                    </button>
                                    <button class="action-btn" title="Envoyer un email">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    <button class="action-btn" title="Chat">
                                        <i class="fas fa-comment"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- TAB CONTENT - LIST VIEW -->
                <div class="tab-content" id="listTabContent">
                    <div class="employees-list">
                        <div class="list-header">
                            <div>Employé</div>
                            <div>Entreprise</div>
                            <div>Contact</div>
                            <div>Département</div>
                            <div>Actions</div>
                        </div>
                        @foreach ($utilisateurs as $user)
                            <div class="list-item" data-company="{{ $user->entreprise->code_entreprise }}"
                                data-department="{{ $user->service->nom_service }}" data-status="online">
                                <div class="list-employee">
                                <div class="list-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                    <div class="list-info">
                                        <h4>{{ $user->nom }} {{ $user->prenom }}</h4>
                                        <p>{{ $user->fonction ?? '------' }}</p>
                                    </div>
                                </div>
                                <div class="list-company">{{ $user->entreprise->nom_entreprise ?? '------' }}</div>
                                <div class="list-contact">
                                    <span class="detail-item">
                                        <i class="fas fa-phone detail-icon"></i>
                                        <span class="detail-text">{{ $user->telephone ?? '------' }}</span>
                                    </span>
                                    <span class="detail-item">
                                        <i class="fas fa-envelope detail-icon"></i>
                                        <span class="detail-text">{{ $user->email_professionnel ?? '------' }}</span>
                                    </span>
                                </div>
                                <div class="list-department">{{ $user->service->nom_service ?? '------' }}</div>
                                <div class="list-actions">
                                    <button class="action-btn" title="Appeler">
                                        <i class="fas fa-phone"></i>
                                    </button>
                                    <button class="action-btn" title="Email">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    <button class="action-btn" title="Chat">
                                        <i class="fas fa-comment"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        <!-- Autres éléments de liste... -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ====================================================== //
            // ==                  FUNCTIONALITY                   == //
            // ====================================================== //

            // --- Elements --- //
            // Boutons des onglets (IDs corrigées)
            const gridTabBtn = document.getElementById('gridTabBtn');
            const listTabBtn = document.getElementById('listTabBtn');
            // Contenu des onglets (IDs corrigées)
            const gridTabContent = document.getElementById('gridTabContent');
            const listTabContent = document.getElementById('listTabContent');

            // --- Changement de vue (Tabs) --- //
            gridTabBtn.addEventListener('click', () => {
                gridTabBtn.classList.add('active');
                listTabBtn.classList.remove('active');
                gridTabContent.classList.add('active');
                listTabContent.classList.remove('active');
                filterEmployees(); // Met à jour le compteur
            });

            listTabBtn.addEventListener('click', () => {
                listTabBtn.classList.add('active');
                gridTabBtn.classList.remove('active');
                listTabContent.classList.add('active');
                gridTabContent.classList.remove('active');
                filterEmployees(); // Met à jour le compteur
            });

            // --- Filtres et Recherche --- //
            const searchInput = document.getElementById('searchInput');
            const companyFilter = document.getElementById('companyFilter');
            const departmentFilter = document.getElementById('departmentFilter');
            const statusFilter = document.getElementById('statusFilter');
            const totalCount = document.getElementById('totalCount');

            function filterEmployees() {
                const searchTerm = searchInput.value.toLowerCase();
                const companyValue = companyFilter.value;
                const departmentValue = departmentFilter.value;
                const statusValue = statusFilter.value;

                // Éléments de la vue grille
                const employeeCards = document.querySelectorAll('#gridTabContent .employee-card');
                // Éléments de la vue liste  
                const listItems = document.querySelectorAll('#listTabContent .list-item');

                let visibleCount = 0;

                // Filter grid view
                employeeCards.forEach(card => {
                    const name = card.querySelector('.employee-name').textContent.toLowerCase();
                    const title = card.querySelector('.employee-title').textContent.toLowerCase();
                    const company = card.getAttribute('data-company');
                    const department = card.getAttribute('data-department');
                    const status = card.getAttribute('data-status');

                    const matchesSearch = name.includes(searchTerm) || title.includes(searchTerm);
                    const matchesCompany = !companyValue || company === companyValue;
                    const matchesDepartment = !departmentValue || department === departmentValue;
                    const matchesStatus = !statusValue || status === statusValue;

                    if (matchesSearch && matchesCompany && matchesDepartment && matchesStatus) {
                        card.style.display = 'block';
                        if (gridTabContent.classList.contains('active')) {
                            visibleCount++;
                        }
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Filter list view
                listItems.forEach(item => {
                    const nameElement = item.querySelector('h4');
                    const titleElement = item.querySelector('p');

                    if (nameElement && titleElement) {
                        const name = nameElement.textContent.toLowerCase();
                        const title = titleElement.textContent.toLowerCase();
                        const company = item.getAttribute('data-company');
                        const department = item.getAttribute('data-department');
                        const status = item.getAttribute('data-status');

                        const matchesSearch = name.includes(searchTerm) || title.includes(searchTerm);
                        const matchesCompany = !companyValue || company === companyValue;
                        const matchesDepartment = !departmentValue || department === departmentValue;
                        const matchesStatus = !statusValue || status === statusValue;

                        if (matchesSearch && matchesCompany && matchesDepartment && matchesStatus) {
                            item.style.display = 'grid';
                            if (listTabContent.classList.contains('active')) {
                                visibleCount++;
                            }
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });

                totalCount.textContent = visibleCount;
            }

            // --- Event Listeners pour les filtres --- //
            searchInput.addEventListener('input', filterEmployees);
            companyFilter.addEventListener('change', filterEmployees);
            departmentFilter.addEventListener('change', filterEmployees);
            statusFilter.addEventListener('change', filterEmployees);

            // --- Actions sur les boutons (Appel, Email, Chat) --- //
            document.addEventListener('click', (e) => {
                if (e.target.closest('.action-btn')) {
                    const btn = e.target.closest('.action-btn');
                    const icon = btn.querySelector('i');

                    if (icon.classList.contains('fa-phone')) {
                        alert('Fonctionnalité d\'appel à implémenter');
                    } else if (icon.classList.contains('fa-envelope')) {
                        alert('Fonctionnalité d\'email à implémenter');
                    } else if (icon.classList.contains('fa-comment')) {
                        alert('Fonctionnalité de chat à implémenter');
                    }
                }
            });

            // --- Initialisation au chargement de la page --- //
            filterEmployees();
        });
    </script>
</body>

</html>
