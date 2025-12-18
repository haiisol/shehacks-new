<div class="modal fade" id="modal-delete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data</h5>
                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            
            <div class="modal-body">
                <form>
                    <input type="hidden" name="id" id="id3" value="">
                    <input type="hidden" name="method" id="method" value="">

                    <p>Apakah anda yakin ingin menghapus data ini?</p>

                    <div class="modal-action">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary"><span class="icon feather icon-x"></span>Batal</button>
                        <button type="button" id="button-delete" class="btn btn-danger"><span class="icon feather icon-trash"></span>Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-kurasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kurasi Data User</h5>
                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            
            <div class="modal-body">
                <form>
                    <input type="hidden" name="id" id="id3kurasi" value="">

                    <p class="modal-des">Apakah anda yakin ingin mengkurasi data ini?</p>

                    <div class="modal-action">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary"><span class="icon feather icon-x"></span>Batal</button>
                        <button type="button" id="button-kurasi" class="btn btn-success"><span class="icon feather icon-user"></span>Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-change-status">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modal-status-title">Change Status</h5>
                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            
            <div class="modal-body">
                <form>
                    <input type="hidden" name="id4" id="id4" value="">
                    <input type="hidden" name="param4" id="param4" value="">

                    <p class="modal-status-caption">Apakah anda yakin ingin mengubah status data ini?</p>

                    <div class="modal-action">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary"><span class="icon feather icon-x"></span>Batal</button>
                        <button type="button" id="submit-change-status" class="btn btn-warning"><span class="icon feather icon-check"></span>Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>