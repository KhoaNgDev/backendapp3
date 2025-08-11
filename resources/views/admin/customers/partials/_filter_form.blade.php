<form id="filter-form" class="row align-items-end gy-2 mb-4">
    <div class="col-md-4">
        <label for="keyword" class="form-label fw-semibold">Tìm kiếm</label>
        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Tên, SĐT hoặc Email"
            maxlength="50">
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold d-block">&nbsp;</label>
        <a href="#" id="export-btn" class="btn btn-success w-100">
            <i class="fas fa-file-excel me-1"></i> Xuất Excel
        </a>
    </div>

    <div class="col-md-5">
        <label class="form-label fw-semibold d-block">&nbsp;</label>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary w-100" id="search-btn">
                <i class="fas fa-search me-1"></i> Tìm kiếm
            </button>
            <button type="button" class="btn btn-outline-secondary" id="reset-btn">
                <i class="fas fa-sync-alt me-1"></i> Reset
            </button>
        </div>
    </div>
</form>
