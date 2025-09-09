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
            <a href="#" data-bs-toggle="modal" data-bs-target="#addTypeItemModal">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center overflow-hidden">
                        <div>
                            <h4 class="fw-medium mb-1 text-truncate">Type items</h4>
                            <h4>{{ $typesCount }}</h4>
                        </div>
                    </div>
                    <div>
                        <span class="avatar avatar-lg bg-success flex-shrink-0">
                            <i class="fa fa-tags fa-2x"></i>
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
</div>
