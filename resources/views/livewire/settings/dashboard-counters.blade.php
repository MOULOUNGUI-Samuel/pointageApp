<div class="row">
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill shadow">
            <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#addDomainModal">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center overflow-hidden">
                        <div>
                            <h4 class="fw-medium mb-1 text-truncate">Domaines</h4>
                            <h4>{{ $domainesCount }}</h4>
                        </div>
                    </div>
                    <div>
                        <span class="avatar avatar-lg bg-primary flex-shrink-0">
                            <i class="fa fa-folder-open fa-2x"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill shadow">
            <a href="#" data-bs-toggle="modal" data-bs-target="#addCategoryDomainModal">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center overflow-hidden">
                        <div>
                            <h4 class="fw-medium mb-1 text-truncate">Catégories</h4>
                            <h4>{{ $categoriesCount }}</h4>
                        </div>
                    </div>
                    <div>
                        <span class="avatar avatar-lg bg-info flex-shrink-0">
                            <i class="fa fa-folder fa-2x"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill shadow">
            <a href="#" data-bs-toggle="modal" data-bs-target="#addItemModal">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center overflow-hidden">
                        <div>
                            <h4 class="fw-medium mb-1 text-truncate">Items</h4>
                            <h4>{{ $itemsCount }}</h4>
                        </div>
                    </div>
                    <div>
                        <span class="avatar avatar-lg bg-warning flex-shrink-0">
                            <i class="fa fa-tag fa-2x"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 d-flex">
        <div class="card flex-fill shadow">
            <a href="#" data-bs-toggle="modal" data-bs-target="#IAModal" class="text-decoration-none">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div class="d-flex flex-column overflow-hidden">
                        <h5 class="fw-bold mb-2 text-dark">
                            <i class="fa fa-robot text-warning me-2"></i>
                            Générer avec IA
                        </h5>
                        <p class="text-muted mb-0 small">
                            L'IA se charge de tout paramétrer pour vous
                        </p>
                    </div>
                    <div class="ms-3">
                        <span class="avatar avatar-lg bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fa fa-robot text-warning" style="font-size: 1.8rem;"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
      
      
</div>
