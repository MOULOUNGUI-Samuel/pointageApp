<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sommaire Automatique</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --bg-color: #f5f5f5;
            --text-color: #333;
            --folder-color: #f39c12;
            --progress-color: #2ecc71;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
        }

        .search-container {
            margin-bottom: 20px;
            position: relative;
        }

        #search {
            width: 100%;
            padding: 12px 20px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .progress-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 4px;
            margin-bottom: 20px;
            overflow: hidden;
            height: 20px;
            display: none;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--progress-color);
            width: 0%;
            transition: width 0.3s ease;
            text-align: center;
            color: white;
            font-size: 12px;
            line-height: 20px;
        }

        .file-tree {
            margin-top: 20px;
        }

        .folder, .file {
            margin: 5px 0;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .folder {
            cursor: pointer;
            font-weight: bold;
            color: var(--folder-color);
        }

        .folder:hover {
            background-color: rgba(243, 156, 18, 0.1);
        }

        .file a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .file a:hover {
            text-decoration: underline;
        }

        .file-content {
            margin-left: 20px;
            display: none;
            border-left: 2px solid #eee;
            padding-left: 10px;
        }

        .folder.active .file-content {
            display: block;
        }

        .folder-icon::before {
            content: "📁";
            margin-right: 5px;
        }

        .folder.active .folder-icon::before {
            content: "📂";
        }

        .file-icon::before {
            margin-right: 5px;
        }

        .file-pdf .file-icon::before {
            content: "📄";
            color: #e74c3c;
        }

        .file-docx .file-icon::before {
            content: "📄";
            color: #3498db;
        }

        .file-html .file-icon::before {
            content: "📄";
            color: #e67e22;
        }

        .file-other .file-icon::before {
            content: "📄";
            color: #95a5a6;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-style: italic;
            display: none;
        }

        .select-folder-btn {
            display: block;
            margin: 0 auto 20px auto;
            padding: 12px 25px;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .select-folder-btn:hover {
            background-color: #2980b9;
        }

        .info-message {
            text-align: center;
            margin: 20px 0;
            font-style: italic;
            color: #7f8c8d;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            #search {
                padding: 8px 15px;
            }
            
            .select-folder-btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sommaire des Fichiers</h1>
        
        <button id="select-folder-btn" class="select-folder-btn">Sélectionner un dossier</button>
        
        <div class="search-container">
            <input type="text" id="search" placeholder="Rechercher des fichiers...">
        </div>
        
        <div class="progress-container">
            <div class="progress-bar">0%</div>
        </div>
        
        <div id="file-tree" class="file-tree"></div>
        
        <div class="no-results">Aucun résultat trouvé</div>
        
        <div class="info-message">
            Note: Pour des raisons de sécurité, le navigateur ne permet l'accès qu'aux dossiers explicitement sélectionnés par l'utilisateur.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Éléments DOM
            const fileTree = document.getElementById('file-tree');
            const searchInput = document.getElementById('search');
            const progressContainer = document.querySelector('.progress-container');
            const progressBar = document.querySelector('.progress-bar');
            const noResults = document.querySelector('.no-results');
            const selectFolderBtn = document.getElementById('select-folder-btn');
            
            // Variables globales
            let fileStructure = {};
            let totalItems = 0;
            let processedItems = 0;
            let rootDirHandle = null;
            
            // Vérifier si l'API File System Access est disponible
            const isFileSystemAccessSupported = 'showDirectoryPicker' in window;
            
            if (!isFileSystemAccessSupported) {
                fileTree.innerHTML = `
                    <div class="info-message" style="color: #e74c3c;">
                        Votre navigateur ne prend pas en charge l'API File System Access.
                        <br>Veuillez utiliser un navigateur compatible comme Chrome, Edge ou Opera.
                    </div>
                `;
                selectFolderBtn.disabled = true;
            }

            // Événement pour sélectionner un dossier
            selectFolderBtn.addEventListener('click', async () => {
                if (!isFileSystemAccessSupported) {
                    alert("Votre navigateur ne prend pas en charge cette fonctionnalité. Veuillez utiliser Chrome, Edge ou Opera.");
                    return;
                }

                try {
                    rootDirHandle = await window.showDirectoryPicker();
                    scanDirectory();
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error('Erreur lors de la sélection du dossier:', error);
                        alert('Une erreur est survenue lors de la sélection du dossier.');
                    }
                }
            });

            // Fonction pour scanner l'arborescence de fichiers
            async function scanDirectory() {
                try {
                    // Réinitialiser les variables
                    fileStructure = {};
                    totalItems = 0;
                    processedItems = 0;
                    
                    // Afficher la barre de progression
                    progressContainer.style.display = 'block';
                    updateProgress(5, 'Initialisation...');
                    
                    // Compter le nombre total d'éléments (pour la barre de progression)
                    await countItems(rootDirHandle);
                    
                    // Scanner le dossier
                    updateProgress(10, 'Analyse des fichiers...');
                    await processDirectoryEntry(rootDirHandle, '');
                    
                    // Construire l'arborescence dans le DOM
                    updateProgress(90, 'Construction de l\'interface...');
                    buildFileTree();
                    
                    // Configurer les événements
                    setupEvents();
                    
                    // Cacher la barre de progression
                    updateProgress(100, 'Terminé!');
                    setTimeout(() => {
                        progressContainer.style.display = 'none';
                    }, 500);
                } catch (error) {
                    console.error('Erreur lors du scan:', error);
                    updateProgress(100, 'Erreur!');
                    setTimeout(() => {
                        progressContainer.style.display = 'none';
                    }, 500);
                    fileTree.innerHTML = `
                        <div class="info-message" style="color: #e74c3c;">
                            Une erreur est survenue lors de l'analyse du dossier.
                            <br>Erreur: ${error.message}
                        </div>
                    `;
                }
            }
            
            // Compter le nombre total d'éléments pour la barre de progression
            async function countItems(dirHandle) {
                totalItems++;
                
                for await (const entry of dirHandle.values()) {
                    if (entry.kind === 'directory') {
                        try {
                            const subDirHandle = await dirHandle.getDirectoryHandle(entry.name);
                            await countItems(subDirHandle);
                        } catch (error) {
                            console.warn(`Impossible d'accéder au dossier ${entry.name}:`, error);
                            totalItems++;
                        }
                    } else {
                        totalItems++;
                    }
                }
            }
            
            // Traiter une entrée de dossier
            async function processDirectoryEntry(dirHandle, path) {
                const currentDir = {};
                
                try {
                    for await (const entry of dirHandle.values()) {
                        processedItems++;
                        updateProgress(
                            Math.floor((processedItems / totalItems) * 80) + 10,
                            `Traitement: ${entry.name}`
                        );
                        
                        if (entry.kind === 'directory') {
                            try {
                                const subDirHandle = await dirHandle.getDirectoryHandle(entry.name);
                                currentDir[entry.name] = await processDirectoryEntry(subDirHandle, 
                                    path ? `${path}/${entry.name}` : entry.name);
                            } catch (error) {
                                console.warn(`Impossible d'accéder au dossier ${entry.name}:`, error);
                                currentDir[entry.name] = { '.access_error': 'error' };
                            }
                        } else {
                            // C'est un fichier
                            currentDir[entry.name] = getFileType(entry.name);
                        }
                    }
                } catch (error) {
                    console.error(`Erreur lors du traitement du dossier ${path}:`, error);
                }
                
                // Si c'est le dossier racine, mettre à jour la structure globale
                if (!path) {
                    fileStructure = currentDir;
                }
                
                return currentDir;
            }
            
            // Mettre à jour la barre de progression
            function updateProgress(percent, text) {
                progressBar.style.width = `${percent}%`;
                progressBar.textContent = text || `${percent}%`;
            }
            
            // Construire l'arborescence HTML
            function buildFileTree() {
                fileTree.innerHTML = '';
                buildTreeRecursive(fileStructure, fileTree, '');
            }
            
            // Construire l'arborescence de manière récursive
            function buildTreeRecursive(structure, parentElement, path) {
                Object.keys(structure).forEach(name => {
                    const value = structure[name];
                    const currentPath = path ? `${path}/${name}` : name;
                    
                    if (typeof value === 'object') {
                        // C'est un dossier
                        const folderElement = document.createElement('div');
                        folderElement.className = 'folder';
                        folderElement.innerHTML = `<span class="folder-icon"></span>${name}`;
                        
                        const contentElement = document.createElement('div');
                        contentElement.className = 'file-content';
                        folderElement.appendChild(contentElement);
                        
                        parentElement.appendChild(folderElement);
                        
                        // Créer récursivement les enfants
                        buildTreeRecursive(value, contentElement, currentPath);
                    } else if (name === '.access_error') {
                        // Message d'erreur d'accès
                        const errorElement = document.createElement('div');
                        errorElement.className = 'file file-error';
                        errorElement.innerHTML = `
                            <span class="file-icon" style="color: #e74c3c;">⚠️</span>
                            <span style="color: #e74c3c;">Accès refusé ou erreur</span>
                        `;
                        parentElement.appendChild(errorElement);
                    } else {
                        // C'est un fichier
                        const fileType = value;
                        const fileElement = document.createElement('div');
                        fileElement.className = `file file-${fileType}`;
                        
                        // Créer le lien avec les bons attributs
                        const isHtml = fileType === 'html';
                        let linkHtml;
                        
                        if (rootDirHandle) {
                            // Utiliser l'API File System Access pour créer un lien valide
                            linkHtml = `
                                <span class="file-icon"></span>
                                <a href="#" data-path="${currentPath}" class="file-link" 
                                   data-is-html="${isHtml}" data-filename="${name}">${name}</a>
                            `;
                        } else {
                            // Fallback pour les navigateurs non supportés
                            linkHtml = `
                                <span class="file-icon"></span>
                                <span>${name}</span>
                            `;
                        }
                        
                        fileElement.innerHTML = linkHtml;
                        parentElement.appendChild(fileElement);
                    }
                });
            }
            
            // Obtenir le type de fichier en fonction de l'extension
            function getFileType(filename) {
                const extension = filename.split('.').pop().toLowerCase();
                
                const commonTypes = ['pdf', 'docx', 'xlsx', 'pptx', 'html', 'css', 'js', 'txt', 'png', 'jpg', 'jpeg', 'gif'];
                
                return commonTypes.includes(extension) ? extension : 'other';
            }
            
            // Configurer les événements
            function setupEvents() {
                // Ouvrir/fermer les dossiers en cliquant
                document.querySelectorAll('.folder').forEach(folder => {
                    folder.addEventListener('click', function(e) {
                        // S'assurer que le clic n'est pas sur un lien à l'intérieur du dossier
                        if (e.target.tagName !== 'A') {
                            this.classList.toggle('active');
                            e.stopPropagation(); // Empêcher la propagation du clic
                        }
                    });
                });
                
                // Gérer les clics sur les fichiers
                document.querySelectorAll('.file-link').forEach(link => {
                    link.addEventListener('click', async function(e) {
                        e.preventDefault();
                        
                        const filePath = this.getAttribute('data-path');
                        const isHtml = this.getAttribute('data-is-html') === 'true';
                        const fileName = this.getAttribute('data-filename');
                        
                        try {
                            // Récupérer le fichier en suivant le chemin à partir de la racine
                            let currentHandle = rootDirHandle;
                            const pathParts = filePath.split('/');
                            
                            // Parcourir tous les segments du chemin sauf le dernier (qui est le nom du fichier)
                            for (let i = 0; i < pathParts.length - 1; i++) {
                                currentHandle = await currentHandle.getDirectoryHandle(pathParts[i]);
                            }
                            
                            // Récupérer le fichier
                            const fileHandle = await currentHandle.getFileHandle(pathParts[pathParts.length - 1]);
                            const file = await fileHandle.getFile();
                            
                            // Créer une URL pour le fichier
                            const fileURL = URL.createObjectURL(file);
                            
                            if (isHtml) {
                                // Ouvrir dans un nouvel onglet
                                window.open(fileURL, '_blank');
                            } else {
                                // Télécharger le fichier
                                const downloadLink = document.createElement('a');
                                downloadLink.href = fileURL;
                                downloadLink.download = fileName;
                                downloadLink.click();
                                
                                // Libérer l'URL après utilisation
                                setTimeout(() => URL.revokeObjectURL(fileURL), 100);
                            }
                        } catch (error) {
                            console.error('Erreur lors de l\'accès au fichier:', error);
                            alert(`Erreur lors de l'accès au fichier: ${error.message}`);
                        }
                    });
                });
                
                // Recherche
                searchInput.addEventListener('input', debounce(function() {
                    const searchTerm = this.value.toLowerCase();
                    filterFiles(searchTerm);
                }, 300));
            }
            
            // Fonction de debounce pour limiter les appels lors de la recherche
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }
            
            // Filtrer les fichiers en fonction du terme de recherche
            function filterFiles(searchTerm) {
                const allFolders = document.querySelectorAll('.folder');
                const allFiles = document.querySelectorAll('.file');
                let hasResults = false;
                
                // Réinitialiser l'affichage
                allFolders.forEach(folder => {
                    folder.style.display = 'block';
                    folder.classList.remove('active');
                });
                
                allFiles.forEach(file => {
                    file.style.display = 'block';
                });
                
                if (searchTerm) {
                    // Filtrer les fichiers
                    allFiles.forEach(file => {
                        const fileName = file.textContent.toLowerCase();
                        if (fileName.includes(searchTerm)) {
                            // Afficher ce fichier et tous ses dossiers parents
                            file.style.display = 'block';
                            let parent = file.parentElement;
                            while (parent && parent.classList.contains('file-content')) {
                                parent.parentElement.classList.add('active');
                                parent = parent.parentElement.parentElement;
                            }
                            hasResults = true;
                        } else {
                            file.style.display = 'none';
                        }
                    });
                    
                    // Cacher les dossiers vides (après filtrage)
                    allFolders.forEach(folder => {
                        const visibleContent = folder.querySelector('.file-content');
                        if (visibleContent) {
                            const visibleFiles = Array.from(visibleContent.querySelectorAll('.file'))
                                .filter(file => file.style.display !== 'none').length;
                            const visibleFolders = Array.from(visibleContent.querySelectorAll('.folder'))
                                .filter(subfolder => subfolder.style.display !== 'none').length;
                            
                            if (visibleFiles === 0 && visibleFolders === 0) {
                                folder.style.display = 'none';
                            } else {
                                hasResults = true;
                            }
                        }
                    });
                } else {
                    hasResults = true;
                }
                
                // Afficher ou cacher le message "Aucun résultat"
                noResults.style.display = hasResults ? 'none' : 'block';
            }
        });
    </script>
</body>
</html>