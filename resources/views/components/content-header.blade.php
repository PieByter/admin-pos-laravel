<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h2>{{ $title ?? '' }}</h2>
            </div>
            <div class="col-sm-6 d-flex justify-content-end">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumbUrl ?? '#' }}">{{ $breadcrumbParent ?? '' }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title ?? '' }}</li>
                </ol>
            </div>
        </div>
        <hr class="mb-0 mt-1">
    </div>
</div>
