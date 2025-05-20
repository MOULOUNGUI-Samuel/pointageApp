<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autre test</title>
    <style>
        /* ========== STYLES GÉNÉRAUX ========== */
        :root {
            /* Palette de couleurs principale - Peut être adaptée à la charte graphique client */
            --primary-color: #2c3e50;
            --primary-light: #34495e;
            --secondary-color: #3498db;
            --secondary-light: #5dade2;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --text-color: #333333;
            --text-light: #7f8c8d;
            --bg-light: #f5f7fa;
            --bg-white: #ffffff;
            --border-color: #dfe4ea;
            
            /* Variables pour l'espacement et dimensions */
            --header-height: 60px;
            --sidebar-width: 260px;
            --sidebar-collapsed: 70px;
            --border-radius: 5px;
            --box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            background-color: var(--bg-light);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 500;
            line-height: 1.2;
            margin-bottom: 0.5em;
            color: var(--primary-color);
        }
        
        a {
            text-decoration: none;
            color: var(--secondary-color);
        }
        
        /* ========== DISPOSITION PRINCIPALE ========== */
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        /* ========== EN-TÊTE ========== */
        header {
            background-color: var(--primary-color);
            color: white;
            height: var(--header-height);
            padding: 0 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: var(--box-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 36px;
            margin-right: 10px;
        }
        
        .logo h1 {
            margin: 0;
            font-size: 18px;
            color: white;
        }
        
        .top-nav {
            display: flex;
            align-items: center;
        }
        
        .top-nav-item {
            position: relative;
            margin-left: 20px;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            margin-left: 30px;
        }
        
        .user-info img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid rgba(255,255,255,0.2);
        }
        
        .user-details {
            line-height: 1.2;
        }
        
        .user-name {
            font-weight: 500;
            font-size: 14px;
        }
        
        .user-role {
            font-size: 12px;
            opacity: 0.7;
        }
        
        /* ========== BARRE LATÉRALE (MENU) ========== */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-light);
            color: #ecf0f1;
            padding-top: var(--header-height);
            box-shadow: var(--box-shadow);
            position: fixed;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            transition: width 0.3s ease;
            z-index: 900;
        }
        
        .sidebar-collapsed {
            width: var(--sidebar-collapsed);
        }
        
        .toggle-sidebar {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .toggle-sidebar:hover {
            background-color: rgba(255,255,255,0.2);
        }
        
        .main-menu {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }
        
        .main-menu > li {
            margin-bottom: 1px;
        }
        
        .main-menu > li > a {
            display: flex;
            align-items: center;
            padding: 13px 20px;
            color: #ecf0f1;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
            border-left: 4px solid transparent;
        }
        
        .main-menu > li > a:hover, .main-menu > li.active > a {
            background-color: rgba(0,0,0,0.1);
            border-left-color: var(--secondary-color);
        }
        
        .main-menu > li.active > a {
            background-color: rgba(0,0,0,0.2);
        }
        
        .menu-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            margin-right: 12px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 4px;
            font-style: normal;
            font-weight: bold;
        }
        
        .menu-text {
            flex: 1;
            transition: opacity 0.3s;
        }
        
        .sidebar-collapsed .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .menu-arrow {
            font-size: 12px;
            transition: transform 0.3s;
        }
        
        .main-menu > li.active .menu-arrow {
            transform: rotate(90deg);
        }
        
        /* Sous-menu */
        .sub-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: rgba(0,0,0,0.15);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .main-menu > li.active .sub-menu {
            max-height: 500px; /* Valeur arbitraire suffisamment grande */
        }
        
        .sub-menu li a {
            display: block;
            padding: 10px 20px 10px 56px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
            position: relative;
        }
        
        .sub-menu li a:hover, .sub-menu li.active a {
            color: white;
            background-color: rgba(0,0,0,0.1);
        }
        
        .sub-menu li.active a:before {
            content: "";
            position: absolute;
            left: 40px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--secondary-color);
        }
        
        .sidebar-collapsed .sub-menu {
            display: none;
        }
        
        /* ========== CONTENU PRINCIPAL ========== */
        .content {
            flex: 1;
            padding: calc(var(--header-height) + 20px) 30px 30px calc(var(--sidebar-width) + 30px);
            transition: padding 0.3s ease;
        }
        
        .sidebar-collapsed-content {
            padding-left: calc(var(--sidebar-collapsed) + 30px);
        }
        
        .breadcrumb {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
            display: flex;
            font-size: 14px;
            color: var(--text-light);
        }
        
        .breadcrumb li {
            display: flex;
            align-items: center;
        }
        
        .breadcrumb li:not(:last-child)::after {
            content: "›";
            margin: 0 10px;
            color: var(--text-light);
        }
        
        .breadcrumb a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        
        .breadcrumb li:last-child {
            color: var(--text-light);
        }
        
        .page-header {
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            margin: 0;
            font-weight: 500;
            color: var(--primary-color);
            font-size: 24px;
        }
        
        .page-actions {
            display: flex;
            gap: 10px;
        }
        
        /* ========== COMPOSANTS DE L'INTERFACE ========== */
        /* Cartes */
        .card {
            background-color: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .card-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--border-color);
            background-color: rgba(0,0,0,0.02);
        }
        
        /* Boutons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            outline: none;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-light);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }
        
        .btn-outline:hover {
            background-color: rgba(0,0,0,0.05);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-icon-text {
            margin-left: 8px;
        }
        
        /* Tableaux */
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead th {
            background-color: rgba(0,0,0,0.03);
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
            color: var(--text-color);
            border-bottom: 2px solid var(--border-color);
            position: relative;
        }
        
        tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
        }
        
        tbody tr:hover {
            background-color: rgba(0,0,0,0.015);
        }
        
        /* Tags et badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-success {
            background-color: rgba(39, 174, 96, 0.15);
            color: var(--success-color);
        }
        
        .badge-warning {
            background-color: rgba(243, 156, 18, 0.15);
            color: var(--warning-color);
        }
        
        .badge-danger {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--danger-color);
        }
        
        /* Formulaires */
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            outline: none;
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%237f8c8d' d='M6 8.825l-4.175-4.175 1.4-1.4 2.775 2.775 2.775-2.775 1.4 1.4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }
        
        /* Grid layout */
        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 20px;
        }
        
        .col-3 {
            grid-column: span 3;
        }
        
        .col-4 {
            grid-column: span 4;
        }
        
        .col-6 {
            grid-column: span 6;
        }
        
        .col-8 {
            grid-column: span 8;
        }
        
        .col-9 {
            grid-column: span 9;
        }
        
        .col-12 {
            grid-column: span 12;
        }
        
        /* Composants de visualisation de données */
        .stat-card {
            display: flex;
            padding: 15px;
            background-color: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: var(--border-radius);
            margin-right: 15px;
            font-size: 20px;
            color: white;
        }
        
        .stat-primary {
            background-color: var(--secondary-color);
        }
        
        .stat-success {
            background-color: var(--success-color);
        }
        
        .stat-warning {
            background-color: var(--warning-color);
        }
        
        .stat-danger {
            background-color: var(--danger-color);
        }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 600;
            line-height: 1;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: var(--text-light);
        }
        
        .chart-container {
            height: 300px;
            position: relative;
            margin-bottom: 20px;
        }
        
        .chart {
            width: 100%; 
            height: 100%;
            background-color: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
        }
        
        /* Modules spécifiques au SMI */
        .process-map {
            height: 400px;
            background-color: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        
        .process-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .process-container {
            flex: 1;
            border: 1px dashed var(--border-color);
            border-radius: var(--border-radius);
            padding: 15px;
            overflow: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        .process-box {
            width: 150px;
            height: 80px;
            background-color: var(--bg-light);
            border: 2px solid var(--secondary-color);
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 10px;
            position: absolute;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
        }
        
        .process-box:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .process-box.management {
            border-color: #3498db;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .process-box.operational {
            border-color: #2ecc71;
            top: 150px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .process-box.support {
            border-color: #e67e22;
            top: 270px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .process-box-title {
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .process-box-subtitle {
            font-size: 12px;
            color: var(--text-light);
        }
        
        .document-browser {
            display: flex;
            height: 500px;
            background-color: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .document-folders {
            width: 280px;
            background-color: var(--bg-light);
            border-right: 1px solid var(--border-color);
            padding: 15px;
            overflow-y: auto;
        }
        
        .folder-list {
            list-style: none;
        }
        
        .folder-item {
            padding: 8px 10px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-bottom: 2px;
        }
        
        .folder-item:hover {
            background-color: rgba(0,0,0,0.05);
        }
        
        .folder-item.active {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }
        
        .folder-icon {
            margin-right: 10px;
            color: var(--text-light);
        }
        
        .folder-item.active .folder-icon {
            color: var(--secondary-color);
        }
        
        .document-list {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
        }
        
        .document-item {
            padding: 12px;
            border-radius: var(--border-radius);
            cursor: pointer;
            border: 1px solid var(--border-color);
            margin-bottom: 10px;
            transition: all 0.2s;
        }
        
        .document-item:hover {
            border-color: var(--secondary-color);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .document-title {
            font-weight: 500;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        
        .document-icon {
            margin-right: 8px;
        }
        
        .document-meta {
            display: flex;
            font-size: 13px;
            color: var(--text-light);
        }
        
        .document-meta div {
            margin-right: 15px;
            display: flex;
            align-items: center;
        }
        
        .document-meta div i {
            margin-right: 5px;
            font-size: 14px;
        }
        
        /* Audit planning */
        .audit-calendar {
            background-color: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        
        .calendar-day {
            aspect-ratio: 1;
            padding: 5px;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
        }
        
        .day-header {
            text-align: right;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .day-content {
            flex: 1;
            overflow: hidden;
            font-size: 11px;
        }
        
        .audit-event {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
            padding: 2px 4px;
            border-radius: 2px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Risques */
        .risk-matrix {
            width: 100%;
            border-collapse: separate;
            border-spacing: 2px;
        }
        
        .risk-matrix th, .risk-matrix td {
            text-align: center;
            padding: 15px 10px;
            position: relative;
        }
        
        .risk-matrix th {
            background-color: var(--bg-light);
            font-weight: 500;
        }
        
        .risk-cell {
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .risk-cell:hover {
            transform: scale(1.05);
            z-index: 1;
        }
        
        .risk-low {
            background-color: rgba(46, 204, 113, 0.2);
        }
        
        .risk-medium {
            background-color: rgba(241, 196, 15, 0.2);
        }
        
        .risk-high {
            background-color: rgba(231, 76, 60, 0.2);
        }
        
        .risk-extreme {
            background-color: rgba(231, 76, 60, 0.5);
        }
        
        .risk-item {
            display: inline-block;
            width: 20px;
            height: 20px;
            line-height: 20px;
            background-color: var(--bg-white);
            border-radius: 50%;
            font-size: 10px;
            font-weight: bold;
            margin: 2px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .grid {
                grid-template-columns: repeat(6, 1fr);
            }
            
            .col-3, .col-4, .col-6 {
                grid-column: span 6;
            }
            
            .col-8, .col-9, .col-12 {
                grid-column: span 6;
            }
            
            .document-browser {
                flex-direction: column;
                height: auto;
            }
            
            .document-folders {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed);
            }
            
            .menu-text {
                opacity: 0;
                width: 0;
                overflow: hidden;
            }
            
            .content {
                padding-left: calc(var(--sidebar-collapsed) + 20px);
                padding-right: 20px;
            }
            
            .grid {
                grid-template-columns: repeat(1, 1fr);
            }
            
            .col-3, .col-4, .col-6, .col-8, .col-9, .col-12 {
                grid-column: span 1;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-actions {
                margin-top: 10px;
            }
            
            .user-info {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- En-tête principal de l'application -->
    <header>
        <div class="logo">
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='%233498db'%3E%3Cpath d='M50 10L90 30v40L50 90 10 70V30L50 10z'/%3E%3Cpath fill='white' d='M50 25L75 37.5v25L50 75 25 62.5v-25L50 25z'/%3E%3C/svg%3E" alt="Logo Nedcore">
            <h1>Nedcore ERP - test</h1>
        </div>
        <div class="top-nav">
            <div class="top-nav-item">
                <svg width="20" height="20" fill="white" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88
<svg width="20" height="20" fill="white" viewBox="0 0 16 16">
                   <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
               </svg>
               <span class="notification-badge">3</span>
           </div>
           <div class="top-nav-item">
               <svg width="20" height="20" fill="white" viewBox="0 0 16 16">
                   <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.105V5.383zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
               </svg>
               <span class="notification-badge">5</span>
           </div>
           <div class="user-info">
               <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23bdc3c7'%3E%3Ccircle cx='12' cy='7' r='5'/%3E%3Cpath d='M17 14h-10c-3.86 0-7 3.14-7 7v0h24v0c0-3.86-3.14-7-7-7z'/%3E%3C/svg%3E" alt="User">
               <div class="user-details">
                   <div class="user-name">Thomas Dupont</div>
                   <div class="user-role">Administrateur SMI</div>
               </div>
           </div>
       </div>
   </header>
   
   <!-- Structure principale de l'application -->
   <div class="container">
       <!-- Menu latéral avec les options du module SMI -->
       <aside class="sidebar" id="sidebar">
           <ul class="main-menu">
               <li class="active">
                   <a href="#governance">
                       <span class="menu-icon">G</span>
                       <span class="menu-text">Gouvernance & Pilotage</span>
                       <span class="menu-arrow">›</span>
                   </a>
                   <ul class="sub-menu">
                       <li><a href="#dashboard">Tableaux de bord SMI</a></li>
                       <li class="active"><a href="#reviews">Revues de direction</a></li>
                       <li><a href="#orgchart">Structure organisationnelle</a></li>
                       <li><a href="#comms">Communication interne</a></li>
                   </ul>
               </li>
               <li>
                   <a href="#docs">
                       <span class="menu-icon">D</span>
                       <span class="menu-text">Gestion Documentaire</span>
                       <span class="menu-arrow">›</span>
                   </a>
                   <ul class="sub-menu">
                       <li><a href="#repository">Référentiel documentaire</a></li>
                       <li><a href="#lifecycle">Cycle de vie des documents</a></li>
                       <li><a href="#distribution">Diffusion et consultation</a></li>
                       <li><a href="#extdocs">Documentation externe</a></li>
                   </ul>
               </li>
               <li>
                   <a href="#processes">
                       <span class="menu-icon">P</span>
                       <span class="menu-text">Processus & Procédures</span>
                       <span class="menu-arrow">›</span>
                   </a>
                   <ul class="sub-menu">
                       <li><a href="#mapping">Cartographie des processus</a></li>
                       <li><a href="#procedures">Procédures opérationnelles</a></li>
                       <li><a href="#optimization">Analyse et optimisation</a></li>
                       <li><a href="#workinstruction">Gestion des modes opératoires</a></li>
                   </ul>
               </li>
               <li>
                   <a href="#compliance">
                       <span class="menu-icon">C</span>
                       <span class="menu-text">Conformité & Audits</span>
                       <span class="menu-arrow">›</span>
                   </a>
                   <ul class="sub-menu">
                       <li><a href="#requirements">Exigences & Référentiels</a></li>
                       <li><a href="#auditplan">Programme d'audits</a></li>
                       <li><a href="#auditprep">Préparation et réalisation</a></li>
                       <li><a href="#auditreports">Rapports et suivi</a></li>
                   </ul>
               </li>
               <li>
                   <a href="#riskmanagement">
                       <span class="menu-icon">R</span>
                       <span class="menu-text">Gestion des Risques</span>
                       <span class="menu-arrow">›</span>
                   </a>
                   <ul class="sub-menu">
                       <li><a href="#riskanalysis">Identification & Analyse</a></li>
                       <li><a href="#treatment">Plans de traitement</a></li>
                       <li><a href="#crisis">Gestion de crise</a></li>
                       <li><a href="#watch">Veille stratégique</a></li>
                   </ul>
               </li>
               <li>
                   <a href="#nonconformities">
                       <span class="menu-icon">N</span>
                       <span class="menu-text">Non-conformités & Actions</span>
                       <span class="menu-arrow">›</span>
                   </a>
                   <ul class="sub-menu">
                       <li><a href="#nc">Non-conformités & incidents</a></li>
                       <li><a href="#complaints">Réclamations clients</a></li>
                       <li><a href="#actions">Actions correctives & préventives</a></li>
                       <li><a href="#innovation">Innovation & amélioration</a></li>
                   </ul>
               </li>
               <li>
                   <a href="#training">
                       <span class="menu-icon">T</span>
                       <span class="menu-text">Compétences & Formation</span>
                       <span class="menu-arrow">›</span>
                   </a>
                   <ul class="sub-menu">
                       <li><a href="#skillmanagement">Gestion des compétences</a></li>
                       <li><a href="#trainingplan">Plan de formation</a></li>
                       <li><a href="#awareness">Sensibilisation & Communication</a></li>
                       <li><a href="#certification">Évaluation & Certification</a></li>
                   </ul>
               </li>
           </ul>
           <div class="toggle-sidebar" id="toggleSidebar">
               <svg width="16" height="16" fill="white" viewBox="0 0 16 16">
                   <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
               </svg>
           </div>
       </aside>
       
       <!-- Contenu principal de l'application -->
       <main class="content" id="content">
           <!-- Fil d'Ariane -->
           <ul class="breadcrumb">
               <li><a href="#home">Accueil</a></li>
               <li><a href="#governance">Gouvernance & Pilotage</a></li>
               <li>Revues de direction</li>
           </ul>
           
           <!-- En-tête de page -->
           <div class="page-header">
               <h2 class="page-title">Revues de direction</h2>
               <div class="page-actions">
                   <button class="btn btn-outline">
                       <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                           <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                       </svg>
                   </button>
                   <button class="btn btn-primary">
                       <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                           <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                       </svg>
                       <span class="btn-icon-text">Nouvelle revue</span>
                   </button>
               </div>
           </div>
           
           <!-- Statistiques de haut niveau -->
           <div class="grid">
               <div class="col-3">
                   <div class="stat-card">
                       <div class="stat-icon stat-primary">
                           <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                               <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                               <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                               <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
                           </svg>
                       </div>
                       <div class="stat-content">
                           <div class="stat-value">3</div>
                           <div class="stat-label">Revues planifiées</div>
                       </div>
                   </div>
               </div>
               <div class="col-3">
                   <div class="stat-card">
                       <div class="stat-icon stat-success">
                           <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                               <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                               <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                           </svg>
                       </div>
                       <div class="stat-content">
                           <div class="stat-value">86%</div>
                           <div class="stat-label">Actions clôturées</div>
                       </div>
                   </div>
               </div>
               <div class="col-3">
                   <div class="stat-card">
                       <div class="stat-icon stat-warning">
                           <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                               <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                               <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                           </svg>
                       </div>
                       <div class="stat-content">
                           <div class="stat-value">12</div>
                           <div class="stat-label">Actions en retard</div>
                       </div>
                   </div>
               </div>
               <div class="col-3">
                   <div class="stat-card">
                       <div class="stat-icon stat-danger">
                           <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                               <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                           </svg>
                       </div>
                       <div class="stat-content">
                           <div class="stat-value">4</div>
                           <div class="stat-label">NC critiques ouvertes</div>
                       </div>
                   </div>
               </div>
           </div>
           
           <!-- Tableau des revues de direction -->
           <div class="card">
               <div class="card-header">
                   <h3 class="card-title">Liste des revues de direction</h3>
                   <div>
                       <button class="btn btn-outline btn-icon">
                           <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                               <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                           </svg>
                       </button>
                   </div>
               </div>
               <div class="card-body">
                   <div class="table-container">
                       <table>
                           <thead>
                               <tr>
                                   <th>Référence</th>
                                   <th>Date</th>
                                   <th>Type</th>
                                   <th>Statut</th>
                                   <th>Actions</th>
                                   <th>Resp.</th>
                                   <th>Actions</th>
                               </tr>
                           </thead>
                           <tbody>
                               <tr>
                                   <td>RD-2025-001</td>
                                   <td>15/01/2025</td>
                                   <td>Revue trimestrielle</td>
                                   <td><span class="badge badge-success">Terminée</span></td>
                                   <td>8/8 clôturées</td>
                                   <td>M. Dupont</td>
                                   <td>
                                       <button class="btn btn-outline btn-icon">
                                           <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                               <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                               <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                           </svg>
                                       </button>
                                   </td>
                               </tr>
                               <tr>
                                   <td>RD-2025-002</td>
                                   <td>15/04/2025</td>
                                   <td>Revue trimestrielle</td>
                                   <td><span class="badge badge-warning">En cours</span></td>
                                   <td>5/12 clôturées</td>
                                   <td>M. Martin</td>
                                   <td>
                                       <button class="btn btn-outline btn-icon">
                                           <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                               <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                               <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                           </svg>
                                       </button>
                                   </td>
                               </tr>
                               <tr>
                                   <td>RD-2025-003</td>
                                   <td>15/07/2025</td>
                                   <td>Revue trimestrielle</td>
                                   <td><span class="badge badge-warning">Planifiée</span></td>
                                   <td>0/0 clôturées</td>
                                   <td>Mme Dubois</td>
                                   <td>
                                       <button class="btn btn-outline btn-icon">
                                           <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                               <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                               <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                           </svg>
                                       </button>
                                   </td>
                               </tr>
                           </tbody>
                       </table>
                   </div>
               </div>
               <div class="card-footer">
                   <div>Affichage de 1 à 3 sur 3 entrées</div>
               </div>
           </div>
           
           <!-- Graphique et tendances -->
           <div class="grid">
               <div class="col-8">
                   <div class="card">
                       <div class="card-header">
                           <h3 class="card-title">Tendances des indicateurs clés</h3>
                           <button class="btn btn-outline btn-icon">
                               <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                   <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                               </svg>
                           </button>
                       </div>
                       <div class="card-body">
                           <div class="chart-container">
                               <div class="chart">
                                   <!-- Simulation d'un graphique -->
                                   <div style="text-align: center;">
                                       [Graphique d'évolution des indicateurs clés]
                                       <div style="font-size: 12px; margin-top: 10px; color: #7f8c8d;">
                                           Cette zone affichera un graphique interactif des indicateurs de performance clés
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="col-4">
                   <div class="card">
                       <div class="card-header">
                           <h3 class="card-title">Répartition des actions</h3>
                       </div>
                       <div class="card-body">
                           <div class="chart-container" style="height: 200px;">
                               <div class="chart">
                                   <!-- Simulation d'un graphique en camembert -->
                                   <div style="text-align: center;">
                                       [Graphique de répartition des actions]
                                       <div style="font-size: 12px; margin-top: 10px; color: #7f8c8d;">
                                           Cette zone affichera un graphique de répartition des actions par statut et priorité
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div style="margin-top: 20px;">
                               <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                   <span>Actions correctives</span>
                                   <span>12</span>
                               </div>
                               <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                   <span>Actions préventives</span>
                                   <span>8</span>
                               </div>
                               <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                   <span>Opportunités d'amélioration</span>
                                   <span>15</span>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
           
           <!-- Exemple d'autres modules SMI -->
           <div class="card">
               <div class="card-header">
                   <h3 class="card-title">Modules du Système de Management Intégré</h3>
               </div>
               <div class="card-body">
                   <div class="grid">
                       <div class="col-4">
                           <div class="card">
                               <div class="card-header">
                                   <h4 class="card-title">
                                       <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px;">
                                           <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                           <path d="M3
<path d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-1z"/>
                                       </svg>
                                       Gestion Documentaire
                                   </h4>
                               </div>
                               <div class="card-body">
                                   <p>Interface de gestion du cycle de vie des documents, avec workflow d'approbation et distribution.</p>
                                   <div class="document-browser" style="height: 200px;">
                                       <div class="document-folders">
                                           <ul class="folder-list">
                                               <li class="folder-item active">
                                                   <span class="folder-icon">📁</span>
                                                   Procédures
                                               </li>
                                               <li class="folder-item">
                                                   <span class="folder-icon">📁</span>
                                                   Instructions
                                               </li>
                                               <li class="folder-item">
                                                   <span class="folder-icon">📁</span>
                                                   Formulaires
                                               </li>
                                               <li class="folder-item">
                                                   <span class="folder-icon">📁</span>
                                                   Enregistrements
                                               </li>
                                           </ul>
                                       </div>
                                       <div class="document-list">
                                           <div class="document-item">
                                               <div class="document-title">
                                                   <span class="document-icon">📄</span>
                                                   PR-001 Maîtrise documentaire
                                               </div>
                                               <div class="document-meta">
                                                   <div><i>👤</i> M. Dupont</div>
                                                   <div><i>📅</i> 15/02/2025</div>
                                                   <div><span class="badge badge-success">Approuvé</span></div>
                                               </div>
                                           </div>
                                           <div class="document-item">
                                               <div class="document-title">
                                                   <span class="document-icon">📄</span>
                                                   PR-002 Audits internes
                                               </div>
                                               <div class="document-meta">
                                                   <div><i>👤</i> Mme Martin</div>
                                                   <div><i>📅</i> 28/03/2025</div>
                                                   <div><span class="badge badge-warning">En révision</span></div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="col-4">
                           <div class="card">
                               <div class="card-header">
                                   <h4 class="card-title">
                                       <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px;">
                                           <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                           <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                       </svg>
                                       Gestion des Risques
                                   </h4>
                               </div>
                               <div class="card-body">
                                   <p>Analyse et traitement des risques selon différentes méthodologies.</p>
                                   <div style="margin-top: 15px;">
                                       <table class="risk-matrix">
                                           <tr>
                                               <th style="width: 80px;"></th>
                                               <th>Faible</th>
                                               <th>Moyen</th>
                                               <th>Élevé</th>
                                               <th>Extrême</th>
                                           </tr>
                                           <tr>
                                               <th>Fréquent</th>
                                               <td class="risk-cell risk-medium"><span class="risk-item">R3</span></td>
                                               <td class="risk-cell risk-high"><span class="risk-item">R5</span></td>
                                               <td class="risk-cell risk-extreme"><span class="risk-item">R1</span></td>
                                               <td class="risk-cell risk-extreme"></td>
                                           </tr>
                                           <tr>
                                               <th>Probable</th>
                                               <td class="risk-cell risk-low"></td>
                                               <td class="risk-cell risk-medium"><span class="risk-item">R7</span></td>
                                               <td class="risk-cell risk-high"></td>
                                               <td class="risk-cell risk-extreme"></td>
                                           </tr>
                                           <tr>
                                               <th>Possible</th>
                                               <td class="risk-cell risk-low"></td>
                                               <td class="risk-cell risk-medium"></td>
                                               <td class="risk-cell risk-high"><span class="risk-item">R2</span></td>
                                               <td class="risk-cell risk-high"></td>
                                           </tr>
                                           <tr>
                                               <th>Rare</th>
                                               <td class="risk-cell risk-low"><span class="risk-item">R4</span></td>
                                               <td class="risk-cell risk-low"></td>
                                               <td class="risk-cell risk-medium"></td>
                                               <td class="risk-cell risk-high"></td>
                                           </tr>
                                       </table>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="col-4">
                           <div class="card">
                               <div class="card-header">
                                   <h4 class="card-title">
                                       <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px;">
                                           <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v7A1.5 1.5 0 0 0 1.5 10H6v1H1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5v-1h4.5A1.5 1.5 0 0 0 16 8.5v-7A1.5 1.5 0 0 0 14.5 0h-13Zm0 1h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5ZM12 12.5a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0Zm2 0a.5.5 0 1 1 1 0 .5.5 0 0 1-1 0ZM1.5 12h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1ZM1 14.25a.25.25 0 0 1 .25-.25h5.5a.25.25 0 1 1 0 .5h-5.5a.25.25 0 0 1-.25-.25Z"/>
                                       </svg>
                                       Processus & Procédures
                                   </h4>
                               </div>
                               <div class="card-body">
                                   <p>Cartographie et modélisation des processus de l'entreprise.</p>
                                   <div class="process-map">
                                       <div class="process-header">
                                           <h5>Cartographie des processus</h5>
                                           <button class="btn btn-outline btn-icon">
                                               <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                   <path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z"/>
                                               </svg>
                                           </button>
                                       </div>
                                       <div class="process-container">
                                           <div class="process-box management">
                                               <div class="process-box-title">Processus de management</div>
                                               <div class="process-box-subtitle">Direction</div>
                                           </div>
                                           <div class="process-box operational">
                                               <div class="process-box-title">Processus opérationnels</div>
                                               <div class="process-box-subtitle">Cœur de métier</div>
                                           </div>
                                           <div class="process-box support">
                                               <div class="process-box-title">Processus support</div>
                                               <div class="process-box-subtitle">RH, IT, Achats</div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
           
           <!-- Formulaire d'exemple pour une revue de direction -->
           <div class="card">
               <div class="card-header">
                   <h3 class="card-title">Formulaire de création d'une revue de direction</h3>
                   <button class="btn btn-outline btn-icon">
                       <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                           <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                           <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                       </svg>
                   </button>
               </div>
               <div class="card-body">
                   <div class="grid">
                       <div class="col-6">
                           <div class="form-group">
                               <label for="review-ref">Référence</label>
                               <input type="text" id="review-ref" class="form-control" value="RD-2025-004">
                           </div>
                           <div class="form-group">
                               <label for="review-type">Type de revue</label>
                               <select id="review-type" class="form-control">
                                   <option>Revue trimestrielle</option>
                                   <option>Revue semestrielle</option>
                                   <option>Revue annuelle</option>
                                   <option>Revue exceptionnelle</option>
                               </select>
                           </div>
                           <div class="form-group">
                               <label for="review-date">Date planifiée</label>
                               <input type="date" id="review-date" class="form-control" value="2025-10-15">
                           </div>
                       </div>
                       <div class="col-6">
                           <div class="form-group">
                               <label for="review-resp">Responsable</label>
                               <select id="review-resp" class="form-control">
                                   <option>Thomas Dupont</option>
                                   <option>Céline Martin</option>
                                   <option>Laurent Dubois</option>
                               </select>
                           </div>
                           <div class="form-group">
                               <label for="review-participants">Participants</label>
                               <select id="review-participants" class="form-control" multiple style="height: 80px;">
                                   <option selected>Thomas Dupont</option>
                                   <option selected>Céline Martin</option>
                                   <option>Laurent Dubois</option>
                                   <option>Sophie Lefebvre</option>
                                   <option>Pierre Moreau</option>
                               </select>
                           </div>
                           <div class="form-group">
                               <label for="review-scope">Périmètre</label>
                               <select id="review-scope" class="form-control">
                                   <option>Système complet</option>
                                   <option>Qualité uniquement</option>
                                   <option>Sécurité uniquement</option>
                                   <option>Environnement uniquement</option>
                               </select>
                           </div>
                       </div>
                       <div class="col-12">
                           <div class="form-group">
                               <label for="review-objectives">Objectifs</label>
                               <textarea id="review-objectives" class="form-control" rows="3">Évaluer l'efficacité du système de management intégré et identifier les axes d'amélioration.</textarea>
                           </div>
                       </div>
                       <div class="col-12">
                           <div class="form-group">
                               <label>Points à l'ordre du jour</label>
                               <div style="margin-bottom: 10px;">
                                   <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                       <input type="checkbox" id="agenda1" checked style="margin-right: 10px;">
                                       <label for="agenda1" style="margin-bottom: 0;">Suivi des actions précédentes</label>
                                   </div>
                                   <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                       <input type="checkbox" id="agenda2" checked style="margin-right: 10px;">
                                       <label for="agenda2" style="margin-bottom: 0;">Résultats des audits</label>
                                   </div>
                                   <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                       <input type="checkbox" id="agenda3" checked style="margin-right: 10px;">
                                       <label for="agenda3" style="margin-bottom: 0;">Suivi des indicateurs de performance</label>
                                   </div>
                                   <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                       <input type="checkbox" id="agenda4" style="margin-right: 10px;">
                                       <label for="agenda4" style="margin-bottom: 0;">Revue des non-conformités</label>
                                   </div>
                                   <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                       <input type="checkbox" id="agenda5" style="margin-right: 10px;">
                                       <label for="agenda5" style="margin-bottom: 0;">Analyse des risques et opportunités</label>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="card-footer">
                   <div style="display: flex; justify-content: flex-end; gap: 10px;">
                       <button class="btn btn-outline">Annuler</button>
                       <button class="btn btn-primary">Enregistrer</button>
                   </div>
               </div>
           </div>
       </main>
   </div>

   <!-- Scripts pour le fonctionnement de l'interface -->
   <script>
       // Fonction pour basculer l'état du menu latéral
       document.getElementById('toggleSidebar').addEventListener('click', function() {
           const sidebar = document.getElementById('sidebar');
           const content = document.getElementById('content');
           
           sidebar.classList.toggle('sidebar-collapsed');
           content.classList.toggle('sidebar-collapsed-content');
       });
       
       // Gestion des clics sur les menus principaux
       document.querySelectorAll('.main-menu > li > a').forEach(item => {
           item.addEventListener('click', function(e) {
               e.preventDefault();
               
               // Fermer tous les menus actifs
               document.querySelectorAll('.main-menu > li').forEach(li => {
                   if (li !== this.parentElement) {
                       li.classList.remove('active');
                   }
               });
               
               // Basculer l'état actif du menu cliqué
               this.parentElement.classList.toggle('active');
           });
       });
       
       // Simulation de l'interaction avec les éléments de l'interface
       document.querySelectorAll('.btn, .folder-item, .document-item, .process-box, .risk-cell').forEach(item => {
           item.addEventListener('click', function(e) {
               // Empêcher la navigation
               e.preventDefault();
               
               // Ajouter un effet visuel au clic
               this.style.transform = 'scale(0.98)';
               setTimeout(() => {
                   this.style.transform = '';
               }, 100);
           });
       });
   </script>
</body>
</html>
